<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class AdminForm extends CFormModel
{
	public $id;
	public $username;
	public $password;
	public $phone;
	public $email;
	public $role_id;
	public $is_system;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('username, phone,email', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			array('id,role_id,is_system','numerical', 'integerOnly'=>true),
			// verifyCode needs to be entered correctly
			array('phone','checkPhone'),
			array('phone','match','pattern'=>'/^(13|14|15|17|18)\d{9}$/i','message'=>'你所输入的不是手机号码'),
			array('username','checkUsername'),
			array('password', 'required','on'=>'add')
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'username'=>'用户名',
			'phone'=>'手机号码',
			'email'=>'邮箱',
			'password'=>'密码',
			'is_effect'=>'是否启用',
			'is_system' => '是否系统管理员',
			'role_id' => '管理组',
		);
	}

	public function checkPhone($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('phone=:phone');
		$criteria->addCondition('role_id<>0');
		$criteria->params = array(':phone' => $this->phone);

		$this->scenario == 'add' ? '' : $criteria->addCondition('id<>' . $this->id);
		$phoneCount = AdminModel::model()->count($criteria);
		if ($phoneCount > 0)
			$this->addError('phone', '手机号码重复了');

	}

	public function checkUsername($attribute, $params)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('username=:username');
		$criteria->params = array(':username' => $this->username);

		$this->scenario == 'add' ? '' : $criteria->addCondition('id<>' . $this->id);
		$usernameCount = AdminModel::model()->count($criteria);
		if ($usernameCount > 0)
			$this->addError('username', '用户名重复了');

	}

	public function save(){
		$admin = $this->id ? AdminModel::model()->findByPk($this->id) : new AdminModel();
		$admin->phone = $this->phone;
		$admin->username = $this->username;
		$admin->password = $this->password ? AdminLoginForm::hashPassword($this->password) : $admin->password;
		$admin->email = $this->email;
		$admin->role_id = $this->role_id;
		$admin->is_system = $this->is_system;
		if($admin->save())
			return true;
		else
			return false;

	}
}