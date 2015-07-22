<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 充值指南</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
</head>
<script language="javascript">
$(function(){
	view(<?php echo ($_GET['viewId']); ?>);
	$('#menu_pay').addClass('d');
})
function view(divId){
	if(!divId)divId=1;
	$(".list").hide();
	$(".left_con dd").removeClass("ttbg");
	$("#cont"+divId).show();
	$("#menu_"+divId).addClass("ttbg");
}
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
						<li class="d"><a href="/index.php?s=/Notice/pay">充值指南</a></li>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>充值指南</div>
					<div class="servicehaed">
						<ul>
							<li id="menu_1" onclick="javascript:view(1)"><a href="#">如何在优玩平台充值？</a></li>
                            <li id="menu_12" onclick="javascript:view(12)"><a href="#">什么是优玩币？</a></li>
                            <li id="menu_2" onclick="javascript:view(2)"><a href="#">充值常见问题FAQ</a></li>
                            <li id="menu_3" ><a href="/index.php?s=/Faq/bankList">网上银行充值指南</a></li>
                            <li id="menu_4" onclick="javascript:view(4)"><a href="#">短信充值帮助</a></li>
                            <li id="menu_5" onclick="javascript:view(5)"><a href="#">电信卡充值帮助</a></li>
                            <li id="menu_6" onclick="javascript:view(6)"><a href="#">联通卡充值帮助</a></li>
                            <li id="menu_7" onclick="javascript:view(7)"><a href="#">神州卡充值帮助</a></li>
                            <li id="menu_8" onclick="javascript:view(8)"><a href="#">固话（V币）充值帮助</a></li>
                            <li id="menu_9" onclick="javascript:view(9)"><a href="#">盛大卡充值帮助</a></li>
                            <li id="menu_10" onclick="javascript:view(10)"><a href="#">骏网卡充值帮助</a></li>
                            <li id="menu_11" onclick="javascript:view(11)"><a href="#">征途卡充值帮助</a></li>
						</ul>						
					</div>
					
					
                    <style>.list{line-height:1.7em;}.list div{line-height:normal;}</style>
                    
                    <div class="list" id="cont1" style="display:none">
                   		<div class="servicetit">如何在优玩平台充值？</div>
      					<Br/><Br/>
                        第一步：进入优玩平台页面，<a href="http://www.uwan.com/index.php" target="_blank">【点击进入】</a>
                        <p>&nbsp;</p>
                        第二步：请登录优玩平台账号（与您的游戏账号相一致），在下列位置输入您的优玩平台账号与密码：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0004.jpg" /></center>
                        <p>&nbsp;</p>
                        第三步：登录成功后，如图点击选项进入充值中心页面进行充值操作：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0005.jpg" /></center>
                        <p>&nbsp;</p>
                        第四步：进入充值中心页面以后，选择您所充值的游戏，然后点击“我要充值”按钮。<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0012.jpg" /></center>
                        <p>&nbsp;</p>
                        第五步：在左边的充值渠道列表，选择您充值的渠道。然后右边确定您的账号、服务器及充值金额。<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0006.jpg" /></center>
                        <p>&nbsp;</p>
                        第六步：最后在页面下方点击按钮“确认支付”即可进行第三方支付平台进行支付，支付完成后程序会在您的游戏中加上对应的金币。 <br/>
                        <p>&nbsp;</p>
                        <p style="color:#ff0000">注：不同的充值方式步骤有所不同，请您仔细按照我们的指示进行充值。</p>

      </div>

		<div class="list" id="cont12">            		
				<div class="servicetit">什么是优玩币？</div><Br/><br/>　　
                 用户在优玩平台进行充值（<a href="http://www.uwan.com">www.uwan.com</a>），通过多种便捷的充值渠道购买优玩币后在游戏中使用，系统会自动兑换成相应的游戏币。游戏虚拟货币统称为“优玩币”。<br/>
                        <p>&nbsp;</p>
　　             <b>虚拟货币单位购买价格:</b><br/>
　　             优玩网平台中的虚拟货币“优玩币”与人民币的兑换比例为（人民币：优玩币=1：1），即1元人民币可以兑换1个游戏虚拟优玩币。
                 <p>&nbsp;</p>
