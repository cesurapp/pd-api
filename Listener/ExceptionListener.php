<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\Listener;

use Pd\ApiBundle\PdApiBundle;
use Pd\ApiBundle\Services\AcceptContentType;
use ReflectionClass;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionListener implements EventSubscriberInterface
{
    public function __construct(private SerializerInterface $serializer,
                                private TranslatorInterface $translator,
                                private AcceptContentType $accept)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->attributes->get(PdApiBundle::ZONE_ATTRIBUTE)) {
            return;
        }

        // Create Exception Message
        $exception = $event->getThrowable();
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();
        $error = [
            'message' => $this->translator->trans($exception->getMessage(), [], 'exception'),
            'code' => $statusCode ?: 500,
            'type' => (new ReflectionClass($exception))->getShortName(),
        ];

        // Replace Response
        $response = (new Response())
            ->setContent($this->serializer->serialize($error, $this->accept->getAcceptType()))
            ->setStatusCode($error['code']);
        $response->headers->set('Content-Type', 'application/'.$this->accept->getAcceptType());
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => [['onKernelException', 50]]];
    }
}
