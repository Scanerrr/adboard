<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m180818_080826_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'method' => $this->string(24)->notNull()->unique(),
            'description' => $this->string(),
        ]);
        $this->insert('{{%payment}}', ['method' => 'privat24', 'description' => 'Приват 24']);
        $this->insert('{{%payment}}', ['method' => 'cash', 'description' => 'Наличными']);
        $this->insert('{{%payment}}', ['method' => 'cashless', 'description' => 'Безналичныq расчет']);
        $this->addForeignKey('fk_ad_payment_key', '{{%ads}}', 'payment_id', '{{%payment}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment}}');
    }
}
