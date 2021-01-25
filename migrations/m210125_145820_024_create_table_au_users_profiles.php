<?php

use yii\db\Migration;

class m210125_145820_024_create_table_au_users_profiles extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%users_profiles}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'user_id' => $this->integer(5)->notNull(),
                    'last_name' => $this->string(),
                    'first_name' => $this->string(),
                    'otchestvo' => $this->string(50),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%users_profiles}}');
    }
}
