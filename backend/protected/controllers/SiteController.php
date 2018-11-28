<?php


class SiteController extends AdminController
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			/*'page'=>array(
				'class'=>'CViewAction',
			),*/
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
         if (Yii::app()->user->isGuest) {
            $this->redirect(array('site/login'));
        }
		$this->render('index');
	}
	/**
	 * This is the action to handle external exceptions.
	 */
    public function actionError()
    {

            if ($error = Yii::app()->errorHandler->error) {
                if (Yii::app()->request->isAjaxRequest)
                    echo $error['message'];
                else
                    $this->render('error', $error);
            }
    }
    
    public function actionCheckUser()
    {
    	$input = '';
    	if(isset($_REQUEST['username']) && $_REQUEST['username']){
    		$input = $_REQUEST['username'];
    	}else{
    		die(Utils::jsonResult(-1,'用户不存在'));
    	}
    	
    	$inputType = Utils::getInputType($input);
    	$user = null;
    	switch($inputType){
    		case 1://手机
    			$user = AdminModel::model()->find('phone=:phone',array(':phone'=>$input));
    			break;
    		case 2://email
    			$user = AdminModel::model()->find('email=:email',array(':email'=>$input));
    			break;
    		default:
    			$user = AdminModel::model()->find('username=:username',array(':username'=>$input));
    			break;
    	}
    	
    	if($user && $user->phone){
    		$smsRes = SmsLogModel::sendTipSMS($user->phone,4);
    		if($smsRes == '1'){
    			die(Utils::jsonResult(1,'发送成功'));
    		}else{
    			die(Utils::jsonResult(0,'发送失败'));
    		}
    	}
    	die(Utils::jsonResult(-1,'用户不存在'));
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{

		$model=new LoginForm();
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()) {
                FrontLogModel::saveLog(FrontLogModel::TYPE_LOGIN, Utils::getIp());
                $this->redirect(Yii::app()->user->returnUrl);
            }
		}
        $session = Yii::app()->session;
		$sendCode = $session->itemAt('globeSms');
		$code = unserialize($sendCode);
		$time = 120 - time() + $code['times'];
		$time = $time >= 0 ? $time : 0;
		$this->renderPartial('login',array('model'=>$model,'time'=>$time));
	}

	public function actionCheckPass(){
		$username = Yii::app()->request->getPost('username');
		$password = LoginForm::hashPassword(Yii::app()->request->getPost('userpass'));
		$admin = AdminModel::model()->find('username=:username and password=:password',
					array(':username'=>$username,':password'=>$password));
		if($admin){
			die(Utils::jsonResult('1','验证通过'));
		}else{
			die(Utils::jsonResult('0','用户名或密码错误'));
		}
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


    public function actionEditorDemo(){
        $this->render('editorDemo');
    }


}