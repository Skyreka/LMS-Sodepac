<?php

namespace App\Http\Controller;

use App\Domain\Auth\Form\UserType;
use App\Domain\Auth\Repository\UsersRepository;
use App\Domain\Product\Entity\Products;
use App\Domain\Product\Repository\ProductsRepository;
use App\Http\Form\PasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AccountController
 * @package App\Controller
 * @Route("/account")
 */
class  AccountController extends AbstractController
{
    public function __construct(
        private readonly UsersRepository $repository,
        private readonly EntityManagerInterface $em
    )
    {
    }

    /**
     * @Route("/", name="account_index", methods={"GET", "POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        if(! $user) {
            throw $this->createNotFoundException('Utilisateur introuvable.');
        }

        // User information
        $form = $this->createForm(UserType::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Informations éditées avec succès');
            return $this->redirectToRoute('account_index');
        }

        // User password
        $formPassword = $this->createForm(PasswordType::class, $user);
        $formPassword->handleRequest($request);

        if($formPassword->isSubmitted() && $formPassword->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $formPassword['password']->getData()));
            // Disable reset of technician edit pass of user
            $user->setReset(0);
            $this->em->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
            return $this->redirectToRoute('account_index');
        } elseif($formPassword->isSubmitted() && $formPassword->isValid() == false) {
            $this->addFlash('danger', 'Une erreur est survenue. Le mot de passe doit faire au moins 6 caractères et les 2 identiques.');
            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/index.html.twig', [
            'form' => $form->createView(),
            'form_password' => $formPassword->createView()
        ]);
    }

    //TODO DELETE
    /**
     * @Route("/api/product/{productSlug}", name="api_get_product_by_slug")
     */
    public function getProductBySlug($productSlug, ProductsRepository $pr)
    {
        $product = $pr->findOneBy(['slug' => $productSlug]);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        return new JsonResponse(['name' => $product->getName()]);
    }

}
