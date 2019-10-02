<?php

namespace saltyPerformanceAnalysis;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class saltyPerformanceAnalysis extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatch_Backend_Base' => 'extendExtJS'
        ];
    }

    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('salty_performance_analysis.plugin_dir', $this->getPath());
        parent::build($container);
    }

    public function install(InstallContext $context)
    {

    }
}
