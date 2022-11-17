<?php

namespace App\Http\Controller;

use App\Domain\Auth\Users;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * Display array of error from Form to Flash Error Message
     */
    protected function flashErrors(FormInterface $form): void
    {
        $errors   = $form->getErrors();
        $messages = [];
        foreach($errors as $error) {
            $messages[] = $error->getMessage();
        }
        $this->addFlash('danger', implode("\n", $messages));
    }

    protected function getUserOrThrow(): Users
    {
        $user = $this->getUser();

        if(! ($user instanceof Users)) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    /*
     * Redirect user to last page or route if fallback
     */
    protected function redirectBack(string $route, array $params = []): RedirectResponse
    {
        /** @var RequestStack $stack */
        $stack   = $this->get('request_stack');
        $request = $stack->getCurrentRequest();
        if($request && $request->server->get('HTTP_REFERER')) {
            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        return $this->redirectToRoute($route, $params);
    }
}
