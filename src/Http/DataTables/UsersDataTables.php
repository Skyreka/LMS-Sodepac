<?php

namespace App\Http\DataTables;

use App\Domain\Auth\Users;
use DataTables\DataTableHandlerInterface;
use DataTables\DataTableQuery;
use DataTables\DataTableResults;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UsersDataTables implements DataTableHandlerInterface
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
        $repository = $this->doctrine->getRepository(Users::class);
        
        $results = new DataTableResults();
        
        // Total number of users.
        $query                 = $repository->createQueryBuilder('u')->select('COUNT(u.id)');
        $results->recordsTotal = $query->getQuery()->getSingleScalarResult();
        
        // Query to get requested entities.
        $query = $repository->createQueryBuilder('u');
        
        // Search.
        if($request->search->value) {
            $query->where('(LOWER(u.lastname) LIKE :search OR' .
                ' LOWER(u.email) LIKE :search OR' .
                ' LOWER(u.company) LIKE :search OR' .
                ' LOWER(u.firstname) LIKE :search)');
            $query->setParameter('search', strtolower("%{$request->search->value}%"));
        }
        
        // Filter by columns.
        foreach($request->columns as $column) {
            if($column->search->value) {
                $value = strtolower($column->search->value);
                
                // "Info" column
                if($column->data == 0) {
                    $query->andWhere('u.lastname = :lastname');
                    $query->setParameter('lastname', intval($value));
                }
            }
        }
        
        // Get filtered count.
        $queryCount = clone $query;
        $queryCount->select('COUNT(u.id)');
        $results->recordsFiltered = $queryCount->getQuery()->getSingleScalarResult();
        
        // Order.
        foreach($request->order as $order) {
            
            // "ID" column
            if($order->column == 0) {
                $query->addOrderBy('u.lastname', $order->dir);
            }
            if($order->column == 4) {
                $query->addOrderBy('u.pack', $order->dir);
            }
        }
        
        // Restrict results.
        $query->setMaxResults($request->length);
        $query->setFirstResult($request->start);
        
        /** @var \AppBundle\Entity\User[] $users */
        $users = $query->getQuery()->getResult();
        
        foreach($users as $user) {
            // Tech
            $technician = 'Aucun';
            if($user->getStatus() == 'ROLE_USER' && $user->getTechnician() != null) {
                $technician = $user->getTechnician()->getIdentity();
            }
            
            // Pack
            $pack = match ($user->getPack()) {
              'PACK_FULL' => '<span class="label label-megna">Pack Full</span>',
              'PACK_LIGHT' => '<span class="label label-info">Pack Light</span>',
              'PACK_DEMO' => '<span class="label label-light-info">Pack Demo</span>',
              default => '<span class="label label-default">Inactif</span>',
            };
            
            // Exploitation
            if($user->getExploitation() == NULL) {
                $exploitation = '<small>Aucune</small>';
            } else {
                $exploitation = '<a href="' . $this->router->generate('management_user_show', ['id' => $user->getId()]) . '"><h6>' . $user->getExploitation()->getSize() . ' ha</h6></a>';
            }
            
            $results->data[] = [
                '
                    <a href="' . $this->router->generate('management_user_show', ['id' => $user->getId()]) . '">
                        <h6 class="text-info">' . $user->getIdentity() . '</h6><small class="text-muted">' . $user->getEmail() . '</small>
                    </a>
                ',
                $exploitation,
                $user->getPhone(),
                $user->getCity(),
                $pack,
                $user->getCertificationPhyto(),
                $technician
            ];
        }
        
        return $results;
    }
}

