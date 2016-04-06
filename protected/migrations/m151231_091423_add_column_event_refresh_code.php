<?php

class m151231_091423_add_column_event_refresh_code extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "refresh_code", "INT NOT NULL DEFAULT 1");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "refresh_code");
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