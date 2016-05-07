<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace RestrictAccess\Service\Authentication;

use RestrictAccess\Service\AbstractAuthentication;
use RestrictAccess\Service\AuthenticationInterface;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Stdlib\Exception\RuntimeException;

use Zend\Authentication\Adapter\Ldap as AuthenticationAdapterLdap;

use Zend\Db\Sql\Select;

use Zend\Crypt\Password\Bcrypt;

/**
 *
 *
 * LdapService
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class LdapService extends AbstractAuthentication implements AuthenticationInterface
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
     * @return LdapService Provides a fluent interface
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
     * Set Authentication Adapter
     *
     * @return LdapService Provides a fluent interface
     */
    public function setAuthenticationAdapter()
    {
        $authenticationAdapter = new AuthenticationAdapterLdap($this->getOptions());

        $this->authenticationAdapter = $authenticationAdapter;
        return $this;
    }

    /**
     * Persist - Save identity data
     *
     * @return void
     */
    public function persist()
    {
        $authenticationService = $this->getAuthenticationService();
        $authenticationAdapter = $authenticationService->getAdapter();
        $authenticationStorage = $authenticationService->getStorage();
        $authenticationStorage->write($authenticationAdapter->getAccountObject());
    }
}