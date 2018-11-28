<?php

/**
 * This is the model class for table "{{sms_log}}".
 *
 * The followings are the available columns in table '{{sms_log}}':
 * @property integer $id
 * @property string $phone
 * @property string $content
 * @property integer $type
 * @property integer $created_at
 * @property string $error_code
 * @property string $code
 * @property string $times
 */
class SmsLogModel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{sms_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at', 'required'),
			array('type, created_at', 'numerical', 'integerOnly'=>true),
			array('phone', 'length', 'max'=>11),
			array('content', 'length', 'max'=>300),
			array('error_code', 'length', 'max'=>5),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, phone, content, type, created_at, error_code,code,times', 'safe', 'on'=>'search'),
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
			'phone' => '手机号码',
			'content' => '短信类容',
			'type' => '短信类型',
			'created_at' => '发送时间',
			'error_code' => '状态',
			'code' => '验证码',
			'times' => '次数',
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
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('created_at',$this->created_at);
		$criteria->compare('error_code',$this->error_code,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('times',$this->times,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SmsLogModel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 发送手机验证码  function
	 * type: 1 : 登录  2: 注册   3：找回密码, 4:其他类验证码
	 */
	public static function sendTipSMS($phone, $type = 1, $params = array(),$sms=null)
	{
		$status = 0;
		$content = '';
		$config = CHtml::listData(ConfModel::model()->findAll(),'name','value');
		$time = time();
		$code = "";
		$codeType = array(1, 2, 3, 4,6,7,10);
		if (in_array($type, $codeType)) {
			$sendCode = SmsLogModel::model()->find(array(
				'condition' => 'phone=:phone and error_code=0',
				'params' => array(':phone' => $phone),
				'order' => "id desc",
			));
			$remainTime = 120 - time() + $sendCode['created_at'];
			if($remainTime>0)
				return $status;
			$code = Utils::generateCode(4);
			$session = Yii::app()->session;
			$sendCode = array('times' => 0, 'code' => $code, 'phone' => $phone,'created_at'=>$time);
			$session->add('globeSms', serialize($sendCode));
		}

		switch ($type) {
			case 1:
				$key = 'login';
				$data = array('code' => $code);
                $templateid = "295923";
				break;
			case 2:
				$expiredTime = Yii::app()->params['expiredTime'];
				$data = array('code' => $code,'expiredTime'=>$expiredTime.'分钟');
				$key = 'register';
                $templateid = "294749";
				break;
			case 3:
				$expiredTime = Yii::app()->params['expiredTime'];
				$data = array('code' => $code,'expiredTime'=>$expiredTime.'分钟');
				$key = 'find';
                $templateid = "294752";
				break;
			case 4:
				$data = array('code' => $code);
				$key = 'other';
                $templateid = "295924";
				break;
		}

		$data = array_merge($data,array('tel'=>$config[ConfModel::CONFIG_PHONE]));

		$content = Yii::t('sms', $key, $data);
		$client = new sendSms();
		$result = $client->SendSms($templateid,$code,$phone);
		//持久化数据
		$sms = new SmsLogModel;
		$sms->phone = $phone;
		$sms->content = $content;
		$sms->error_code = '0';
		$sms->created_at = $time;
		$sms->type = $type;
		$sms->code = $code;
		$sms->times = 0;
		if ($result == 0) {
			$sms->save();
			$status = 1;
		} else {
			$sms->error_code = $result;
			$sms->save();
		}
		return $status;
	}

	/**
	 * 验证码校验
	 * @param $code
	 * @param $phone
	 * @param bool|false $isremove
	 * @return array
	 */
	public static function checkSmsCode($code, $phone, $isremove = false)
	{

		$sendCode = SmsLogModel::model()->find(array(
				'condition' => 'phone=:phone  and error_code=0',
				'params' => array(':phone' => $phone),
				'order' => "id desc",
		));
		$sendCodearray = json_decode(CJSON::encode($sendCode),TRUE);

		$result = array('code' => -1, 'message' => '');

		if (!$sendCodearray) {
			$result['message'] = '验证码错误';
		}else{
			if ($sendCodearray['phone'] != $phone) {
				$result['message'] = '验证码错误';
			} elseif ($sendCodearray['times'] >= 4) {
				$result['message'] = '验证码错误或已失效,请重新获取';
			} elseif (($sendCodearray['code'] != $code || time() - $sendCodearray['created_at'] > Yii::app()->params['expiredTime'] * 60)) {
				$sendCodearray['times'] += 1;
				$sendCode->times=$sendCodearray['times'];
				$sendCode->save();
				$result['message'] = '验证码错误或已过期';
			} else {
				$result['code'] = 1;
				$sendCodearray['times'] += 1;
				$sendCode->times=$sendCodearray['times'];
				$sendCode->save();
			}
		}

		return $result;
	}

	/**
	 * 手机验证码再次发送倒计时
	 * @return int
	 */
	public static function getOverTimes()
	{
		$session = Yii::app()->session;
		$sendCode = $session->itemAt('globeSms');
		$code = unserialize($sendCode);
		$time = 120 - time() + $code['created_at'];
		$time = $time >= 0 ? $time : 0;
		return $time;
	}
}
