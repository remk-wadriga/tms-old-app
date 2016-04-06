<?php

class m150113_195925_event_date_add_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "date_add", "TIMESTAMP");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "date_add");
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