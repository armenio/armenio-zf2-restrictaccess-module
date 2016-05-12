<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace RestrictAccess\Acl;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * AclServiceFactory
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class AclServiceFactory implements FactoryInterface
{
    /**
     * Create a Acl instance.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Acl
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate
        $acl = new Acl();
        return $acl;
    }

    /**
     * zend-servicemanager v2 factory for creating Acl instance.
     *
     * Proxies to `__invoke()`.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns Acl
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Acl::class);
    }
}
