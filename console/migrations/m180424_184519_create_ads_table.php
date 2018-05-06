<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ads`.
 */
class m180424_184519_create_ads_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%ads}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(70)->notNull(),
            'category_id' => $this->integer()->notNull(),
            'city_id' => $this->integer()->notNull(),
            'price' => $this->string()->notNull(),
            'description' => $this->text(),
            'telephone' => $this->string()->notNull(),
            'image' => $this->string(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('ads_images', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_ad_image_key', '{{%ads_images}}', 'ad_id', '{{%ads}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_ad_image_key');
        $this->dropTable('{{%ads_images}}');
        $this->dropTable('{{%ads}}');
    }
}
