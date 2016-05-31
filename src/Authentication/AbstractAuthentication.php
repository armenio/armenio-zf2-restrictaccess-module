<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace Armenio\RestrictAccess\Authentication;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Stdlib\Exception\RuntimeException;

use Zend\Authentication\AuthenticationService;
use Armenio\RestrictAccess\Authentication\Storage\Session as AuthenticationStorageSession;

abstract class AbstractAuthentication extends AuthenticationService implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    
    /**
     * @var string
     */
    protected $namespace;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set storage namespace
     *
     * @throws Exception\RuntimeException
     * @param string $storageNamespace
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function setStorageNamespace($storageNamespace)
    {
        $storageNamespace = trim($storageNamespace);
        if( empty($storageNamespace) ){
            throw new RuntimeException('Invalid Storage Namespace');
        }
        $this->storageNamespace = sprintf('Zend_Auth_%s', $storageNamespace);

        $this->setStorage(new AuthenticationStorageSession($this->getStorageNamespace()));

        return $this;
    }

    /**
     * Get storage namespace
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getStorageNamespace()
    {
        if( empty($this->storageNamespace) ){
            throw new RuntimeException('Empty Storage Namespace');
        }
        return $this->storageNamespace;
    }
    
    /**
     * Set the TTL (in seconds) for the session cookie expiry
     *
     * Can safely be called in the middle of a session.
     *
     * @param  null|int $ttl
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function rememberMe($ttl = null)
    {
        $this->getStorage()->getManager()->rememberMe($ttl);
        return $this;
    }
    
    /**
     * Set a 0s TTL for the session cookie
     *
     * Can safely be called in the middle of a session.
     *
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function forgetMe()
    {
        $this->getStorage()->getManager()->forgetMe();
        return $this;
    }

    /**
     * doAuthenticate
     *
     * @param  mixed          $identity
     * @param  mixed           $credential
     * @return Zend\Authentication\Result
     */
    public function doAuthenticate($identity, $credential)
    {
        $this->prepareAdapter();

		$adapter = $this->getAdapter();
        $adapter->setIdentity($identity);
        $adapter->setCredential($credential);

        $authenticationResult = $this->authenticate();

        if( $authenticationResult->isValid() ){
            $this->persist();
        }

        return $authenticationResult;
    }
}