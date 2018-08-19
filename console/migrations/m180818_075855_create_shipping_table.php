<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping`.
 */
class m180818_075855_create_shipping_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipping}}', [
            'id' => $this->primaryKey(),
            'method' => $this->string(24)->notNull()->unique(),
            'description' => $this->string(),
        ]);
        $this->insert('{{%shipping}}', ['method' => 'novaphoshta', 'description' => 'Новая почта']);
        $this->insert('{{%shipping}}', ['method' => 'intime', 'description' => 'Интайм']);
        $this->insert('{{%shipping}}', ['method' => 'ukrposhta', 'description' => 'Укрпочта']);
        $this->addForeignKey('fk_ad_shipping_key', '{{%ads}}', 'shipping_id', '{{%shipping}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipping}}');
    }
}
