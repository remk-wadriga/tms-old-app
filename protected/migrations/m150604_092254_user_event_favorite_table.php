<?php

class m150604_092254_user_event_favorite_table extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{user_favorites}}", array(
			"user_id"=>"INT(11) NOT NULL",
			"model"=>"VARCHAR(255) NOT NULL",
			"model_id"=>"INT(11) NOT NULL"
		), "ENGINE=InnoDb COLLATE=utf8_general_ci");

		$this->addForeignKey("fk_{{user_favorites}}_{{user}}", "{{user_favorites}}", "user_id", "{{user}}", "id");

	}

	public function down()
	{
		$this->dropForeignKey("fk_{{user_favorites}}_{{user}}", "{{user_favorites}}");

		$this->dropTable("{{user_favorites}}");
	}
}