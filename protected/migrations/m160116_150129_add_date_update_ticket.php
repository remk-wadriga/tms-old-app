<?php

class m160116_150129_add_date_update_ticket extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "date_update", "TIMESTAMP");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "date_update");
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