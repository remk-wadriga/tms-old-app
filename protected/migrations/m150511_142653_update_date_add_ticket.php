<?php

class m150511_142653_update_date_add_ticket extends CDbMigration
{
	public function up()
	{
		$this->alterColumn("{{ticket}}", "date_add", "TIMESTAMP NOT NULL");
		$this->alterColumn("{{order}}", "date_add", "TIMESTAMP NOT NULL");
		$this->addColumn("{{order}}", "date_update", "TIMESTAMP NOT NULL");
		$this->addColumn("{{quote_info}}", "event_id", "INT(11) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{quote_info}}", "event_id");
		$this->dropColumn("{{order}}", "date_update");
		$this->alterColumn("{{order}}", "date_add", "TIMESTAMP NULL");
		$this->alterColumn("{{ticket}}", "date_add", "TIMESTAMP NULL");
	}
}