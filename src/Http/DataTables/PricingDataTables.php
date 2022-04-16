<?php

namespace App\Http\DataTables;

use App\Domain\Product\Entity\Products;
use DataTables\DataTableHandlerInterface;
use DataTables\DataTableQuery;
use DataTables\DataTableResults;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PricingDataTables implements DataTableHandlerInterface
{
    protected $doctrine;
    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    
    /**
     * Dependency Injection constructor.
     * @param Registry $doctrine
     * @param UrlGeneratorInterface $router
     */
    public function __construct(Registry $doctrine, UrlGeneratorInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router   = $router;
    }
    
    /**
     * {@inheritdoc}
     */
    public function handle(DataTableQuery $request): DataTableResults
    {
        /** @var \Doctrine\ORM\EntityRepository $repository */
        $repository = $this->doctrine->getRepository(Products::class);
        
        $results = new DataTableResults();
        
        // Total number of products.
        $query                 = $repository
            ->createQueryBuilder('p')
            ->where('p.private = 0')
            ->select('COUNT(p.id)');
        $results->recordsTotal = $query->getQuery()->getSingleScalarResult();
        
        // Query to get requested entities.
        $query = $repository->createQueryBuilder('p');
        $query->where('p.private = 0');
        
        // Search.
        if($request->search->value) {
            $query->where('(LOWER(p.name) LIKE :search)');
            $query->setParameter('search', strtolower("%{$request->search->value}%"));
        }
        
        // Filter by columns.
        foreach($request->columns as $column) {
            if($column->search->value) {
                $value = strtolower($column->search->value);
                
                // "Info" column
                if($column->data == 0) {
                    $query->andWhere('p.name = :name');
                    $query->setParameter('name', intval($value));
                }
            }
        }
        
        // Get filtered count.
        $queryCount = clone $query;
        $queryCount->select('COUNT(p.id)');
        $results->recordsFiltered = $queryCount->getQuery()->getSingleScalarResult();
        
        // Order.
        foreach($request->order as $order) {
            
            // "ID" column
            if($order->column == 0) {
                $query->addOrderBy('p.name', $order->dir);
            }
        }
        
        // Restrict results.
        $query->setMaxResults($request->length);
        $query->setFirstResult($request->start);
        
        // OrdeNumber
        if(isset($request->customData['orderNumber'])) {
            $orderNumber = $request->customData['orderNumber'];
        } else {
            $orderNumber = null;
        }
        
        /** @var \AppBundle\Entity\Ur[] $products */
        $products = $query->getQuery()->getResult();
        
        foreach($products as $product) {
            $results->data[] = [
                $product->getName() . ' (' . $product->getIsActive() . ')',
                $product->getPrice(),
                $product->getRpd(),
                '
                    <a class="btn btn-warning" href="' . $this->router->generate('pricing_product_edit', ['id' => $product->getId()]) . '">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form class="d-inline" method="POST" action="' . $this->router->generate('pricing_product_update', ['id' => $product->getId()]) . '">
                        <input type="hidden" name="_method" value="UPDATE">
                        <button class="btn btn-success ml-3"><i class="fa fa-eye"></i></button>
                    </form>
                ',
            ];
        }
        
        return $results;
    }
}

