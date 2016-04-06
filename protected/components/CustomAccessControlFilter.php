<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 22.04.15
 * Time: 12:51
 */

class CustomAccessControlFilter extends CAccessControlFilter {
    /**
     * @var string the error message to be displayed when authorization fails.
     * This property can be overridden by individual access rule via {@link CAccessRule::message}.
     * If this property is not set, a default error message will be displayed.
     * @since 1.1.1
     */
    public $message;

    public function filter($filterChain){
        parent::filter($filterChain);
    }

    /**
     * Performs the pre-action filtering.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @return boolean whether the filtering process should continue and the action
     * should be executed.
     */
    protected function preFilter($filterChain)
    {
        $app=Yii::app();
        $user=$app->getUser();
        if ($this->isAllowed($user, $filterChain)==false) {
            $this->accessDenied($user,"");
            return false;
        }
        return true;
    }

    public function isAllowed($user, $filterChain)
    {
        return true;
        if ($user->checkAccess('admin')||$filterChain->controller->action->id=="login"||$filterChain->controller->id=="site")
            return true;
        else {
            if (!Yii::app()->user->isGuest&&in_array($filterChain->controller->action->id, $filterChain->controller->accessList()))
                    return true;
            if (isset($filterChain->controller->module)) {
                $controller = '/'.$filterChain->controller->module->id.
                    '/'.$filterChain->controller->id.'/'.$filterChain->action->id;
                $result = Yii::app()->authManager->checkAccess(Yii::app()->user->role, Yii::app()->user->id, array(),$controller);

                if (!$result&&$controller=="/event/event/index"&&Yii::app()->authManager->checkAccess(Yii::app()->user->role,
                        Yii::app()->user->id, array(), '/statistics/statistics/basic')) {
                    Yii::app()->controller->redirect(array('/statistics/statistics/basic'));
                }
                return $result;
            } elseif((isset($filterChain->controller))) {
                return Yii::app()->authManager->checkAccess(Yii::app()->user->role, Yii::app()->user->id, array(),
                    "/".$filterChain->controller->id.'/'.$filterChain->controller->action->id);
            }
            elseif (count($this->getRules())) {
                $app=Yii::app();
                $request=$app->getRequest();
                $user=$app->getUser();
                $verb=$request->getRequestType();
                $ip=$request->getUserHostAddress();

                foreach($this->getRules() as $rule)
                {
                    if(($allow=$rule->isUserAllowed($user,$filterChain->controller,$filterChain->action,$ip,$verb))>0) // allowed
                        return true;
                    elseif($allow<0) // denied
                    {
                        if(isset($rule->deniedCallback))
                            call_user_func($rule->deniedCallback, $rule);
                        else
                            $this->accessDenied($user,$this->resolveErrorMessage($rule));
                        return false;
                    }
                }
            }
            return false;
        }
    }

    /**
     * Denies the access of the user.
     * This method is invoked when access check fails.
     * @param IWebUser $user the current user
     * @param string $message the error message to be displayed
     */
    protected function accessDenied($user,$message)
    {
        if($user->getIsGuest())
            $user->loginRequired();
        else
            throw new CHttpException(403,$message);
    }

    protected function resolveErrorMessage($rule)
    {
        if($rule->message!==null)
            return $rule->message;
        elseif($this->message!==null)
            return $this->message;
        else
            return Yii::t('yii','You are not authorized to perform this action.');
    }
}