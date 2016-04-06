<?php

class m150618_113240_modify_event_description_field_type extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey("fk_{{event}}_{{multimedia}}1", "{{event}}");
		$this->dropColumn("{{event}}", "description_id");
		$this->addColumn("{{event}}", "description_id", "TEXT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{event}}", "description_id");
		$this->addColumn("{{event}}", "description_id", "INT NULL");
		$this->addForeignKey("fk_{{event}}_{{multimedia}}1", "{{event}}", "description_id", "{{multimedia}}", "id");
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