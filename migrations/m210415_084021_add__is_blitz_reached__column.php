<?php

use yii\db\Migration;

/**
 * Class m210415_084021_add__is_blitz_reached__column
 */
class m210415_084021_add__is_blitz_reached__column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('au_good', 'is_blitz_reached', $this->boolean()->comment('Достигнута блитц-цена'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('au_good', 'is_blitz_reached');
    }
}
