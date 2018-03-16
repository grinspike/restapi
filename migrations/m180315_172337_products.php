<?php

use yii\db\Migration;

/**
 * Class m180315_172337_products
 */
class m180315_172337_products extends Migration
{

    public function up()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'price' => $this->double(),
            'amount' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }

}
