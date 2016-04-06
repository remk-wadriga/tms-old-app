<?php

class m141014_104642_scheme_place extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{type_sector}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{type_row}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{type_place}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{scheme}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(45) NOT NULL",
			"location_id"=>"INT(11) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{scheme}}_{{location}}", "{{scheme}}", "location_id", "{{location}}", "id");

		$this->createTable("{{sector}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"type"=>"INT(11) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1",
			"scheme_id"=>"INT(11) NOT NULL",
			"places"=>"TEXT NULL",
			"type_sector_id"=>"INT(11) NOT NULL"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{sector}}_{{scheme}}", "{{sector}}", "scheme_id", "{{scheme}}", "id");
		$this->addForeignKey("fk_{{sector}}_{{type_sector}}", "{{sector}}", "type_sector_id", "{{type_sector}}", "id");

		$this->createTable("{{place}}", array(
			"id"=>"pk",
			"row"=>"INT(11) NOT NULL",
			"place"=>"INT(11) NOT NULL",
			"status"=>"INT(11) NOT NULL DEFAULT 1",
			"sector_id"=>"INT(11) NOT NULL"
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{place}}_{{sector}}", "{{place}}", "sector_id", "{{sector}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{place}}_{{sector}}", "{{place}}");

		$this->dropTable("{{place}}");

		$this->dropForeignKey("fk_{{sector}}_{{type_sector}}", "{{sector}}");
		$this->dropForeignKey("fk_{{sector}}_{{scheme}}", "{{sector}}");

		$this->dropTable("{{sector}}");

		$this->dropForeignKey("fk_{{scheme}}_{{location}}", "{{scheme}}");

		$this->dropTable("{{scheme}}");
		$this->dropTable("{{type_place}}");
		$this->dropTable("{{type_row}}");
		$this->dropTable("{{type_sector}}");
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