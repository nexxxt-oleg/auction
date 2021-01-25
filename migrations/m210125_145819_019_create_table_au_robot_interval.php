<?php

use yii\db\Migration;

class m210125_145819_019_create_table_au_robot_interval extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%robot_interval}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'name' => $this->string()->notNull(),
                    'value' => $this->integer()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%robot_interval}}');
    }
}
