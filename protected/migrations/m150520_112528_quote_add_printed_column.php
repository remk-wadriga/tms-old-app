<?php

class m150520_112528_quote_add_printed_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{quote_info}}", "printed", "INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{quote_info}}", "printed");
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