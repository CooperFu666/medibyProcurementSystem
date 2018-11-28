<?php

/**
 * This is the model class for table "ps_user_research".
 *
 * The followings are the available columns in table 'ps_user_research':
 * @property string $id
 * @property string $user_id
 * @property string $procurement_id
 * @property string $commodity_id
 * @property string $company
 * @property integer $type
 * @property string $research_time
 */
class UserResearchModel extends CActiveRecord
{
    const RESEARCH_INTERNAL = 0;
    const RESEARCH_FOREIGN = 1;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_user_research';
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
			array('user_id, procurement_id, commodity_id, research_time', 'length', 'max'=>11),
			array('company', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, procurement_id, commodity_id, company, type, research_time', 'safe', 'on'=>'search'),
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
			'user_id' => '调研者',
			'procurement_id' => '关联procurement_main',
			'commodity_id' => '商品id，关联commodity',
			'company' => '单位',
			'type' => '1国内价格调研2国外价格调研',
			'research_time' => '调研时间',
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
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('procurement_id',$this->procurement_id,true);
		$criteria->compare('commodity_id',$this->commodity_id,true);
		$criteria->compare('company',$this->company,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('research_time',$this->research_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserResearchModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function getResearchList($commodityId, $userId)
    {
        $sql = "SELECT company,IFNULL(no_tax_price,'') AS noTaxPrice,IFNULL(tax_price,'') AS taxPrice,type FROM ps_user_research WHERE commodity_id=:commodity_id AND user_id=:user_id";
        return self::model()->getDbConnection()->createCommand($sql)->queryAll(true, [':commodity_id' => $commodityId, ':user_id' => $userId]);
    }

    public static function getResearchTitle($procurementId)
    {
        $sql = "SELECT * FROM ps_user_research WHERE procurement_id=:procurement_id GROUP BY user_id,type";
        return self::model()->getDbConnection()->createCommand($sql)->queryAll(true, [':procurement_id' => $procurementId]);
    }
}
