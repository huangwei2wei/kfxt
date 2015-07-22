<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 多元化的服务团队</title>
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
						<li class="d"><a href="/index.php?s=/Notice/">服务指引</a></li>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>服务指引</div>
				<div class="serviceyintit">
                <a href="/index.php?s=/Notice/">全面升级的服务体系 </a>
                <a href="/index.php?s=/Notice/strategy">服务使用攻略</a>
                <a href="/index.php?s=/Notice/channel" class="d">多元化的服务团队</a>
                </div>
				<div class="servicewen">
					<p style="text-indent:2em">优玩客服长期以来秉承着客户至上的服务理念，致力于为用户提供高效、便捷的精品化服务，我们的目标是专业、细心、真诚！</p>
				<p>&nbsp;</p>
<p><b>在线游戏服务</b></p>

<p>　　精彩的游戏会使人兴致勃发、引人入胜，但当在游戏过程中碰到这样或那样的问题时，将会使您浓浓兴趣受到干扰。这时候您应该尝试使用最方便快捷的服务方式“在线游戏服务”。当您有问题需要提问时，遇到不懂玩的地方时，快试一下点击游戏中的“客服”功能，并填写相关描述，那么一会时间您将收到我们最专业的回答。无论何时何地，只要您在玩游戏中需要提问，我们都会热忱为您提供7x24小时服务。<br />
方便星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>所有服务最方便快捷的服务方式，适合在线玩游戏的用户。</b></p>
<p>&nbsp;</p>
<p><b>在线充值QQ服务</b><br />
您是否在充值时候遇到阻碍很焦急呢？<br />
您是否在急于等待论坛充值区的回复呢？<br />
不用急，我们7x24小时全天候在线充值QQ服务能及时为您解决问题，给您提供快速方便的途径。<br />
7x24小时在线充值QQ：<br />
692942790<br />
方便星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>快速处理游戏充值问题，专业。</b></p>
<p>&nbsp;</p>
<p><b>论坛服务</b></p>
<p>　　优玩论坛给广大玩家提供一个交流互动平台，当您在玩游戏的过程中发现到惊喜有趣的事情，可以马上到论坛发表，让千万玩家共同分享您的喜悦。当您遇到游戏上的难题，你也可以在论坛发贴，热心的玩家会很快发表自己的见解，为您解答疑难。难得清闲时，别忘了狂论坛哦！同时，在论坛公告板块，你还能了解我们最新的公告和活动。<br />
方便星级：<img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>一起玩游戏，快乐齐分享。</b></p>
<p>&nbsp;</p>
<p><b>自助服务</b></p>
<p>　　精彩的游戏会使人兴致勃发、引人入胜，但当在游戏过程中碰到这样或那样的问题时，将会使您浓浓兴趣受到干扰。<br />
所谓“求人不如求己”，有些问题如果可以通过自己的双手来解决，您是否也将会得到不小的满足感呢？<br />
当您需要咨询如何游戏时；找游戏攻略时；或需要找回密码，自助服务都将成为您的首选。<br />
当您遇到以上种种难题时，您只需要先进入客服的相关网站，查看相关信息，随后选择您想要解决的问题，填写必要的资料，轻轻的点击一下鼠标，您就能很快的到达您的目的地。<br />
使用自助服务时，简单方便，何乐不为呢。</p>
方便星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>适合咨询游戏资料，一般问题的用户使用，成功率高，快速实用。</b>
<p>&nbsp;</p>
<p><b>传真服务</b></p>
<p>　　在科技发达的今天，传真已经融入到我们日常生活中，作为新时代的一员，您是否已经享受过传真给您带来的方便呢？</p>
<p>如您想找回账号密码，可是大部分注册资料已经印象模糊了，怎么办？<br />
如果您的账号被盗了，很焦急但需要提供身份证件，怎么办？<br />
如果您想找回丢失的物品，又该如何操作？<br />
在诸如此类的情况下，我们向您推荐使用传真服务。只有它才能在这样的情况下，对您起到最大的帮助。
7x24小时传真热线：<br />
020-38741065<br />
方便星级：<img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>在所需提供资料缺失的情况下，建议您尝试使用此项服务，说不定会为您带来意外的惊喜哦。</b></p>
<p>&nbsp;</p>
<p><b>电话服务</b></p>
<p>　　无论您身在何处，当您遇到问题时，身边只要有一部电话，即可与我们客服取得联系，提出您现在面临的问题。<br />
尤其是在游戏时出现的突发状况，例如：反复掉线、丢失物品、充值没到账等问题，您都可以通过电话服务来进行反馈。<br />
我们会认真对待每一次服务，对于您所反映的问题类别，我们会有针对性的问您几个小问题。帮助您有效地叙述事件内容。从而节约您的时间。了解您的问题后，我们将会根据具体的情况，为您做出相应的解答。有些比较特殊的情况，我们也会为您记录反映，直到您满意为止。
7x24小时客服热线：</p>
<p>400-888-4818<br />
方便星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
效率星级：<img src="/Public/front/default/images/xing.jpg" /><img src="/Public/front/default/images/xing.jpg" /><br />
服务特色：<b>在您叙述问题时，能给予引导。灵活性强，适合受理突发事件。</b></p>
<p>&nbsp;</p>
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