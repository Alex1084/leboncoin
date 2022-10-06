<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class VerifyUserSubscriber implements EventSubscriberInterface
{
    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if (!$user->isVerified()) {

            throw new AuthenticationException("Merci de vÃ©rifier vos email et valider votre compte.");
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }
}
