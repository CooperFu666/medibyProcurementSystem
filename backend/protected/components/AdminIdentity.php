<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class AdminIdentity extends CUserIdentity
{
    private $_id;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        $user = AdminModel::model()->find('username=:username',array(':username'=>$this->username));
        if ($user) {
            $session = Yii::app()->session;
            $sendCode = $session->itemAt('globeSms');
            $code = unserialize($sendCode);

            //测试用，正式环境需开启if-else注释
            if(empty($code))
            {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }else if ($code['phone'] != $user->phone || $code['code'] != $this->password || $code['times'] >= 3 ||
            		time() - $code['created_at'] > Yii::app()->params['expiredTime'] * 60)
            {
                $code['times'] +=1;
                $session->add('globeSms',serialize($code));
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            } else {
                $this->username = $user->username;
                //记录登录信息
                $user->login_at = time();
                $user->login_ip = Utils::getIp();
                $user->save();
                $this->_id = $user['id'];
                Yii::app()->user->setState('role_id', $user->role_id);
                $this->errorCode = self::ERROR_NONE;
            }
        } else {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
		return !$this->errorCode;
	}

    public function getId(){
           return $this->_id;
    }
}