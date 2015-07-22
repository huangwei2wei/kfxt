<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 常见问题</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script src="__PUBLIC__/common/js/libs/jquery.form.js" language="javascript"></script>
<script>
$(function(){
	$('#menu_faq').addClass('d');
});

function displaySelect(){
	$("#cause").css("display","");
}
function checkOther(value){
	if(value==5){
		$("#cause").after("<input type='text' id='opinion' name='content' />");	
	}else{
		$("#opinion").remove();
	}
}
function processJson(data){
	if(data.msg==1){
		$('#tion_03_1').css("display","none");
		if($('#faq_info').attr('checked')==true){
			//有用
			$("#tion_03_2").css("display","");
		}else{
			$("#tion_03_3").css("display","");
		}
		
		
	}
}

$(function(){
	$("#faqOpinionForm").ajaxForm({
		dataType:'json',
		success:processJson
	});
	$('#menu_faq').addClass('d');
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
				<div style="padding-bottom:15px;"><img src="/Public/front/default/images/button_11.gif" /></div>
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
				<div class="pquantitle"><span>
            <select onchange="window.location.href='<?php echo U('Faq/ls/game_type_id/');?>'+this.value;">
            <?php if(is_array($faqKind)): $i = 0; $__LIST__ = $faqKind;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option  value="<?php echo ($vo["Id"]); ?>" <?php if($vo["Id"] == $gameTypeId): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?> 
            </select>
	    </span>常见问题</div>
					<div class="servicehaed">
                        <?php if(is_array($types)): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><b style=" padding-top:1px"><a href="<?php echo U("Faq/ls/game_type_id/$gameTypeId/typeid/$vo[Id]");?>"><?php echo ($vo["name"]); ?></a></b><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="serviceso">
                    	
						<dl><dt><input type="text" class="bau" name="keyword" id="faqkeyword" value="<?php echo ($keyword); ?>" /></dt>
                        <dd><a href="javascript:void(0);" id="search_button" onclick="searchFaq(<?php echo ($gameTypeId); ?>);"><img src="/Public/front/default/images/button_78.gif" /></a></dd></dl>
						<p>热点关键字:
                         <?php if(is_array($keywords)): $i = 0; $__LIST__ = $keywords;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><a href="javascript:void(0)" onclick="$('#faqkeyword').val($(this).html());$('#search_button').click()" style="color:#fb8e00"><?php echo ($vo); ?></a> 　<?php endforeach; endif; else: echo "" ;endif; ?>
                        </p>
					</div>
					<div class="showtext">
							<div class="tion_02">
                                <dl><dt>问题标题：</dt><dd><?php echo (stripcslashes($faq["question"])); ?></dd></dl>
                                <div style="clear:left;"></div>
                                <dl><dt>解决方法：</dt><dd><?php echo (stripcslashes($faq["answer_s"])); ?></dd></dl>
                            </div>
                            
                            <div class="tion_03" id="tion_03_1">
                            <form action="<?php echo U('Faq/opinion');?>" id="faqOpinionForm" method="post">
                            <input type="hidden" value="<?php echo ($Id); ?>" name="player_faq_id" />
                                <h2>以上信息是否能为您解决问题？</h2>
                                <p>
                                    <input name="faq_info" id="faq_info" type="radio" value="1" onclick="$('#cause').css('display','none')" checked = "true" />是　
                                    <input onclick="displaySelect()" name="faq_info" type="radio" value="0"  />否
                                    <input type="hidden" name="game_type_id" value="<?php echo ($_GET['game_type_id']); ?>" />
                                    <select style="display:none;" id="cause" name="faq_opinion" onchange="checkOther($(this).val())">
                                    <?php if(is_array($faqOpinion)): foreach($faqOpinion as $k=>$vo): ?><option value="<?php echo ($k); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </p>
                                <p><input type="image" src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/images/input_14.gif" /></p>
                            </form>
                            </div>
                            <div class="tion_03" id="tion_03_2" style="display:none;">
                                <h2 style="padding:20px 0; font-weight:normal; color:#666">感谢您对我们工作的支持！</h2>
                            </div>
                            <div class="tion_03" id="tion_03_3" style="display:none;">
                                <h2 style="text-align:center;padding:20px 0;">非常感谢您对于我们工作的评价，您还可以通过 <a href="<?php echo U('Question/index');?>">提交问题</a> 给我们客服，以获得满意的答案。 </h2>
                            </div>
                            <div class="tion_04">
                                <h2 style="text-align:left">相关问题:</h2>
                                <ul>
                                    <?php if(is_array($relatedFaq)): foreach($relatedFaq as $key=>$list): ?><li><a href="<?php echo U("/Faq/show/game_type_id/$gameTypeId/Id/$list[Id]");?>"><?php echo (stripcslashes($list["question"])); ?></a></li><?php endforeach; endif; ?>
                                </ul>
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