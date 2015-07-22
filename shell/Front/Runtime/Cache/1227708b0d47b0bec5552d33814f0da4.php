<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 常见问题</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script>
$(function(){
	$('#menu_faq').addClass('d');
});
</script>
</head>

<body>
<div id="bgtainer">
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
		<div class="conner">
			<div class="Personaleft">
				
				<div class="peesonanav">
					<ul>
						<li><a href="/index.php?s=/Question/index">我要提问</a></li>
						<li class="d"><a href="/index.php?s=/Faq/Index">常见问题</a></li>
						<li><a href="/index.php?s=/Notice/">服务指引</a></li>
						<li><a href="/index.php?s=/Notice/pay">充值指南</a></li>
                        <li><a href="/index.php?s=/Notice/addicted">防沉迷系统</a></li>
					</ul>
				</div>
				<div style="padding:15px 0;"><img src="/Public/front/default/images/button_11.gif" /></div>
				<div class="peesonke"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/ke.jpg" /><br />电话：400-888-4818<br />

传真：020-38741065<br />

投诉通道：<a href="/index.php?s=/Question/index">点击进入</a><br />

<a title="投诉邮箱" href="mailto:BTOcustomerservice@cndw.com">BTOcustomerservice@cndw.com</a><br />

欢迎对我们的客服质量进行监督
</div>
<!--<div style="padding-bottom:15px;"><img src="/Public/front/default/images/button_11.gif" /></div>
<div style="margin-top:10px;padding-left:3px;">
	<img src="/Public/front/default/images/1.jpg" />
</div>-->
			</div>
			<div class="personaquan">
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>常见问题</div>
					<h3>请选择游戏</h3>
					<div class="personaquangame"><a href="/index.php?s=/Faq/ls/game_type_id/1"><img src="/Public/front/default/images/button_79.gif" /></a><a href="/index.php?s=/Faq/ls/game_type_id/1">常见问题</a> | <a href="http://bto.uwan.com" target="_blank">官方网站</a></div>
					<div class="personaquangame"><a href="/index.php?s=/Faq/ls/game_type_id/2"><img src="/Public/front/default/images/button_80.gif" /></a><a href="/index.php?s=/Faq/ls/game_type_id/2">常见问题</a> | <a href="http://r.uwan.com" target="_blank">官方网站</a></div>
                    <div class="personaquangame"><a href="/index.php?s=/Faq/ls/game_type_id/5"><img src="/Public/front/default/images/button_81.gif" /></a><a href="/index.php?s=/Faq/ls/game_type_id/5">常见问题</a> | <a href="http://xx.uwan.com/index.html" target="_blank">官方网站</a></div>
					<!--<div class="personaquangame"><a href="/index.php?s=/Faq/ls/game_type_id/3"><img src="/Public/front/default/images/button_81.gif" /></a><a href="/index.php?s=/Faq/ls/game_type_id/3">常见问题</a> | <a href="http://x.uwan.com/" target="_blank">官方网站</a></div>-->
							</div>
								</div>
		<div class="connerbo"></div>
						
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