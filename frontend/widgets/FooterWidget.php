<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/28
 * Time: 13:55
 */

namespace frontend\widgets;


use yii\base\Widget;

class FooterWidget extends Widget
{
    public $name="路客商城";
    public function run()
    {
        $html=<<<html
<div class="footer w1210 bc mt10">
		<p class="links">
			<a href="/">关于我们</a> |
			<a href="/">联系我们</a> |
			<a href="/">人才招聘</a> |
			<a href="/">商家入驻</a> |
			<a href="/">千寻网</a> |
			<a href="/">奢侈品网</a> |
			<a href="/">广告服务</a> |
			<a href="/">移动终端</a> |
			<a href="/">友情链接</a> |
			<a href="/">销售联盟</a> |
			<a href="/">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 {$this->name} 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href="/"><img src="/images/xin.png" alt="" /></a>
			<a href="/"><img src="/images/kexin.jpg" alt="" /></a>
			<a href="/"><img src="/images/police.jpg" alt="" /></a>
			<a href="/"><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
html;
return $html;
    }
}