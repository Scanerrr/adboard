<?php

use yii\db\Migration;

/**
 * Handles the creation of table `categories`.
 */
class m180424_184300_create_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%categories}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull()->unique(),
            'slug' => $this->string(128)->notNull()->unique(),
            'parent_id' => $this->integer()->null(),
            'image' => $this->string()->null()
        ]);

        $this->createIndex('idx-category-parent_id', '{{%categories}}', 'parent_id');
        $this->addForeignKey('fk-category-parent', '{{%categories}}', 'parent_id', '{{%categories}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%categories}}');
    }
}