　　             <b>充值方式</b></br>
　　             我公司为游戏用户提供了多种充值方式，具体如下：<br/>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;银行卡充值</p>
　　             我公司与第三方支付公司合作，提供银行卡直充方式。支持大部分银行卡种，用户可以通过网上银行接口直接充值游戏内的“优玩币”。<br/>
　　             面额主要有10种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0007.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;支付宝充值</p>
　　             我公司与支付宝公司合作，提供支付宝直充方式。用户可以使用支付宝接口直接充值游戏内的“优玩币”。 <br/>
　　             面额主要有10种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0008.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;财付通充值</p>
　　             我公司与腾讯公司合作，提供财付通直充方式。用户可以使用财付通接口直接充值游戏内的“优玩币”。<br/>
　　             面额主要有10种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0008.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;快钱充值</p>
　　             我公司与快钱公司合作，提供快钱直充方式。用户可以使用快钱接口直接充值游戏内的“优玩币”。<br/>
　　             面额主要有10种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0008.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州行卡充值</p>
　　             我公司与第三方支付公司合作，提供神州行卡充值。用户可直接在报刊亭、移动营业厅等售卡处购买神州行充值卡，通过充值接口直接充值游戏内的“优玩币”。<br/>
　　             面额主要有5种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0009.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;手机短信充值</p>
　　             我公司与移动短信业务公司合作，提供移动短信充值。用户可以直接使用移动手机通过发放短信的方式充值游戏内的“优玩币”。<br/>
　　             面额主要有4种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0010.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;骏网卡充值</p>
　　             我公司与第三方支付公司合作，提供骏网卡充值卡直充方式。骏网卡充值卡均可以直接在全国各地的软件销售网和卡类商店点购买。通过游戏充值接口可以直接兑换成游戏内的“优玩币”。<br/>
　　             骏网卡充值面值主要有4种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0011.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;盛大卡充值</p>
　　             我公司与第三方支付公司合作，提供盛大卡充值卡直充方式。盛大卡充值卡均可以直接在全国各地的软件销售网和卡类商店点购买。通过游戏充值接口可以直接兑换成游戏内的“优玩币”。<br/>
　　             盛大卡充值面值主要有3种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0012.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;征途卡充值</p>
　　             我公司与第三方支付公司合作，提供征途卡充值卡直充方式。征途卡充值卡均可以直接在全国各地的软件销售网和卡类商店点购买。通过游戏充值接口可以直接兑换成游戏内的“优玩币”。<br/>
　　             征途卡充值面值主要有6种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0013.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;电信充值</p>
　　             我公司与第三方支付公司合作，提供电信充值卡直充方式。电信充值卡均可以直接在全国各地的电信营业厅、软件销售网和卡类商店点购买。通过游戏充值接口可以直接兑换成游戏内的“优玩币”。<br/>
　　             电信充值面值主要有3种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0014.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;联通充值</p>
　　             我公司与第三方支付公司合作，提供联通充值卡直充方式。联通充值卡均可以直接在全国各地的联通营业厅、软件销售网和卡类商店点购买。通过游戏充值接口可以直接兑换成游戏内的“优玩币”。<br/>
　　             联通充值面值主要有3种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0014.jpg" /></center>
　　             <p style="color: #0000ff">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;固话充值[V币]</p>
　　             我公司与深圳市盈华讯方通信技术有限公司合作，提供全国各地的固定电话或者小灵通拨打对应的声讯号码获取V币卡号和密码,在网上充值电话钱包后进行游戏充值。通过电话钱包可以向网站充值兑换成游戏内的“优玩币”。<br/>
　　             面额主要有8种：<br/>
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0015.jpg" /></center>
                </div>



		<div class="list" id="cont2">            		
			<div class="servicetit">充值常见问题 FAQ</div><Br/><br/>
            <p><strong>1.优玩充值中心有哪些功能？ </strong><br />
              答：用户来到充值中心，可以对旗下的所有游戏进行充值、查询充值历史等。 <br/>
            &nbsp;</p>
            <p><strong>2．如何对我的充值帐户进行管理？</strong><br />
              答：您可以登录优玩账号，进入充值中心页面查询各个游戏的充值历史。 </p>
            <p>&nbsp;</p>
            <p><strong>3．优玩网充值中心提供了哪些充值方式？</strong><br />
              答：优玩网充值中心为您提供了快捷、便利、多样化的充值方式，具体有：网银类充值（银行卡、快钱网银、财付通、易宝网银）、神州行类充值、手机短信充值、骏网卡充值、盛大卡充值、征途卡充值、电信卡充值、联通卡充值、固话充值等。 </p>
            <p>&nbsp;</p>
            <p><strong>4．什么是优玩币？</strong><br />
              答：用户在优玩平台进行充值（<a target="_blank" href="http://www.uwan.com/">www.uwan.com</a>），通过多种便捷的充值渠道购买优玩币后在游戏中使用，系统会自动兑换成相应的游戏币。游戏虚拟货币统称为“优玩币”。 <br />
              优玩网平台中的虚拟货币“优玩币”与人民币的兑换比例为（人民币：优玩币=1：1），即1元人民币可以兑换1个游戏虚拟优玩币。 </p>
            <p>&nbsp;</p>
            <p><strong>5．如何选择充值方式？</strong><br />
              答：如果您开通了网上银行，我们建议您采用网银充值；如果您拥有支付宝帐号，我们建议您采用支付宝方式支付；如果您拥有财付通帐号，我们建议您采用财付通支付；如果您周围有网吧、软件专卖店、零售点等，我们建议您购买一卡通进行充值；如果您是神州行用户，您可以通过神州行充值或手机短信充值；当然，您也可以通过固定电话拨打声讯电话进行充值。 </p>
            <p>&nbsp;</p>
            <p><strong>6．充值中心的充值流程是怎样的？</strong><br />
              答：如果您已经登陆，您可以直接进入充值中心，先选择您需要充值的游戏，之后您会进入“我要充值”页面，然后您在此选择您的服务器，充值帐号、选择充值套餐，选择银行（网银类选用）、或者输入一卡通卡号、输入手机号码（手机短信支付渠道专用、仅限移动）、输入电话号码（声讯电话充值专用），然后输入验证码（部分渠道不用输入），最后在页面下方点击按钮“确认支付”即可进行第三方支付平台进行支付，支付完成后程序会在您的游戏中加上对应的金币。 </p>
            <p>&nbsp;</p>
            <p><strong>7．扣了钱没有加金币怎么办？</strong><br />
              答：如果您在第三方支付平台进行支付，支付完成扣款成功后您的游戏服务器里没有加金币，这时可能是由于网络延迟而造成的，这时您稍等几分钟即可；如果较长时间仍未到帐，可能是系统问题出现了，这时候你可以通过<a target="_blank" href="http://service.uwan.com/index.php?s=/Question/index">客服中心-提交问题</a>，然后选择您充值的游戏，提问类型选择“商城/充值问题”，提交给我们的充值客服进行手动补单。 </p>
