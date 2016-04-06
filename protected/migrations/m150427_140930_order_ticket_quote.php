<?php

class m150427_140930_order_ticket_quote extends CDbMigration
{
	public function up()
	{
		$this->createTable("{{appointment}}", array(
			'id'=>'pk',
			'name'=>'VARCHAR(128) NOT NULL',
			'status'=>'INT(11) NOT NULL DEFAULT 1'
		), "ENGINE=InnoDB COLLATE=utf8_general_ci");

		$this->createTable("{{order}}", array(
			'id'=>'pk',
			'total'=>'INT(11) NOT NULL',
            'comment'=>'TEXT NULL',
            'user_id'=>'INT(11) NOT NULL',
            'role_id'=>'INT(11) NOT NULL',
            'date_add'=>'TIMESTAMP NULL',
            'type'=>'INT(11) NOT NULL DEFAULT 0',
            'status'=>'INT(11) NULL DEFAULT 1',
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey("fk_{{order}}_{{user}}", "{{order}}", "user_id", "{{user}}", "id");
        $this->addForeignKey("fk_{{order}}_{{role}}", "{{order}}", "role_id", "{{role}}", "id");

        $this->createTable("{{quote_info}}", array(
            'id'=>'pk',
            'name'=>'VARCHAR(128) NULL',
            'order_id'=>'INT(11) NOT NULL',
            'role_from_id'=>'INT(11) NOT NULL',
            'role_to_id'=>'INT(11) NOT NULL',
            'from_legal_detail'=>'TEXT NULL',
            'to_legal_detail'=>'TEXT NULL',
            'percent'=>'INT(11) NOT NULL',
            'comment'=>'TEXT NULL'
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");


        $this->addForeignKey("fk_{{quote_info}}_{{order}}", "{{quote_info}}", "role_from_id", "{{role}}", "id");
        $this->addForeignKey("fk_{{quote_info}}_{{role}}", "{{quote_info}}", "role_from_id", "{{role}}", "id");
        $this->addForeignKey("fk_{{quote_info}}_{{role}}0", "{{quote_info}}", "role_to_id", "{{role}}", "id");

        $this->createTable("{{ticket}}", array(
            'id'=>'pk',
            'order_id'=>'INT(11) NOT NULL',
            'place_id'=>'INT(11) NOT NULL',
            'code'=>'VARCHAR(45) NOT NULL',
            'type'=>'INT(11) NOT NULL',
            'price'=>'INT(11) NOT NULL',
            'date_add'=>'TIMESTAMP NULL',
            'date_pay'=>'TIMESTAMP NULL',
            'date_print'=>'TIMESTAMP NULL',
            'user_id'=>'INT(11) NOT NULL',
            'role_id'=>'INT(11) NOT NULL',
            'author_print_id'=>'INT(11) NULL',
            'platform_id'=>'INT(11) NOT NULL',
            'comment'=>'TEXT NULL',
            'owner_surname'=>'VARCHAR(128) NULL',
            'owner_phone'=>'VARCHAR(128) NULL',
            'owner_mail'=>'VARCHAR(128) NULL',
            'appointment_id'=>'INT(11) NOT NULL',
            'discount'=>'INT(11) NULL',
            'status'=>'INT(11) NOT NULL DEFAULT 1',
        ), "ENGINE=InnoDB COLLATE=utf8_general_ci");

        $this->addForeignKey('fk_{{ticket}}_{{order}}', "{{ticket}}", "order_id", "{{order}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{place}}', "{{ticket}}", "place_id", "{{place}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{user}}', "{{ticket}}", "author_print_id", "{{user}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{user}}0', "{{ticket}}", "user_id", "{{user}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{role}}', "{{ticket}}", "role_id", "{{role}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{appointment}}', "{{ticket}}", "appointment_id", "{{appointment}}", "id");
        $this->addForeignKey('fk_{{ticket}}_{{platform}}', "{{ticket}}", "platform_id", "{{platform}}", "id");
	}

	public function down()
	{
        $this->dropForeignKey('fk_{{ticket}}_{{platform}}', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{appointment}}', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{role}}', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{user}}0', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{user}}', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{place}}', '{{ticket}}');
        $this->dropForeignKey('fk_{{ticket}}_{{order}}', '{{ticket}}');

        $this->dropTable('{{ticket}}');

        $this->dropForeignKey("fk_{{quote_info}}_{{role}}0", "{{quote_info}}");
        $this->dropForeignKey("fk_{{quote_info}}_{{role}}", "{{quote_info}}");
        $this->dropForeignKey("fk_{{quote_info}}_{{order}}", "{{quote_info}}");

        $this->dropTable("{{quote_info}}");

        $this->dropForeignKey("fk_{{order}}_{{role}}", "{{order}}");
        $this->dropForeignKey("fk_{{order}}_{{user}}", "{{order}}");

        $this->dropTable("{{order}}");
        $this->dropTable("{{appointment}}");
	}
}
