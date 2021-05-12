<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class InvalidRequestBodyException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('Invalid request body');
    }
}
