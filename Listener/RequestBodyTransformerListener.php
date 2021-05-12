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

use Pd\ApiBundle\Exception\InvalidRequestBodyException;
use Pd\ApiBundle\PdApiBundle;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class RequestBodyTransformerListener implements EventSubscriberInterface
{
    public function __construct(private DecoderInterface $decoder)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->attributes->get(PdApiBundle::ZONE_ATTRIBUTE)) {
            return;
        }

        // XML/JSON Body Decode
        $content = $request->getContent();
        if ($content && \in_array($request->getContentType(), ['xml', 'json'], true)) {
            try {
                $request->request->replace(
                    $this->decoder->decode($content, $request->getContentType())
                );
            } catch (\Exception $e) {
                throw new InvalidRequestBodyException();
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 100]]];
    }
}
