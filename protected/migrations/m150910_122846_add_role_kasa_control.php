<?php

class m150910_122846_add_role_kasa_control extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{kasa_control}}", "role_id","INT(11) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{kasa_control}}", "role_id");
	}
}