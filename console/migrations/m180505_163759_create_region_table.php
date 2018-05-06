<?php

use yii\db\Migration;

/**
 * Handles the creation of table `region`.
 */
class m180505_163759_create_region_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%region}}', [
            'id' => $this->primaryKey(),
            'name_ru' => $this->string(100)->notNull(),
            'name_uk' => $this->string(100)->notNull(),
            'url' => $this->string(50),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        $this->createIndex('idx_region_url', '{{%region}}', 'url', true);
        $this->createIndex('idx_region_status', '{{%region}}', 'status');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%region}}');
    }
}
