<?php

class m141215_192119_figures_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{artist}}", array(
			'id'=>'pk',
			'name'=>'VARCHAR(128) UNIQUE NOT NULL',
			'description'=>'TEXT NULL',
			'classification_id'=>'INT NOT NULL',
			'status'=>'INT NULL DEFAULT 1'
		),"ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{event_artist}}", array(
			'event_id'=>'INT(11) NOT NULL',
			'artist_id'=>'INT(11) NOT NULL'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{artist}}_{{classification}}", "{{artist}}", "classification_id", "{{classification}}", "id");
		$this->addForeignKey("fk_{{event_artist}}_{{artist}}", "{{event_artist}}", "artist_id", "{{artist}}", "id");
		$this->addForeignKey("fk_{{event_artist}}_{{event}}", "{{event_artist}}", "event_id", "{{event}}", "id");

		$this->createTable("{{organizer}}", array(
			'id'=>'pk',
			'name'=>'VARCHAR(128) UNIQUE NOT NULL',
			'description'=>'TEXT NULL',
			'status'=>'INT NULL DEFAULT 1'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{event_organizer}}", array(
			'event_id'=>'INT(11) NOT NULL',
			'organizer_id'=>'INT(11) NOT NULL'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey('fk_{{event_organizer}}_{{organizer}}', '{{event_organizer}}', 'organizer_id', '{{organizer}}', 'id');
		$this->addForeignKey('fk_{{event_organizer}}_{{event}}', '{{event_organizer}}', 'event_id', '{{event}}', 'id');

		$this->createTable("{{organizer_classification}}", array(
			'organizer_id'=>'INT(11) NOT NULL',
			'classification_id'=>'INT(11) NOT NULL'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey('fk_{{organizer_classification}}_{{organizer}}', '{{organizer_classification}}', 'organizer_id', '{{organizer}}', 'id');
		$this->addForeignKey('fk_{{organizer_classification}}_{{classification}}', '{{organizer_classification}}', 'classification_id', '{{classification}}', 'id');

		$this->createTable("{{partner}}", array(
			'id'=>'pk',
			'name'=>'VARCHAR(128) UNIQUE NOT NULL',
			'description'=>'TEXT NULL',
			'status'=>'INT NULL DEFAULT 1'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{event_partner}}", array(
			'event_id'=>'INT(11) NOT NULL',
			'partner_id'=>'INT(11) NOT NULL'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey('fk_{{event_partner}}_{{partner}}', '{{event_partner}}', 'partner_id', '{{partner}}', 'id');
		$this->addForeignKey('fk_{{event_partner}}_{{event}}', '{{event_partner}}', 'event_id', '{{event}}', 'id');

		$this->createTable("{{partner_classification}}", array(
			'partner_id'=>'INT(11) NOT NULL',
			'classification_id'=>'INT(11) NOT NULL'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey('fk_{{partner_classification}}_{{partner}}', '{{partner_classification}}', 'partner_id', '{{partner}}', 'id');
		$this->addForeignKey('fk_{{partner_classification}}_{{classification}}', '{{partner_classification}}', 'classification_id', '{{classification}}', 'id');

	}

	public function down()
	{
		$this->dropForeignKey("fk_{{partner_classification}}_{{classification}}", "{{partner_classification}}");
		$this->dropForeignKey("fk_{{partner_classification}}_{{partner}}", "{{partner_classification}}");

		$this->dropTable("{{partner_classification}}");

		$this->dropForeignKey("fk_{{event_partner}}_{{event}}", "{{event_partner}}");
		$this->dropForeignKey("fk_{{event_partner}}_{{partner}}", "{{event_partner}}");

		$this->dropTable("{{event_partner}}");
		$this->dropTable("{{partner}}");

		$this->dropForeignKey("fk_{{organizer_classification}}_{{classification}}", "{{organizer_classification}}");
		$this->dropForeignKey("fk_{{organizer_classification}}_{{organizer}}", "{{organizer_classification}}");

		$this->dropTable("{{organizer_classification}}");

		$this->dropForeignKey("fk_{{event_organizer}}_{{event}}", "{{event_organizer}}");
		$this->dropForeignKey("fk_{{event_organizer}}_{{organizer}}", "{{event_organizer}}");

		$this->dropTable("{{event_organizer}}");
		$this->dropTable("{{organizer}}");

		$this->dropForeignKey("fk_{{event_artist}}_{{event}}", "{{event_artist}}");
		$this->dropForeignKey("fk_{{event_artist}}_{{artist}}", "{{event_artist}}");
		$this->dropForeignKey("fk_{{artist}}_{{classification}}", "{{artist}}");

		$this->dropTable("{{event_artist}}");
		$this->dropTable("{{artist}}");
	}

}