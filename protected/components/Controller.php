<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	public static function getParams()
	{
		return array();
	}

	public static function getActions()
	{
		return array();
	}

	public static function accessFilters()
	{
        return array(
            "index"=>array(
                "name"=>get_called_class(),
                "params"=>array(
                    "access"=>array(
                        "name"=>"Доступ до функціоналу",
                        "params"=>array(),
                        "type"=>Access::TYPE_CHECKBOX,
						"allow_actions"=>array()
                    )
                ),
            )
        );
	}

    public function accessList()
    {
        return array();
    }

	public function filterAccessControl($filterChain)
	{
		$filter = new CustomAccessControlFilter;
		$filter->setRules($this->accessRules());
		$filter->filter($filterChain);
	}

	protected function beforeAction($action) {
		$authManager = Yii::app()->authManager;
		$roles = $authManager->getRoles();

		if (empty($roles))
			Install::createAdminUser();
		if (!Yii::app()->user->isGuest && $action->id=='login')
			$this->redirect(array('/site/index'));
		return true;
	}
}