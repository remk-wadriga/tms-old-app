<?php

class m150525_142152_role_add_date_add_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{role}}", "date_add", "TIMESTAMP NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{role}}", "date_add");
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