<?php

namespace ContainerGQBspH3;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/**
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getLuckyControllerService extends App_KernelDevDebugContainer
{
    /**
     * Gets the public 'App\Controller\LuckyController' shared autowired service.
     *
     * @return \App\Controller\LuckyController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).'/src/Controller/LuckyController.php';

        return $container->services['App\\Controller\\LuckyController'] = new \App\Controller\LuckyController();
    }
}
