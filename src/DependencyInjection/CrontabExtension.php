<?php

namespace BenTools\CrontabBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class CrontabExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('bentools_crontab.dist_file', isset($config['dist_file']) ? $config['dist_file'] : null);


        $loader = new YamlFileLoader($container, new FileLocator([__DIR__ . '/../Resources/config/']));
        $loader->load('services.yaml');
    }


    /**
     * @inheritDoc
     */
    public function getAlias()
    {
        return 'bentools_crontab';
    }
}
