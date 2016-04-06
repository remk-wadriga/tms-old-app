<?php

class m150727_094213_add_position_to_event extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "position", "INT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "position");
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