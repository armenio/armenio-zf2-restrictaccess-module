# armenio-zf2-restrictaccess-module
The Restrict Access Module for Zend Framework 2

## How to install


1. Install via composer. Don't know how? [Look here](http://getcomposer.org/doc/00-intro.md#introduction)

2. `cd my/project/directory`

3. Edit composer.json :

	```json
	{
		"require": {
			"armenio/armenio-zf2-restrictaccess-module": "1.*"
		}
	}
	```

4. Edit config/application.config.php :

	```php
	'modules' => array(
		 'Application',
		 'RestrictAccess', //<==============================
	)
	```

5. Edit module/config/module.config.php

	```php
		'service_manager' => array(
	        'factories' => array(
	            'AuthenticationService' => function(\Zend\ServiceManager\ServiceManager $serviceManager) {
	                $service = new \RestrictAccess\Service\Authentication\DbTableService();
	                // $service = new \RestrictAccess\Service\Authentication\LdapService();
	                
	                $service->setServiceManager($serviceManager);

	                return $service;
	            }
	        ),
	    ),
	```

6. Usage inside Controllers

	6.1 Use with Zend\Db

	```php
	$username = $data['username'];
	$password = $data['password'];

	$authService = $this->getServiceLocator()->get('AuthenticationService');

	$authService->setNamespace('Default');
	$authService->setTableName('users');
	$authService->setIdentityColumn('username');
	$authService->setCredentialColumn('password');

	$authenticationResult = $authService->authenticate($username, $password);

	if( ! $authenticationResult->isValid() ){
		var_dump($authenticationResult->getMessages());
	}
	// else var_dump($authService->getIdentity());
	```

	6.2 Use with Zend\Ldap

	```php
	$username = $post['username'];
	$password = $post['password'];

	$ldapOptions = array(
		'server1' => array(
			'host' => 'dc1.w.net',
			'useStartTls' => 'false',
			'useSsl' => 'false',
			'baseDn' => 'CN=Users,DC=w,DC=net',
			'accountCanonicalForm' => 3,
			'accountDomainName' => 'w.net',
			'accountDomainNameShort' => 'W',
		),
	);

	$authService = $this->getServiceLocator()->get('AuthenticationService');

	$authService->setNamespace('Default');
	$authService->setOptions($ldapOptions);

	$authenticationResult = $authService->authenticate($username, $password);

	if( ! $authenticationResult->isValid() ){
		var_dump($authenticationResult->getMessages());
	}
	// else var_dump($authService->getIdentity());
	```

7. Getting user identity

	```php
	if( $authService->hasIdentity() ){
		var_dump($authService->getIdentity());
	}
	```