<?php

use yii\db\Migration;

class m210125_145819_007_create_table_au_filter extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%filter}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'category_id' => $this->integer()->notNull(),
                    'name' => $this->string()->notNull(),
                    'value' => $this->string(),
                    'level' => $this->integer()->notNull(),
                    'parent' => $this->integer()->notNull(),
                    'active' => $this->string(1)->notNull(),
                    'column_view' => $this->integer(3)->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%filter}}');
    }
}
