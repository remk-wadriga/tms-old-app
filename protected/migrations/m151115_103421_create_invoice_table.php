<?php

class m151115_103421_create_invoice_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{invoice}}", array(
			'id'=>'pk',
			"date_create"=>"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
			"invoice_type"=>"INT(11) NOT NULL DEFAULT 0",
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');
	}

	public function down()
	{
		$this->dropTable("{{invoice}}");
	}

}