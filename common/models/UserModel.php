<?php

/**
 * This is the model class for table "ps_front_user".
 *
 * The followings are the available columns in table 'ps_front_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $phone
 * @property string $email
 * @property integer $role_id
 * @property integer $login_at
 * @property string $login_ip
 * @property integer $is_first_login
 */
class UserModel extends CActiveRecord
{
    public $role_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_front_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, phone, email, role_id', 'required'),
			array('role_id, login_at, is_first_login', 'numerical', 'integerOnly'=>true),
			array('username, login_ip', 'length', 'max'=>15),
			array('password', 'length', 'max'=>32),
			array('phone', 'length', 'max'=>11),
			array('email', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, password, phone, email, role_id, login_at, login_ip, is_first_login', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'role_access' => array(self::HAS_MANY, 'FrontRoleAccessModel', 'role_id', 'on'=>'t.role_id = role_access.role_id'),
            'front_role' => array(self::BELONGS_TO, 'FrontRoleModel', 'role_id', 'on'=>'t.role_id = front_role.id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => '用户名',
			'password' => '密码',
			'phone' => '手机号码',
			'email' => 'Email',
			'role_id' => '角色ID',
			'login_at' => '上次登录时间',
			'login_ip' => '上次登录IP',
			'is_first_login' => '0不是第一次登录1第一次登录',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('role_id',$this->role_id);
		$criteria->compare('login_at',$this->login_at);
		$criteria->compare('login_ip',$this->login_ip,true);
		$criteria->compare('is_first_login',$this->is_first_login);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getNicknameByPK($pk) {
        $sql = 'SELECT nickname FROM ps_front_user WHERE id = :id';
        return self::model()->getDbConnection()->createCommand($sql)->queryScalar([':id' => $pk]);
    }

    public static function getRoleIdByPK($pk) {
        $sql = 'SELECT role_id FROM ps_front_user WHERE id = :id';
        return self::model()->getDbConnection()->createCommand($sql)->queryScalar([':id' => $pk]);
    }

    public static function makeMarkForMyPurchase($procurementId)
    {
        $submitUserId = UserActionModel::model()->find('type=:type AND procurement_id=:procurement_id', [
            ':type' => UserActionModel::APPLICATION_SUBMIT,
            ':procurement_id' => $procurementId,
        ])->user_id;
        Yii::app()->redis->executeCommand('HSET', ["redMarkMyPurchase:userId|{$submitUserId}", "procurementId|{$procurementId}", '']);
    }
}
