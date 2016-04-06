<?php

class m150901_125903_add_fields_toRole extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{role}}", "company_name", "VARCHAR(255) NULL");
		$this->addColumn("{{role}}", "code_yerdpou", "INT(8) NULL");
		$this->addColumn("{{role}}", "post", "VARCHAR(255) NULL");
		$this->addColumn("{{role}}", "real_name", "VARCHAR(255) NULL");
	}

	public function down()
	{
		$this->dropColumn("{{role}}", "real_name");
		$this->dropColumn("{{role}}", "post");
		$this->dropColumn("{{role}}", "code_yerdpou");
		$this->dropColumn("{{role}}", "company_name");
	}

}