<?php

class m151031_083201_create_post_table extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('{{multimedia}}','event_id','INT(11) NULL');

		$this->createTable("{{post}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(255) NOT NULL",
			"description"=>"TEXT NOT NULL",
			"alias_url"=>"TEXT NOT NULL",
			"html_header"=>"TEXT NOT NULL",
			"meta_description"=>"TEXT NOT NULL",
			"keywords"=>"TEXT NOT NULL",
			"multimedia_id"=>"INT(11) NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{post}}_{{multimedia}}", "{{post}}", "multimedia_id", "{{multimedia}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{post}}_{{multimedia}}","{{post}}");
		$this->dropTable("{{post}}");
	}

}