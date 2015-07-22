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
                <a href="/index.php?s=/Notice/strategy" class="d">服务使用攻略</a>
                <a href="/index.php?s=/Notice/channel">多元化的服务团队</a>
                </div>
				<div class="servicewen">
					<p><b>使用我们游戏产品中遇到问题，可以通过哪些途径来解决？</b><br />
<span>您可以通过以下途径来解决：</span>
</p>					
					<p>途径一：通过客服中心网站搜索问题的解决方法；<br />
途径二：在客服中心网站“常见问题”中寻找游戏FAQ的帮助信息；<br />
途径三：使用自助服务自已解决；<br />
途径四：游戏中可以使用FAQ询找答案，如果无法为您解决还可以通过客服服务提交问题询求帮助；</p>
					<p>&nbsp;</p>
					<p><b>客服中心网站“常见问题”栏目介绍</b><br />
<span>客服中心网站的常见问题包括了各种产品的使用帮助信息。操作步骤如下：</span><br />
1、请先登录优玩网客服网站，点击页面左边“常见问题FAQ”；<br />
2、直接选择点击您想了解的游戏相关信息，进入相关业务的帮助页面后，您可以根据“热点关键字”或左侧目录，选择您要查看的分类及问题。<br />
3、如果按以上步骤没有找到您想了解的问题，您可以在客服网站上方的搜索框中输入关键字进行搜索。</p>
<p>&nbsp;</p>
					<p><b>什么是自助服务？可以解决哪些问题？</b><br />
“自助服务”是玩家可以通过自己操作解决问题的服务，通过“自助服务”，能解决以下问题：</p>
					<p><table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#c8cbce">
  <tr>
    <td bgcolor="#e5eaee"><strong>自助服务</strong></td>
    <td bgcolor="#e5eaee"><strong>服务描述</strong></td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">充值自助 </td>
    <td bgcolor="#f3f3f5">充值自助 目前支持有12种充值方式，包括网银与卡类充值。 </td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">家长监护体系</td>
    <td bgcolor="#f3f3f5">家长监护体系 引导未成年人健康、绿色参与网络游戏。</td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">找回密码 </td>
    <td bgcolor="#f3f3f5">找回密码 可以通过邮件或者密保为您快速找回密码。 </td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">密码保护</td>
    <td bgcolor="#f3f3f5">密码保护 让您的账号的安全性大大提高，同时还可以用于找回密码。</td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">游戏自助</td>
    <td bgcolor="#f3f3f5">游戏自助 为您快速解答游戏内的常见问题，非常方便。</td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">防沉迷申请</td>
    <td bgcolor="#f3f3f5">防沉迷申请 当您加入防沉迷系统后，如果您年满18周岁将没有特定的防沉迷限制。 </td>
  </tr>
</table></p>
<p><b>何通过客服中心提交问题？ </b><br />
<span>给客服提交问题：</span><br />
第一步：请登录优玩网客服中心，接着点击网页左上方的“提交问题”;<br />
第二步：如果您没有登录，请输入用户名、密码进行登录，或者快速注册一个账号，成功登录后会回到提交问题页面。已经登录的用户会自动跳过这一步;<br />
第三步：选择游戏产品，选择提问类型，填写问题标题，填写标题过程中，会自动列出相符的FAQ，如果您没有找到合适的FAQ，可以继续填写表单，有需要的情况下请上传截图，点击“提交”我们就能收到您的问题了。</p>
<p><img src="/Public/front/default/images/button_86.gif" /></p>
<p><span>查询提问结果：</span>
点击“客服中心”的左上角“查询结果”（提交问题的下方），在“待处理问题”和“已处理问题”中即可以查询问题的处理进度，您在作出评价之前还也可以继续追问。</p>
<p>&nbsp;</p>
<p><span>客服中心网站搜索功能介绍</span>
客服中心网站的搜索功能，致力于帮助用户更加容易地找到大亨、富人国游戏的帮助信息（常见问题），使用搜索功能简单方便，您只需在搜索框中输入需要查询的内容，敲回车键或用鼠标点击“搜索”按钮，就可以得到符合查询需求的结果。</p>
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