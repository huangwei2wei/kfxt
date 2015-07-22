<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>优玩网 - 客服中心</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/css/page.css" />
<script src="__PUBLIC__/<?php echo (APP_NAME); ?>/<?php echo (C("DEFAULT_THEME")); ?>/js/common.js"></script>
<script src="__PUBLIC__/common/js/libs/jquery.js" language="javascript"></script>
<script type="text/javascript">
//以下是身份证检验
var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",
        	21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",
        	33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",
        	42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",
        	51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",
        	63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"
           };

function checkCard()
{
	var card = document.getElementById('host_number').value;
	//是否为空
	if(card === '')
	{
		alert('请输入身份证号，身份证号不能为空');
		document.getElementById('host_number').focus;
		return false;
	}
	//校验长度，类型
	if(isCardNo(card) === false)
	{
		alert('您输入的身份证号码不正确，请重新输入');
		document.getElementById('host_number').focus;
		return false;
	}
	//检查省份
	if(checkProvince(card) === false)
	{
		alert('您输入的身份证号码不正确,请重新输入');
		document.getElementById('host_number').focus;
		return false;
	}
	//校验生日
	if(checkBirthday(card) === false)
	{
		alert('您输入的身份证号码生日不正确,请重新输入');
		document.getElementById('host_number').focus();
		return false;
	}
	//检验位的检测
	if(checkParity(card) === false)
	{
		alert('您的身份证校验位不正确,请重新输入');
		document.getElementById('host_number').focus();
		return false;
	}
	alert('OK');
	return true;
};


//检查号码是否符合规范，包括长度，类型
isCardNo = function(card)
{
	//身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X
	var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;
	if(reg.test(card) === false)
	{
		return false;
	}

	return true;
};

//取身份证前两位,校验省份
checkProvince = function(card)
{
	var province = card.substr(0,2);
	if(vcity[province] == undefined)
	{
		return false;
	}
	return true;
};

//检查生日是否正确
checkBirthday = function(card)
{
	var len = card.length;
	//身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字
	if(len == '15')
	{
		var re_fifteen = /^(\d{6})(d{2})(\d{2})(d{2})(\d{3})$/; 
		var arr_data = card.match(re_fifteen);
		var year = arr_data[2];
		var month = arr_data[3];
		var day = arr_data[4];
		var birthday = new Date('19'+year+'/'+month+'/'+day);
		return verifyBirthday('19'+year,month,day,birthday);
	}
	//身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X
	if(len == '18')
	{
		var re_eighteen = /^(\d{6})(d{4})(\d{2})(d{2})(\d{3})([0-9]|X)$/;
		var arr_data = card.match(re_eighteen);
		var year = arr_data[2];
		var month = arr_data[3];
		var day = arr_data[4];
		var birthday = new Date(year+'/'+month+'/'+day);
		return verifyBirthday(year,month,day,birthday);
	}
	return false;
};

//校验日期
verifyBirthday = function(year,month,day,birthday)
{
	var now = new Date();
	var now_year = now.getFullYear();
	//年月日是否合理
	if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)
	{
		//判断年份的范围（3岁到100岁之间)
		var time = now_year - year;
		if(time >= 3 && time <= 100)
		{
			return true;
		}
		return false;
	}
	return false;
};

//校验位的检测
checkParity = function(card)
{
	//15位转18位
	card = changeFivteenToEighteen(card);
	var len = card.length;
	if(len == '18')
	{
		var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
		var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
		var cardTemp = 0, i, valnum; 
		for(i = 0; i < 17; i ++) 
		{ 
			cardTemp += card.substr(i, 1) * arrInt[i]; 
		} 
		valnum = arrCh[cardTemp % 11]; 
		if (valnum == card.substr(17, 1)) 
		{
			return true;
		}
		return false;
	}
	return false;
};

//15位转18位身份证号
changeFivteenToEighteen = function(card)
{
	if(card.length == '15')
	{
		var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); 
		var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); 
		var cardTemp = 0, i;   
		card = card.substr(0, 6) + '19' + card.substr(6, card.length - 6);
		for(i = 0; i < 17; i ++) 
		{ 
			cardTemp += card.substr(i, 1) * arrInt[i]; 
		} 
		card += arrCh[cardTemp % 11]; 
		return card;
	}
	return card;
};
//以上是身份证检验

