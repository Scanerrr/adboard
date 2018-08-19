<?php

use yii\db\Migration;

/**
 * Handles the creation of table `currency`.
 */
class m180818_075243_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(3)->notNull()->unique(),
            'description' => $this->string(),
        ]);
        $this->insert('{{%currency}}', ['code' => 'UAH', 'description' => 'Гривна']);
        $this->insert('{{%currency}}', ['code' => 'EUR', 'description' => 'Евро']);
        $this->insert('{{%currency}}', ['code' => 'USD', 'description' => 'Доллар']);
        $this->addForeignKey('fk_ad_currency_key', '{{%ads}}', 'currency_id', '{{%currency}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
