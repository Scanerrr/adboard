<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'firstname' => $this->string(),
            'lastname' => $this->string(),
            'role' => $this->integer()->notNull(),
            'city_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%roles}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->insert('{{%roles}}', ['name' => 'Администратор', 'description' => 'Роль, имеющая больше всего привелегий']);
        $this->insert('{{%roles}}', ['name' => 'Модератор', 'description' => 'Стандартная роль для модераторов']);
        $this->insert('{{%roles}}', ['name' => 'Пользователь', 'description' => 'Стандартная роль для пользователя']);
        $this->addForeignKey('fk_roles_key', '{{%user}}', 'role', '{{%roles}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_roles_key', '{{%user}}');
        $this->dropTable('{{%roles}}');
        $this->dropTable('{{%user}}');
    }
}
