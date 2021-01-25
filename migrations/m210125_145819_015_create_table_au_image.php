<?php

use yii\db\Migration;

class m210125_145819_015_create_table_au_image extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%image}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'url' => $this->string()->notNull(),
                    'type' => $this->integer()->notNull(),
                    'parent_id' => $this->integer()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%image}}');
    }
}
