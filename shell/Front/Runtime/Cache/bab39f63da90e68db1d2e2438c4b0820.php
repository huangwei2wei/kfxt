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
					<a href="/index.php?s=/Notice/addicted">防沉迷系统介绍</a><a href="/index.php?s=/Notice/realname" class="d">实名注册与防沉迷</a><a href="/index.php?s=/Notice/addictedqs">防沉迷常见问题</a>
				</div>

				<div class="servicewen">
					<p><b>什么是实名注册？</b><br />
根据2010年8月1日文化部推行的《网络游戏管理暂行办法》，网络游戏用户需使用有效身份证件进行实名注册。<br /><br /></p>
					<p><b>什么是防沉迷系统？</b><br />
防沉迷是针对未成年人过度沉迷于网络游戏，并对其身心健康造成不利影响的情况，文化部于2010年8月1日正式实施的《网络游戏管理暂行办法》，限制未成年人依赖网络游戏长时间在线来获得游戏内的收益增长，有效控制未成年人在线游戏的时间，使得未成年人有个健康的环境进行游戏。<br /><br /></p>
					<p><b>实名注册和防沉迷系统的关系</b><br />
实名注册是防沉迷系统的前提条件：<br />
1）未经实名注册的用户，将无法注册成为优玩网游戏平台的用户。<br />
2）已通过实名注册的用户，将根据年龄信息判断是否为未成年玩家（未满18岁者），如果是未成年玩家，将收到防沉迷系统的提示信息和游戏时长限制。<br /><br /></p>
					<p><b>防沉迷系统的详细规则</b><br />
在未成年人进行游戏的过程，系统会自动统计未成年用户的累计在线时间；<br />
3小时以内的游戏时间为“健康”游戏时间；<br />
超过3小时，至5小时以内的2个小时游戏时间为“疲劳”游戏时间；<br />
超过5小时的游戏时间为“不健康”游戏时间。</p>
<p><table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#c8cbce">
  <tr bgcolor="#91b0ce">
    <td><strong>累计在线时间</strong></td>
    <td><strong>健康时间</strong></td>
    <td><strong>疲劳时间</strong></td>
    <td><strong>不健康时间</strong></td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">0－3小时内</td>
    <td bgcolor="#f3f3f5">1小时　　2小时　　3小时</td>
    <td bgcolor="#f3f3f5">4小时　　5小时</td>
    <td bgcolor="#f3f3f5">6小时　　...小时</td>
  </tr>
  </table></p>

					<p>未成年人累计在线时间超过健康时间范围内，游戏收益会递减： </p>
					<p><table width="55%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#c8cbce">
  <tr>
    <td bgcolor="#e5eaee"><strong>累计在线时间</strong></td>
    <td bgcolor="#e5eaee"><strong>游戏收益</strong></td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">0－3小时内</td>
    <td bgcolor="#f3f3f5">正常</td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">超过3小时后，5小时内</td>
    <td bgcolor="#f3f3f5">降为正常值的50％</td>
  </tr>
  <tr>
    <td bgcolor="#f3f3f5">5小时以上</td>
    <td bgcolor="#f3f3f5">降为0</td>
  </tr>
  </table></p>

					<p>游戏收益 指游戏中与游戏角色成长升级相关的所有数据（包括但不限于经验值、荣誉值、声望值、称号等）的提升＋获得的包括道具、装备、虚拟货币等在内的虚拟财产。<br />
游戏收益收益为50％，指游戏中与游戏角色成长升级相关的所有数据和获得的包括道具、装备、虚拟货币等在内的虚拟财产均减半。<br />
游戏收益收益为0，指无法获得游戏中与游戏角色成长升级相关的所有数据和包括道具、装备、虚拟货币等在内的虚拟财产。<br /><br />
</p>
					<p><b>如何解除防沉迷系统的限制？</b><br />
防沉迷系统是为保护未成年人的身心健康而设计的，成年人不受此设计影响，可以登录优玩平台 - 用户中心 - 防沉迷系统页面，填写您的个人身份资料，如果资料验证通过，确认您是成年人则防沉迷将不再提示和限制你的游戏受益。</p>						
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