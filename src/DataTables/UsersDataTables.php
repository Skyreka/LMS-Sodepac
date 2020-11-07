<?php
namespace App\DataTables;

use App\Entity\Users;
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
     *
     * @param Registry $doctrine
     * @param UrlGeneratorInterface $router
     */
    public function __construct(Registry $doctrine, UrlGeneratorInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
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
            if ($order->column == 4) {
                $query->addOrderBy('u.pack', $order->dir);
            }
        }

        // Restrict results.
        $query->setMaxResults($request->length);
        $query->setFirstResult($request->start);

        /** @var \AppBundle\Entity\User[] $users */
        $users = $query->getQuery()->getResult();

        foreach ($users as $user) {
            // Tech
            $technician = 'Aucun';
            if ($user->getStatus() == 'ROLE_USER') {
                $technician = $user->getTechnician()->getIdentity();
            }

            // Pack
            switch ($user->getPack()) {
                case 'PACK_FULL':
                    $pack = '<span class="label label-megna">Pack Full</span>';
                    break;
                case 'PACK_LIGHT':
                    $pack = '<span class="label label-info">Pack Light</span>';
                    break;
                case 'PACK_DEMO':
                    $pack = '<span class="label label-light-info">Pack Demo</span>';
                    break;
                default:
                    $pack = '<span class="label label-default">Inactif</span>';
                    break;
            }

            // Exploitation
            if ($user->getExploitation() == NULL) {
                $exploitation = '<a href="'.$this->router->generate('admin_users_exploitation_new', ['id' => $user->getId()]).'"><span class="label label-info">Ajouter une exploitation</span></a>';
            } else {
                $exploitation = '<a href="'.$this->router->generate('management_user_show', ['id' => $user->getId()]).'"><h6>'.$user->getExploitation()->getSize().' ha</h6></a>';
            }

            $results->data[] = [
                '
                    <a href="'.$this->router->generate('management_user_show', ['id' => $user->getId()]).'">
                        <h6>'.$user->getIdentity().'</h6><small class="text-muted">'.$user->getEmail().'</small>
                    </a>
                ',
                $exploitation,
                $user->getPhone(),
                $user->getCity(),
                $pack,
                $user->getCertificationPhyto(),
                $technician,
                '
                <a href="'.$this->router->generate('management_user_show', ['id' => $user->getId()]).'" class="text-inverse p-r-10" data-toggle="tooltip" title="" data-original-title="Edit">
                    <i class="ti-lock"></i>
                </a> 
                <a href="'.$this->router->generate('management_user_show', ['id' => $user->getId()]).'" class="text-inverse p-r-10" title="" data-toggle="tooltip" data-original-title="Delete">
                    <i class="ti-pencil-alt"></i>
                </a>
                '
            ];
        }

        return $results;
    }
}

