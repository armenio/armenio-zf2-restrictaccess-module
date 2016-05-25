<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace Armenio\RestrictAccess\Authentication;

use Armenio\RestrictAccess\Authentication\AbstractAuthentication;
use Armenio\RestrictAccess\Authentication\AuthenticationInterface;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Stdlib\Exception\RuntimeException;

use Zend\Authentication\Adapter\Ldap as AuthenticationAdapterLdap;

/**
 *
 *
 * Ldap
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class Ldap extends AbstractAuthentication implements AuthenticationInterface
{
    /**
     * $options - Ldap options
     *
     * @var mixed
     */
    protected $options;

     /**
     * Set Options
     *
     * @param  mixed $options
     * @throws Exception\InvalidArgumentException
     * @return Ldap Provides a fluent interface
     */
    public function setOptions($options)
    {
        if( ! is_array($options) ){
            throw new InvalidArgumentException('Invalid Options');
        }
        $this->options = $options;
        return $this;
    }

    /**
     * Get Options
     *
     * @throws Exception\RuntimeException
     * @return mixed
     */
    public function getOptions()
    {
        if( empty($this->options) ){
            throw new RuntimeException('Empty Options');
        }
        return $this->options;
    }

    /**
     * Prepare Authentication Adapter
     *
     * @return Ldap Provides a fluent interface
     */
    public function prepareAdapter()
    {
        $this->setAdapter(new AuthenticationAdapterLdap($this->getOptions()));
        return $this;
    }

    /**
     * Persist - Save identity data
     *
     * @return void
     */
    public function persist()
    {
        $this->getStorage()->write($this->getAdapter()->getAccountObject());
    }
}