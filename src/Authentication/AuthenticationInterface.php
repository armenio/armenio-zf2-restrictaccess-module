<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace Armenio\RestrictAccess\Authentication;

interface AuthenticationInterface
{
    /**
     * Set storage namespace
     *
     * @throws Exception\RuntimeException
     * @param string $storageNamespace
     * @return AbstractAuthentication Provides a fluent interface
     */
    public function setStorageNamespace($storageNamespace);

    /**
     * Get storage namespace
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getStorageNamespace();

    /**
     * Prepare Authentication Adapter
     *
     * @return AuthenticationInterface Provides a fluent interface
     */
    public function prepareAdapter();

    /**
     * Persist - Save identity data
     *
     * @return void
     */
    public function persist();

    /**
     * doAuthenticate
     *
     * @return Zend\Authentication\Result
     */
    public function doAuthenticate($identity, $credential);
}