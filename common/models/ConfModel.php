<?php

/**
 * This is the model class for table "{{conf}}".
 *
 * The followings are the available columns in table '{{conf}}':
 * @property string $name
 * @property string $value
 * @property integer $group_id
 * @property string $china_name
 */
class ConfModel extends CActiveRecord
{
	const CONFIG_PHONE='phone';//400电话
	const CONFIG_WECHAT_SERVICE = 'wechat_service';
	const CONFIG_SIAN = 'sina';
	const CONFIG_WECHAT_SUBSCRIBE = 'wechat_subscribe';
	const CONFIG_QQ = 'qq';
	const CONFIG_EMAIL='email';
	const CONFIG_IOS_DOWNLOAD_URL = 'ios_download_url';
	const CONFIG_ANDROID_DOWNLOAD_URL = 'android_download_url';
	const CONFIG_IOS_VERSION = 'ios_version';
	const CONFIG_ANDROID_VERSION = 'android_version';
	const CONFIG_RECORD_NUMBER = 'record_number';

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{conf}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>15),
			array('value, china_name', 'length', 'max'=>150),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, value, group_id, china_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => '配置键名',
			'value' => '配置值',
			'group_id' => '分组id，1：系统配置',
			'china_name' => '中文名字',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('china_name',$this->china_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConfModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return static
	 * 订单过期时间
	 */
	public static function getValue($name)
	{
		$result = ConfModel::model()->findByAttributes(array('name' => $name));
		return $result ? $result['value'] : '';
	}
}
