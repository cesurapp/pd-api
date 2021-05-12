<?php

/**
 * This file is part of the pd-admin pd-api package.
 *
 * @package     pd-api
 * @license     LICENSE
 * @author      Ramazan APAYDIN <apaydin541@gmail.com>
 * @link        https://github.com/appaydin/pd-api
 */

namespace Pd\ApiBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class PdApiExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        // Load Configuration
        $configuration = new Configuration();
        $configs = $this->processConfiguration($configuration, $configs);

        // Set Configuration
        foreach ($configs as $key => $value) {
            $container->setParameter('pd_api.'.$key, $value);
        }

        // Load Services
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $container->loadFromExtension('twig', [
            'paths' => [__DIR__.'/../Resources/views/NelmioApiDocBundle' => 'NelmioApiDoc'],
        ]);
    }
}
