<?php

class m150421_131823_module_type_access extends CDbMigration
{
	public function up()
	{
		$this->addColumn("{{template_role_model}}", "type", "INT NOT NULL DEFAULT 0");
		$this->addColumn("{{role_model}}", "type", "INT NOT NULL DEFAULT 0");

		$this->createTable("{{role_template}}", array(
			"role_id"=>"INT(11) NOT NULL",
			"template_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{role_template}}_{{role}}", "{{role_template}}", "role_id", "{{role}}", "id");
		$this->addForeignKey("fk_{{role_template}}_{{template_role}}", "{{role_template}}", "template_id", "{{template_role}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{role_template}}_{{template_role}}", "{{role_template}}");
		$this->dropForeignKey("fk_{{role_template}}_{{role}}", "{{role_template}}");

		$this->dropTable("{{role_template}}");

		$this->dropColumn("{{role_model}}", "type");
		$this->dropColumn("{{template_role_model}}", "type");
	}
}