<p><br />
        </p>
	  </div>
      

      
		<div class="list" id="cont3" style="display:_none">
          <div class="servicetit">中国银行开通网银的具体</div><br/><br/>
        </div>
      
      
		<div class="list" id="cont4" style="display:_none">
        
        
          <div class="servicetit">移动短信充值帮助</div>
          <ol>
            <li><strong>1、如何进行移动短信充值？</strong></li>
          </ol>
          <p>答：(1)玩家需要<a href="https://www.uwan.com/UserCenter/Login.php" target="_blank">登录优玩账号</a>，然后进入<a href="https://www.uwan.com/InpourCenter/index.php" target="_blank">充值中心页面</a>。<br/>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2)选择您要充值的游戏，点击“我要充值”。<br/>   
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3)在左边充值渠道列表中，选择“手机短信”渠道。<br/>   
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(4)确定需要充值的账号、服务器，选择需要充值的金额，并输入您的手机号码，然后点击“确认支付”。<br/>
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5)按照提示进行编辑短信，发送短信后在1分钟内，会有短信提示回复，按照要求回复就能完成充值。<br/>   
                        <center><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/tu_0016.jpg" /></center>
            <p>&nbsp;</p>
          <ol>
            <li><strong>2、为什么我收不到短信呢？</strong></li>
          </ol>
          <p>答：请核对您的手机是否满足开通短信服务需求，您的手机话费是否充足，手机信号是否畅通，并核对您所发送的指令是否正确，或可咨询合作商客服。</p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>3、我去哪里充值？</strong></li>
          </ol>
          <p>答：请您进入充值中心，选择手机短信渠道，输入具体充值信息，选择充值金额进行充值。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>4、关于短信购买优玩币的限额？</strong></li>
          </ol>
          <p>答：根据自身的运营情况以及客户手机号码的安全性考虑充值会有限额，详细的限额请选择页面上相应的额度进行充值。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>5、所获得的优玩币能否退换？</strong></li>
          </ol>
          <p>答：由于短信购买优玩币是以明文形式交付，一经交易，概不退换。有问题请咨询客服。 </p>
        </div> 
      
      
      
		<div class="list" id="cont5" style="display:_none">
          <div class="servicetit">电信充值卡FAQ</div><br/><br/>
          <ol>
            <li><strong>1、什么是电信卡充值？</strong></li>
          </ol>
          <p>答：电信充值卡支付，就是消费者使用中国电信发行的手机充值卡来进行支付。电信全国充值卡全国各个城市，甚至县级市都能够随处方便地购买，并且支付过程特别简捷、方便。只要您能购买到适合支付的电信手机充值卡，就可以直接选择电信卡充值为您的优玩游戏账户购买优玩币。电信充值卡支付目前仅支持30元、50元、100元三种面值充值卡。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>2、我才给手机充过一张卡，能不能用正在使用的手机卡支付购买？</strong></li>
          </ol>
          <p>答：不能，您需要购买一张新的电信充值卡，并且不能将它充值到您的手机内，才可以进行支付。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>3、使用电信卡充值如何操作？</strong></li>
          </ol>
          <p>答：(1)玩家需要先购买电信全国通用充值卡；<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2)登录优玩平台充值中心，选择充值游戏，选择电信卡充值； <br />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3)进入电信卡的充值页面后，输入游戏账号信息，账号所在服务器，充值的金额，电信卡卡号以及密码，确认支付； </p>
          <ol>
            <p>&nbsp;</p>
            <li><strong>4、电信卡与优玩币的换算比例？</strong></li>
          </ol>
          <p>答：换算比例为1比0.91，电信卡1元面值等同与优玩币0.91元面值。 </p>
        </div>
      
      
      
		<div class="list" id="cont6" style="display:_none">
          <div class="servicetit">联通充值卡支付FAQ</div><br/><br/>
          <ol>
            <li><strong>1、联通充值卡如何在优玩充值中心支付？</strong></li>
          </ol>
          <p>答：操作流程如下：<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1)玩家需要先购买联通充值卡；<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2)然后进入优玩平台<a href="https://www.uwan.com/InpourCenter/index.php" target="_blank">充值中心</a>，选择使用“联通”支付渠道； <br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(3)确认您充值的账号,选择您充值的服务器；<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(4)输入联通卡的卡号及卡密；<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(5)选择充值的金额，点击“确认支付”，即可支付成功。<br />
            <p>&nbsp;</p>
          <ol>
            <li><strong>2、联通充值卡在哪里购买？</strong><strong> </strong></li>
          </ol>
          <p>答：联通充值卡有两种方式购买：<br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(1)网上购买充值卡，登录中国联通<a href="http://ecard.10010.com/buyCard/buyCardInit.action" target="_blank">网上营业厅</a>,选择充值卡面值，输入验证码，点击“确认购卡”。进入网上银行支付页面，确认支付。<br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(2)玩家可以在联通营业厅、网吧、书报厅等地方购买实卡。</p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>3、联通充值卡支付支持哪些充值卡？</strong><strong> </strong></li>
          </ol>
          <p>答：联通充值卡支付目前支持：联通一卡充，充值卡信息如下：<br />
            联通一卡充：卡号15位，密码19位；面值：30元、50、100元。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>4、联通充值卡分省支付支持哪些地区的充值卡？</strong><strong> </strong></li>
          </ol>
          <p>答：可拨打10011充值话费的卡。<br />
            全国联通一卡充：卡号15位，密码19位；面值：50、100元。 </p>
            <p>&nbsp;</p>

          <ol>
            <li><strong>5、为什么提示我的充值卡被锁了，不能继续支付？</strong></li>
          </ol>
          <p>答：为了保证用户卡的安全，每一张联通充值卡一天内支付失败超3次（不含3次）系统禁止该卡在19PAY支付网关继续使用。若用户确定其充值卡是有效的，则请联系19PAY客服核实情况，进行解锁处理。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>6、为什么提示我系统忙，稍后再试？</strong></li>
          </ol>
          <p>答：由于使用19PAY支付网关的用户非常多，会造成一段时间的系统繁忙，系统无法及时处理每个支付请求，所以，当用户遇到这种情况时，可过一段时间再提交支付请求。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>7、联通卡与优玩币的换算比例？</strong><strong> </strong></li>
          </ol>
          <p>答：换算比例为1比0.91，联通卡1元面值等同与优玩币0.91元面值。 </p>
        </div>
      
      
      
		<div class="list" id="cont7" style="display:_none">
            <div class="servicetit">神州行充值帮助</div><br /><br/>
            <p><strong>1、什么是神州行充值？</strong> <br />
            答：为了能让广大优玩网用户更方便快捷地为账户进行储值，优玩网推出神州行卡充值业务，即日起完美时空用户可以直接使用神州行卡对您自己的游戏账户进行充值。 <br /></p>
            <p>&nbsp;</p>
            <p><strong>2、神州行卡充值的操作步骤？</strong><br />
            答：（1）玩家购买神州行充值卡。 <br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（2）登录<a href="https://www.uwan.com/InpourCenter/index.php">优玩平台充值中心</a>选择要充值的游戏产品。<br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（3）选择神州行充值项目，输入充值账号，选择游戏服务器，选择充值面值，（请选择与神州行卡对应的面值进行充值，这样就不会产生余额问题）点击提交跳转到核对页面，（请记录订单号码以便日后核对使用）如核对信息无误，点击神州行支付跳转到神州行充值端口。<br />
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（4）按照页面提示输入神州行充值卡卡号、密码、电子邮件（电子邮件为必填项否则不能进行充值操作，请您填写您真正的邮箱地址，如产生余额问题可以用此邮箱接收您的余额）选择面值（请选择与神州行充值卡对应的面值，否则将不能成功充值）点击充值即可完成对优玩网游戏账户的充值操作。 <br /></p>
            <p>&nbsp;</p>
            <p><strong>3、我才给手机充过一张卡，能不能用正在使用的手机卡支付货款？</strong><br />
            答：不能。您需要购买一张全新未使用过的神州行充值卡才能支付。 <br />
            <p>&nbsp;</p>
            <p><strong>4、神州行账户里的资金可以转换为人民币吗？</strong><br />
            答：目前，个人用户的神州行账户无法提现。 </p>
        </div>
        
        
        
		<div class="list" id="cont8" style="display:_none">
          <div class="servicetit">固话（V币）充值帮助</div><br/><br/>
          <p><strong>1、什么是声讯充值（V币）？</strong><br />
            </strong>答：声讯充值（V币）指的就是固定电话支付，目前国内较为常见的固定电话分为电信固定电话和网通固定电话。固话声讯支付指的就是通过固定电话来代替现金支付的一种支付方式。 <br />
            <p>&nbsp;</p>
            <strong>2、是否可以通过声讯电话充值？</strong><br />
            答：为了更好的为玩家提供更为便捷的购卡服务。如果您在充值上遇到任何问题，请致电V币客服热线：400-884-0755 <br />
            <p>&nbsp;</p>
  <strong>3、我是手机用户能否进行V币充值？</strong> <br />
            答：目前用手机拨打热线也能购买V币，手机支付渠道支持电信、联通手机支付，目前支持号段为133、153、189。请在V币购买方法中选择“手机支付”查询。 <br />
  注意事项：<br />
            1.玩家通过声讯电话购卡时应注意，您所购买的点卡卡号均为阿拉伯数字，请您注意记录清卡号和密码。如果您在电话里没有听清卡号密码或记录的卡号密码有误，请及时联系客服。 <br />
            2.成功购买V币后请在有效期（3个月）内使用完成，建议立刻充值，以免带来不必要的损失。 </p>
        </div>
        
      

		<div class="list" id="cont9" style="display:_none">
          <div class="servicetit">盛大卡充值帮助</div><br/><br/>
          <p><strong>1、什么是盛大卡？</strong><br />
            答：盛大卡是由上海盛大网络发展有限公司发行的游戏充值点卡。盛大互动娱乐卡可以多次进行在线支付，直至卡内余额为零。 <br />
            <p>&nbsp;</p>
  <strong>2、盛大互动娱乐卡如何支付？</strong> <br />
            答：持有盛大互动娱乐卡各种面值实体卡、虚拟卡的用户，在进行网上购物时，使用卡上的余额进行抵付货款的方式称为盛大互动娱乐卡支付（类似银行卡）。 <br />
            <p>&nbsp;</p>
  <strong>3、可以用于支付的游戏卡种？</strong> <br />
            答：全国各地都可买到的盛大卡，请使用卡号以CS、S、CA开头的“盛大互动娱乐卡”进行支付，暂不支持SC开头的卡。 <br />
            <p>&nbsp;</p>
  <strong>4、盛大互动娱乐卡有哪些面值的卡？</strong> <br />
            答：盛大互动娱乐卡面值：5元、10元、15元、30元、50元、100元。 <br />
            <p>&nbsp;</p>
  <strong>5、盛大卡可以充值优玩币的金额额度？</strong><br />
            答：目前可提供的充值金额额度为：10元、30元、50元、100元。<br />
            <p>&nbsp;</p>
  <strong>6、盛大卡与优玩币的换算比例？</strong><br />
            答：换算比例为1比0.8，盛大卡1元面值等同与优玩币0.8元面值。 </p>
        </div>
        
        
        
		<div class="list" id="cont10" style="display:_none">
          <div class="servicetit">骏网一卡通充值帮助</div><br/><br/>
          <ol>
            <li><strong>1、什么是骏网一卡通？</strong><strong> </strong></li>
          </ol>
          <p>答：骏网一卡通是由北京汇元网科技有限责任公司发行的多种网络产品的支付手段，目前骏网一卡通支持优玩平台产品的充值。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>2、在哪里可以购买到骏网一卡通？</strong><strong> </strong></li>
          </ol>
          <p>答：您可以到骏网一卡通官方网站的经销商查询页面查看，<a href="http://www.jcard.cn/Account/AgentList.aspx" target="_blank">【点击查看页面】</a>，选择您所在的省份地区找到最方便您的购买点卡的地点。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>3、骏网一卡通与优玩币的换算比例？</strong><strong> </strong></li>
          </ol>
          <p>答：换算比例为1比0.8，骏网一卡通1元面值等同与优玩币0.8元面值。 </p>
            <p>&nbsp;</p>
          <ol>
            <li><strong>4、想确认自己所购买的骏网一卡通是否被使用过？</strong><strong> </strong></li>
          </ol>
          <p>答：购买骏网一卡通的客户可以进入<a href="http://www.jcard.cn/Bill/TradeSearch.aspx" target="_blank">骏网一卡通状态查询</a>页面,点击“卡状态查询”,然后输入卡号和密码及验证码后方可查询输入的点卡状态。 </p>
        </div>
        
        
        
		<div class="list" id="cont11" style="display:_none">
          <div class="servicetit">征途充值帮助</div><br/><br/>
          <ol>
            <li><strong>1、征途卡充值的操作步骤？</strong><strong> </strong></li>
          </ol>
          <p>答：（1）玩家需要先购买征途充值卡； <br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（2）登录优玩平台充值中心，选择充值游戏，选择征途卡充值； <br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（3）进入征途卡的充值页面后，输入游戏账号信息，账号所在服务器，充值的金额，征途卡卡号以及密码，确认支付； </p>
          <ol>
            <p>&nbsp;</p>
            <li><strong>2、征途卡与优玩币的换算比例？</strong><strong> </strong></li>
          </ol>
          <p>答：换算比例为1比0.8，电信卡1元面值等同与优玩币0.8元面值。 </p>
          <ol>
            <p>&nbsp;</p>
            <li><strong>3、充值完毕后没有回应？</strong><strong> </strong></li>
          </ol>
          <p>答：由于征途一卡通卡网关的验证比较严格，所以充值处理时间比较长;确认输入卡和密码无误成功下订单后,二分钟内到冲值查询查看充值结果。<strong> </strong></p>
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