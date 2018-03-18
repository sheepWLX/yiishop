<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m180318_030534_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull()->comment('深度'),
            'name' => $this->string()->notNull()->comment('商品名称'),
            'intro' => $this->string()->comment('简介'),
            'prent_id' => $this->integer()->notNull()->comment('父级编号')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
