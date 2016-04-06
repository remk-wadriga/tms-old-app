<?php

class m141001_114849_user extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{user}}", array(
			'id'=>'pk',
			'username'=>'VARCHAR(128) NOT NULL',
			'password'=>'VARCHAR(128) NOT NULL',
			'salt'=>'VARCHAR(45) NOT NULL',
			'name'=>'VARCHAR(128) NULL',
			'email'=>'VARCHAR(128) NOT NULL',
			'reg_date'=>'TIMESTAMP NOT NULL',
			'role'=>'VARCHAR(45) NOT NULL',
			'status'=>'INT NULL DEFAULT 0'
		), 'ENGINE=InnoDB COLLATE=utf8_general_ci');

		$this->createTable("{{auth_item}}", array(
			"name" => "VARCHAR(64) NOT NULL",
			"type" => "INT NOT NULL",
			"description" => "TEXT",
			"bizrule" => "TEXT",
			"data" => "TEXT"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addPrimaryKey("name", "{{auth_item}}", "name");

		$this->createTable("{{auth_item_child}}", array(
			"parent"=>"VARCHAR(64) NOT NULL",
			"child"=>"VARCHAR(64) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addPrimaryKey("parent_child", "{{auth_item_child}}", "parent, child");
		$this->addForeignKey("fk_{{auth_item_child}}_{{auth_item}}_parent", "{{auth_item_child}}", "parent", "{{auth_item}}", "name", "cascade", "cascade");
		$this->addForeignKey("fk_{{auth_item_child}}_{{auth_item}}_child", "{{auth_item_child}}", "child", "{{auth_item}}", "name", "cascade", "cascade");

		$this->createTable("{{auth_assignment}}", array(
			"itemname"=>"VARCHAR(64) NOT NULL",
			"userid"=>"VARCHAR(64) NOT NULL",
			"bizrule"=>"TEXT",
			"data"=>"TEXT"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addPrimaryKey("itemname_userid", "{{auth_assignment}}", "itemname, userid");
		$this->addForeignKey("fk_{{auth_assignment}}_{{auth_item}}", "{{auth_assignment}}", "itemname", "{{auth_item}}", "name", "cascade", "cascade");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{auth_assignment}}_{{auth_item}}", "{{auth_assignment}}");
		$this->dropPrimaryKey("itemname_userid", "{{auth_assignment}}");
		$this->dropTable("{{auth_assignment}}");

		$this->dropForeignKey("fk_{{auth_item_child}}_{{auth_item}}_child", "{{auth_item_child}}");
		$this->dropForeignKey("fk_{{auth_item_child}}_{{auth_item}}_parent", "{{auth_item_child}}");
		$this->dropPrimaryKey("parent_child", "{{auth_item_child}}");
		$this->dropTable("{{auth_item_child}}");

		$this->dropPrimaryKey("name", "{{auth_item}}");
		$this->dropTable("{{auth_item}}");

		$this->dropTable("{{user}}");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}


	public function safeDown()
	{
	}
	*/
}