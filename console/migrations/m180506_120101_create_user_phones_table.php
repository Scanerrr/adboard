<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_phones`.
 */
class m180506_120101_create_user_phones_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%user_phones}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'telephones' => $this->string('15')->notNull()
        ]);

        $this->addForeignKey('fk_user_phones_key', '{{%user_phones}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_user_phones_key', '{{%city}}');
        $this->dropTable('{{%user_phones}}');
    }
}
