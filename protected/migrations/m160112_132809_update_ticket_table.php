<?php

class m160112_132809_update_ticket_table extends CDbMigration
{
	public function up()
	{

		$this->addColumn("{{ticket}}", "event_id", "INT(11) default null");
		$this->addColumn("{{ticket}}", "sector_id", "INT(11) default null");
		$this->addColumn("{{ticket_temp}}", "event_id", "INT(11) default null");
		$this->addColumn("{{ticket_temp}}", "sector_id", "INT(11) default null");

		$this->addForeignKey("fk_{{ticket}}_{{event}}", "{{ticket}}", "event_id", "{{event}}", "id");
		$this->addForeignKey("fk_{{ticket}}_{{sector}}", "{{ticket}}", "sector_id", "{{sector}}", "id");
		$this->addForeignKey("fk_{{ticket_temp}}_{{event}}", "{{ticket_temp}}", "event_id", "{{event}}", "id");
		$this->addForeignKey("fk_{{ticket_temp}}_{{sector}}", "{{ticket_temp}}", "sector_id", "{{sector}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{ticket_temp}}_{{sector}}", "{{ticket_temp}}");
		$this->dropForeignKey("fk_{{ticket_temp}}_{{event}}", "{{ticket_temp}}");
		$this->dropForeignKey("fk_{{ticket}}_{{sector}}", "{{ticket_temp}}");
		$this->dropForeignKey("fk_{{ticket}}_{{event}}", "{{ticket_temp}}");

		$this->dropColumn("{{ticket_temp}}", "sector_id");
		$this->dropColumn("{{ticket_temp}}", "event_id");
		$this->dropColumn("{{ticket}}", "sector_id");
		$this->dropColumn("{{ticket}}", "event_id");
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