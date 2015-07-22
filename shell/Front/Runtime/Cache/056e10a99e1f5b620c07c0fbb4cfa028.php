<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 优网玩客服守则</title>
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>优网玩客服守则</div>
				

				<div class="servicewen">
					<p>
                    <b>一、客服的定义</b><br/>
                    　　客服又称游戏客户服务人员、GAME MASTER（GM）。客服扮演的角色是：用户在使用优玩网游戏各项服务的协助者、保障游戏正常环境的管理者、用户与公司相关职能部门之间的桥梁。<br/>
                    <b>二、服务承诺</b><br/>
                    　　1.	提供7*24小时的优质服务，快速有效地回应与解决用户问题；<br/>
                    　　2.	提供不超过48小时投诉处理服务；<br/>
                    　　3.	任何时候都秉承我们的服务理念：用心服务、感动客户。<br/>
                    <b>三、客服的职责：</b> <br/>
                    　　1、提供24小时多元化的、双向沟通服务手段（客服电话、在线答疑、自助FAQ、传真、电子邮件），协助用户正常、顺利地进行游戏。<br/>
                    　　2、通过游戏管理，维护游戏世界正常秩序（优玩网服务条款），维护用户的合法权利，让用户愉快地享受游戏。<br/>
					　　3、监督线上游戏秩序，记录违规行为，对线上用户的行为进行正确宣导。如果用户在游戏中的行为违反《优玩网服务条款》的相关规定，客服在查实后将对违规帐号进行相应的处理。<br/>
					　　4、找出游戏内的特殊问题以及游戏中出现的BUG和不足，记录用户反馈，及时提交给相关部门以寻求解决之道。<br/>
                    　　5、监测服务器状况，及时将服务器异常状况汇报相关部门进行处理并填写记录。<br/>
                    　　6、为保证在线游戏顺利进行而开展的其它工作。<br/>
                    　　7、协助其他部门做好线上活动的执行、监督和维护工作。<br/>
                   <b> 四、客服的工作规范：</b><br/>
					　　1、	客服帐号命名规则都是以客服编号进行，例如“客服001，客服002，客服003……等形式，不得向用户透露姓名、联系方式等个人信息。<br/>
                    　　2、	严格遵守用户资料保密制度，不对外透露用户姓名、电子邮箱等个人登记信息，除非得到用户授权或相关的法律程序要求提供用户的个人资料。<br/>
                    　　3、	在游戏中保持绝对中立，不涉及用户之间的纠纷。<br/>
                    　　4、	不得以任何手段协助用户以非正常的方式进行游戏。<br/>
                    　　5、	不得以任何方式泄漏游戏机制，比如任务情况、怪物状态等。<br/>
                    　　6、	不得在游戏中与用户进行非工作性质的对话，即：聊天或者其它交流方式。<br/>
                    　　7、	不得无故对用户帐号进行停权、禁言，不得影响用户的正常游戏及驱离游戏。<br/>
                    　　8、	严禁与用户发生争吵，严禁对用户进行人身攻击，不能推托责任。对于工作及程序方面带给用户的不便，应给予真诚道歉；<br/>
                    　　9、	对于用户提出来的问题，如果暂时无法解决，也必须先给用户回复，说明事由。有答案后应立即给用户准确答复；<br/>
                    　　10、	用户以下的行为将会被视为违规、并会被处以：暂时或永久性禁言、暂时或永久性帐号停权、强制离线等处罚：<br/>
                    　　　　a)	在公共频道上有不断吵闹、重复发言多次、连续打广告、恶意刷屏等侵犯大多数玩家正常游戏进行的；<br/>
                    　　　　b)	在公用频道或公开场合上使用脏话或侮辱性语言，客服宣导无效的。<br/>
                    　　　　c)	直接或间接利用程序BUG或侵害其他用户权益，经管理人员查知或其他用户举报后查证属实的。<br/>
                    　　　　d)	宣传外挂,使用外挂程序的。<br/>
                    　　　　e)	不服从客服人员安排，辱骂GM，情节恶劣的。<br/>
                    　　　　f)	用户在游戏中使用有任何政治意图或侮辱性文字的,或冒充系统和工作人员引起用户误会的名字的；<br/>
                    　　　　g)	其它违反用户守则的行为.<br/>
                    <b>五、客服的工作权限：</b><br/>
					　　1、禁言：强制暂停违规用户的在线发言功能，使违规用户无法在游戏中的公共频道发言或与其他用户对话，直到此次处罚到期或是永久禁言。<br/>
                    　　2、帐号停权：强制暂停违规用户帐号登入游戏的权力，直到此次处罚到期或是永久停权。 <br/>
                    　　3、强制离线：强制让违规用户离开当前游戏，结束用户游戏程序的执行。<br/>
                    　　
                    <br/><font color="red">以上客服守则的各项条款，优玩网拥有最终的解释权。如有与法律条款有相抵触的内容，以法律条款为准。</font><br/>

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