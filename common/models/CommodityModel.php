<?php

/**
 * This is the model class for table "ps_commodity".
 *
 * The followings are the available columns in table 'ps_commodity':
 * @property string $id
 * @property string $procurement_id
 * @property string $brand_title
 * @property string $model_title
 * @property integer $apply_purchase_quantity
 * @property integer $suggest_purchase_quantity
 * @property string $commodity_title
 * @property string $commodity_title_english
 * @property string $unit
 * @property integer $is_purchase
 * @property integer $is_register
 * @property string $create_time
 * @property string $update_time
 */
class CommodityModel extends CActiveRecord
{
    const PURCHASE = 1;
    const NOT_PURCHASE = 0;

    const REGISTER_NOT_SELECT = 2;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_commodity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('apply_purchase_quantity, suggest_purchase_quantity, is_purchase, is_register', 'numerical', 'integerOnly'=>true),
			array('procurement_id, create_time, update_time', 'length', 'max'=>11),
			array('brand_title, model_title, commodity_title, commodity_title_english, unit', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, procurement_id, brand_title, model_title, apply_purchase_quantity, suggest_purchase_quantity, commodity_title, commodity_title_english, unit, is_purchase, is_register, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return [
            'user_research' => [self::HAS_MANY, 'UserResearchModel', 'commodity_id', 'on' => 't.id = user_research.commodity_id'],
            'commodity_purchase' => [self::HAS_MANY, 'CommodityPurchaseModel', 'commodity_id', 'on' => 't.id = commodity_purchase.commodity_id'],
            'commodity_target_price' => [self::HAS_ONE, 'CommodityTargetPriceModel', 'commodity_id', 'on' => 't.id = commodity_target_price.commodity_id'],
        ];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'procurement_id' => '关联procurement_main',
			'brand_title' => '品牌,只能选择产品库中存在的品牌',
			'model_title' => '型号，可以自己随意填写',
			'apply_purchase_quantity' => '申请采购数量',
			'suggest_purchase_quantity' => '建议采购数量',
			'commodity_title' => '商品名',
			'commodity_title_english' => '商品英文名称',
			'unit' => '规格/包装，如多少把',
			'is_purchase' => '0不采购1采购',
			'is_register' => '0没注册1注册',
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
		$criteria->compare('procurement_id',$this->procurement_id,true);
		$criteria->compare('brand_title',$this->brand_title,true);
		$criteria->compare('model_title',$this->model_title,true);
		$criteria->compare('apply_purchase_quantity',$this->apply_purchase_quantity);
		$criteria->compare('suggest_purchase_quantity',$this->suggest_purchase_quantity);
		$criteria->compare('commodity_title',$this->commodity_title,true);
		$criteria->compare('commodity_title_english',$this->commodity_title_english,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('is_purchase',$this->is_purchase);
		$criteria->compare('is_register',$this->is_register);
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
	 * @return CommodityModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function checkCommodity($procurementId, $commodityIdList) {
        $sql = "SELECT id FROM ps_commodity WHERE procurement_id=:procurement_id";
        $params = [':procurement_id' => $procurementId];
        $dataCommodityIdList = self::model()->getDbConnection()->createCommand($sql)->queryColumn($params);
        foreach ($commodityIdList as $value) {
            if (!in_array($value, $dataCommodityIdList)) {
                new ApiException(ApiException::PARAMS_ERROR);
            }
        }
        return true;
    }

}
