<?php

class m150306_120245_platform_and_update_place_table extends CDbMigration
{
	public function up()
	{
        $this->dropForeignKey("fk_{{place}}_{{type_place}}", "{{place}}");
        $this->dropForeignKey("fk_{{place}}_{{type_row}}", "{{place}}");
        $this->dropForeignKey("fk_{{place}}_{{sector}}", "{{place}}");

        $this->dropTable("{{place}}");

        $this->createTable("{{place}}", array(
            "id"=>"pk",
            "row"=>"INT(11) NOT NULL",
            "place"=>"INT(11) NOT NULL",
            "event_id"=>"INT(11) NOT NULL",
            "sector_id"=>"INT(11) NOT NULL",
            "price"=>"INT(11) NULL",
            "code"=>"VARCHAR(45) NULL",
            "type"=>"INT NOT NULL DEFAULT 0",
            "edited_row"=>"VARCHAR(128) NULL",
            "edited_place"=>"VARCHAR(128) NULL",
            "status"=>"INT(11) NOT NULL DEFAULT 1",
        ),"ENGINE=InnoDB COLLATE=utf8_general_ci");


        $this->addForeignKey("fk_{{place}}_{{event}}", "{{place}}", "event_id", "{{event}}", "id");
        $this->addForeignKey("fk_{{place}}_{{sector}}", "{{place}}", "sector_id", "{{sector}}", "id");

        $this->createTable("{{platform}}", array(
            "id"=>"pk",
            "name"=>"VARCHAR(45) NOT NULL",
            "type"=>"INT(2) NOT NULL",
            "partner_id"=>"INT(11) NOT NULL",
            "status"=>"INT(2) NOT NULL",
        ),"ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{platform}}_{{partner}}", "{{platform}}", "partner_id", "{{partner}}", "id");

        $this->createTable("{{platform_place}}", array(
            "platform_id"=>"INT(11) NOT NULL",
            "place_id"=>"INT(11) NOT NULL"
        ),"ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{platform_place}}_{{platform}}", "{{platform_place}}", "platform_id", "{{platform}}", "id");
        $this->addForeignKey("fk_{{platform_place}}_{{place}}", "{{platform_place}}", "place_id", "{{place}}", "id");

        $this->alterColumn("{{sector}}", "type_row_id", "INT(11) NULL");
        $this->alterColumn("{{sector}}", "type_place_id", "INT(11) NULL");
	}

	public function down()
	{
        $this->alterColumn("{{sector}}", "type_place_id", "INT(11) NOT NULL");
        $this->alterColumn("{{sector}}", "type_row_id", "INT(11) NOT NULL");

        $this->dropForeignKey("fk_{{platform_place}}_{{place}}", "{{platform_place}}");
        $this->dropForeignKey("fk_{{platform_place}}_{{platform}}", "{{platform_place}}");

        $this->dropTable("{{platform_place}}");

        $this->dropForeignKey("fk_{{platform}}_{{partner}}", "{{platform}}");
        $this->dropTable("{{platform}}");

        $this->dropForeignKey("fk_{{place}}_{{sector}}", "{{place}}");
        $this->dropForeignKey("fk_{{place}}_{{event}}", "{{place}}");

        $this->dropTable("{{place}}");

        $this->createTable("{{place}}", array(
            "id"=>"pk",
            "row"=>"INT(11) NOT NULL",
            "place"=>"INT(11) NOT NULL",
            "status"=>"INT(11) NOT NULL DEFAULT 1",
            "sector_id"=>"INT(11) NOT NULL",
            "type_row_id"=>"INT(11) NOT NULL",
            "type_place_id"=>"INT(11) NOT NULL",
            "edited_row"=>"VARCHAR(128) NULL",
            "edited_place"=>"VARCHAR(128) NULL",
        ),"ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{place}}_{{sector}}", "{{place}}", "sector_id", "{{sector}}", "id");
        $this->addForeignKey("fk_{{place}}_{{type_row}}", "{{place}}", "type_row_id", "{{type_row}}", "id");
        $this->addForeignKey("fk_{{place}}_{{type_place}}", "{{place}}", "type_place_id", "{{type_place}}", "id");
	}
}