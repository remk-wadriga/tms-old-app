<?php

class m150508_144919_update_field_ticket extends CDbMigration
{
	public function up()
	{
		$this->dropForeignKey("fk_{{ticket}}_{{appointment}}", "{{ticket}}");
		$this->dropForeignKey("fk_{{ticket}}_{{platform}}", "{{ticket}}");
		$this->alterColumn("{{ticket}}", "appointment_id", "INT(11) NULL");
	}

	public function down()
	{
		$this->alterColumn("{{ticket}}", "appointment_id", "INT(11) NOT NULL");
		$this->addForeignKey('fk_{{ticket}}_{{platform}}', "{{ticket}}", "platform_id", "{{platform}}", "id");
		$this->addForeignKey('fk_{{ticket}}_{{appointment}}', "{{ticket}}", "appointment_id", "{{appointment}}", "id");
	}
}