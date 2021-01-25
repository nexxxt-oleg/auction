<?php

use yii\db\Migration;

class m210125_145820_023_create_table_au_users_groups extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%users_groups}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->integer()->notNull(),
                    'name' => $this->string(50)->notNull(),
                    'description' => $this->string(100)->notNull(),
                ],
                $tableOptions
            );

            $this->createIndex('id', '{{%users_groups}}', ['id']);
        }
    }

    public function down()
    {
        $this->dropTable('{{%users_groups}}');
    }
}
