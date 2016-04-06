<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

	protected $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate(){
		// Производим стандартную аутентификацию, описанную в руководстве.
		$user = User::model()->find('username=?', array($this->username));
		if(!isset($user))
			$user = User::model()->find('email=?', array($this->username));
		if(($user===null) || $user->type == User::TYPE_SOC_USER || (!$user->validatePassword($this->password))) {
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		} elseif ($user->status != User::STATUS_ACTIVE)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		else {

			$this->_id = $user->id;

			$this->errorCode = self::ERROR_NONE;
		}
		return !$this->errorCode;
	}

	public function getId(){
		return $this->_id;
	}


}