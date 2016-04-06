<?php

class m150529_100103_role_id_event_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "role_id", "INT(11) NOT NULL");
		$this->addColumn("{{user_access}}", "controller", "VARCHAR(255) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "role_id");
		$this->dropColumn("{{user_access}}", "controller");

	}
}