<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AcceptContentType
{
    private ?string $type = null;

    public function __construct(private RequestStack $requestStack, private ParameterBagInterface $bag)
    {
    }

    public function getAcceptType(): string
    {
        if (!$this->type) {
            $request = $this->requestStack->getCurrentRequest();
            $this->type = $request->getFormat($request->headers->get('Accept'));

            if (!\in_array($this->type, $this->bag->get('pd_api.allow_accept'), true)) {
                $this->type = $this->bag->get('pd_api.default_accept');
            }
        }

        return $this->type;
    }
}
