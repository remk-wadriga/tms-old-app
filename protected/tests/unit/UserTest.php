<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 09.01.16
 * Time: 12:58
 */
class UserTest extends DbTestCase
{
    public $fixtures = array(
        'users'=>'User'
    );

    public function testGetFullName()
    {
        $user = $this->users('user1');
        $this->assertTrue(is_string($user->fullName));
    }
}
