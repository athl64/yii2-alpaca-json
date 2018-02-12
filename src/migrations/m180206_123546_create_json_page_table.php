<?php

use console\components\Migration;

/**
 * Class m180206_123546_create_json_page_table migration
 */
class m180206_123546_create_json_page_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%json_page}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),

                'class_name' => $this->string()->notNull()->unique(),

                'created_at' => $this->integer()->notNull()->comment('Created At'),
                'updated_at' => $this->integer()->notNull()->comment('Updated At'),
            ],
            $this->tableOptions
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
