<?php

class m151027_094338_modify_access_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{access}}", "condition", "VARCHAR(255) NULL");
        $this->addColumn("{{access}}", "type", "INT(11) NOT NULL DEFAULT 0");

		$this->createTable("{{access_event}}", array(
            "access_id"=>"INT(11) NOT NULL",
            "event_id"=>"INT(11) NOT NULL"
		));

	}

	public function down()
	{
        $this->dropTable("{{access_event}}");

		$this->dropColumn("{{access}}", "type");
		$this->dropColumn("{{access}}", "condition");
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