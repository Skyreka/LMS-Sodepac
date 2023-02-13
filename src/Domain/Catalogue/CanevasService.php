<?php

namespace App\Domain\Catalogue;

use App\Domain\Auth\AuthService;
use App\Domain\Catalogue\Entity\CanevasDisease;
use App\Domain\Catalogue\Entity\CanevasIndex;
use App\Domain\Catalogue\Entity\CanevasProduct;
use App\Domain\Catalogue\Entity\CanevasStep;
use App\Domain\Catalogue\Entity\Catalogue;
use App\Domain\Catalogue\Repository\CatalogueRepository;
use App\Domain\Product\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CanevasService
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function createDisease(CanevasDisease $disease, CanevasIndex $canevasIndex): void
    {
        $disease
            ->setCanevas($canevasIndex);
        $this->em->persist($disease);
        $this->em->flush();
    }

    public function createStep(CanevasStep $step, CanevasIndex $canevasIndex): void
    {
        $step
            ->setCanevas($canevasIndex);
        $this->em->persist($step);
        $this->em->flush();
    }

    public function createBouton(
        CanevasProduct $canevasProduct,
        CanevasIndex $canevasIndex,
        Request $request
    ): void
    {
        $stepId = $request->request->get('canevas_product')['step'];
        $diseaseId = $request->request->get('canevas_product')['disease'];
        $step = $this->em->getRepository(CanevasStep::class)->find($stepId);
        $disease = $this->em->getRepository(CanevasDisease::class)->find($diseaseId);

        $canevasProduct
            ->setCanevas($canevasIndex)
            ->setStep($step)
            ->setDisease($disease);
        $this->em->persist($canevasProduct);
        $this->em->flush();
    }

    public function updateBouton(
        CanevasProduct $canevasProduct,
        CanevasIndex $canevasIndex,
        Request $request
    ): void
    {
        $btnId = $request->request->get('canevas_product_edit')['btn_id'];
        $canevasProduct = $this->em->getRepository(CanevasProduct::class)->find($btnId);

        $stepId = $request->request->get('canevas_product_edit')['step'];
        $diseaseId = $request->request->get('canevas_product_edit')['disease'];

        $color = $request->request->get('canevas_product_edit')['color'];
        $unit = $request->request->get('canevas_product_edit')['unit'];
        $dose = $request->request->get('canevas_product_edit')['dose'];
        $productId = $request->request->get('canevas_product_edit')['product'] ?? null;

        $step = $this->em->getRepository(CanevasStep::class)->find($stepId);
        $disease = $this->em->getRepository(CanevasDisease::class)->find($diseaseId);

        if($productId) {
            $product = $this->em->getRepository(Products::class)->find($productId);
            $canevasProduct
                ->setProduct($product);
        }

        $canevasProduct
            ->setColor($color)
            ->setUnit($unit)
            ->setDose($dose)
            ->setStep($step)
            ->setDisease($disease);

        $this->em->flush();
    }

    public function deleteButton(int $btnId) : void
    {
        // Search btn
        $canevasProduct = $this->em->getRepository(CanevasProduct::class)->find($btnId);

        // Vérifier que le bouton existe
        if (!$canevasProduct) {
            throw $this->createNotFoundException('Le bouton n\'existe pas');
        }

        $this->em->remove($canevasProduct);
        $this->em->flush();
    }

    public function getAllCanevas(): array
    {
        return $this->em->getRepository(CanevasIndex::class)->findAll();
    }

    public function delete(CanevasIndex $canevasIndex): void
    {
        $canevasIndex->setIsActive(0);
        $this->em->flush();
    }

    public function reactivate(CanevasIndex $canevasIndex): void
    {
        $canevasIndex->setIsActive(1);
        $this->em->flush();
    }
}
