<?php

/**
 * This is the model class for table "ps_user_action".
 *
 * The followings are the available columns in table 'ps_user_action':
 * @property string $id
 * @property string $user_id
 * @property string $procurement_id
 * @property integer $type
 * @property string $action_time
 * @property string $create_time
 */
class UserActionModel extends CActiveRecord
{
    const APPLICATION_SUBMIT = 1;   //  采购项目申请者
    const APPLICATION_PRINCIPAL= 2; //  项目负责人
    const APPLICATION_SUGGEST = 3;  //  建议人
    const APPLICATION_PRICING = 4;  //  定价执行者
    const APPLICATION_APPROVAL = 5; //  审批执行者
    const APPLICATION_PURCHASE = 6; //  采购执行者
    const APPLICATION_RESEARCH = 7; //  调研执行者
    const APPLICATION_WITHDRAWAL_RESEARCH = 8; //  调研报告打回者
    const APPLICATION_END_RESEARCH = 9; //  调研阶段终止者
    const APPLICATION_END_PRICING = 10; //  定价阶段终止者
    const APPLICATION_END_SUGGEST = 11; //  建议采购量/审批阶段终止者
    const APPLICATION_BASE_INFO = 12; //  基础信息填写者

    const IS_INTERVAL = 0;    //  国内
    const IS_FOREIGN = 1;     //  国外
    const IS_ALL = 2;         //  全球

    const ACTION_SAVE = 0;      //  保存
    const ACTION_SUBMIT = 1;    //  提交

    public static $typeArr = [
        "APPLICATION_SUBMIT" => 1,   //  采购项目申请者
        "APPLICATION_PRINCIPAL" => 2, //  项目负责人
        "APPLICATION_SUGGEST" => 3,  //  建议人
        "APPLICATION_PRICING" => 4,  //  定价执行者
        "APPLICATION_APPROVAL" => 5, //  审批执行者
        "APPLICATION_PURCHASE" => 6, //  采购执行者
        "APPLICATION_RESEARCH" => 7, //  调研执行者
        "APPLICATION_WITHDRAWAL_RESEARCH" => 8, //  调研报告打回者
        "APPLICATION_END_RESEARCH" => 9, //  调研阶段终止者
        "APPLICATION_END_PRICING" => 10, //  定价阶段终止者
        "APPLICATION_END_SUGGEST" => 11, //  建议采购量/审批阶段终止者
        "APPLICATION_BASE_INFO" => 12, //  基础信息填写者
    ];
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ps_user_action';
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
			array('user_id, procurement_id, action_time, create_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, procurement_id, type, action_time, create_time', 'safe', 'on'=>'search'),
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
            'front_user' => array(self::BELONGS_TO, 'UserModel', 'user_id', 'on'=>'t.user_id = front_user.id'),
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
			'type' => '1采购项目申请者2项目负责人3建议人4定价执行者5审批执行者6采购执行者',
			'action_time' => '执行时间',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('action_time',$this->action_time,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserActionModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function isResearchOK($procurementId)
    {
        $sql = "SELECT user_id FROM ps_user_action WHERE procurement_id = :procurement_id AND type = :type AND action=:action";
        $userIdList = self::model()->getDbConnection()->createCommand($sql)->queryColumn(
            [
                ':procurement_id' => $procurementId,
                ':type' => self::APPLICATION_RESEARCH,
                ':action' => self::ACTION_SUBMIT,
            ]
        );
        $userIdList = array_unique($userIdList);
        sort($userIdList);
        $sql = "SELECT user_id FROM ps_user_action WHERE procurement_id = :procurement_id AND type = :type";
        $userIdArr = self::model()->getDbConnection()->createCommand($sql)->queryColumn(
            [
                ':procurement_id' => $procurementId,
                ':type' => self::APPLICATION_RESEARCH,
            ]
        );
        $userIdArr = array_unique($userIdArr);
        sort($userIdArr);
//        $sql = "SELECT user_id FROM ps_user_research WHERE procurement_id = :procurement_id";
//        $userIdArr = UserResearchModel::model()->getDbConnection()->createCommand($sql)->queryColumn(
//            [
//                ':procurement_id' => $procurementId,
//            ]
//        );
//        $userIdArr = array_unique($userIdArr);
//        sort($userIdArr);
        if ($userIdList === $userIdArr)
            return true;
        return false;
    }

    public static function addUserAction($userId, $procurementId, $type, $actionTime = 0, $isForeign = 0)
    {
        $userActionModel = new UserActionModel();
        $userActionModel->user_id = $userId;
        $userActionModel->type = $type;
        $userActionModel->procurement_id = $procurementId;
        if (!empty($actionTime))
            $userActionModel->action_time = $actionTime;
        if (!empty($isForeign))
            $userActionModel->is_foreign = $isForeign;
        $userActionModel->create_time = time();
        return $userActionModel->save();
    }

    public static function checkAccess($userId, $procurementId, $procurementStatus, $userActionType = '')
    {
        if (empty($userActionType))
            $userActionType = ProcurementMainModel::getUserActionType($procurementStatus);
        $isExists = self::model()->exists('user_id=:user_id AND procurement_id=:procurement_id AND type=:type', [
            ':user_id' => $userId,
            ':procurement_id' => $procurementId,
            ':type' => $userActionType,
        ]);
        $flag = 0;
        if ($isExists)
            $flag = 1;
        return $flag;
    }

    public static function checkAccessAndThrow($userId, $procurementId, $procurementStatus)
    {
        $userActionType = ProcurementMainModel::getUserActionType($procurementStatus);
        $isExists = self::model()->exists('user_id=:user_id AND procurement_id=:procurement_id AND type=:type', [
            ':user_id' => $userId,
            ':procurement_id' => $procurementId,
            ':type' => $userActionType,
        ]);
        if (!$isExists)
            new ApiException(ApiException::PERMISSION_DENIED);
    }
}
