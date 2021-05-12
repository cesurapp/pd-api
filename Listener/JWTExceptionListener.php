<?php

namespace Pd\ApiBundle\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Pd\ApiBundle\Services\AcceptContentType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class JWTExceptionListener implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private TranslatorInterface $translator,
        private AcceptContentType $accept)
    {
    }

    public function onJWTAuthFailure(AuthenticationFailureEvent $event): void
    {
        $event->setResponse($this->handleException($event->getResponse(), (new \ReflectionClass($event->getException()))->getShortName()));
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse($this->handleException($event->getResponse(), 'InvalidTokenException'));
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $event->setResponse($this->handleException($event->getResponse(), 'ExpiredTokenException'));
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $event->setResponse($this->handleException($event->getResponse(), 'MissingTokenException'));
    }

    private function handleException(Response $response, string $type): Response
    {
        // Create Exception Response
        $message = json_decode($response->getContent(), true)['message'];
        $response->setContent($this->serializer->serialize([
            'message' => $this->translator->trans($message, [], 'exception'),
            'code' => $response->getStatusCode(),
            'type' => $type
        ], $this->accept->getAcceptType()));

        // Set Accept Content Type
        $response->headers->set('Content-Type', 'application/' . $this->accept->getAcceptType());

        return $response;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_FAILURE => [['onJWTAuthFailure']],
            Events::JWT_INVALID => [['onJWTInvalid']],
            Events::JWT_EXPIRED => [['onJWTExpired']],
            Events::JWT_NOT_FOUND => [['onJWTNotFound']],
        ];
    }
}
