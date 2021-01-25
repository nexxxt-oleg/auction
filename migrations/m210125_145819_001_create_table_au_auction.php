<?php

use yii\db\Migration;

class m210125_145819_001_create_table_au_auction extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%auction}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                '{{%auction}}',
                [
                    'id' => $this->primaryKey(),
                    'name' => $this->string()->notNull(),
                    'description' => $this->string()->notNull(),
                    'url' => $this->string(),
                    'active_date' => $this->dateTime(),
                    'start_date' => $this->dateTime()->notNull(),
                    'end_date' => $this->dateTime()->notNull(),
                    'active' => $this->string(1)->notNull(),
                    'currency' => $this->integer(3)->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%auction}}');
    }
}
