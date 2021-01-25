<?php

use yii\db\Migration;

class m210125_145819_005_create_table_au_category extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%category}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'name' => $this->string()->notNull(),
                    'description' => $this->string(),
                    'url' => $this->string(),
                    'priority' => $this->integer()->notNull(),
                    'active' => $this->string(1)->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%category}}');
    }
}
