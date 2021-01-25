<?php

use yii\db\Migration;

class m210125_145819_016_create_table_au_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%log}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'msg' => $this->string()->notNull(),
                    'auction_id' => $this->integer()->notNull(),
                    'user_id' => $this->integer()->notNull(),
                    'good_id' => $this->integer()->notNull(),
                    'type' => $this->integer()->notNull(),
                    'date' => $this->date()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%log}}');
    }
}
