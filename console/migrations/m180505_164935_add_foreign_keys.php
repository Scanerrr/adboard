<?php

use yii\db\Migration;

/**
 * Class m180505_164935_add_foreign_keys
 */
class m180505_164935_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addForeignKey('fk_city_user', '{{%user}}', 'city_id', '{{%city}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_category_ads', '{{%ads}}', 'category_id', '{{%categories}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_city_ads', '{{%ads}}', 'city_id', '{{%city}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_city_ads', '{{%ads}}');
        $this->dropForeignKey('fk_category_ads', '{{%ads}}');
        $this->dropForeignKey('fk_city_user', '{{%user}}');
    }
}
