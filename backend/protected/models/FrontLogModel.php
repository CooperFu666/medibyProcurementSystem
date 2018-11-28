<?php

/**
 * This is the model class for table "ps_front_log".
 *
 * The followings are the available columns in table 'ps_front_log':
 * @property integer $id
 * @property string $action_id
 * @property integer $action_type
 * @property string $obj_str
 * @property string $create_time
 */
class FrontLogModel extends CActiveRecord
{
    const TYPE_CREATE_ACCOUNT = 1;      //创建账号
    const TYPE_ALTER_ACCOUNT = 2;       //修改账号信息
    const TYPE_RESET_PASSWORD = 3;      //重置密码
    const TYPE_ALERT_ROLE_INFO = 4;     //修改账号角色
    const TYPE_LOGIN = 5;               //登录
    const TYPE_CREATE_ROLE = 6;         //新增角色
    const TYPE_ALTER_ROLE = 7;          //编辑角色
    const TYPE_DELETE_ROLE = 8;         //删除角色

    public static $typeArr = [
        self::TYPE_CREATE_ACCOUNT => '创建账号',
        self::TYPE_ALTER_ACCOUNT => '修改账号信息',
        self::TYPE_RESET_PASSWORD => '重置密码',
        self::TYPE_ALERT_ROLE_INFO => '修改账号角色',
        self::TYPE_LOGIN => '登录',
        self::TYPE_CREATE_ROLE => '新增角色',
        self::TYPE_ALTER_ROLE => '编辑角色',
        self::TYPE_DELETE_ROLE => '删除角色',
    ];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_front_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action_type', 'numerical', 'integerOnly'=>true),
			array('action_id, create_time', 'length', 'max'=>11),
			array('obj_str', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, action_id, action_type, obj_str, create_time', 'safe', 'on'=>'search'),
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
            'backend_admin'=>array(self::BELONGS_TO,'AdminModel','action_id', 'on' => 't.action_id=backend_admin.id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'action_id' => '操作者',
			'action_type' => '1、创建账号，2、修改账号信息，3、重置密码，4、修改账号角色，5、登录，6、新增角色，7、编辑角色，8、删除角色',
			'obj_str' => '对象',
			'create_time' => '创建时间',
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
		$criteria->compare('action_id',$this->action_id,true);
		$criteria->compare('action_type',$this->action_type);
		$criteria->compare('obj_str',$this->obj_str,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FrontLogModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function saveLog($actionType, $objStr)
    {
        $frontLogModel = new FrontLogModel();
        $frontLogModel->action_id = Yii::app()->user->id;
        $frontLogModel->action_type = $actionType;
        $frontLogModel->obj_str = $objStr;
        $frontLogModel->create_time = time();
        $frontLogModel->save();
    }
}
