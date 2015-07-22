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
						<li class="d"><a href="/index.php?s=/Notice/addicted">防沉迷系统</a></li>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>防沉迷系统</div>
				<div class="serviceyintit">
					<a href="/index.php?s=/Notice/addicted" class="d">防沉迷系统介绍</a><a href="/index.php?s=/Notice/realname">实名注册与防沉迷</a><a href="/index.php?s=/Notice/addictedqs">防沉迷常见问题</a>
				</div>

				<div class="servicewen">
					<p><b>防沉迷系统简介</b><br />
1、防沉迷是针对未成年人过度沉迷于网络游戏，并对其身心健康造成不利影响的情况，国家文化部于2010年8月1日正式实施的《网络游戏管理暂行办法》，限制未成年人依赖网络游戏长时间在线来获得游戏内的收益增长，有效控制未成年人在线游戏的时间，使得未成年人有个健康的环境进行游戏。<br />
2、保护未成年人身心健康，未满18岁的用户将受到防沉迷系统的限制；<br />
在进行游戏的过程，系统会提示您的累计在线时间；<br />
累计游戏时间超过3小时，游戏收益（经验，金钱）减半；<br />
累计游戏时间超过5小时，游戏收益为0。<br /><br /></p>
					<p><b>防沉迷系统设计目的</b><br />
1、防止未成年人过度沉迷游戏，倡导健康游戏习惯，保护未成年人的合法权益； <br />
2、帮助法定监护人了解其监护对象参与的网络游戏、是否受到防沉迷系统的保护、是否适宜未成年人参与等情况；<br /> 
3、在实现上述目的的同时，也正确引导成年玩家自主支配其自身游戏时间及合法的权益。 <br /><br /></p>
					<p><b>防沉迷系统执行的对象？（我是否受到防沉迷系统的影响）</b><br />
1、未成年用户（未满18岁者）； <br />
2、身份验证信息不完整的用户； <br />
3、未经过身份验证的用户。<br /><br /> 
</p>
					<p><b>促进使用者养成健康的游戏习惯</b><br />
为保障使用者适度使用并有足够的休息时间，对游戏的间隔时间和收益进行限制和引导的处理办法： <br />
根据以上考虑，不同累计在线时间的游戏收益处理如下：累计在线时间在3小时以内，游戏收益为正常；3-5小时内，收益降为正常值的50%；5小时以上，收益降为0。 <br />
由于不同的游戏有不同范畴，因此对于当前角色扮演类的网络游戏，特别是目前将作为试点的游戏，建议定义为“游戏收益=游戏中获得的经验值＋获得的虚拟物品”。收益为50％，则指获得经验值减半，虚拟物品减半。收益为0,则指无法获得经验值，无法获得虚拟物品。 
<br /><br /></p>
					<p><b>初始化累计时间——由于使用者上下线的行为比较复杂，会出现以下多种情况，因此限时与提示的实现方法如下：</b><br />
使用者在线后，其持续在线时间将累计计算，称为“累计在线时间”。 <br />
使用者下线后，其不在线时间也将累计计算，称为“累计下线时间”。 <br />
使用者累计在线时间在3小时以内的，游戏收益正常。每累计在线时间满1小时，应提醒一次：“您累计在线时间已满1小时。”至累计在线时间满3小时时，应提醒：“您累计在线时间已满3小时，请您下线休息，适当的活动身体。” <br />
如果累计在线时间超过3小时进入第4－5小时，在开始进入时就应做出警示：“您已经进入疲劳游戏时间，您的游戏收益将降为正常值的50％，请您尽快下线休息，做适当身体活动。”此后，应每30分钟警示一次。 <br />
如果累计在线时间超过5小时进入第6小时，在开始进入时就应做出警示：“您已进入不健康游戏时间，请您立即下线休息。如不下线，您的身体健康将受到损害，您的游戏目前收益已降为零。”此后，应每15分钟警示一次。 <br />
受防沉迷系统限制的用户，当下线时间超过5小时时，累计游戏时间初始化为0。初始化后进入游戏就会开始重新计算累计游戏时间。 <br />
</p>
									
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