<?php

use yii\db\Migration;

class m210125_145819_003_create_table_au_bid extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%bid}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'value' => $this->decimal(10, 2)->notNull(),
                    'user_id' => $this->integer()->notNull(),
                    'good_id' => $this->integer()->notNull(),
                    'date' => $this->dateTime()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%bid}}');
    }
}
