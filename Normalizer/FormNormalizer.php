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

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;

/**
 * Symfony Form Normalizer.
 *
 * @author Ramazan APAYDIN <apaydin541@gmail.com>
 */
class FormNormalizer implements ContextAwareNormalizerInterface
{
    /**
     * @param FormInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $config = $object->getConfig();
        $form = [
            'method' => $config->getMethod(),
            'action' => $config->getAction(),
            'allow_file_upload' => $config->getOption('allow_file_upload'),
            'csrf_protection' => $config->getOption('csrf_protection'),
            'help' => $config->getOption('help'),
            'help_attr' => $config->getOption('help_attr'),
            'children' => [],
        ];

        foreach ($object->createView()->children as $child) {
            $form['children'][$child->vars['name']] = [
                'id' => $child->vars['id'],
                'name' => $child->vars['name'],
                'attr' => $child->vars['attr'],
                'label' => $child->vars['label'],
                'label_attr' => $child->vars['label_attr'],
                'disabled' => $child->vars['disabled'],
                'value' => $child->vars['value'],
                'type' => '_token' === $child->vars['name'] ? 'hidden' : $object->get($child->vars['name'])->getConfig()->getType()->getBlockPrefix(),
                'help' => $child->vars['help'],
                'required' => $child->vars['required'],
            ];
        }

        return $form;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FormInterface && !$data->isSubmitted();
    }
}
