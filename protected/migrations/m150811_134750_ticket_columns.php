<?php

class m150811_134750_ticket_columns extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "tag", "VARCHAR(255) NULL");
		$this->addColumn("{{ticket}}", "cash_user_id", "INT(11) NULL");
		$this->addColumn("{{ticket}}", "cancel_day", "TIMESTAMP NULL");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "cancel_day");
		$this->dropColumn("{{ticket}}", "cash_user_id");
		$this->dropColumn("{{ticket}}", "tag");
	}
}