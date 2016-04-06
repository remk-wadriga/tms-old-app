<?php

class m150416_144004_change_column_access_table extends CDbMigration
{
	public function up()
	{
		$this->alterColumn("{{template_role_access}}", "action", "VARCHAR(255) NOT NULL");
		$this->alterColumn("{{access}}", "action", "VARCHAR(255) NOT NULL");
	}

	public function down()
	{
		$this->alterColumn("{{template_role_access}}", "action", "VARCHAR(55) NOT NULL");
		$this->alterColumn("{{access}}", "action", "VARCHAR(55) NOT NULL");
	}
}