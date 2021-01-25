<?php

use yii\db\Migration;

class m210125_145819_017_create_table_au_mail extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%mail}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'user_id' => $this->integer()->notNull(),
                    'user_name' => $this->string()->notNull(),
                    'subject' => $this->string(1000)->notNull(),
                    'body' => $this->text()->notNull(),
                    'date' => $this->dateTime()->notNull(),
                    'type' => $this->integer(3)->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%mail}}');
    }
}
