<?php /* Smarty version 2.6.26, created on 2013-04-07 10:18:18
         compiled from Index/Top.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'Index/Top.html', 8, false),array('modifier', 'default', 'Index/Top.html', 43, false),)), $this); ?>
<html>
<head>
<title>管理页面 </title>
<meta http-equiv=Content-Type content=text/html;charset=utf8 />
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.js"></script>
<script language="JavaScript">
function logout(){
	if (confirm("<?php echo ((is_array($_tmp='CONFIRMLOGOUT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
")){
		parent.location.href="<?php echo $this->_tpl_vars['url']['Index_LoginOut']; ?>
";
	}
	return false;
}

function onLine(){
	$.getJSON("<?php echo $this->_tpl_vars['url']['Index_SetOnline']; ?>
",
		  function(data){
			  $("#mail_total").html(data.data.mail_total);
			  $("#mail_not_red").html(data.data.mail_not_read);
			  $("#incomplete_order_num").html(data.data.incomplete_order_num);
		  }
	);
}

function moudle(curBtn){
	//$('#moudle_btn :button').attr('class','btn-blue');
	//curBtn.attr('class','btn-red');
	//parent.leftFrame.location.href=curBtn.attr('url');
	window.open(curBtn.attr('url'),'','');
}

function changeMoudle(obj){
	var text = obj.find('option:selected').text(); // 选中文本
	var url = obj.val(); // 选中值
	if(url != ''){
		$('#moudle_btn :button').attr('class','btn-blue');
		parent.leftFrame.location.href=url;
		parent.document.title='SEVICE-'+text;
	}
}

$(function(){
	onLine();
	var btn = $('#model_<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['value'])) ? $this->_run_mod_handler('default', true, $_tmp, 'Default') : smarty_modifier_default($_tmp, 'Default')); ?>
');
	btn.attr('class','btn-red');
	parent.document.title='SEVICE-'+btn.val();
})

//每30秒刷新一次
setInterval('onLine()', 1000*30);
</script>
<style type="text/css">
body,td,th {font-family: Verdana, Geneva, sans-serif;font-size: 12px;}
body { margin:0px; padding:0px;}
a {font-size: 12px;color: #09F;}
a:link {text-decoration: none;color: #06F;}
a:visited {text-decoration: none;color: #06F;}
a:hover {text-decoration: underline;color: #000000;}
a:active {text-decoration: none;color: #06F;}
table{margin:2px;border-collapse: collapse; border:0px;}
ul li{display:inline;padding:2px;}
input{
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 2px;
	font-size: 12px;
}
.btn {
	display: inline-block;
	padding: 5px 10px;
	color: #777 !important;
	text-decoration: none;
	font-weight: bold;
	font-size: 12px;
	font-family: Verdana, Tahoma, Arial, sans-serif;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	/*text-shadow: 0 1px 1px rgba(255,255,255,0.9);*/
	position: relative;
	cursor: pointer;
	border:1px solid #ccc !important;
	background:#fff url("<?php echo $this->_tpl_vars['__IMG__']; ?>
/default/btn-overlay.png") repeat-x !important;
}
.btn:hover, .btn:focus, .btn:active {
	outline:medium none;
	border:1px solid #329ECC !important;
	opacity:0.9;
	-khtml-opacity: .9;
	-moz-opacity: 0.9;
	-moz-box-shadow:0 0 5px rgba(82, 168, 236, 0.5);
	-webkit-box-shadow: 0 0 5px rgba(82, 168, 236, 0.5);
	box-shadow: 0 0 5px rgba(82, 168, 236, 0.5);
}

.btn-green {
	color: #fff !important;
	/*text-shadow: 0 1px 1px rgba(0,0,0,0.25);*/
	border:1px solid #749217 !important;
	background-color: #6AB620 !important;
}
.btn-green:hover, .btn-green:focus, .btn-green:active {
	-moz-box-shadow:0 0 5px rgba(116, 146, 23, 0.9);
	-webkit-box-shadow: 0 0 5px rgba(116, 146, 23, 0.9);
	box-shadow: 0 0 5px rgba(116, 146, 23, 0.9);
	border:1px solid #749217 !important;
}

.btn-blue {
	color: #fff !important;
	/*text-shadow: 0 1px 1px rgba(0,0,0,0.25);*/
	border:1px solid #2D69AC !important;
	background-color: #3C6ED1 !important;
}
.btn-blue:hover, .btn-blue:focus, .btn-blue:active {
	-moz-box-shadow:0 0 5px rgba(71, 131, 243, 0.9);
	-webkit-box-shadow:0 0 5px rgba(71, 131, 243, 0.9);
	box-shadow: 0 0 5px rgba(71, 131, 243, 0.9);
	border:1px solid #2D69AC !important;
}

