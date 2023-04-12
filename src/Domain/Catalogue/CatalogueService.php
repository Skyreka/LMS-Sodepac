<?php

namespace App\Domain\Catalogue;

use App\Domain\Auth\AuthService;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Repository\CatalogueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Exception;
use Dompdf\Options;
use iio\libmergepdf\Merger;
use Symfony\Component\Filesystem\Filesystem;

class CatalogueService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AuthService $authService
    ) {
    }

    public function create(Catalogue $catalogue): void
    {
        $catalogue
            ->setAddedAt(new \DateTimeImmutable())
            ->setIsActive(true)
            ->setCreateBy( $this->authService->getUser() )
            ->setStatus( Catalogue::DRAFT )
        ;

        $this->em->persist($catalogue);

        $this->em->flush();
    }

    public function getCataloguesForStaff(): array
    {
        return $this->em->getRepository(Catalogue::class)->findBy(['isActive' => 1], ['addedAt' => 'DESC']);
    }
}
