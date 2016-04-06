<?php

class m150819_114246_ticket_user_role_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "cash_role_id", "INT(11) NULL");
		$this->addColumn("{{ticket}}", "print_role_id", "INT(11) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "print_role_id");
		$this->dropColumn("{{ticket}}", "cash_role_id");
	}
}