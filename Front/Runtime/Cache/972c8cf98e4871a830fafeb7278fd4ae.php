<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo (($_page_title)?($_page_title):"优玩网 - 客服中心"); ?></title>
    <link rel='SHORTCUT ICON' href='__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/uwan2Icon.ico'/>
    <link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
</head>
<body>
<div id="bg">
<div id="bgtainer">
    <!---->
    <div id="container">
<div id="header">
	<div class="contai">
	<div class="top_logo"><a href="#"></a></div>
		<div class="top">
			
			<ul id="links">
 <li class="nav_t"><a style="cursor:pointer;width:144px;height:21px;display:block;background:url('images/tpl201103/images/button_03.gif')"></a><div style="display: none;" class="cybox">
    <div class="cyboxtop">
    <div class="cyboycon">
	<a target="_blank" href="http://r.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_frg.gif"></span>富人国<img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/hot.gif"></a>
 	<a target="_blank" href="http://bto.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_bto.gif"></span>商业大亨 </a>
	<a target="_blank" href="http://h.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_xh.gif"></span>幻世仙征 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
    <a target="_blank" href="http://g.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_gf.gif"></span>功夫 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
	<a target="_blank" href="http://xx.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_xx.gif"></span>寻侠 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
    <a target="_blank" href="http://s.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_s.gif"></span>双龙诀 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
    <a target="_blank" href="http://bto2.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_bto2.gif"></span>商业大亨2 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
    <a target="_blank" href="http://hd.uwan.com"><span><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/lingk_hd.gif"></span>海岛大亨 <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/new.gif"></a>
	</div>
    </div>
</div></li>
 </ul>
 <div class="liong">	
    <!---->
    <?php if($username): ?><a href="https://www.uwan.com/UserCenter/EditAccount.php"><?php echo ($username); ?></a><a href="http://www.uwan.com/UserCenter/Logout.php" style="border-right:0; padding-left:8px;margin-left:8px;">退出</a>
    <?php else: ?>
        <b><a href="https://login.uwan.com/UserCenter/Login.php?back=http://service.uwan.com/">登录</a></b><a href="http://www.uwan.com/UserCenter/GetAccount.php">注册</a><a style="border:0" href="#">加入收藏</a><?php endif; ?>
    
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
			</div>
			<div style="float:right;padding-top:8px;"><iframe src=" http://open.qzone.qq.com/like?url=http%3A%2F%2Fuser.qzone.qq.com%2F692942699&type=button_num&width=400&height=30" allowtransparency="true" scrolling="no" border="0" frameborder="0" style="width:100px;height:30px;border:none;overflow:hidden;"></iframe></div>
		</div>
		<div class="nav">
			<ul>
				<li class="shou"><a href="http://www.uwan.com/">首页</a></li>
				<li><a href="http://www.uwan.com/mm/index.php">动网美眉</a></li>
				<li><a href="http://www.uwan.com/NewsCenter/index.php">新闻中心</a></li>
				<li><a href="http://www.uwan.com/UserCenter/index.php">用户中心</a></li>
				<li><a href="https://pay.uwan.com/InpourCenter/index.php">充值中心</a></li>
				<li><a class="d" href="/index.php">客服中心</a></li>
				<li style="background:0"><a target="_blank" href="http://www.uwan.com/bbs.php">游戏论坛</a></li>
			</ul>
		</div>
	</div>
</div>
</div>

    <div class="contai">
        <!---->
        <div class="left">
            <!---->
            <div class="left1">
    <div class="left_tit">uwan客服中心</div>
    <ul>
        <li style="background:0;"><a href="<?php echo U('Question/index');?>">我要提问</a></li>	
        <li><a href="<?php echo U('Question/ls/status/0');?>">待处理问题<font <?php if(empty($username)): ?>color="#999999"<?php endif; ?>>（<?php echo (($waitCount)?($waitCount):"0"); ?>个）</font></a></li>	
        <li><a href="<?php echo U('Question/ls/status/3');?>">已处理问题<font <?php if(empty($username)): ?>color="#999999"<?php endif; ?>>（<?php echo (($haveCount)?($haveCount):"0"); ?>个）</font></a></li>	
        <li><a href="<?php echo U('Faq/index');?>">常见问题</a></li>	
        <li><a href="<?php echo U('Notice/Index');?>">服务指引</a></li>
        <li><a href="<?php echo U('Notice/pay');?>">充值指南</a></li>
        <li><a href="<?php echo U('Notice/addicted');?>">防沉迷系统</a></li>
    </ul>
</div>
            <!---->
            <div class="left2">
    <h2>服务公告</h2>
    <ul>
		<li><a href="<?php echo U("/Notice/article_5");?>">服务热线变更公告</a></li>
        <li><a href="<?php echo U("/Notice/article_2");?>">新版客服中心服务指引</a></li>
    </ul>
    <h2 style="padding-top:20px">重要通知</h2>
    <ul>
        <li><a href="<?php echo U("/Notice/article_4");?>">求粉！求关注！</a></li>
        <li><a href="<?php echo U("/Notice/article_3");?>">优玩网客服中心改版啦！</a></li>
        <li><a href="<?php echo U("/Notice/article_1");?>">《功夫》今日公测 版本大更新...</a></li>
    </ul>
</div>
            <!---->
            <div class="left3">
	<div class="left_tit">客服中心(7×24)</div>
		<p>投诉通道：<a href="<?php echo U('Question/index');?>">点击进入</a><br/>
<a href="mailto:BTOcustomerservice@cndw.com">BTOcustomerservice@cndw.com</a><br/>
欢迎对我们的客服质量进行监督
		</p>
