<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%good_user_price}}`.
 */
class m210125_151121_create_good_user_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%good_user_price}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'good_id' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull()->comment('Цена за которою пользователь готов выкупить лот'),
        ]);

//        $this->addForeignKey('fk_gup_user', '{{%good_user_price}}', 'user_id', 'au_user', 'id', 'cascade');
//        $this->addForeignKey('fk_gup_good', '{{%good_user_price}}', 'good_id', 'au_good', 'id', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%good_user_price}}');
    }
}
