<?php

class m150928_085430_update_country_city extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{region}}", array(
			"id"=>"pk",
            "name"=>"VARCHAR(128) NOT NULL",
            "country_id"=>"INT(11) NOT NULL",
            "vk_id"=>"INT(11) NULL",
            "status"=>"INT(1) NOT NULL DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addColumn("{{country}}", "vk_id", "INT(11) NULL");
        $this->addColumn("{{city}}", "vk_id", "INT(11) NULL");
	}

	public function down()
	{
        $this->dropColumn("{{city}}", "vk_id");
        $this->dropColumn("{{country}}", "vk_id");

        $this->dropTable("{{region}}");
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