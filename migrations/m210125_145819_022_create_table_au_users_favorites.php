<?php

use yii\db\Migration;

class m210125_145819_022_create_table_au_users_favorites extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%users_favorites}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'event_id' => $this->decimal(),
                    'user_id' => $this->decimal(5, 0),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%users_favorites}}');
    }
}
