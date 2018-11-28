<?php

/**
 * This is the model class for table "ps_commodity_target_price".
 *
 * The followings are the available columns in table 'ps_commodity_target_price':
 * @property string $id
 * @property string $commodity_id
 * @property string $taxPrice
 * @property string $noTaxPrice
 */
class CommodityTargetPriceModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_commodity_target_price';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('commodity_id', 'length', 'max'=>11),
			array('taxPrice, noTaxPrice', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, commodity_id, taxPrice, noTaxPrice', 'safe', 'on'=>'search'),
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
			'taxPrice' => '含税价',
			'noTaxPrice' => '不含税价',
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
		$criteria->compare('taxPrice',$this->taxPrice,true);
		$criteria->compare('noTaxPrice',$this->noTaxPrice,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommodityTargetPriceModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function saveTargetPrice($commodityList)
    {
        $flag = 0;
        $params = [];
        $sql = "INSERT INTO ps_commodity_target_price(commodity_id,taxPrice,noTaxPrice) VALUES ";
        if (!empty($commodityList)) {
            foreach ($commodityList as $key => $value) {
                    $sql .= "(:{$key}commodity_id,:{$key}taxPrice,:{$key}noTaxPrice),";
                    $params[":{$key}commodity_id"] = $value->commodityId;
                    $params[":{$key}taxPrice"] = $value->taxPrice;
                    $params[":{$key}noTaxPrice"] = $value->noTaxPrice;
            }
            if (substr($sql, -1, 1) == ',') {
                $sql = substr($sql, 0, strlen($sql) - 1);
                $sql .= " ON DUPLICATE KEY UPDATE commodity_id=VALUES(commodity_id),taxPrice=VALUES(taxPrice),
                noTaxPrice=VALUES(noTaxPrice)";
            }
            $flag = CommodityTargetPriceModel::model()->getDbConnection()->createCommand($sql)->execute($params);
        }
        return $flag;
    }
}
