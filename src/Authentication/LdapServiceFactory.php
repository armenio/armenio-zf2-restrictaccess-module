<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\RestrictAccess\Authentication;

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
     * zend-servicemanager v2 factory for creating Ldap instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns Ldap
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $ldap = new Ldap();
        return $ldap;
    }
}
