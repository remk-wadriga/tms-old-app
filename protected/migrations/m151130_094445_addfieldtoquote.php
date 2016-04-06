<?php

class m151130_094445_addfieldtoquote extends CDbMigration
{
	public function up()
	{
        $this->addColumn("{{quote_info}}", "status", "INT(11) NOT NULL DEFAULT 0");
        $this->addColumn("{{quote_info}}", "type", "INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
        $this->dropColumn("{{quote_info}}","status");
        $this->dropColumn("{{quote_info}}","type");
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