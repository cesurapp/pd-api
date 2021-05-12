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

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

/**
 * Symfony Form Error Normalizer.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class FormErrorNormalizer implements ContextAwareNormalizerInterface
{
    /**
     * @param FormInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = [
            'code' => $context['status_code'] ?? 406,
            'type' => 'FormError',
            'message' => [],
            'errors' => [],
        ];

        /** @var FormErrorIterator $errors */
        $errors = $object->getErrors(true, true);
        foreach ($errors as $error) {
            if ($error->getOrigin()->isRoot()) {
                $data['message'][] = $error->getMessage();
            } else {
                $data['errors'][$error->getOrigin()?->getName()][] = $error->getMessage();
            }
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FormInterface && $data->isSubmitted() && !$data->isValid();
    }
}
