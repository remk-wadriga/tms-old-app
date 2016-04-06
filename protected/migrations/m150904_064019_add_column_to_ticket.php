<?php

class m150904_064019_add_column_to_ticket extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{ticket}}", "type_blank", "INT(1) NOT NULL");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "type_blank");
	}
}