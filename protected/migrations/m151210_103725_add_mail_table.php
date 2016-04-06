<?php

class m151210_103725_add_mail_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{mail}}", array(
			'id'=>'pk',
			"ticket_id"=>"INT(11) NOT NULL",
			"date_add"=>"TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP",
			"type"=>"INT(11) NOT NULL DEFAULT 0",
			"status"=>"INT(11) NOT NULL DEFAULT 1",
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');
		$this->addForeignKey("fk_{{mail}}_{{ticket}}", "{{mail}}", "ticket_id", "{{ticket}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{mail}}_{{ticket}}", "{{mail}}");
		$this->dropTable("{{mail}}");
	}
}