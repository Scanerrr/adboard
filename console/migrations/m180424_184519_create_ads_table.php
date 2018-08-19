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
            'user_id' => $this->integer()->notNull(),
            'price' => $this->decimal(10, 2)->notNull(),
            'price_type' => $this->tinyInteger(1)->null(),
            'currency_id' => $this->integer()->notNull(),
            'state' => $this->tinyInteger(1)->notNull(),
            'vendor' => $this->string(64)->notNull(),
            'model' => $this->string(70)->notNull(),
            'shipping_id' => $this->integer()->notNull(),
            'payment_id' => $this->integer()->notNull(),
            'description' => $this->text(),
            'image' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'count_view' => $this->integer()->notNull()->defaultValue(0),
            'contact_name' => $this->string(128)->notNull(),
            'contact_email' => $this->string(128)->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('{{%ads_images}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_ad_image_key', '{{%ads_images}}', 'ad_id', '{{%ads}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_ad_user_key', '{{%ads}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%ads_phones}}', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'phone' => $this->string()->notNull(),
            'type' => $this->tinyInteger(1)->notNull(),
        ]);
        $this->addForeignKey('fk_ad_phone_key', '{{%ads_phones}}', 'ad_id', '{{%ads}}', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%ads_phones}}');
        $this->dropTable('{{%ads_images}}');
        $this->dropTable('{{%ads}}');
    }
}
