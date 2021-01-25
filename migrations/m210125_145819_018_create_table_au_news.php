<?php

use yii\db\Migration;

class m210125_145819_018_create_table_au_news extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%news}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'ID' => $this->primaryKey(),
                    'TYPE' => $this->smallInteger()->notNull(),
                    'STATUS' => $this->tinyInteger(4)->notNull(),
                    'CHILD_ID' => $this->integer()->notNull(),
                    'ADD_TIME' => $this->date()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%news}}');
    }
}
