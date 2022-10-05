<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class VerifyEmailSubscriber implements EventSubscriberInterface
{

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user->isVerified()) {

            throw new AuthenticationException("Merci de vérifier vos email et valider votre compte.");
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onAuthenticationSuccess',
        ];
    }

}
