<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180317_091538_create_article_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'title'=>$this->string()->notNull()->comment("标题"),
            'intro'=>$this->text()->comment('简介'),
            'sort'=>$this->smallInteger()->notNull()->defaultValue(100)->comment('排序'),
            'status'=>$this->smallInteger()->notNull()->defaultValue(1)->comment('状态'),
            'category_id'=>$this->integer()->comment('分类id'),
            'create_time'=>$this->integer()->comment('创建时间'),
            'update_time'=>$this->integer()->comment('修改时间')
        ]);
        $this->createTable('article_content', [
            'id' => $this->primaryKey(),
            'content'=>$this->string()->notNull()->comment("内容"),
            'article_id'=>$this->integer()->comment('文章id'),

        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('article');
        $this->dropTable('article_content');
    }
}