.btn-red {
	color: #fff !important;
	/*text-shadow: 0 1px 1px rgba(0,0,0,0.25);*/
	border:1px solid #AE2B2B !important;
	background-color: #D22A2A !important;
}
.btn-red:hover, .btn-red:focus, .btn-red:active {
	-moz-box-shadow:0 0 5px rgba(174, 43, 43, 0.9);
	-webkit-box-shadow:0 0 5px rgba(174, 43, 43, 0.9);
	box-shadow: 0 0 5px rgba(174, 43, 43, 0.9);
	border:1px solid #AE2B2B !important;
}

.btn-special {
	font-size:110%;
	width: 210px;
}
</style>
</head>          
<body style="border-bottom:5px solid #06508b;height:47px;background:url('<?php echo $this->_tpl_vars['__IMG__']; ?>
/default/top_bg.gif'); padding-top:10px;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td rowspan="2"><img style="padding-left:20px;" src="<?php echo $this->_tpl_vars['__IMG__']; ?>
/default/top_logo.gif" /></td>
        <td valign="bottom"><font color="#FF0000"><?php echo ((is_array($_tmp='PLEASEUSE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <a target="_blank" href="http://download.mozilla.org/?product=firefox-3.6.13&os=win&lang=zh-CN">FireFox[火狐]</a> <?php echo ((is_array($_tmp='ORUSE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <a href="http://www.google.com/chrome/eula.html?hl=zh_cn&brand=CHMA&utm_campaign=zh_cn&utm_source=zh_cn-ha-apac-zh_cn-bk&utm_medium=ha&installdataindex=homepagepromo" target="_blank">google[chrome]</a> <?php echo ((is_array($_tmp='BROWSER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
,<?php echo ((is_array($_tmp='NOWFOR')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <b style="color:#09F">IE</b> <?php echo ((is_array($_tmp='SUPPORTNOTWELL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>
              </td>
        <td align="right" valign="bottom">
        	<ul style="padding:0px; margin:0px; padding-right:10px;">
                <li><?php echo $this->_tpl_vars['userClass']['_nickName']; ?>
[<?php echo $this->_tpl_vars['userClass']['_userName']; ?>
] <?php echo ((is_array($_tmp='HELLOWORD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</li>
                <li><a target="main" href="<?php echo $this->_tpl_vars['url']['Index_Right']; ?>
" style="color:#09F"><?php echo ((is_array($_tmp='PERCONALPROFILE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a></li>
                <li><a href="javascript:void(0)" onClick="logout();" style="color:#09F"><?php echo ((is_array($_tmp='SECURITYLOGOUT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a></li>
          </ul>    
        </td>
      </tr>
      <tr>
        <td align="left" id="moudle_btn">
        	<?php if ($this->_tpl_vars['game']): ?>
        	<select onChange="changeMoudle($(this))">
            	<option value="admin.php?c=Index&a=Left&value=">-请选择-</option>
            	<?php $_from = $this->_tpl_vars['game']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
            	<option value="<?php echo $this->_tpl_vars['list']['url']; ?>
"><?php echo $this->_tpl_vars['list']['name']; ?>
</option>
            	<?php endforeach; endif; unset($_from); ?>
            </select>
            <?php endif; ?>
        	<?php $_from = $this->_tpl_vars['moudelMenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
            	<input type="button" id="model_<?php echo $this->_tpl_vars['list']['value']; ?>
" class="btn-blue" url="<?php echo $this->_tpl_vars['list']['url']; ?>
" onClick="moudle($(this))" value="<?php echo $this->_tpl_vars['list']['name']; ?>
" />
            <?php endforeach; endif; unset($_from); ?>
        </td>
        <td align="right" valign="bottom">
        	<ul style="padding:0px; margin:0px; padding-right:10px; ">
                <li>
                	<b><?php echo ((is_array($_tmp='MYTASK')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</b>:  <?php echo ((is_array($_tmp='PENDING')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 (<a id="incomplete_order_num" target="main" href="<?php echo $this->_tpl_vars['url']['MyTask_Index']; ?>
" style="color:#FF0000"></a>)
                				<?php echo ((is_array($_tmp='UNREADMSG')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 (<a id="mail_not_red" target="main" href="<?php echo $this->_tpl_vars['url']['NoReadUser_MailIndex']; ?>
" style="color:#FF0000"></a>)
                                <?php echo ((is_array($_tmp='ALLMSG')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
(<a id="mail_total" target="main" href="<?php echo $this->_tpl_vars['url']['User_MailIndex']; ?>
" style="color:#FF0000"></a>)
                </li>
            </ul>
        </td>
      </tr>
    </table>
</body>
</html>