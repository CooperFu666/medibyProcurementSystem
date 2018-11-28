<?php

/**
 * This is the model class for table "{{role_action}}".
 *
 * The followings are the available columns in table '{{role_action}}':
 * @property string $id
 * @property string $action
 * @property string $name
 * @property integer $is_effect
 * @property string $group_id
 * @property string $module_id
 */
class RoleActionModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{role_action}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('action, name, is_effect, group_id, module_id', 'required'),
			array('is_effect', 'numerical', 'integerOnly'=>true),
			array('action, name', 'length', 'max'=>30),
			array('group_id, module_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, action, name, is_effect, group_id, module_id', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'action' => 'Action',
			'name' => 'Name',
			'is_effect' => 'Is Effect',
			'group_id' => '后台分组菜单分组ID',
			'module_id' => 'Module',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_effect',$this->is_effect);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('module_id',$this->module_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoleActionModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
