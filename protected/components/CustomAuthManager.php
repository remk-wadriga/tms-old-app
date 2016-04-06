<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 02.02.15
 * Time: 19:40
 */

class CustomAuthManager extends CDbAuthManager
{
    public $accesses = array();

    /**
     * Returns the children of the specified item.
     * @param mixed $names the parent item name. This can be either a string or an array.
     * The latter represents a list of item names.
     * @return array all child items of the parent
     */
    public function getItemChildren($names)
    {
        if (is_string($names))
            $condition = 'parent=' . $this->db->quoteValue(Role::getRoleId($names));
        elseif (is_array($names) && $names !== array()) {
            foreach ($names as &$name)
                $name = $this->db->quoteValue($name);
            $condition = 'parent IN (' . implode(', ', $names) . ')';
        }

        $rows = $this->db->createCommand()
            ->select('name, description')
            ->from(array(
                $this->itemTable,
                $this->itemChildTable
            ))
            ->where($condition . ' AND id=child')
            ->queryAll();

        $children = array();
        foreach ($rows as $row) {
            $children[$row['name']] = new CustomAuthItem($this, $row['name'], null, $row['description'], null, null);
        }
        return $children;
    }

    /**
     * Adds an item as a child of another item.
     * @param string $itemName the parent item name
     * @param string $childName the child item name
     * @return boolean whether the item is added successfully
     * @throws CException if either parent or child doesn't exist or if a loop has been detected.
     */
    public function addItemChild($itemName, $childName)
    {
        if ($itemName === $childName)
            throw new CException(Yii::t('yii', 'Cannot add "{name}" as a child of itself.',
                array('{name}' => $itemName)));

        $rows = $this->db->createCommand()
            ->select()
            ->from($this->itemTable)
            ->where('id=:name1 OR id=:name2', array(
                ':name1' => $itemName,
                ':name2' => $childName
            ))
            ->queryAll();

        if (count($rows) == 2) {
            if ($this->detectLoop($itemName, $childName))
                throw new CException(Yii::t('yii', 'Cannot add "{child}" as a child of "{name}". A loop has been detected.',
                    array('{child}' => $childName, '{name}' => $itemName)));

            $this->db->createCommand()
                ->insert($this->itemChildTable, array(
                    'parent' => $itemName,
                    'child' => $childName,
                ));

            return true;
        } else
            throw new CException(Yii::t('yii', 'Either "{parent}" or "{child}" does not exist.', array('{child}' => $childName, '{parent}' => $itemName)));
    }

    /**
     * Assigns an authorization item to a user.
     * @param string $itemName the item name
     * @param mixed $userId the user ID (see {@link IWebUser::getId})
     * @param string $bizRule the business rule to be executed when {@link checkAccess} is called
     * for this particular authorization item.
     * @param mixed $data additional data associated with this assignment
     * @return CAuthAssignment the authorization assignment information.
     * @throws CException if the item does not exist or if the item has already been assigned to the user
     */
    public function assign($itemName, $userId, $bizRule = null, $data = null)
    {
        $this->db->createCommand()
            ->insert($this->assignmentTable, array(
                'role_id' => $itemName,
                'user_id' => $userId,
                'type' => Role::TYPE_USER
            ));

        return new CAuthAssignment($this, $itemName, $userId, $bizRule, $data);
    }

    /**
     * Revokes an authorization assignment from a user.
     * @param string $itemName the item name
     * @param mixed $userId the user ID (see {@link IWebUser::getId})
     * @return boolean whether removal is successful
     */
    public function revoke($itemName, $userId)
    {
        return $this->db->createCommand()
            ->delete($this->assignmentTable, 'role_id=:itemname AND user_id=:userid', array(
                ':itemname' => $itemName,
                ':userid' => $userId
            )) > 0;
    }

    public function createAuthItem($name, $type, $description = '', $bizRule = null, $data = null)
    {
        $this->db->createCommand()
            ->insert($this->itemTable, array(
                'name' => $name,
                'status' => Role::STATUS_ACTIVE
            ));
        return new CAuthItem($this, $name, $type, $description, $bizRule, $data);
    }

    /**
     * Returns roles.
     * This is a shortcut method to {@link IAuthManager::getAuthItems}.
     * @param mixed $userId the user ID. If not null, only the roles directly assigned to the user
     * will be returned. Otherwise, all roles will be returned.
     * @return array roles (name=>CAuthItem)
     */
    public function getRoles($userId = null)
    {
        return $this->getAuthItems(null, $userId);
    }

