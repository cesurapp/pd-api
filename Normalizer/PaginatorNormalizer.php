<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\Normalizer;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PaginatorNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(private ObjectNormalizer $normalizer)
    {
    }

    /**
     * @param PaginationInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'data' => [],
            'pager' => [
                'total' => $object->getTotalItemCount(),
                'page' => $object->getCurrentPageNumber(),
                'limit' => $object->getItemNumberPerPage(),
            ],
        ];

        foreach ($object->getItems() as $item) {
            $data['data'][] = $this->normalizer->normalize($item, $format, $context);
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface;
    }
}
