<?php

use yii\db\Migration;

class m210125_145819_002_create_table_au_auction_categories extends Migration
{
    public function up()
    {
        $tableOptions = null;
        $tableName = '{{%auction_categories}}';
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        if (Yii::$app->db->getTableSchema($tableName, true) === null) {
            $this->createTable(
                $tableName,
                [
                    'id' => $this->primaryKey(),
                    'category_id' => $this->integer()->notNull(),
                    'auction_id' => $this->integer()->notNull(),
                ],
                $tableOptions
            );
        }
    }

    public function down()
    {
        $this->dropTable('{{%auction_categories}}');
    }
}
