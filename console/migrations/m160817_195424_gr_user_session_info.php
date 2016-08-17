<?php

use yii\db\Migration;

class m160817_195424_gr_user_session_info extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%gr_user_session_info}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(10)->notNull(),
            'time' => $this->integer(16),
            'ip_address' => $this->string(24),
            'soft' => $this->string(392),
            'language' => $this->string(64),
            'query' => $this->string(512),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%gr_user_session_info}}');
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
