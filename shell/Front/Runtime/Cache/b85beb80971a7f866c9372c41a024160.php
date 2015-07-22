<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 客服中心</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script src="__PUBLIC__/common/js/libs/jquery.form.js" language="javascript"></script>
<script>
$(function(){
	$('#menu_question').addClass('d');
	$("#ev_submit").ajaxForm({
		dataType:'json',
		success:processJson
	});
});

function processJson(data){
	var tag = $('#re_question').find(':radio').attr("checked")&&($('#re_question').find(':radio').val()==1);
	$("#ev_submit").empty();
	if(tag){
		$("#ev_submit").append('<h2 style="text-align:center;padding:20px 0;">感谢您对我们工作的支持！</h2>');
		}
	else{
		$("#ev_submit").append('<h2 style="text-align:center;padding:20px 0;">感谢您对我们工作的支持！您还可以通过<a href="<?php echo U('Question/index');?>">提交问题</a>给我们的客服，以获得满意的答案！</h2>');
		}
	$("#reply_href").hide();
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
						<li class="d"><a href="/index.php?s=/Question/index">我要提问</a></li>
						<li><a href="/index.php?s=/Question/ls/status/0">待处理问题（<?php echo ($waitCount); ?>个）</a></li>
						<li><a href="/index.php?s=/Question/ls/status/3">已处理问题（<?php echo ($haveCount); ?>个）</a></li>
                        <div style="padding-bottom:15px;"><img src="/Public/front/default/images/button_11.gif" /></div>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>我要提问</div>
					
                 <div class="listkan">
                 <p class="listkann">
                     <b>问题标题：</b><?php echo ($workOrder["title"]); ?><br/>
                     <b>问题类型：</b><?php echo ($workOrder["question_type_name"]); ?><br/>	
                     <b>游戏类型：</b><?php echo ($workOrder["game_type_name"]); ?><br/>		
                     <?php if($game_server_name): ?><b>所在服务器：</b><?php echo ($workOrder["game_server_name"]); ?><?php endif; ?> 
                    <!--动态表单-->
                    <?php if(is_array($workOrder[actionFormArray])): $i = 0; $__LIST__ = $workOrder[actionFormArray];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$form): ++$i;$mod = ($i % 2 )?><b><?php echo ($key); ?>：</b><?php echo ($form); ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
			 	</p>
			 	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><p class="listkann">
                        <b>
                            <?php if($i == 1): ?>问题描述 <?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?>：
                            <?php elseif($vo["qa"] == 0): ?>
                                追问内容 <?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?>：
                            <?php else: ?>
                                客服[<?php echo ($serivceUsers[$vo[user_id]]); ?>] <?php echo (date("Y-m-d H:i:s",$vo["create_time"])); ?>：<?php endif; ?>
                        </b>
                    	<br />
						<?php echo (nl2br($vo["content"])); ?>
                   </p><?php endforeach; endif; else: echo "" ;endif; ?>	
            <div id="re_question" >
            
            <?php if($workOrder["evaluation_status"] == 0): ?><a href="javascript:void(0)" onclick="$('#reply').show();$('#ev_submit').hide();$(this).hide()" id="reply_href">继续追问</a>
                <form name="myform" action="<?php echo U('Question/addqa');?>" id="reply" method="post" style="display:none" onsubmit="if ($('#content').val()==''){alert('回复内容不能为空');return false;}">
                <input type="hidden" value="<?php echo ($id); ?>" name="id" />
                    <p><b>问题补充：</b><textarea id="content" name="content"></textarea></p>	
                    <p style="text-align:center;border:0"><a href="#"><input type="image" src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/input_03.gif" /></a></p>		
                </form>	
                <form action="<?php echo U("Question/opinion/id/$workOrder[Id]");?>" id="ev_submit" method="post" <?php if($workOrder["status"] != 3): ?>style="display:none"<?php endif; ?> >
                     <font style="margin-top:7px;color:#de6100">请对我们的服务质量进行评价： </font>
                     <?php if(is_array($playerEvaluation)): $evi = 0; $__LIST__ = $playerEvaluation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ev): ++$evi;$mod = ($evi % 2 )?><input name="ev" type="radio" value="<?php echo ($key); ?>" 
                                <?php if($ev["isDefault"] == true): ?>checked="checked"<?php endif; ?>
                                <?php if($ev["Description"] != null): ?>onclick="$('#ev_des').show();"<?php else: ?>onclick="$('#ev_des').hide();$('#des').hide();"<?php endif; ?>
                          /><?php echo ($ev["title"]); ?>
                         
                         <?php if($ev["Description"] != null): ?><select name="ev_des" id="ev_des" style="display:none;">
                              <?php if(is_array($ev["Description"])): $des_key = 0; $__LIST__ = $ev["Description"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ev_des): ++$des_key;$mod = ($des_key % 2 )?><option value="<?php echo ($des_key); ?>"><?php echo ($ev_des); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                             </select><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
                     <input type="submit" value="提交" />
                </form><?php endif; ?>
            
            </div> 	
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