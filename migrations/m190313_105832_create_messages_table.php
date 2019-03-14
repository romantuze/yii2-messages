<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m190313_105832_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'msg'=>$this->text(),
            'user_id_1'=>$this->integer()->notNull(),
            'user_id_2'=>$this->integer()->notNull(),
            'user_hidden_1'=>$this->integer(1)->notNull()->defaultValue(0),
            'user_hidden_2'=>$this->integer(1)->notNull()->defaultValue(0),
            'created'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%messages}}');
    }
}
