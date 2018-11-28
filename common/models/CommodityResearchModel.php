<?php

/**
 * This is the model class for table "ps_commodity_research".
 *
 * The followings are the available columns in table 'ps_commodity_research':
 * @property string $id
 * @property string $commodity_id
 * @property string $company
 * @property string $price
 * @property integer $type
 * @property string $tender_time
 * @property string $create_time
 * @property string $update_time
 * @property string $is_delete
 */
class CommodityResearchModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_commodity_research';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'numerical', 'integerOnly'=>true),
			array('commodity_id, tender_time, create_time, update_time', 'length', 'max'=>11),
			array('company', 'length', 'max'=>255),
			array('price', 'length', 'max'=>20),
			array('is_delete', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, commodity_id, company, price, type, tender_time, create_time, update_time, is_delete', 'safe', 'on'=>'search'),
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
			'commodity_id' => '商品id，关联commodity',
			'company' => '单位',
			'price' => '价格',
			'type' => '1招标价格2代理商价格3经销商价格4国外价格',
			'tender_time' => '招标时间',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'is_delete' => '0正常1删除',
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
		$criteria->compare('commodity_id',$this->commodity_id,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('tender_time',$this->tender_time,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);
		$criteria->compare('is_delete',$this->is_delete,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommodityResearchModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
