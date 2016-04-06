<?php

class m160126_094742_add_event_barcodeType extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{event}}", "barcode_type", "INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "barcode_type");
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