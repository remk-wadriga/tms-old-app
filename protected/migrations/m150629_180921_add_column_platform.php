<?php

class m150629_180921_add_column_platform extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{platform}}", "role_id", "INT(11) NOT NULL");
		$this->addColumn("{{platform}}", "description", "TEXT NULL");

		$this->addForeignKey("fk_{{platform}}_{{role}}", "{{platform}}", "role_id", "{{role}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{platform}}_{{role}}", "{{platform}}");

		$this->dropColumn("{{platform}}", "description");
		$this->dropColumn("{{platform}}", "role_id");
	}
}