<?php

class m150505_135656_add_quote_info_field extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{quote_info}}", "type_payment", "INT(11) NOT NULL DEFAULT 1");
	}

	public function down()
	{
		$this->dropColumn("{{quote_info}}", "type_payment");
	}
}