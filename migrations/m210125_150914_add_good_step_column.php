<?php

use yii\db\Migration;

/**
 * Class m210125_150914_add_good_step_column
 */
class m210125_150914_add_good_step_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('au_good', 'step', $this->integer()->comment('Минимальный шаг ставки'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('au_good', 'step');
    }
}
