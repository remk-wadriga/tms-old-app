<?php

class m150715_151507_ticket_style extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{event_ticket}}", array(
			"id"=>"pk",
			"event_id"=>"INT(11) NOT NULL",
			"ticket"=>"TEXT NULL",
			"style"=>"TEXT NULL",
			"type"=>"INT(1) NOT NULL"
		), "ENGINE=InnoDb COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{event_ticket}}_{{event}}", "{{event_ticket}}", "event_id", "{{event}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{event_ticket}}_{{event}}", "{{event_ticket}}");
		$this->dropTable("{{event_ticket}}");
	}
}