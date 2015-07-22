<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 自助服务</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script>
$(function(){
	$('#menu_selfservice').addClass('d');
});
</script>
</head>

<body>
<div id="bg"><div id="bgtainer">
	<div id="bgtai">
		<div class="topnei">
			<a href="http://www.uwan.com/" style="width:321px;height:88px;float:left"></a>
			<div class="hader"> 
             <ul id="links">
             <li class="nav_t"><a style="cursor:pointer;width:129px;height:28px;display:block;background:url('__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/button_03.gif')"></a><div class="cybox"  style="margin-left:-344px; ">
                <div class="cyboxtop">
                <div class="cyboycon">
                <a href=" http://r.uwan.com" target="_blank"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_frg.gif" /></span>富人国 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/hot.gif" /></a>
                <a href=" http://bto.uwan.com" target="_blank"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_bto.gif" /></span>商业大亨</a>
                <a href=" http://x.uwan.com" target="_blank"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_xh.gif" /></span>仙魂 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif" /></a>
                <a href=" http://g.uwan.com" target="_blank"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_gf.gif" /></span>功夫 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif" /></a>
                <a href=" http://xx.uwan.com" target="_blank"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_xx.gif" /></span>寻侠 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif" /></a>
                </div>
                </div>
            </div></li>
             </ul>
            <script type="text/javascript">
            var links = document.getElementById("links");
            var int = links.getElementsByTagName("LI");
            for(var i=0;i<int.length;i++){
                mouseEvent(int[i])
            }
            function mouseEvent(obj){
                obj.onmouseover = function(){
                    if(obj.childNodes[1])obj.childNodes[1].style.display = "block";
                    else return;
                }
                obj.onmouseout = function(){
                    if(obj.childNodes[1])obj.childNodes[1].style.display = "none";
                    else return;
                }
            }
            </script>
            <div class="long">
                    <?php if($username): ?><a href="https://www.uwan.com/UserCenter/EditAccount.php"><?php echo ($username); ?></a><a href="https://www.uwan.com/UserCenter/Logout.php" style="border-left:1px #647c8f solid; padding-left:8px;margin-left:8px;">退出</a>
                    <?php else: ?>
                    <a href="https://www.uwan.com/UserCenter/Login.php?back=http://service.uwan.com/"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/button_01.gif" /></a> <a href="http://www.uwan.com/UserCenter/GetAccount.php"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/button_02.gif" /></a><?php endif; ?>
                </div>
            </div>
			<div class="navnei">
				<a href="http://www.uwan.com/">首页</a><a href="http://www.uwan.com/UserCenter/">个人中心</a><a href="https://www.uwan.com/InpourCenter/">充值中心</a><a href="/" class="d">客服中心</a><a href="http://www.uwan.com/bbs.php">游戏论坛</a>
			</div>
</div>
        
		<div class="conde">
        			<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_01.jpg" />
					<h2>充值自助</h2>
					<p>优玩平台目前支持有12种充值方式，包括网银与卡类充值，玩家如有问题可以联系客服进行咨询。</p>
					<p><a href="https://www.uwan.com/InpourCenter/index.php">点击进入</a></p></div>
					<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_002.jpg" />
					<h2>家长监护体系</h2>
					<p>旨在加强家长对未成年人参与网络游戏的监护，引导未成年人健康、绿色参与网络游戏...</p>
					<p><a href="http://bto.uwan.com/jianhu/index.htm">点击进入</a></p></div>
					<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_03.jpg" />
					<h2>找回密码</h2>
					<p>为您提供取回优玩账号密码、密码保护、号码申诉和设置安全等服务。</p>
					<p><a href="http://www.uwan.com/UserCenter/default.php">点击进入</a></p></div>
					<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_04.jpg" />
					<h2>密码保护</h2>
					<p>设置密码保护功能，将会大大提升您的账号安全，同时还可以用于找回密码。</p>
					<p><a href="http://www.uwan.com/UserCenter/AdvancedPs1.php">点击进入</a></p></div>
					<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_05.jpg" />
					<h2>游戏自助</h2>
					<p>游戏内相关问题、账号问题、等可以通过游戏自助FAQ系统查询到相关解决方法。</p>
					<p><a href="<?php echo U('Faq/index');?>">点击进入</a></p></div>
					<div class="service"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/pic_06.jpg" />
					<h2>防沉迷申请</h2>
					<p>未满18周岁的玩家将受到防沉迷的限制，游戏沉迷时间超过3小时收益减50%，超过5小时收益为0。 </p>
					<p><a href="http://www.uwan.com/UserCenter/Indulged.php">点击进入</a></p></div>
		</div>
		
	<div style="clear:left;"></div>
			
       </div>
     </div>
	<div id="bgbottom"> 
<div class="bottom">
		<div class="bott">
			<p style="padding-top:70px;">健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活 </p>
			<p style="padding-top:20px;"><a href="http://www.cndw.com/about/the/" target="_blank">关于我们</a> | <a href="http://www.cndw.com/about/contact/" target="_blank">联系我们</a> | <a href="http://www.cndw.com/about/hr/">人才招聘</a> | <a href="http://www.uwan.com/duty.php" target="_blank">免责声明</a>&nbsp;&nbsp;优玩平台已启用<a href="http://service.uwan.com/index.php?s=/Notice/realname" target="_blank"><b style="color:red">实名注册</b></a>和<a href="http://www.uwan.com/jianhu/" target="_blank"><b style="color:red">家长监护</b></a><br />
版权所有 <a href="#">动网先锋网络科技有限公司</a><br /> 
网络文化经营许可证 编号：文网文[2009]105号 增值电信业务许可证：琼 B2-20090005 </p>
		</div>
	</div>
    
</div>
</div>

<div style="display:none;"><script src="http://s11.cnzz.com/stat.php?id=2558818&web_id=2558818" language="JavaScript"></script></div>		
</div>
</body>

</html>