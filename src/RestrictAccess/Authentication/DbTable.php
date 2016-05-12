<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */
 
namespace RestrictAccess\Authentication;

use RestrictAccess\Authentication\AbstractAuthentication;
use RestrictAccess\Authentication\AuthenticationInterface;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Stdlib\Exception\RuntimeException;

use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthenticationAdapterDbTable;

use Zend\Db\Sql\Select;

use Zend\Crypt\Password\Bcrypt;

/**
 *
 *
 * DbTable
 * @author Rafael Armenio <rafael.armenio@gmail.com>
 *
 *
 */
class DbTable extends AbstractAuthentication implements AuthenticationInterface
{
    /**
     * Database Connection
     *
     * @var DbAdapter
     */
    protected $zendDb;

    /**
     * $tableName - the table name to check
     *
     * @var string
     */
    protected $tableName;

    /**
     * $identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $identityColumn;

    /**
     * $credentialColumns - columns to be used as the credentials
     *
     * @var string
     */
    protected $credentialColumn;

    /**
     * $credentialValidationCallback - This overrides the Treatment usage to provide a callback
     * that allows for validation to happen in code
     *
     * @var callable
     */
    protected $credentialValidationCallback;

    /**
     * $checkStatusColumn - check if status column equals 1
     *
     * @var string
     */
    protected $checkStatusColumn = true;

    /**
     * $joinTables - join tables to build identity details
     *
     * @var mixed
     */
    protected $joinTables;

    /**
     * $cryptCost - Bcrypt cost
     *
     * @var int
     */
    protected $cryptCost = 14;

    public function setZendDb(\Zend\Db\Adapter\Adapter $zendDb)
    {
        $this->zendDb = $zendDb;
        return $this;
    }

    public function getZendDb()
    {
        if( $this->zendDb === null ){
            // $zendDb = $this->getServiceManager()->get('Zend\Db\Adapter');

            // $this->setZendDb($zendDb);
        }

        return $this->zendDb;
    }

