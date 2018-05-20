<?php

use yii\db\Migration;

/**
 * Handles the creation of table `settings`.
 */
class m180424_185351_create_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'key' => $this->string()->notNull()->unique(),
            'value' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%settings}}');
    }
}
