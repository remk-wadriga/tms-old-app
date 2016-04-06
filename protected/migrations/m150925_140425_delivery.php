<?php

class m150925_140425_delivery extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{delivery}}", array(
			"id"=>"pk",
            "city_id"=>"INT(11) NOT NULL",
            "order_id"=>"INT(11) NOT NULL",
            "address"=>"TEXT NOT NULL",
            "status"=>"INT(1) NOT NULL",
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{delivery}}_{{order}}", "{{delivery}}", "order_id", "{{order}}", "id");
        $this->addForeignKey("fk_{{delivery}}_{{city}}", "{{delivery}}", "city_id", "{{city}}", "id");
	}

	public function down()
	{
        $this->dropForeignKey("fk_{{delivery}}_{{city}}", "{{delivery}}");
        $this->dropForeignKey("fk_{{delivery}}_{{order}}", "{{delivery}}");
        $this->dropTable("{{delivery}}");

	}
}