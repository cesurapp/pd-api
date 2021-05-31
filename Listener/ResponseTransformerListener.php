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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseTransformerListener implements EventSubscriberInterface
{
    public function __construct(private SerializerInterface $serializer,
                                private AcceptContentType $accept)
    {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->attributes->get(PdApiBundle::ZONE_ATTRIBUTE)) {
            return;
        }

        // Check Master Request || Response Object
        if (!$event->isMainRequest() || ($result = $event->getControllerResult()) instanceof Response) {
            return;
        }

        // Create Response
        $result = $this->serializer->serialize($result, $this->accept->getAcceptType());
        $response = (new Response())->setContent($result)->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/'.$this->accept->getAcceptType());
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::VIEW => [['onKernelView']]];
    }
}
