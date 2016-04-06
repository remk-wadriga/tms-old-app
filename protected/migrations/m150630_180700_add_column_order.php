<?php

class m150630_180700_add_column_order extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{order}}", "api", "INT(11) NULL");
		$this->addColumn("{{ticket}}", "api", "INT(11) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{ticket}}", "api");
		$this->dropColumn("{{order}}", "api");
	}
}