<?php

declare(strict_types=1);

namespace Laminas\View\Helper\Service;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\View\Exception;
use Laminas\View\Helper\Asset;

use function is_array;
use function method_exists;

class AssetFactory implements FactoryInterface
{
    /**
     * @param string $name
     * @param null|array $options
     * @return Asset
     * @throws Exception\RuntimeException
     */
    public function __invoke(ContainerInterface $container, $name, ?array $options = null)
    {
        // test if we are using Laminas\ServiceManager v2 or v3
        if (! method_exists($container, 'configure')) {
            $container = $container->getServiceLocator();
        }
        $helper = new Asset();

        $config = $container->get('config');
        if (isset($config['view_helper_config']['asset'])) {
            $configHelper = $config['view_helper_config']['asset'];
            if (isset($configHelper['resource_map']) && is_array($configHelper['resource_map'])) {
                $helper->setResourceMap($configHelper['resource_map']);
            } else {
                throw new Exception\RuntimeException('Invalid resource map configuration.');
            }
        }

        return $helper;
    }

    /**
     * Create service
     *
     * @param string|null $rName
     * @param string|null $cName
     * @return Asset
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $rName = null, $cName = null)
    {
        return $this($serviceLocator, $cName);
    }
}