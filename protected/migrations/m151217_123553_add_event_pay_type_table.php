<?php

class m151217_123553_add_event_pay_type_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{event_pay_type}}", array(
			'id'=>'pk',
			"event_id"=>"INT(11) NOT NULL",
			"type"=>"INT(11) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1",
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');
		$this->addForeignKey("fk_{{event_pay_type}}_{{event}}", "{{event_pay_type}}", "event_id", "{{event}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{event_pay_type}}_{{event}}", "{{event_pay_type}}");
		$this->dropTable("{{event_pay_type}}");
	}

}