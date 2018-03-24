<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>欢迎<?=Yii::$app->user->identity->username?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?=Yii::$app->user->isGuest?'离线':'在线';?></a>
            </div>
        </div>

        <!-- search form -->

        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [

                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' =>
                \backend\models\Mulu::menu()]
//                'items' => [
//
//                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
//                    [
//                        'label' => '商品管理',
//                        'icon' => 'shopping-bag',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => '商品列表', 'icon' => 'file-code-o', 'url' => ['/goods/index'],],
//                            ['label' => '添加商品', 'icon' => 'dashboard', 'url' => ['/goods/add'],],
//                        ],
//                    ],
//                    [
//                        'label' => '分类管理',
//                        'icon' => 'certificate ',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => '分类列表', 'icon' => 'file-code-o', 'url' => ['/category/index'],],
//                            ['label' => '添加分类', 'icon' => 'dashboard', 'url' => ['/category/add'],],
//                        ],
//                    ],
//                    [
//                        'label' => '品牌管理',
//                        'icon' => 'angellist',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => '品牌列表', 'icon' => 'file-code-o', 'url' => ['/brand/index'],],
//                            ['label' => '添加品牌', 'icon' => 'dashboard', 'url' => ['/brand/add'],],
//                        ],
//                    ],
//                    [
//                        'label' => '文章管理',
//                        'icon' => 'book',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => '文章列表', 'icon' => 'file-code-o', 'url' => ['/article/index'],],
//                            ['label' => '添加文章', 'icon' => 'dashboard', 'url' => ['/article/add'],],
//                        ],
//                    ],
//                    [
//                        'label' => '文章分类管理',
//                        'icon' => 'map-o',
//                        'url' => '#',
//                        'items' => [
//                            ['label' => '文章分类列表', 'icon' => 'file-code-o', 'url' => ['/article-category/index'],],
//                            ['label' => '添加文章分类', 'icon' => 'dashboard', 'url' => ['/article-category/add'],],
//                        ],
//                    ],
//                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
//                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
//                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
//
//                ],
//            ]
        ) ?>

    </section>

</aside>
