<?php

class m150408_105916_access_table extends CDbMigration
{
	public function up()
	{

		$this->createTable("{{role}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"short_name"=>"VARCHAR(45) NOT NULL",
			"description"=>"TEXT",
			"entity"=>"INT DEFAULT 0",
			"legal_detail"=>"TEXT",
			"status"=>"INT DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{user_role}}", array(
			"user_id"=>"INT(11) NOT NULL",
			"role_id"=>"INT(11) NOT NULL",
			"type"=>"INT(1) NOT NULL DEFAULT 0"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{user_role}}_{{role}}", "{{user_role}}", "role_id", "{{role}}", "id");
		$this->addForeignKey("fk_{{user_role}}_{{user}}", "{{user_role}}", "user_id", "{{user}}", "id");

		$this->createTable("{{role_child}}", array(
			"parent"=>"INT(11) NOT NULL",
			"child"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{role_child}}_{{role}}0", "{{role_child}}", "parent", "{{role}}", "id");
		$this->addForeignKey("fk_{{role_child}}_{{role}}1", "{{role_child}}", "child", "{{role}}", "id");

		$this->createTable("{{access}}", array(
			"id"=>"pk",
			"role_id"=>"INT(11) NOT NULL",
			"action"=>"VARCHAR(55) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{access}}_{{role}}", "{{access}}", "role_id", "{{role}}", "id");

		$this->createTable("{{role_model}}", array(
			"model"=>"VARCHAR(45) NOT NULL",
			"role_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{role_model}}_{{role}}", "{{role_model}}", "role_id", "{{role}}", "id");

		$this->createTable("{{template_role}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"sys_name"=>"VARCHAR(45) NOT NULL",
			"description"=>"TEXT NULL",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{template_role_model}}", array(
			"model"=>"VARCHAR(45) NOT NULL",
			"template_role_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{template_role_model}}_{{template_role}}", "{{template_role_model}}", "template_role_id", "{{template_role}}", "id");

		$this->createTable("{{template_role_access}}", array(
			"id"=>"pk",
			"action"=>"VARCHAR(45) NOT NULL",
			"template_role_id"=>"INT(11) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{template_role_access}}_{{template_role}}", "{{template_role_access}}", "template_role_id", "{{template_role}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{template_role_access}}_{{template_role}}", "{{template_role_access}}");

		$this->dropTable("{{template_role_access}}");

		$this->dropForeignKey("fk_{{template_role_model}}_{{template_role}}", "{{template_role_model}}");

		$this->dropTable("{{template_role_model}}");
		$this->dropTable("{{template_role}}");

		$this->dropForeignKey("fk_{{role_model}}_{{role}}", "{{role_model}}");

		$this->dropTable("{{role_model}}");

		$this->dropForeignKey("fk_{{access}}_{{role}}", "{{access}}");

		$this->dropTable("{{access}}");

		$this->dropForeignKey("fk_{{role_child}}_{{role}}1", "{{role_child}}");
		$this->dropForeignKey("fk_{{role_child}}_{{role}}0", "{{role_child}}");

		$this->dropTable("{{role_child}}");


		$this->dropForeignKey("fk_{{user_role}}_{{user}}", "{{user_role}}");
		$this->dropForeignKey("fk_{{user_role}}_{{role}}", "{{user_role}}");

		$this->dropTable("{{user_role}}");

		$this->dropTable("{{role}}");
	}
}
