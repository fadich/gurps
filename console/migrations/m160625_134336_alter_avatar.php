<?php

use yii\db\Migration;

class m160625_134336_alter_avatar extends Migration
{
    public function up()
    {
        $this->alterColumn('profile', 'avatar', $this->integer());
    }

    public function down()
    {
        $this->alterColumn('profile', 'avatar', $this->string(128));
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
