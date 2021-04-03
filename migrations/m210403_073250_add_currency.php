<?php

use yii\db\Migration;

/**
 * Class m210403_073250_add_currency
 */
class m210403_073250_add_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('au_auction');
        if(!isset($table->columns['currency'])) {
            $this->addColumn('au_auction', 'currency', $this->string());
        }
        $this->alterColumn('au_auction', 'currency', $this->string());
        $this->update('au_auction', ['currency' => '$']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('au_auction', 'currency');
    }
}
