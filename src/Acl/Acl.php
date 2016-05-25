<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace Armenio\RestrictAccess\Acl;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Stdlib\Exception\RuntimeException;

class Acl extends ZendAcl implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    /**
     * $roleResourcePrivileges - Acl data
     *
     * @var mixed
     */
    protected $roleResourcePrivileges;

    /**
     * @var Zend\Permissions\Acl\Acl
     */
    protected $this;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set Role Resource Privileges
     *
     * @param  mixed $roleResourcePrivileges
     * @throws Exception\InvalidArgumentException
     * @return Acl Provides a fluent interface
     */
    public function setRoleResourcePrivileges($roleResourcePrivileges)
    {
        if( empty($roleResourcePrivileges) ){
            throw new InvalidArgumentException('Invalid Role Resource Privileges');
        }
        $this->roleResourcePrivileges = $roleResourcePrivileges;
        $this->addRoleResourcePrivileges();
        return $this;
    }

    /**
     * Get Role Resource Privileges
     *
     * @throws Exception\RuntimeException
     * @return mixed
     */
    public function getRoleResourcePrivileges()
    {
        if( empty($this->roleResourcePrivileges) ){
            throw new RuntimeException('Empty Role Resource Privileges');
        }
        return $this->roleResourcePrivileges;
    }

    /**
     * Add Role Resource Privileges - Build Acl
     *
     * @return void
     */
    public function addRoleResourcePrivileges()
    {
        foreach($this->roleResourcePrivileges as $roleName => $resources){
            if (! $this->hasRole($roleName)) {
                $this->addRole(new Role($roleName));
            }
            
            foreach($resources as $resourceName => $privileges){
                if ( ! $this->hasResource($resourceName)) {
                    $this->addResource(new Resource($resourceName));
                }

                foreach ($privileges as $privilegeName) {
                    $this->allow($roleName, $resourceName, $privilegeName);
                }
            }
        }
    }

    /**
     * Returns true if and only if the Role has access to the Resource
     *
     * @param  Zend\Permissions\Acl\Role\RoleInterface|string           $role
     * @param  Zend\Permissions\Acl\Resource\ResourceInterface|string   $resource
     * @param  string                                                   $privilege
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        if (! $this->hasRole($role)) {
            return false;
        }

        if ( ! $this->hasResource($resource)) {
            return false;
        }

        return parent::isAllowed($role, $resource, $privilege);
    }
}