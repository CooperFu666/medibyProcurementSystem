<?php

/**
 * This is the model class for table "{{comment}}".
 *
 * The followings are the available columns in table '{{comment}}':
 * @property integer $id
 * @property integer $goodsid
 * @property integer $orderid
 * @property integer $userid
 * @property string $content
 * @property string $reply
 * @property integer $status
 * @property integer $stars
 */
class CommentModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{comment}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goodsid, orderid', 'required'),
			array('goodsid, orderid, userid, status, stars', 'numerical', 'integerOnly'=>true),
			array('content, reply', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goodsid, orderid, userid, content, reply, status, stars', 'safe', 'on'=>'search'),
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
			'goodsid' => '商品id',
			'orderid' => '订单id',
			'userid' => '评论人UID',
			'content' => '评论内容',
			'reply' => '商城回复',
			'status' => '状态 1待审核2通过3未通过',
			'stars' => '评论星数',
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
		$criteria->compare('goodsid',$this->goodsid);
		$criteria->compare('orderid',$this->orderid);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('reply',$this->reply,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('stars',$this->stars);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommentModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