    /**
     * Returns the authorization items of the specific type and user.
     * @param integer $type the item type (0: operation, 1: task, 2: role). Defaults to null,
     * meaning returning all items regardless of their type.
     * @param mixed $userId the user ID. Defaults to null, meaning returning all items even if
     * they are not assigned to a user.
     * @return array the authorization items of the specific type.
     */
    public function getAuthItems($type = null, $userId = null)
    {

        if ($type === null && $userId === null) {
            $command = $this->db->createCommand()
                ->select()
                ->from($this->itemTable);
        } elseif ($userId === null) {
            $command = $this->db->createCommand()
                ->select()
                ->from($this->itemTable)
                ->where('type=:type', array(':type' => $type));
        } elseif ($type === null) {
            $command = $this->db->createCommand()
                ->select('name,description')
                ->from(array(
                    $this->itemTable . ' t1',
                    $this->assignmentTable . ' t2'
                ))
                ->where('t1.id=role_id AND user_id=:userid', array(':userid' => $userId));
        } else {
            $command = $this->db->createCommand()
                ->select('name,type,description,t1.bizrule,t1.data')
                ->from(array(
                    $this->itemTable . ' t1',
                    $this->assignmentTable . ' t2'
                ))
                ->where('name=itemname AND type=:type AND userid=:userid', array(
                    ':type' => $type,
                    ':userid' => $userId
                ));
        }
        $items = array();


        foreach ($command->queryAll() as $row) {
            if (($data = @unserialize($row['data'])) === false)
                $data = null;
            $items[$row['name']] = new CustomAuthItem($this, $row['name'], null, $row['description'], null, $data);
        }
        return $items;
    }

    public function checkAccess($itemName, $userId, $params = array(), $controller = false, $event=false)
    {
        if (Yii::app()->user->isAdmin)
            return true;

        $assignments = $this->getAuthAssignments($itemName);

        $result = Yii::app()->user->getState($itemName.$userId.json_encode($params).$controller);

        if ($result==null) {
            $result = $this->checkAccessRecursive($itemName, $userId, $params, $assignments, $controller);

            Yii::app()->user->setState($itemName.$userId.json_encode($params).$controller, $result);
        }
        $events_access = Yii::app()->user->getState("access_event".Yii::app()->user->currentRoleId);
        if ($result&&$event&&$controller&&$events_access)
            if (array_key_exists($controller, $events_access))
                return in_array($event, $events_access[$controller]);

        return $result;
    }

    /**
     *
     */
    public function getAllowedIds($controller)
    {
        $event_access = Yii::app()->user->getState("access_event".Yii::app()->user->currentRoleId);
        if ($event_access) {
            if (array_key_exists($controller, $event_access))
                return $event_access[$controller];
        }
        return array();
    }

