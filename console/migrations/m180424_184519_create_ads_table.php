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
            'price' => $this->string()->notNull(),
            'description' => $this->text(),
            'phone' => $this->string()->notNull(),
            'image' => $this->string(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'count_view' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->createTable('ads_images', [
            'id' => $this->primaryKey(),
            'ad_id' => $this->integer()->notNull(),
            'image' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_ad_image_key', '{{%ads_images}}', 'ad_id', '{{%ads}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_ad_user_key', '{{%ads}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%statuses}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text(),
        ]);

        $this->insert('{{%statuses}}', ['name' => 'Активный', 'description' => 'Объявление, видимое для остальных пользователей']);
        $this->insert('{{%statuses}}', ['name' => 'Проверяется', 'description' => 'Объявление, в очереди на модерацию']);
        $this->insert('{{%statuses}}', ['name' => 'Выключенный', 'description' => 'Объявление, откюченное пользователем или не прошедшое модерацию']);
        $this->addForeignKey('fk_statuses_key', '{{%ads}}', 'status', '{{%statuses}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_statuses_key', '{{%ads}}');
        $this->dropTable('{{%statuses}}');

        $this->dropForeignKey('fk_ad_user_key', '{{%ads}}');
        $this->dropForeignKey('fk_ad_image_key','{{%ads}}');
        $this->dropTable('{{%ads_images}}');
        $this->dropTable('{{%ads}}');
    }
}
