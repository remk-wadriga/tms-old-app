<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 01.10.14
 * Time: 16:02
 */

class WebUser extends CWebUser {
    private $_model = null;

    function getIsAdmin() {
        return Yii::app()->user->role == 'admin';
    }

    function getCurrentRoleId()
    {
        if (Yii::app()->user->hasState("currentRoleId"))
            return Yii::app()->user->getState("currentRoleId");
        if($user = $this->getModel()){
            // в таблице User есть поле role
            $role = $this->getRole();
            $role_id = Role::getRoleId($role);
            $this->setUserRoleId($role_id);
            return $role_id;
        }
    }

    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->with('roles')->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }

    function getRole() {
        if (Yii::app()->user->hasState("currentRole"))
            return Yii::app()->user->getState("currentRole");
        if($user = $this->getModel()){
            // в таблице User есть поле role
            $role = "";
            if ($user->role=="") {
                if (!empty($user->user_roles))
                    $role = current($user->user_roles);
            } else
                $role = $user->role;
            $this->setUserRole($role);
            return $role;
        }
    }

    function setUserRole($role)
    {
        Yii::app()->user->setState("currentRole", $role);
        $this->setUserRoleId(Role::getRoleId($role));
    }

    function setUserRoleId($role)
    {
        Yii::app()->user->setState("currentRoleId", $role);
    }

    function getIsAdminOfRole() {
        $role = $this->getRole();
        return Yii::app()->db->createCommand()
            ->select("*")
            ->from("{{user_role}}")
            ->where("user_id=:user_id AND role_id=:role_id AND type=:type", array(
                ":user_id"=>Yii::app()->user->id,
                ":role_id"=>Role::getRoleId($role),
                ":type"=>Role::TYPE_ADMIN
            ))
            ->queryScalar();
    }

    function getUserRolesList() {

        if (Yii::app()->user->hasState('userRoles'))
            return Yii::app()->user->getState('userRoles');
        $roles = array();
        if ($user = $this->getModel()){

            $roles = $user->getUserRoles();
            Yii::app()->user->setState('userRoles', $roles);
        }
        return $roles;
    }
}