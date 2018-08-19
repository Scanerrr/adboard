<?php

use yii\db\Migration;

/**
 * Handles the creation of table `favourite`.
 */
class m180818_081703_create_favourite_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorite}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_ad_favorite_key', '{{%favorite}}', 'ad_id', '{{%ads}}', 'id');
        $this->addForeignKey('fk_user_favorite_key', '{{%favorite}}', 'user_id', '{{%user}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%favorite}}');
    }
}
