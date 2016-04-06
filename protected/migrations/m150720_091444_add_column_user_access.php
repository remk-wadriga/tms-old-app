<?php

class m150720_091444_add_column_user_access extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{user_access}}", "role_id", "INT(11) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{user_access}}", "role_id");
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