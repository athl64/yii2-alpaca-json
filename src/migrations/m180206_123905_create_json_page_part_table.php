<?php

use console\components\Migration;

/**
 * Class m180206_123905_json_page_part_page migration
 */
class m180206_123905_create_json_page_part_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%json_page_part}}';
    public $tableNameRelated = "{{%json_page}}";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),

                'json_page_id' => $this->integer()->notNull()->comment('Page'),
                'lang' => $this->string()->notNull()->comment('Locale'),
                'attribute' => $this->string()->notNull()->comment('Page attribute'),
                'json' => 'JSON null',

                'created_at' => $this->integer()->notNull()->comment('Created At'),
                'updated_at' => $this->integer()->notNull()->comment('Updated At'),
            ],
            $this->tableOptions
        );

        $this->addForeignKey(
            'fk-json_page_part-json_page_id_json_page-id',
            $this->tableName,
            'json_page_id',
            $this->tableNameRelated,
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->createIndex('json_page_part-lang-index', $this->tableName, 'lang');
        $this->createIndex('json_page_part-attribute-index', $this->tableName, 'attribute');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-json_page_part-json_page_id_json_page-id', $this->tableName);
        $this->dropIndex('json_page_part-attribute-index', $this->tableName);
        $this->dropIndex('json_page_part-lang-index', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
