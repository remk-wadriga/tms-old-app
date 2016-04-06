<?php

class m151101_202000_access_add_level_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{access}}", "level", "INT(1) NOT NULL DEFAULT 0");
		$this->addColumn("{{access}}", "user_id", "INT(1) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{access}}", "user_id");
		$this->dropColumn("{{access}}", "level");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}