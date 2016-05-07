<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace RestrictAccess\Service;

interface AuthenticationInterface
{
    /**
     * Set namespace
     *
     * @throws Exception\RuntimeException
     * @param string $namespace
     * @return AuthenticationInterface Provides a fluent interface
     */
    public function setNamespace($namespace);

    /**
     * Get namespace
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getNamespace();

    /**
     * Set Authentication Adapter
     *
     * @return AuthenticationInterface Provides a fluent interface
     */
    public function setAuthenticationAdapter();

    /**
     * Get Authentication Adapter
     *
     * @return Zend\Authentication\Adapter\AdapterInterface
     */
    public function getAuthenticationAdapter();

    /**
     * Set Authentication Storage
     *
     * @return AuthenticationInterface Provides a fluent interface
     */
    public function setAuthenticationStorage();

    /**
     * Get Authentication Storage
     *
     * @return Zend\Authentication\Storage\StorageInterface
     */
    public function getAuthenticationStorage();

    /**
     * Set Authentication Service
     *
     * @return AuthenticationInterface Provides a fluent interface
     */
    public function setAuthenticationService();

    /**
     * Get Authentication Service
     *
     * @return Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService();

    /**
     * Persist - Save identity data
     *
     * @return void
     */
    public function persist();

    /**
     * Authenticate
     *
     * @return Zend\Authentication\Result
     */
    public function authenticate($identity, $credential);

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity();
}