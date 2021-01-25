<?php

use yii\db\Migration;

class m210125_145819_020_create_table_au_subscribe extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%subscribe}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'email' => $this->string()->notNull()->comment('Email:'),
                    'status' => $this->integer(2)->notNull()->defaultValue('1')->comment('Статус:'),
                ],
                $tableOptions
            );

            $this->createIndex('subscribe_email', '{{%subscribe}}', ['email'], true);
        }
    }

    public function down()
    {
        $this->dropTable('{{%subscribe}}');
    }
}
