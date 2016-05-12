<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace RestrictAccess\Authentication;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * LdapServiceFactory
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class LdapServiceFactory implements FactoryInterface
{
    /**
     * Create a Ldap instance.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return Ldap
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate
        $ldap = new Ldap();
        return $ldap;
    }

    /**
     * zend-servicemanager v2 factory for creating Ldap instance.
     *
     * Proxies to `__invoke()`.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns Ldap
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, Ldap::class);
    }
}