function selectServer(game_id){
	$('#servers').html('');
	$('<option/>').attr('value',0).text("　　请选择服务器　　").appendTo('#servers'); 
	if(game_id>0){
		$.each(serverLists[game_id],
			function(Id,server_name){ 
				$('<option/>').attr('value',Id).text(server_name).appendTo('#servers');
			}
		);	
	}
}

function chlayer(k){

	if(k == 1)
	{
		var html = '<dl><dt>*充值类型：</dt><dd><input type="radio" name="charge_type" value="1" checked="checked" onclick="orderid(0)"/>充值卡<input type="radio" name="charge_type" value="2" onclick="orderid(1)"/>网上银行<input type="radio" name="charge_type" value="3" onclick="orderid(0)"/>其他</dd></dl>';
       
		$('#ch_layer').html(html);
	}
	else
	{
		$('#ch_layer').html("");
	}
}

function orderid(n){
	if(n == 1){
		var html = '<dl><dt>*其中一次充值网银订单：</dt><dd><input type="text" class="input_01 required" name="charge_order" id="charge_order" /></dd></dl>';
		$('#ch_layers').html(html);
	}else{
		$('#ch_layers').html("");
	}
}

function hslayer(j)
{
	if(j == 1)
	{
		var html = '<dl><dt>*帐号绑定的有效证件号码：</dt><dd><input type="text" class="input_01 required" name="host_number" id="host_number" /></dd></dl>';
        var html = html+' <dl><dt>*身份持有人姓名：</dt><dd><input type="text" class="input_01 required" name="host_username" id="host_username" /></dd></dl>';
		$('#hs_layer').html(html);
	}
	else
	{
		var html = '<dl><dt>*身份证号码：</dt><dd><input type="text" class="input_01 required" name="host_number" id="host_number" /></dd></dl>';
		var html = html+' <dl><dt>*真实姓名：</dt><dd><input type="text" class="input_01 required" name="host_username" id="host_username" /></dd></dl>';
		$('#hs_layer').html(html);
	}
}


function sumbits(){
	
	if($('#game_type').val()==''){
		alert('请先选择游戏');
		return false;
	}
	
	if($("#servers").val() <= 0){
		alert('服务器不能为空');
		return false;
	}
	
	if($("#account").attr("value") == ""){ 
		alert('帐号不能为空');
		return false;
	} 


	if($("#game_name").attr("value") == ""){ 
		alert('游戏昵称不能为空');
		return false;
	} 
	if($("#account_area").attr("value") == ""){ 
		alert('注册帐号地区不能为空');
		return false;
	} 

	if($("#often_passsword").attr("value") == ""){ 
		alert('曾经使用密码不能为空');
		return false;
	}
	
	if($(":radio[name=charge][checked]").val() == 1){ 
		
		if($("#charge_order").attr("value") == ""){ 
			alert('充值网银订单不能为空');
			return false;
		} 
	} 
	
	if($(":radio[name=host][checked]").val() == 1){ 
		if($("#host_number").attr("value") == ""){ 
			alert('证件号码不能为空');
			return false;
		} 
		if($("#host_username").attr("value") != ""){ 
			return checkCard();
			//return false;
		} 
	} 
	
	if($("#email").attr("value") == "" && $("#telphone").attr("value") == ""){ 
		alert('请填写一项申诉结果获取途径');
		return false;
	} else{
		if($("#email").attr("value") != ""){
			 if(!$("#email").attr("value").match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)){
    		 	alert("邮箱格式不正确！请重新输入");
				$("#email").focus();
			 	return false;
			}
			
		}
	}
	
}
</script>
</head>

