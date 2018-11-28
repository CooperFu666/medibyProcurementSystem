<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AdminLoginForm extends CFormModel
{
	public $username;
	public $userpass;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username,userpass, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
			array('userpass', 'checkPass'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'保持状态',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors()){
			$this->_identity=new AdminIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate()){
				$this->addError('password','用户名或密码有误');
			}
		}
	}
	public function checkPass($attribute,$params){
		if(!$this->hasErrors()){
			$admin = AdminModel::model()->find('username=:username and password=:password',
					array(':username'=>$this->username,':password'=>self::hashPassword($this->userpass)));
			if(!$admin){
				$this->addError('userpass','用户名或密码有误');
			}
		}
	}
	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new AdminIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===AdminIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
            //log
            $action =__METHOD__;
            $log = new AdminLogModel();
            $log->description =  "用户".Yii::app()->user->name."登录了系统";
            $log->action = substr($action,strripos($action,':')+1);
            $log->module = __CLASS__;
            $log->username = Yii::app()->user->name;
            $log->uid = Yii::app()->user->id;
            $log->save();
			return true;
		}
		else
		{
			return false;
		}
	}
	static public function hashPassword($password){
		return md5(md5($password.Yii::app()->params['userPasswordKey']));
	}
}
