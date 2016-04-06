<?php

class SiteController extends Controller
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
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}



	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('login'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('logout', 'index', 'error', 'changeCurrentRole'),
				'users'=>array('@')
			),
			array('deny',
				'users'=>array('*')
			),
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

		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		echo Yii::app()->errorHandler->error['message'], '<br />';
		echo '<pre>'; print_r(Yii::app()->errorHandler->error['trace']); exit('</pre>');
		if($error=Yii::app()->errorHandler->error)
		{
            if($error['code']=='403'){
                $error['message'] = 'Вибачте, Вам не надано доступу';
            }
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}


	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl()."/js/login.js");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl()."/js/classie.js");
        Yii::app()->clientScript->registerScript("login",
            "
				// trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
				if (!String.prototype.trim) {
					(function() {
						// Make sure we trim BOM and NBSP
						var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
						String.prototype.trim = function() {
							return this.replace(rtrim, '');
						};
					})();
				}
				[].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
					// in case the input is already filled..
					if( inputEl.value.trim() !== '' ) {
						classie.add( inputEl.parentNode, 'input--filled' );
					}

					// events:
					inputEl.addEventListener( 'focus', onInputFocus );
					inputEl.addEventListener( 'blur', onInputBlur );
				} );
				function onInputFocus( ev ) {
					classie.add( ev.target.parentNode, 'input--filled' );
				}
				function onInputBlur( ev ) {
					if( ev.target.value.trim() === '' ) {
						classie.remove( ev.target.parentNode, 'input--filled' );
					}
				}
				var autoFill = $('input:-webkit-autofill');
				if (autoFill && autoFill.length > 0){
					autoFill.each(function(){
						var text = $(this).val();
						var name = $(this).attr('name');
						$(this).after(this.outerHTML).remove();
						$('input[name=' + name + ']').val(text);
					});
				}
			", CClientScript::POS_READY);

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
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);

		}
		// display the login form
		$this->layout='//layouts/login-layouts';
		$this->render('login',array('model'=>$model));

	}

	public function actionChangeCurrentRole($role, $returnUrl)
	{
		Yii::app()->user->setUserRole($role);
		$this->redirect($returnUrl);
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	public function actionTest()
	{
		$this->render("test");
	}

	public function actionTest1()
	{

		$this->render("test1");
	}

	public function actionTest2()
	{
		$this->render("test2");
	}

	public function actionTest3()
	{
		$this->render("test3");
	}

	public function actionTest4()
	{
		$this->render("test4");
	}

	public function actionTest5()
	{
		$this->render("test5");
	}

	public function actionTest6()
	{
		$this->render("test6");
	}

    public function actionTest7()
    {
        $this->render("test7");
    }

    public function actionTest8()
    {
        $this->render("test8");
    }

    public function actionTest9()
    {
        $this->render("test9");
    }

    public function actionTest10()
    {
        $this->render("test10");
    }

    public function actionTest11()
    {
        $this->render("test11");
    }

    public function actionTest12()
    {
        Yii::app()->clientScript->registerCssFile(Yii::app()->getBaseUrl()."/templates/kasa_in_ua/css/style.css");
        $this->render("test12");
    }

	public function actionMail($accessCode)
	{
		$code = "B0uN90LWTlqNoVQBkfs1";
		if($accessCode === $code)
			Mailer::mail();
	}
}