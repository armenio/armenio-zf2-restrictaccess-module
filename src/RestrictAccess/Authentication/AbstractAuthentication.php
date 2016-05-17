<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace RestrictAccess\Authentication;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\Exception\RuntimeException;

use Zend\Authentication\AuthenticationService as AuthenticationService;
use Zend\Authentication\Storage\Session as AuthenticationStorageSession;

abstract class AbstractAuthentication implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var Zend\Authentication\Adapter\AdapterInterface
     */
    protected $authenticationAdapter;

    /**
     * @var Zend\Authentication\Storage\StorageInterface
     */
    protected $authenticationStorage;

    /**
     * @var Zend\Authentication\AuthenticationService
     */
    protected $authenticationService;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set namespace
     *
     * @throws Exception\RuntimeException
     * @param string $namespace
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function setNamespace($namespace)
    {
        $namespace = trim($namespace);
        if( empty($namespace) ){
            throw new RuntimeException('Invalid Namespace');
        }
        $this->namespace = sprintf('Zend_Auth_%s', $namespace);
        return $this;
    }

    /**
     * Get namespace
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getNamespace()
    {
        if( empty($this->namespace) ){
            throw new RuntimeException('Empty Namespace');
        }
        return $this->namespace;
    }

    /**
     * Get Authentication Adapter
     *
     * @return Zend\Authentication\Adapter\AdapterInterface
     */
    public function getAuthenticationAdapter()
    {
        if( $this->authenticationAdapter === null ){
            $this->setAuthenticationAdapter();
        }

        return $this->authenticationAdapter;
    }

    /**
     * Set Authentication Storage
     *
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function setAuthenticationStorage()
    {
        $this->authenticationStorage = new AuthenticationStorageSession($this->getNamespace());
        return $this;
    }

    /**
     * Get Authentication Storage
     *
     * @return Zend\Authentication\Storage\StorageInterface
     */
    public function getAuthenticationStorage()
    {
        if( $this->authenticationStorage === null ){
            $this->setAuthenticationStorage();
        }

        return $this->authenticationStorage;
    }

    /**
     * Set Authentication Service
     *
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function setAuthenticationService()
    {
        $authenticationService = new AuthenticationService();
        
        $authenticationService->setStorage($this->getAuthenticationStorage());

        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * Get Authentication Service
     *
     * @return Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        if( $this->authenticationService === null ){
            $this->setAuthenticationService();
        }

        return $this->authenticationService;
    }

    /**
     * Authenticate
     *
     * @param  mixed          $identity
     * @param  mixed           $credential
     * @return Zend\Authentication\Result
     */
    public function authenticate($identity, $credential)
    {
		$authenticationService = $this->getAuthenticationService();

        $authenticationAdapter = $this->getAuthenticationAdapter();
        $authenticationAdapter->setIdentity($identity);
        $authenticationAdapter->setCredential($credential);

        $authenticationService->setAdapter($authenticationAdapter);

        $authenticationResult = $authenticationService->authenticate();

        if( $authenticationResult->isValid() ){
            $this->persist();
        }

        return $authenticationResult;
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        $authenticationService = $this->getAuthenticationService();

        if( ! $authenticationService->hasIdentity() ){
        	return null;
        }

        return $this->getAuthenticationService()->getIdentity();
    }
}