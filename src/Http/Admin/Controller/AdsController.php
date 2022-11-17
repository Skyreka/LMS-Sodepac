<?php

namespace App\Http\Admin\Controller;

use App\Domain\Ads\Entity\Ads;
use App\Domain\Ads\Form\AdsType;
use App\Domain\Ads\Repository\AdsRepository;
use App\Http\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Redis\Transport\RedisTransportFactory;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/annonces", name="ads_")
 */
class AdsController extends AbstractController
{
    protected string $templatePath = 'ads';
    protected string $menuItem = 'ads';
    protected string $entity = Ads::class;
    protected string $routePrefix = 'admin_ads';
    protected string $searchField = 'name';

    public function __construct(private readonly EntityManagerInterface $em) {
    }


    /**
     * @Route("/", name="index")
     */
    public function index(AdsRepository $repository): Response
    {
        return $this->render('admin/ads/index.html.twig', [
            'ads' => $repository->findBy(['isActive' => true]),
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $ad = new Ads();
        $form = $this->createForm( AdsType::class, $ad );
        $form->handleRequest($request);
        if( $form->isSubmitted() ) {
            if(! $form->isValid()) {
                $this->flashErrors($form);
            } else {
                $this->em->persist($ad);
                $this->em->flush();
                $this->addFlash('success', 'L\'annonce a bien été créée');
                return $this->redirectToRoute('admin_ads_index');
            }
        }

        return $this->render('admin/ads/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/pause", name="pause")
     */
    public function pause(Ads $ad): RedirectResponse
    {
        $ad->setStatus(Ads::STATUS_DISABLED);
        $this->em->flush();
        $this->addFlash('success', 'L\'annonce a bien été désactivée');
        return $this->redirectToRoute('admin_ads_index');
    }

    /**
     * @Route("/{id}/diffuse", name="diffuse")
     */
    public function diffuse(Ads $ad, AdsRepository $ar): RedirectResponse
    {
        // Disable all others ads
        foreach( $ar->findBy(['status' => Ads::STATUS_DISPLAYED]) as $ads ) {
            $ads->setStatus(Ads::STATUS_DISABLED);
            $this->em->persist($ads);
        }
        $ad->setStatus(Ads::STATUS_DISPLAYED);
        $this->em->flush();
        $this->addFlash('success', 'L\'annonce a bien été diffusée');
        return $this->redirectToRoute('admin_ads_index');
    }

    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Ads $ad, Request $request): Response
    {
        $form = $this->createForm( AdsType::class, $ad );
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ) {
            $this->em->persist($ad);
            $this->em->flush();
            $this->addFlash('success', 'L\'annonce a bien été modifiée');
            return $this->redirectToRoute('admin_ads_index');
        }

        return $this->render('admin/ads/edit.html.twig', [
            'controller_name' => 'AdsController',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Ads $ad, Request $request): RedirectResponse
    {
        if($this->isCsrfTokenValid('delete_ad_' . $ad->getId(), $request->get('_token'))) {
            $ad->setIsActive(false);
            $this->em->flush();
            $this->addFlash('success', 'L\'annonce a bien été supprimée');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue');
        }
        return $this->redirectToRoute('admin_ads_index');
    }
}
