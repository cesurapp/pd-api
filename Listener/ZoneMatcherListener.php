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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ZoneMatcherListener implements EventSubscriberInterface
{
    public function __construct(private ParameterBagInterface $bag)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        foreach ($this->bag->get('pd_api.zone') as $zone) {
            if (preg_match("{{$zone}}", $request->getPathInfo())) {
                $request->attributes->set(PdApiBundle::ZONE_ATTRIBUTE, true);

                return;
            }
        }

        $request->attributes->set(PdApiBundle::ZONE_ATTRIBUTE, false);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 248]],
        ];
    }
}
