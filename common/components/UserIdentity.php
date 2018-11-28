<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $uid;
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
		$user= array(
				'phone'=>$this->username,
				'password'=>$this->password,
		);

        $model = UserModel::model()->find(array(
            'condition' => 'phone=:phone and password=:password',
            'params' => array(':phone' => $user['phone'],':password' => UserModel::hashPassword($this->password)),
        ));
        if(!$model){
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            if($model->status==UserModel::STATUS_DISABLE){
                $this->errorCode=-1;//冻结返回码-1;
                return !$this->errorCode;
            }else{
                $model->lasttime = time();
                $model->save();
                $model = json_decode(CJSON::encode($model),TRUE);
                unset($model['password']);
                $this->username = $model['phone'];
                $this->uid = $model['id'];
                $detail = UserDetailModel::model()->find(array(
                    'condition' => 'userid=:userid',
                    'params' => array(':userid' => $model['id']),
                ));
                $detail = json_decode(CJSON::encode($detail),TRUE);
                if($detail){
                    unset($detail['id']);
                    $model = array_merge($model,$detail);
                }
                $this->setPersistentStates($model);
                $this->errorCode=self::ERROR_NONE;
                return true;
            }
        }
        return !$this->errorCode;
	}

	public function getId()
	{
		return $this->uid;
	}
}