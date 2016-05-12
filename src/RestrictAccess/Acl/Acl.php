<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace RestrictAccess\Acl;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Stdlib\Exception\RuntimeException;

class Acl
{
    /**
     * $roleResourcePrivileges - Acl data
     *
     * @var mixed
     */
    protected $roleResourcePrivileges;

    /**
     * @var Zend\Permissions\Acl\Acl
     */
    protected $aclService;

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
     * Set Acl Service
     *
     * @return Acl Provides a fluent interface
     */
    public function setAclService()
    {
        $aclService = new ZendAcl();
        
        $this->aclService = $aclService;
        return $this;
    }

    /**
     * Get Acl Service
     *
     * @return Zend\Permissions\Acl\Acl
     */
    public function getAclService()
    {
        if( $this->aclService === null ){
            $this->setAclService();
        }

        return $this->aclService;
    }

    /**
     * Add Role Resource Privileges - Build Acl
     *
     * @return void
     */
    public function addRoleResourcePrivileges()
    { 
        $aclService = $this->getAclService();

        foreach($this->roleResourcePrivileges as $roleName => $resources){
            if (! $aclService->hasRole($roleName)) {
                $aclService->addRole(new Role($roleName));
            }
            
            foreach($resources as $resourceName => $privileges){
                if ( ! $aclService->hasResource($resourceName)) {
                    $aclService->addResource(new Resource($resourceName));
                }

                foreach ($privileges as $privilegeName) {
                    $aclService->allow($roleName, $resourceName, $privilegeName);
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
        $aclService = $this->getAclService();

        if (! $aclService->hasRole($role)) {
            return false;
        }

        if ( ! $aclService->hasResource($resource)) {
            return false;
        }

        return $aclService->isAllowed($role, $resource, $privilege);
    }
}