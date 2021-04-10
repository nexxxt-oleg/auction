<?php

use yii\db\Migration;

/**
 * Class m210410_133358_add_au_auction__is_test_column
 */
class m210410_133358_add_auction__is_test_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('au_auction', 'is_test', $this->boolean());
        $this->update('au_auction', ['is_test' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('au_auction', 'is_test');
    }
}
