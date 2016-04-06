<?php

class m151229_112257_add_is_admin_user_role extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{user_role}}", "is_admin","INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{user_role}}", "is_admin");
	}
}