<?php

use yii\db\Migration;

class m210125_145819_006_create_table_au_configurations extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%configurations}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'ID' => $this->primaryKey(),
                    'NAME' => $this->string()->notNull(),
                    'VALUE' => $this->string()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%configurations}}');
    }
}
