<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_ad_watches`.
 */
class m180424_185121_create_user_ad_watches_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%user_ad_watches}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'ad_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk_user_id_watches', '{{%user_ad_watches}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_ad_id_watches', '{{%user_ad_watches}}', 'ad_id', '{{%ads}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_ad_id_watches', '{{%user_ad_watches}}');
        $this->dropForeignKey('fk_user_id_watches', '{{%user_ad_watches}}');
        $this->dropTable('{{%user_ad_watches}}');
    }
}
