<?php

/**
 * This is the model class for table "ps_user_to_do".
 *
 * The followings are the available columns in table 'ps_user_to_do':
 * @property string $id
 * @property string $user_id
 * @property string $procurement_id
 * @property integer $status
 * @property string $create_time
 */
class UserToDoModel extends CActiveRecord
{
    const STATUS_INTERVAL = 1;          //1国内价格调研
    const STATUS_FOREIGN = 2;           //2国外价格调研
    const STATUS_RE_INTERVAL = 3;       //3被打回国内价格调研
    const STATUS_RE_FOREIGN = 4;        //4被打回国外价格调研
    const STATUS_INFO = 5;              //5填写基础信息
    const STATUS_PRICING = 6;           //6定价
    const STATUS_SUGGEST = 7;           //7填写建议采购量
    const STATUS_PURCHASE = 8;          //8填写采购报告
    const STATUS_APPROVAL = 9;          //9审批是否继续
    const STATUS_GLOBAL = 10;           //10国内外价格调研
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_user_to_do';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status', 'numerical', 'integerOnly'=>true),
			array('user_id, procurement_id, create_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, procurement_id, status, create_time', 'safe', 'on'=>'search'),
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
            'procurement_main' => array(self::BELONGS_TO, 'ProcurementMainModel', 'procurement_id', 'on'=>'t.procurement_id = procurement_main.id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => '用户id',
			'procurement_id' => '关联procurement_main',
			'status' => '1国内价格调研2国外价格调研3全球价格调研4填写基础信息5定价6填写建议采购量7填写采购报告8审批是否继续',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('procurement_id',$this->procurement_id,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserToDoModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static $toDoArr = [
        self::STATUS_INTERVAL => '填写国内调研报告',
        self::STATUS_FOREIGN => '填写国外调研报告',
        self::STATUS_RE_INTERVAL => '填写国内调研报告',
        self::STATUS_RE_FOREIGN => '填写国外调研报告',
        self::STATUS_INFO => '填写基础信息',
        self::STATUS_PRICING => '定价',
        self::STATUS_SUGGEST => '填写采购建议量',
        self::STATUS_PURCHASE => '填写采购报告',
        self::STATUS_APPROVAL => '审批是否继续',
        self::STATUS_GLOBAL => '填写国内外价格调研',
    ];

    /**
     * 待办事宜与我的采购申请做标记
     * @param $userId
     * @param $procurementId
     * @param $status
     */
	public static function makeMark($userId, $procurementId, $status) {
        $userToDoModel = new UserToDoModel();
        $userToDoModel->user_id = $userId;
        $userToDoModel->procurement_id = $procurementId;
        $userToDoModel->status = $status;
        $userToDoModel->create_time = time();
        $userToDoModel->save();

    }

    public static function deleteMark($userId, $procurementId, $status = '') {
	    $condition = 'user_id=:user_id AND procurement_id=:procurement_id';
	    $params = [':user_id' => $userId, ':procurement_id' => $procurementId,];
        if (!empty($status)) {
            $condition .= ' AND status=:status';
            $params[':status'] = $status;
        }
	    return self::model()->deleteAll($condition, $params);
    }

    public static function getIndexToDo($userId, $page = 1)
    {
        $pageSize = Yii::app()->params['pageSize'];
        $criteria = new CDbCriteria();
        $criteria->with = ['procurement_main'];
        $criteria->condition = 't.user_id=:user_id';
        $criteria->params = [':user_id' => $userId];
        $criteria->limit = $pageSize;
        $criteria->offset = ($page - 1) * $pageSize;
        $criteria->order = 't.create_time DESC';
        $res = UserToDoModel::model()->findAll($criteria);
        $list = [];
        $str = '';
        foreach ($res as $key => $value) {
            $submitUser = UserModel::getNicknameByPK(UserActionModel::model()->find('procurement_id=:procurement_id AND type=:type', [
                ':procurement_id' => $value->procurement_id,
                ':type' => UserActionModel::APPLICATION_SUBMIT,
            ])->user_id);
//            $submitUser = UserModel::getNicknameByPK($value->user_id);
            if ($value->status == UserToDoModel::STATUS_RE_INTERVAL || $value->status == UserToDoModel::STATUS_RE_FOREIGN)
                $str = '被打回，';
            if ($value->status == UserToDoModel::STATUS_APPROVAL)
                $str = '有部分产品采购量不足建议采购量，';
            $info = "{$submitUser}发起的{$value->procurement_main->title}（{$value->procurement_main->item_number}）{$str}需要我"
                . UserToDoModel::$toDoArr[$value->status];
            $list[$key]['str'] = $info;
            $list[$key]['time'] = date('Y-m-d H:i:s', $value->create_time);
            $list[$key]['procurementId'] = $value->procurement_id;
            $list[$key]['status'] = ProcurementMainModel::getStatusByPK($value->procurement_id);
        }
        return $list;
    }

    public static function makeMarkByUserIdAndIsForeign($userIdAndIsForeignArr, $procurementId)
    {
        foreach ($userIdAndIsForeignArr as $userId => $researchTypeArr) {
            if (in_array(UserActionModel::IS_INTERVAL, $researchTypeArr) && in_array(UserActionModel::IS_FOREIGN, $researchTypeArr)) {
                UserToDoModel::makeMark($userId, $procurementId, UserToDoModel::STATUS_GLOBAL);
            } elseif (in_array(UserActionModel::IS_INTERVAL, $researchTypeArr)) {
                UserToDoModel::makeMark($userId, $procurementId, UserToDoModel::STATUS_INTERVAL);
            } elseif (in_array(UserActionModel::IS_FOREIGN, $researchTypeArr)) {
                UserToDoModel::makeMark($userId, $procurementId, UserToDoModel::STATUS_FOREIGN);
            }
        }
    }
}
