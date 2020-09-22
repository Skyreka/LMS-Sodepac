<?php
namespace App\DataTables;

use App\Entity\Users;
use DataTables\DataTableHandlerInterface;
use DataTables\DataTableQuery;
use DataTables\DataTableResults;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UsersDataTables implements DataTableHandlerInterface
{
    protected $doctrine;

    /**
     * Dependency Injection constructor.
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
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
        $query = $repository->createQueryBuilder('u')->select('COUNT(u.id)');
        $results->recordsTotal = $query->getQuery()->getSingleScalarResult();

        // Query to get requested entities.
        $query = $repository->createQueryBuilder('u');

        // Search.
        if ($request->search->value) {
            $query->where('(LOWER(u.lastname) LIKE :search OR' .
                ' LOWER(u.firstname) LIKE :search)');
            $query->setParameter('search', strtolower("%{$request->search->value}%"));
        }

        // Filter by columns.
        foreach ($request->columns as $column) {
            if ($column->search->value) {
                $value = strtolower($column->search->value);

                // "Info" column
                if ($column->data == 0) {
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
        foreach ($request->order as $order) {

            // "ID" column
            if ($order->column == 0) {
                $query->addOrderBy('u.lastname', $order->dir);
            }
        }

        // Restrict results.
        $query->setMaxResults($request->length);
        $query->setFirstResult($request->start);

        /** @var \AppBundle\Entity\User[] $users */
        $users = $query->getQuery()->getResult();

        foreach ($users as $user) {
            $results->data[] = [
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
                '<h6>'.$user->getFirstname().' '.$user->getLastname().'</h6>',
            ];
        }

        return $results;
    }
}

