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
 * DbTableServiceFactory
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class DbTableServiceFactory implements FactoryInterface
{
    /**
     * Create a DbTable instance.
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return DbTable
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Instantiate
        $dbTable = new DbTable();
        $zendDb = $container->get('Zend\Db\Adapter');
        $dbTable->setZendDb($zendDb);
        return $dbTable;
    }

    /**
     * zend-servicemanager v2 factory for creating DbTable instance.
     *
     * Proxies to `__invoke()`.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @returns DbTable
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, DbTable::class);
    }
}