</div>
        </div>
        <!---->
        <div class="right">
        	<!---->
<!---->
<style type="text/css">
#nav { 
	float:left;
	margin:-1px 1px 0 13px; 
	list-style-type: none;
	line-height:24px;
	overflow:hidden;
}
#nav a {
	display: block;
}
#nav li {
	float: left; width: 100px;
}
#nav li ul {
	overflow:hidden; display:none;
	width:205px;
	padding:10px 0 0 10px;
	position: absolute; height:130px;background:url('__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_10.gif');
}
#nav li ul li{ height:27px; margin-bottom:3px;
	 float: left;width:97px; border-bottom:1px #a9b8cb dashed; }
#nav li ul li img{
	vertical-align:middle; margin-right:7px;
}
#nav li ul a{ font-size:12px;font-weight:normal; color:#333;
	display: block; width:92px;text-align:left; padding-left:5px;
}
#nav li ul a:hover  {
	color:#2e3f55;text-decoration: underline;
}
#nav li:hover ul {
	 display:block; margin-left:-63px;
}
#nav li.sfhover ul {
 display:block;
}
</style>

<script>
function getDetail(game_type){
	$.ajax({
		dataType:'text',	
		type: 'GET',
		data:{gametype:game_type},
		url: "<?php echo U('Faq/questionTop');?>",
		success:function(msg){
			$('#faq_hot_list').html("");
			$('#faq_hot_list').append(msg);
		}
	});
}
</script>
<div class="right_tit">
	<span>
        <ul id="nav">
        <li><a href="#"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_09.gif"></a>
            <ul style="margin-left:-115px;">
                <li><a onclick="getDetail('2');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_11.gif">富人国</a></li>
                <li><a onclick="getDetail('1');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_12.gif">商业大亨</a></li>
                <li><a onclick="getDetail('6');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_13.gif">幻世仙征</a></li>
                <li><a onclick="getDetail('7');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_14.gif">双龙诀</a></li>
                <li><a onclick="getDetail('9');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_15.gif">商业大亨2</a></li>
                <li><a onclick="getDetail('8');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_16.gif">海岛大亨</a></li>
                <li><a onclick="getDetail('10');" href="javascript:void(0);"><img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_17.gif">功夫</a></li>
            </ul>
        </li>
        </ul>
    </span>热点问题
    
</div>
<div class="right1">
    <ul id="faq_hot_list">
        <!---->
        <?php if(is_array($topFaqlist)): $i = 0; $__LIST__ = $topFaqlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><li><span>[<?php echo ($vo["game_name"]); ?>] </span><a title="<?php echo (strip_tags($vo["question"])); ?>" href="<?php echo U("/Faq/show/game_type_id/$vo[game_type_id]/Id/$vo[Id]");?>"><?php echo (mb_substr(strip_tags($vo["question"]),0,20,'utf-8')); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>


<div class="right_bo"></div>
<div class="right_b"></div>

<!---->
<div class="right_tit">提交问题</div>
<div class="right2">
    <ul>
    	<!---->
        <?php if(is_array($games)): $i = 0; $__LIST__ = $games;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): ++$i;$mod = ($i % 2 )?><li><div class="right2_img"><a href="<?php echo ($list["ask_url"]); ?>"><img src="<?php echo ($list["img"]); ?>" /></a></div>
            <p><b><a href="<?php echo ($list["ask_url"]); ?>"><?php echo ($list["name"]); ?></a></b><br />游戏类型：<?php echo ($list["kind_name"]); ?><br /><a href="<?php echo ($list["ask_url"]); ?>" class="ask">常见问题</a></p></li><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>

<div class="right_bo"></div>
<div class="right_b"></div>

<!---->
<div class="right_tit">常用功能</div>
<div class="right3">
    <ul>
        <li>
        	<img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_01.gif" />
            <p><a target="_blank" href="http://www.uwan.com/jianhu/index.htm">家长监护体系</a></p>
        </li>
        
		<li>
        	<img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_02.gif" />
            <p><a target="_blank" href="http://www.uwan.com/UserCenter/Indulged.php">防沉迷系统</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_03.gif" />
            <p><a target="_blank" href="http://www.uwan.com/UserCenter/default.php">找回密码</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_04.gif" />
            <p><a href="<?php echo U('Notice/shouze');?>">优网玩客服守则</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_05.gif" />
            <p><a target="_blank" href="http://www.uwan.com/UserCenter/AdvancedPs.php">密码保护</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_06.gif" />
            <p><a href="<?php echo U('Question/index');?>">建议提交</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_07.gif" />
            <p><a href="<?php echo U('Notice/guide');?>">账号防盗手册</a></p>
        </li>
        
        <li>
            <img src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/icon_08.gif" />
            <p><a href="<?php echo U('Faq/index');?>">常见问题</a></p>
        </li>
    </ul>
</div>

<div class="right_bo"></div>
        </div>
        
        <div style="clear:both"></div>
    </div>
    
    <div class="connerbo"></div>
    
    <!---->
	﻿<div class="jiank">健康游戏忠告：抵制不良游戏 拒绝盗版游戏 注意自我保护 谨防受骗上当 适度游戏益脑 沉迷游戏伤身 合理安排时间 享受健康生活</div>
<div class="bottom">网络文化经营许可证 编号：琼网文[2012]0418-002号 互联网出版许可证：新出网证（琼）字003号<br/>
增值电信业务许可证：琼 B2-20090005   琼ICP备08000446号-1<br/>
版权所有动网先锋网络科技有限公司</div>
<div style="display:none;"><script src="http://s11.cnzz.com/stat.php?id=2558818&web_id=2558818" language="JavaScript"></script></div>
</div>
</body>
</html>