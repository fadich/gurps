<?php

use yii\db\Migration;

class m160621_194610_profile_info extends Migration
{
    public function up()
    {
        $this->addColumn('profile', 'info', $this->string(1024));
    }

    public function down()
    {
        $this->dropColumn('profile', 'info');
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
