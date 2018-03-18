<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m180317_073644_create_article_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('名称'),
            'sort' => $this->integer()->notNull()->defaultValue(100)->comment('排序'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'intro' => $this->text()->comment('简介'),
            'is_help'=>$this->smallInteger()->notNull()->defaultValue(0)->comment("是否是帮助类")
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article_category');
    }
}
