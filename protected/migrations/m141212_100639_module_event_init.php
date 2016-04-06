<?php

class m141212_100639_module_event_init extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{classification}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"description"=>"TEXT NULL",
			"root"=>"INT(10) NULL",
			"lft"=>"INT(10) NOT NULL",
			"rgt"=>"INT(10) NOT NULL",
			"level"=>"SMALLINT(5) NOT NULL",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{group}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"description"=>"TEXT NULL",
			"classification_id"=>"INT NOT NULL",
			"type"=>"INT NOT NULL DEFAULT 0",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{group}}_{{classification}}", "{{group}}", "classification_id", "{{classification}}", "id");

		$this->createTable("{{event}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"sys_name"=>"VARCHAR(128) NOT NULL",
			"start_sale"=>"TIMESTAMP NULL",
			"end_sale"=>"TIMESTAMP NULL",
			"custom_params"=>"TEXT",
			"poster_id"=>"INT NULL",
			"description_id"=>"INT NULL",
			"group_id"=>"INT NULL",
			"scheme_id"=>"INT NOT NULL",
			"user_id"=>"INT NOT NULL",
			"status"=>"INT NULL DEFAULT 1"
		), "ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{event}}_{{scheme}}", "{{event}}", "scheme_id", "{{scheme}}", "id");
		$this->addForeignKey("fk_{{event}}_{{group}}", "{{event}}", "group_id", "{{group}}", "id");
		$this->addForeignKey("fk_{{event}}_{{user}}", "{{event}}", "user_id", "{{user}}", "id");

		$this->createTable("{{event_classification}}", array(
			"event_id"=>"INT NOT NULL",
			"classification_id"=>"INT NOT NULL"
		), "ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{event_classification}}_{{event}}", "{{event_classification}}", "event_id", "{{event}}", "id");
		$this->addForeignKey("fk_{{event_classification}}_{{classification}}", "{{event_classification}}", "classification_id", "{{classification}}", "id");

		$this->createTable("{{multimedia}}", array(
			"id"=>"pk",
			"file"=>"TEXT NOT NULL",
			"event_id"=>"INT NOT NULL",
			"status"=>"INT NULL DEFAULT 1"
		),"ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{multimedia}}_{{event}}", "{{multimedia}}", "event_id", "{{event}}", "id", "CASCADE");
		$this->addForeignKey("fk_{{event}}_{{multimedia}}0", "{{event}}", "poster_id", "{{multimedia}}", "id");
		$this->addForeignKey("fk_{{event}}_{{multimedia}}1", "{{event}}", "description_id", "{{multimedia}}", "id");

		$this->createTable("{{cluster}}", array(
			"id"=>"pk",
			"name"=>"VARCHAR(128) NOT NULL",
			"description"=>"TEXT NULL",
			"root"=>"INT(10) NULL",
			"lft"=>"INT(10) NOT NULL",
			"rgt"=>"INT(10) NOT NULL",
			"level"=>"SMALLINT(5) NOT NULL",
			"status"=>"INT NULL DEFAULT 1"

		),"ENGINE = InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{event_cluster}}", array(
			"event_id"=>"INT NOT NULL",
			"cluster_id"=>"INT NOT NULL"
		),"ENGINE = InnoDB COLLATE=utf8_general_ci" );

		$this->addForeignKey("fk_{{event_cluster}}_{{event}}", "{{event_cluster}}", "event_id", "{{event}}", "id");
		$this->addForeignKey("fk_{{event_cluster}}_{{cluster}}", "{{event_cluster}}", "cluster_id", "{{cluster}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{event_cluster}}_{{cluster}}", "{{event_cluster}}");
		$this->dropForeignKey("fk_{{event_cluster}}_{{event}}", "{{event_cluster}}");

		$this->dropTable("{{event_cluster}}");
		$this->dropTable("{{cluster}}");

		$this->dropForeignKey("fk_{{event}}_{{multimedia}}1", "{{event}}");
		$this->dropForeignKey("fk_{{event}}_{{multimedia}}0", "{{event}}");
		$this->dropForeignKey("fk_{{multimedia}}_{{event}}", "{{multimedia}}");

		$this->dropForeignKey("fk_{{event_classification}}_{{classification}}", "{{event_classification}}");
		$this->dropForeignKey("fk_{{event_classification}}_{{event}}", "{{event_classification}}");

		$this->dropTable("{{event_classification}}");

		$this->dropTable("{{multimedia}}");

		$this->dropForeignKey("fk_{{event}}_{{user}}", "{{event}}");
		$this->dropForeignKey("fk_{{event}}_{{group}}", "{{event}}");
		$this->dropForeignKey("fk_{{event}}_{{scheme}}", "{{event}}");

		$this->dropTable("{{event}}");

		$this->dropForeignKey("fk_{{group}}_{{classification}}", "{{group}}");

		$this->dropTable("{{group}}");
		$this->dropTable("{{classification}}");
	}
}