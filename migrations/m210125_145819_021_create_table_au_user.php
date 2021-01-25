<?php

use yii\db\Migration;

class m210125_145819_021_create_table_au_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%user}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'login' => $this->string()->notNull(),
                    'password' => $this->string()->notNull(),
                    'auction_name' => $this->string(),
                    'email' => $this->string()->notNull(),
                    'name' => $this->string(),
                    'phone' => $this->string()->notNull(),
                    'info' => $this->string(),
                    'active' => $this->string(1)->notNull(),
                    'add_time' => $this->date()->notNull(),
                    'password_reset_token' => $this->string(32),
                    'auth_key' => $this->string(32),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
