<?php

class m151103_081703_tempOrder extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{order_temp}}", array(
			"id"=>"pk",
            "total"=>"FLOAT NOT NULL DEFAULT 0",
            "user_id"=>"INT(11) NULL",
            "role_id"=>"INT(11) NOT NULL",
            "date_add"=>"TIMESTAMP NOT NULL",
            "api"=> "INT(11) NULL",
            "token"=>"VARCHAR(128) NOT NULL",
            "status"=>"INT(11) NOT NULL DEFAULT 1"
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{order_temp}}_{{user}}", "{{order_temp}}", "user_id", "{{user}}", "id");
        $this->addForeignKey("fk_{{order_temp}}_{{role}}", "{{order_temp}}", "role_id", "{{role}}", "id");
        $this->addForeignKey("fk_{{order_temp}}_{{platform}}", "{{order_temp}}", "api", "{{platform}}", "id");

        $this->createTable("{{ticket_temp}}", array(
			"id"=>"pk",
            "place_id"=>"INT(11) NOT NULL",
            "order_temp_id"=>"INT(11) NOT NULL",
            "price"=>"FLOAT NOT NULL DEFAULT 0",
            "status"=>"INT(11) NOT NULL DEFAULT 1"
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{ticket_temp}}_{{place}}", "{{ticket_temp}}", "place_id", "{{place}}", "id");
        $this->addForeignKey("fk_{{ticket_temp}}_{{order_temp}}", "{{ticket_temp}}", "order_temp_id", "{{order_temp}}", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
        $this->dropForeignKey("fk_{{ticket_temp}}_{{order_temp}}", "{{ticket_temp}}");
        $this->dropForeignKey("fk_{{ticket_temp}}_{{place}}", "{{ticket_temp}}");
		$this->dropTable("{{ticket_temp}}");

        $this->dropForeignKey("fk_{{order_temp}}_{{platform}}", "{{order_temp}}");
        $this->dropForeignKey("fk_{{order_temp}}_{{role}}", "{{order_temp}}");
        $this->dropForeignKey("fk_{{order_temp}}_{{user}}", "{{order_temp}}");
		$this->dropTable("{{order_temp}}");
	}
}