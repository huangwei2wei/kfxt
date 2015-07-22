<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 全面升级的服务体系</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script>
  $(function(){ 
		$('#menu_notice').addClass('d');
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
		<div class="conner">
			<div class="Personaleft">
				<div class="servicepic">
					<h3><img src="/Public/front/default/images/pic_02.jpg" /></h3>
					<p><b>客服中心</b></p><p>欢迎您来到优玩客服！</p>
				</div>
				<div style="padding-bottom:15px;"><img src="/Public/front/default/images/button_11.gif" /></div>
				<div class="peesonanav">
					<ul>
						<li><a href="/index.php?s=/Question/index">我要提问</a></li>
						<li><a href="/index.php?s=/Faq/Index">常见问题</a></li>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>网络骗术识别</div>
				<div class="serviceyintit"><a href="/index.php?s=/Notice/trick">网络骗术识别</a><a href="/index.php?s=/Notice/guide" class="d">防骗指南</a></div>
				<div class="servicewen">
					<p><b>1、拒绝访问陌生链接</b><br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  拒绝进入不良网站，拒绝访问陌生链接，莫要因为一时好奇而进入木马站点。不要轻易点击聊天工具上传递的陌生链接，即便真的需要，也可以在百度和gooogle对陌生链接进行查询，以估算其是否安全。</p><br />
			  <p><b>2、拒绝索要密码、金钱或物品</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  在论坛、游戏内，您也许会在询问游戏问题后，遇到一些自称游戏客服或者GM的人。冒充“系统公告”，如果您在游戏中见到某条颜色与众不同的“系统公告”、 “游戏管理者”、“游戏客服”，告诉您“游戏官方”正在举办某个活动，并引导您登录某个网站。当您登录该网站后，您的帐号安全就会受到威胁了。骗您的帐号  密码，他们会强调自己的官方身份，并向您索要游戏帐号、密码。骗您的钱财，直接向您索要钱财。<br />
<b>　　注：官方客服工作人员是不会向玩家索要游戏账号、密码、安全码、银行卡等重要信息的，如玩家碰到问题，只需提交服务器和人物的名称给客服人员就可以得到解决。</b></p><br />
			  <p><b>3、拒绝使用外挂、拒绝接收他人传送的文件</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  大部分外挂采用程序自带的登陆系统，使用外挂时需输入个人游戏帐号、密码，这等于是将个人游戏帐号和密码置于外挂制作分子的掌控之下，尤其不少破解软件更  是带有窃取账号密码的功能。而盗号者也常假借给你发照片、发小游戏、发外挂、发网站地址等等各种手段，给被害用户发送一些带有木马病毒的文件。 <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  注：珍爱自己的游戏成果，远离外挂！ 抵制外挂：  为了您自己的帐号安全，请不要尝试任何第三方提供的程序。包括所有挂机、脱机外挂和功能性程序。 坚决举报： 对其他使用外挂的玩家坚决报告官方。</p> <br />
			  <p><b>4、尽量不去陌生的网吧和黑网吧、使用网吧电脑之前先重启</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  在网吧上网的玩家，应尽量选择比较大型的管理严格的网吧，减少被陌生网吧或者黑网吧的不良分子种植木马的机会。并且在网吧使用电脑之最好前重新启动。并且警惕在网吧输入游戏账号和密码时，被他人窥视。 </p><br />
            <p><b>5、巧妙设置输入游戏账号密码</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  采取巧妙的密码输入方法达到在木马面前“隐身”的作用。进入游戏，登陆账号时，你可采取错位输入密码的方法（即故意先输错几次，再以正确的账号密码登陆游  戏）使木马难以差别真假，从而降低被盗的可能。也可以采用先于记事本中输入密码，并以Ctrl+C复制，然后再粘贴入登陆界面的方法，来降低被盗可能。还  可结合传世登陆界面中的软键盘输入账号密码。 <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  请不要设置过于简单的账号和密码，过于简单的账号密码容易被某些软件暴力破解，请尽量使用复合密码，最好设置以英文、数字、符号相结合的密码；经常更换密码，使用不同的密码组合以降低被盗号的风险。</p> <br />
              <p><b>6、拒绝线下交易</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  进行线下交易是最危险的行为之一。任何游戏外的物品交易都是不安全的，当您使用RMB与别的玩家购买虚拟物品时，您可能正在一步步进入一个圈套。骗您购买 虚拟物品：在游戏中商量的好好的，只要您寄钱给他，他就给你某件道具，或者是账号密码给您（卖号），可您的钱刚一寄出手，他就再也找不到了。还有一种是把  账号密码都给您了（卖号），您可以正常登陆游戏，但过了两天，登陆不了，可能他已经使用取回密码功能取回他的账号密码了。<br />
<b>　　注：当您注册优玩平台的时候，需要填写账号安全码、注册邮箱、身份证号码等重要信息。这些信息可作为您日后丢失密码后，找回密码的重要依据。</b></p><br />
              <p><b>7、定期杀查木马、病毒</b> <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  因为木马还有很多的方式可以钻入玩家的电脑，窃取玩家的游戏帐号，所以及时更新您的杀毒软件显得犹为重要。您也可安装各类防火墙，木马专杀工具，以便进一步确保您账号的安全。 </p>
									</div>							
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