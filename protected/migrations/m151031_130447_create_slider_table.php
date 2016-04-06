<?php

class m151031_130447_create_slider_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{slider}}", array(
			"id"=>"pk",
			"event_id"=>"INT(11) NOT NULL",
			"multimedia_id"=>"INT(11) NULL",
			"small_multimedia_id"=>"INT(11) NULL",
			"background_color"=>"VARCHAR(50) NOT NULL",
			"text_color"=>"VARCHAR(50) NOT NULL",
			"is_on_main"=>"INT(11) NOT NULL DEFAULT 0",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{slider_city}}", array(
			"city_id"=>"INT(11) NOT NULL",
			"slider_id"=>"INT(11) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{slider_city}}_{{slider}}", "{{slider_city}}", "slider_id", "{{slider}}", "id");
		$this->addForeignKey("fk_{{slider_city}}_{{city}}", "{{slider_city}}", "city_id", "{{city}}", "id");
		$this->addForeignKey("fk_{{slider}}_{{event}}", "{{slider}}", "event_id", "{{event}}", "id");
		$this->addForeignKey("fk_{{slider}}_{{multimedia}}", "{{slider}}", "multimedia_id", "{{multimedia}}", "id");
		$this->addForeignKey("fk_{{slider}}_{{multimedia}}_2", "{{slider}}", "small_multimedia_id", "{{multimedia}}", "id");
	}

	public function down()
	{
		$this->dropForeignKey("fk_{{slider}}_{{multimedia}}", "{{slider}}");
		$this->dropForeignKey("fk_{{slider}}_{{multimedia}}_2", "{{slider}}");
		$this->dropForeignKey("fk_{{slider}}_{{event}}", "{{slider}}");
		$this->dropForeignKey("fk_{{slider_city}}_{{city}}", "{{slider_city}}");
		$this->dropForeignKey("fk_{{slider_city}}_{{slider}}", "{{slider_city}}");
		$this->dropTable("{{slider_city}}");
		$this->dropTable("{{slider}}");
	}


}