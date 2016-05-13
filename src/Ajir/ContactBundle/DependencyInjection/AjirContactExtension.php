<?php

namespace Ajir\ContactBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AjirContactExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');

        // Once the services definition are read, get your service and add a method call to setConfig()
        $formHandlerDefintion = $container->getDefinition('ajir_contact.form.handler');
        $formHandlerDefintion->addMethodCall('setConfig', array($processedConfig['contact_email']));
    }
}