    /**
     * Returns the item assignments for the specified user.
     * @param mixed $userId the user ID (see {@link IWebUser::getId})
     * @return array the item assignment information for the user. An empty array will be
     * returned if there is no item assigned to the user.
     */
    public function getAuthAssignments($itemName)
    {
        $assignments = array();
        $user = User::model()->findByPk(Yii::app()->user->id);
        if ($user&&$user->getIsRoleAdmin(Yii::app()->user->currentRoleId)==Role::LEVEL_SELF) {
            $accesses = Yii::app()->user->getState("user_accesses".Yii::app()->user->currentRoleId);
//            $access_event = Yii::app()->user->getState("access_event".Yii::app()->user->currentRoleId);
            if (!$accesses) {
                $accesses = $this->db->createCommand()
                    ->select("event_id, id, action, allow_action")
                    ->from("{{access}} a")
                    ->leftJoin("{{access_event}} ae", "a.id=ae.access_id")
                    ->where('role_id=:role_id AND user_id=:user_id AND level=:level', array(
                        ':role_id' => Yii::app()->user->currentRoleId,
                        ':user_id' => $user->id,
                        ":level"=>Access::LEVEL_USER
                    ))
                    ->queryAll();
                $newAccesses = array();
                $withEvent = array();

                foreach ($accesses as $access) {
                    $newAccesses[] = $access['action'];
                    $allow_actions = json_decode($access['allow_action']);
                    if (is_array($allow_actions))
                        foreach ($allow_actions as $allow_action){
                            $newAccesses[] = $allow_action;
                            if ($access['event_id'])
                                $withEvent[$allow_action][] = $access['event_id'];
                        }
                    if ($access['event_id'])
                        $withEvent[$access['action']][] = $access['event_id'];
                }

                $accesses = $newAccesses;
                Yii::app()->user->setState("access_event".Yii::app()->user->currentRoleId, $withEvent);
                Yii::app()->user->setState("user_accesses".Yii::app()->user->currentRoleId, $accesses);
            }
        } else {
            $accesses = Yii::app()->user->getState("role_accesses".Yii::app()->user->currentRoleId);
//            $access_event = Yii::app()->user->getState("role_access_event".Yii::app()->user->currentRoleId);
            if (!$accesses) {
                $accesses = $this->db->createCommand()
                    ->select("event_id, id, action, allow_action")
                    ->from("{{access}} a")
                    ->leftJoin("{{access_event}} ae", "a.id=ae.access_id")
                    ->where('role_id=:role_id', array(':role_id' => Yii::app()->user->currentRoleId))
                    ->queryAll();

                $newAccesses = array();
                $withEvent = array();

                foreach ($accesses as $access) {
                    $newAccesses[] = $access['action'];
                    $allow_actions = json_decode($access['allow_action']);
                    if (is_array($allow_actions))
                        foreach ($allow_actions as $allow_action) {
                            $newAccesses[] = $allow_action;
                            if ($access['event_id'])
                                $withEvent[$allow_action][] = $access['event_id'];
                        }
                    if ($access['event_id'])
                        $withEvent[$access['action']][] = $access['event_id'];
                }
                $accesses = $newAccesses;

                Yii::app()->user->setState("role_access_event".Yii::app()->user->currentRoleId, $withEvent);
                Yii::app()->user->setState("role_accesses".Yii::app()->user->currentRoleId, $accesses);
            }
        }
//        CVarDumper::dump($accesses,10,1);exit;
        $assignments[$itemName] = new CAuthAssignment($this, $itemName, Yii::app()->user->id, null, $accesses);

        return $assignments;
    }

    /**
     * Performs access check for the specified user.
     * This method is internally called by {@link checkAccess}.
     * @param string $itemName the name of the operation that need access check
     * @param mixed $userId the user ID. This should can be either an integer and a string representing
     * the unique identifier of a user. See {@link IWebUser::getId}.
     * @param array $params name-value pairs that would be passed to biz rules associated
     * with the tasks and roles assigned to the user.
     * Since version 1.1.11 a param with name 'userId' is added to this array, which holds the value of <code>$userId</code>.
     * @param array $assignments the assignments to the specified user
     * @return boolean whether the operations can be performed by the user.
     * @since 1.1.3
     */
    protected function checkAccessRecursive($itemName, $userId, $params, $assignments, $controller = false)
    {
        if (($item = $this->getAuthItem($itemName)) === null)
            return false;
        Yii::trace('Checking permission "' . $item->getName() . '"', 'system.web.auth.CDbAuthManager');

        if ($this->executeBizRule($item->getBizRule(), $params, $item->getData())) {

            if (in_array($itemName, $this->defaultRoles))
                return true;
            if (isset($assignments[$itemName])) {
                if (in_array($controller, $assignments[$itemName]->data))
                    return true;

            }
            $parents = Yii::app()->user->getState($itemName.$userId.json_encode($params).json_encode($assignments).$controller);
            if ($parents == null) {
                $parents = $this->db->createCommand()
                    ->select('parent')
                    ->from($this->itemChildTable)
                    ->where('child=:name', array(':name' => Role::getRoleId($itemName)))
                    ->queryColumn();
                Yii::app()->user->setState($itemName.$userId.json_encode($params).json_encode($assignments).$controller, $parents);
            }
            foreach ($parents as $parent) {
                $parent = Role::getRoleName($parent);
                if ($this->checkAccessRecursive($parent, $userId, $params, $assignments, $controller))
                    return true;
            }
        }
        return false;
    }

    /**
     * Returns the authorization item with the specified name.
     * @param string $name the name of the item
     * @return CAuthItem the authorization item. Null if the item cannot be found.
     */
    public function getAuthItem($name)
    {
        $row = $this->db->createCommand()
            ->select()
            ->from($this->itemTable)
            ->where('name=:name', array(':name' => $name))
            ->queryRow();

        if ($row !== false) {
            return new CustomAuthItem($this, $row['name'], null, $row['description']);
        } else
            return null;
    }
}