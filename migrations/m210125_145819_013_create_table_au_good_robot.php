<?php

use yii\db\Migration;

class m210125_145819_013_create_table_au_good_robot extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%good_robot}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'good_id' => $this->integer()->notNull(),
                    'bid_time' => $this->dateTime()->notNull(),
                    'bid_interval' => $this->integer(3)->notNull(),
                    'status' => $this->integer(3)->notNull(),
                    'bid_id' => $this->integer()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%good_robot}}');
    }
}
