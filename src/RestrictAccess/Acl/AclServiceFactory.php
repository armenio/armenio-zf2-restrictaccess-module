<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace RestrictAccess\Acl;

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
     * zend-servicemanager v2 factory for creating Acl instance.
     *
     * Proxies to `__invoke()`.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns Acl
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $acl = new Acl();
        return $acl;
    }
}
