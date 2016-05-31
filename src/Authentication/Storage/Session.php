<?php
/**
 * Rafael Armenio <rafael.armenio@gmail.com>
 *
 * @link http://github.com/armenio for the source repository
 */

namespace Armenio\RestrictAccess\Authentication\Storage;

use Zend\Authentication\Storage\Session as ZendAuthenticationStorageSession;

class Session extends ZendAuthenticationStorageSession
{
	public function getManager()
	{
		return $this->session->getManager();
	}
	
	public function rememberMe($ttl = null)
    {
        $this->session->getManager()->rememberMe($ttl);
    }
    
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}