    /**
     * Set Table Name
     *
     * @param  string $tableName
     * @throws Exception\InvalidArgumentException
     * @return DbTable Provides a fluent interface
     */
    public function setTableName($tableName)
    {
        $tableName = trim($tableName);
        if( empty($tableName) ){
            throw new InvalidArgumentException('Invalid Table Name');
        }
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * Get Table Name
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getTableName()
    {
        if( empty($this->tableName) ){
            throw new RuntimeException('Empty Table Name');
        }
        return $this->tableName;
    }

    /**
     * Set Identity Column
     *
     * @param  string $identityColumn
     * @throws Exception\InvalidArgumentException
     * @return DbTable Provides a fluent interface
     */
    public function setIdentityColumn($identityColumn)
    {
        $identityColumn = trim($identityColumn);
        if( empty($identityColumn) ){
            throw new InvalidArgumentException('Invalid Identity Column');
        }
        $this->identityColumn = $identityColumn;
        return $this;
    }

    /**
     * Get Identity Column
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getIdentityColumn()
    {
        if( empty($this->identityColumn) ){
            throw new RuntimeException('Empty Identity Column');
        }
        return $this->identityColumn;
    }

    /**
     * Set Credential Column
     *
     * @param  string $credentialColumn
     * @throws Exception\InvalidArgumentException
     * @return DbTable Provides a fluent interface
     */
    public function setCredentialColumn($credentialColumn)
    {
        $credentialColumn = trim($credentialColumn);
        if( empty($credentialColumn) ){
            throw new InvalidArgumentException('Invalid Credential Column');
        }
        $this->credentialColumn = $credentialColumn;
        return $this;
    }

    /**
     * Get Credential Column
     *
     * @throws Exception\RuntimeException
     * @return string
     */
    public function getCredentialColumn()
    {
        if( empty($this->credentialColumn) ){
            throw new RuntimeException('Empty Credential Column');
        }
        return $this->credentialColumn;
    }

    /**
     * Set Credential Validation Callback
     *
     * @return DbTable Provides a fluent interface
     */
    public function setCredentialValidationCallback()
    {
        $this->credentialValidationCallback = function($dbCredential, $requestCredential) {
            $bcrypt = new Bcrypt();
            $bcrypt->setCost($this->getCryptCost());

            return $bcrypt->verify($requestCredential, $dbCredential);
        };
        return $this;
    }

    /**
     * Get Credential Validation Callback
     *
     * @return callable
     */
    public function getCredentialValidationCallback()
    {
        if( $this->credentialValidationCallback === null ){
        	$this->setCredentialValidationCallback();
        }

        return $this->credentialValidationCallback;
    }

    /**
     * Set Check Status Column
     *
     * @param  boolean $checkStatusColumn
     * @return DbTable Provides a fluent interface
     */
    public function setCheckStatusColumn($checkStatusColumn)
    {
        $this->checkStatusColumn = $checkStatusColumn;
        return $this;
    }

    /**
     * Get Check Status Column
     *
     * @return boolean
     */
    public function getCheckStatusColumn()
    {
        return $this->checkStatusColumn;
    }

    /**
     * Set Join Tables
     *
     * @param  mixed $joinTables
     * @return DbTable Provides a fluent interface
     */
    public function setJoinTables($joinTables)
    {
        $this->joinTables = $joinTables;
        return $this;
    }

    /**
     * Add Join Table
     *
     * @param  mixed $joinTable
     * @throws Exception\InvalidArgumentException
     * @return DbTable Provides a fluent interface
     */
    public function addJoinTable($joinTable)
    {
        if( ! isset($joinTable['name']) ){
            throw new InvalidArgumentException('Invalid Join Table Name');
        }

        if( ! isset($joinTable['on']) ){
            $joinTable['on'] = sprintf('%s.id = %s.%s_id', $joinTable['name'], $this->getTableName(), $joinTable['name']);
        }

        if( ! isset($joinTable['columns']) ){
            $joinTable['columns'] = Select::SQL_STAR;
        }

        if( ! isset($joinTable['type']) ){
            $joinTable['type'] = Select::JOIN_INNER;
        }

        $this->joinTables[] = $joinTable;
        return $this;
    }

    /**
     * Get Join Tables
     *
     * @return mixed
     */
    public function getJoinTables()
    {
        return $this->joinTables;
    }

     /**
     * Set Crypt Cost
     *
     * @param  int $cryptCost
     * @throws Exception\InvalidArgumentException
     * @return DbTable Provides a fluent interface
     */
    public function setCryptCost($cryptCost)
    {
        $cryptCost = (int) $cryptCost;
        if( empty($cryptCost) || $cryptCost < 1 ){
            throw new InvalidArgumentException('Invalid Crypt Cost');
        }
        $this->cryptCost = $cryptCost;
        return $this;
    }

    /**
     * Get Crypt Cost
     *
     * @return int
     */
    public function getCryptCost()
    {
        if( empty($this->cryptCost) ){
            throw new RuntimeException('Empty Crypt Cost');
        }
        return $this->cryptCost;
    }

    /**
     * Set Authentication Adapter
     *
     * @return DbTable Provides a fluent interface
     */
    public function setAuthenticationAdapter()
    {
        $authenticationAdapter = new AuthenticationAdapterDbTable($this->getZendDb(), $this->getTableName(), $this->getIdentityColumn(), $this->getCredentialColumn(), $this->getCredentialValidationCallback());
        
        $dbSelect = $authenticationAdapter->getDbSelect();

        if( $this->getCheckStatusColumn() === true ){
            $dbSelect->where(array(sprintf('%s.status', $this->getTableName()) => 1));
        }

        $joinTables = $this->getJoinTables();

        if( ! empty($joinTables) ){
            foreach ($joinTables as $joinTable) {
                if( ! empty($joinTable['name']) ){
                    $dbSelect->join($joinTable['name'], $joinTable['on'], $joinTable['columns'], $joinTable['type']);

                    if( $this->getCheckStatusColumn() === true ){
                        $dbSelect->where(array(sprintf('%s.status', $joinTable['name']) => 1));
                    }
                }
            }
        }

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
        $authenticationStorage->write($authenticationAdapter->getResultRowObject(null, array('created', 'updated', 'deleted', 'status', 'password')));
    }
}