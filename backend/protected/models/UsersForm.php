<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UsersForm extends CFormModel
{
    public $id;
	public $phone;
	public $password;
    public $corporate_name;
    public $corporate_type;

	/**
	 * Declares the validation rules.
	 * The rules state that phone and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
            // name, email, subject and body are required
            array('phone, corporate_name,corporate_type', 'required'),
            // email has to be a valid email address
            array('corporate_type','numerical', 'integerOnly'=>true),
            // verifyCode needs to be entered correctly
            array('phone','checkPhone'),
            array('phone','match','pattern'=>'/^(13|14|15|17|18)\d{9}$/i','message'=>'你所输入的不是手机号码'),
            array('password', 'required','on'=>'add')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'phone' => '手机号码',
            'password' => '密码',
            'corporate_name' => '公司名称',
            'corporate_type' => '公司类型',
		);
	}

    public function checkPhone()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('phone=:phone');
        $criteria->params = array(':phone' => $this->phone);
        $this->scenario == 'add' ? '' : $criteria->addCondition('id<>' . $this->id);
        $phoneCount = UserModel::model()->count($criteria);
        if ($phoneCount > 0)
            $this->addError('phone', '手机号码重复了');

    }

    public function save(){

        $user = $this->id ? UserModel::model()->findByPk($this->id) : new UserModel();
        $user->phone = $this->phone;
        if($this->password!=""){
            $user->password = UserModel::hashPassword($this->password);
        }
        $user->regtime = $this->id ? $user->regtime : time();
        $detail =  $this->id ? UserDetailModel::model()->find('userid=:userid',array(':userid'=>$this->id)) : new UserDetailModel();
        $detail->corporate_name = $this->corporate_name;
        $detail->corporate_type = $this->corporate_type;
        $transaction=Yii::app()->db->beginTransaction();
        try{
            $user->save();
            $detail->userid = $user->id;
            $detail->save();
            $transaction->commit();//提交事务会真正的执行数据库操作
            return true;
        }catch (Exception $e) {
            $transaction->rollback();//如果操作失败, 数据回滚
            return false;
        }
    }

}