<body>
<div id="bgtainer">
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
				<div class="pquantitle"><span><a href="javascript:history.go(-1);">返回上一页</a></span>帐号申诉</div>
					
				<div class="poesrzil">  
					<form name="myform" id="myform"  action="__URL__/send" method="post" onsubmit="return sumbits();">
   
					<dl><dt>*游戏产品：</dt><dd>
						<select name="game_type" id="game_type" onchange="selectServer(this.value)" class="required" msg="游戏产品">
                            <option selected="selected" value="">　　请选择游戏　　</option>
                            <?php if(is_array($game_type)): $i = 0; $__LIST__ = $game_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$game): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($game["Id"]); ?>">　　<?php echo ($game["name"]); ?>　　</option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select><span id="c0" style="color:#F00;"></span>
                    </dd></dl>
                    
                    <dl><dt>*服务器：</dt><dd>
                    	<select class="required" id="servers" name="servers">
                        	<option value="0">　　请选择服务器　　</option>
                        </select>
                    </dd></dl>        

                     <div style="color:#F00;font-size:12px; padding-left:100px;">*如在多个服务器建立角色，请选择其中一个。</div>

					<dl><dt>*帐号：</dt><dd>
                    
						<input type="text" class="input_01 required" name="account" id="account" />
                    </dd></dl>
 
					
					<dl><dt>*游戏昵称：</dt><dd>
						<input type="text" class="input_01 required" name="game_name" id="game_name" />
                        
                    </dd></dl>
                    <dl><dt>*帐号注册地区：</dt><dd>
						<input type="text" class="input_01 required" name="account_area" id="account_area" />
                        
                    </dd></dl>
                    <dl><dt>注册初始密码：</dt><dd>
						<input type="text" class="input_01 required" name="start_password" id="start_password" />
                        
                    </dd></dl>
                    <dl><dt>*曾经使用密码：</dt><dd>
						<input type="text" class="input_01 required" name="often_passsword" id="often_passsword" />
                        
                    </dd></dl>
                    <dl><dt>*是否曾经充值：</dt><dd>
						<input type="radio" name="charge" id="charge" value="1" onclick="chlayer(1)" checked="checked"/>是
                        <input type="radio" name="charge" id="charge" value="0" onclick="chlayer(0)"/>否
                    </dd></dl>
                    <span id="ch_layer"> 
                    <dl><dt>*充值类型：</dt><dd>
					<input type="radio" name="charge_type" value="1" checked="checked" onclick="orderid(0)"/>充值卡
                    <input type="radio" name="charge_type" value="2" onclick="orderid(1)"/>网上银行
                    <input type="radio" name="charge_type" value="3" onclick="orderid(0)"/>其他
                        
                    </dd></dl>
                  	</span>
                    <span id="ch_layers"></span>
                    
                    <dl><dt>*帐号是否身份绑定：</dt><dd>
						<input type="radio" name="host" id="host" value="1" onclick="hslayer(1)" checked="checked"/>是
                        <input type="radio" name="host" id="host" value="0" onclick="hslayer(0)"/>否
                        
                    </dd></dl>
                    
                    <span id="hs_layer"> 
                    <dl><dt>*帐号绑定的有效证件号码：</dt><dd>
						<input type="text" class="input_01 required" name="host_number" id="host_number" />
                        
                    </dd></dl>
                    <dl><dt>*身份持有人姓名：</dt><dd>
						<input type="text" class="input_01 required" name="host_username" id="host_username" />
                        
                    </dd></dl>
                    </span>
                    <div style="color:#F00;font-size:12px;">*填写您想通过哪种途径获得申诉结果的途径(其中一项即可)：</div>
                    
                    <dl><dt>电子邮箱：</dt><dd>
						<input type="text" class="input_01 required" name="email" id="email" />
                       
                    </dd></dl>
                    <dl><dt>电话：</dt><dd>
						<input type="text" class="input_01 required" name="telphone" id="telphone" />
                        
                    </dd></dl>
					<dl><dt></dt><dd>
                    	<input type="image" src="/Public/front/default/images/button_78.gif" />
                    	<input type="image" src="/Public/front/default/images/button_45.gif" onclick="javascript:document.myform.reset(); return false;"/>
                    </dd></dl>
                    </form>
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
<script>
var serverLists=<?php echo (json_encode($serverList)); ?>;
</script>
</body>

</html>