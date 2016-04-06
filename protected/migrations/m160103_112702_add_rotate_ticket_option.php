<?php

class m160103_112702_add_rotate_ticket_option extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{user}}", "rotate_ticket", "INT(11) NOT NULL DEFAULT 0");
	}

	public function down()
	{
		$this->dropColumn("{{user}}", "rotate_ticket");
	}

}