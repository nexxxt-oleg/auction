<?php

use yii\db\Migration;

class m210125_145819_008_create_table_au_good extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%good}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'name' => $this->string()->notNull(),
                    'description' => $this->text()->notNull(),
                    'auction_id' => $this->integer()->notNull(),
                    'category_id' => $this->integer()->notNull(),
                    'start_price' => $this->integer()->notNull(),
                    'accept_price' => $this->integer()->notNull(),
                    'end_price' => $this->integer(),
                    'mpc_price' => $this->integer(),
                    'blitz_price' => $this->integer(),
                    'curr_bid_id' => $this->integer(),
                    'win_bid_id' => $this->integer(),
                    'status' => $this->integer()->notNull(),
                    'type' => $this->integer(3)->notNull(),
                    'sell_rule' => $this->integer(3)->notNull(),
                    'add_time' => $this->dateTime(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%good}}');
    }
}
