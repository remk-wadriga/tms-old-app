<?php

class m140922_124531_init extends CDbMigration
{
	public function up()
	{
        $this->createTable("{{country}}", array(
            "id"=>"pk",
            "name"=>"VARCHAR(128) NOT NULL",
            "status"=>"INT NULL DEFAULT 1",
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->createTable("{{city}}", array(
            "id"=>"pk",
            "name"=>"VARCHAR(128) NOT NULL",
            "country_id"=>"INT NOT NULL",
            "status"=>"INT NULL DEFAULT 1",
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{city}}_{{country}}", "{{city}}", "country_id", "{{country}}", "id");

        $this->createTable("{{location_category}}", array(
            "id"=>"pk",
            "name"=>"VARCHAR(128) NOT NULL",
            "status"=>"INT NULL DEFAULT 1"
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->createTable("{{location}}", array(
            "id"=>"pk",
            "name"=>"VARCHAR(128) NOT NULL",
            "short_name"=>"VARCHAR(128) NULL",
            "sys_name"=>"VARCHAR(128) NULL",
            "location_category_id"=>"INT NOT NULL",
            "city_id"=>"INT NOT NULL",
            "address"=>"TEXT NOT NULL",
            "short_address"=>"TEXT NOT NULL",
            "lat"=>"DECIMAL(18,12) NOT NULL DEFAULT 0",
            "lng"=>"DECIMAL(18,12) NOT NULL DEFAULT 0",
            "status"=>"INT NULL DEFAULT 0"
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{location}}_{{city}}", "{{location}}", "city_id", "{{city}}", "id");
        $this->addForeignKey("fk_{{location}}_{{location_category}}", "{{location}}", "location_category_id", "{{location_category}}", "id");
	}

	public function down()
	{
        $this->dropForeignKey("fk_{{location}}_{{location_category}}", "{{location}}");
        $this->dropForeignKey("fk_{{location}}_{{city}}", "{{location}}");

        $this->dropTable("{{location}}");
        $this->dropTable("{{location_category}}");

		$this->dropForeignKey("fk_{{city}}_{{country}}",  "{{city}}");

        $this->dropTable("{{city}}");
        $this->dropTable("{{country}}");
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
