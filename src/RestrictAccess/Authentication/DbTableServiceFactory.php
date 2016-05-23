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
 * DbTableServiceFactory
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class DbTableServiceFactory implements FactoryInterface
{
    /**
     * zend-servicemanager v2 factory for creating DbTable instance.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns DbTable
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $dbTable = new DbTable();
        return $dbTable;
    }
}
