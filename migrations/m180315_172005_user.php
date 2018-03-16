<?php

use yii\db\Migration;

/**
 * Class m180315_172005_user
 */
class m180315_172005_user extends Migration
{

    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->unique()->notNull(),
            'password' => $this->string(),
            'token' => $this->string(),
        ]);

        $this->insert('user', [
            'id' => 1,
            'username' => 'Ivan',
            'password' => '111111',
            'token' => 'jddu38dj38dolguasoi38',
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }

}
