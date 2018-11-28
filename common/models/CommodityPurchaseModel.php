<?php

/**
 * This is the model class for table "ps_commodity_purchase".
 *
 * The followings are the available columns in table 'ps_commodity_purchase':
 * @property string $id
 * @property string $commodity_id
 * @property string $company
 * @property string $subtotal
 * @property string $freight
 * @property integer $number
 * @property integer $is_tax
 * @property string $create_time
 * @property string $update_time
 */
class CommodityPurchaseModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_commodity_purchase';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, is_tax', 'numerical', 'integerOnly'=>true),
			array('commodity_id, create_time, update_time', 'length', 'max'=>11),
			array('company', 'length', 'max'=>255),
			array('subtotal, freight', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, commodity_id, company, subtotal, freight, number, is_tax, create_time, update_time', 'safe', 'on'=>'search'),
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
			'commodity_id' => '关联商品表',
			'company' => '采购单位',
			'subtotal' => '小计',
			'freight' => '运费',
			'number' => '采购数量',
			'is_tax' => '0不含税1含税',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
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
		$criteria->compare('subtotal',$this->subtotal,true);
		$criteria->compare('freight',$this->freight,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('is_tax',$this->is_tax);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommodityPurchaseModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getPurchaseList($commodityId)
    {
        $sql = "SELECT company,subtotal,freight,number,is_tax FROM ps_commodity_purchase WHERE commodity_id=:commodity_id";
        $data = self::model()->getDbConnection()->createCommand($sql)->queryAll(true, [':commodity_id' =>$commodityId]);
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $cost = $value['subtotal'] - $value['freight'];
                if ($cost != 0) {
                    $data[$key]['purchasePrice'] = sprintf('%.2f', $cost / $value['number']);
                }
            }
        }
        return $data;
    }
}
