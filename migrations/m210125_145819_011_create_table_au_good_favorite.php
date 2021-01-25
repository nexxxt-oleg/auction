<?php

use yii\db\Migration;

class m210125_145819_011_create_table_au_good_favorite extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%good_favorite}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'good_id' => $this->integer()->notNull(),
                    'user_id' => $this->integer()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%good_favorite}}');
    }
}
