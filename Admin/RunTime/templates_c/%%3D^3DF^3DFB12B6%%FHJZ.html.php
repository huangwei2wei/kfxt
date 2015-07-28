<?php /* Smarty version 2.6.26, created on 2013-04-07 14:19:40
         compiled from ActionGame_MasterTools/SendMail/FHJZ.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/SendMail/FHJZ.html', 82, false),array('modifier', 'default', 'ActionGame_MasterTools/SendMail/FHJZ.html', 89, false),array('modifier', 'htmlspecialchars', 'ActionGame_MasterTools/SendMail/FHJZ.html', 117, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/kindeditor/kindeditor.js"></script>
<script language="javascript">
//KE.init({id:'title',imageUploadJson : '<?php echo $this->_tpl_vars['url']['UploadImg_Bulletin']; ?>
',afterCreate:function(id){KE.util.focus(id)}});
$(function(){
	$.formValidator.initConfig({
		formid:"form",
		onerror:function(){return false;},
		onsuccess:function(){
			if($(":checkbox[name='server_ids[]']:checked").attr('value') == undefined){
				alert('请选择服务器！'); return false;
			}
			if(!confirm("确定要提交吗?")){
				return false;
			}
			$('.returnTip').remove();	//去掉旧提示
			$(":checkbox[name='server_ids[]']:checked").each(function(i,n){
					var curLi=$("#server_"+n.value);
					$("#form").ajaxSubmit({
						dataType:'json',
						async : false,    // 设置同步
						data:{server_id:n.value},
						success:function(data){
							var fontColor=(data.status==1)?'#00cc00':'#ff0000';
							curLi.append("<font class='returnTip' color='"+fontColor+"'> "+data.info+"</font>");
						}
					});
			});
			alert('处理完成！');
			return false;
		},
	});
    $("#title").formValidator({onshow:"请输入消息标题",oncorrect:"消息标题正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入消息标题"},onerror:"消息标题不能为空"});
    $("#content").formValidator({onshow:"请输入消息内容",oncorrect:"消息内容正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入消息内容"},onerror:"消息内容不能为空"});
    $("#user").formValidator({onshow:"请输入用户,用','号隔开",oncorrect:"输入正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入用户ID"},onerror:"用户ID不能为空"});
})

function fontStyle(color,b,em){
	var font1='<font color="'+color+'">';
	var font2='</font>';
	var b1='<b>';
	var b2='</b>';
	var em1='<em>';
	var em2='</em>';
	if(!color){
		font1 = font2 = '';
	}
	if(!b){
		b1 = b2 = '';
	}
	if(!em){
		em1 = em2 = '';
	}
	var obj = $('#content');
	obj.val(obj.val()+font1+b1+em1+'请输入文字'+em2+b2+font2);
	view()
}
function linkAdd(){
	var obj = $('#content');
	obj.val(obj.val()+'<a target="_blank" href="请输入地址">请输入内容</a>');
	view()
}
function view(){
	$('#view').html($('#content').val());
}
</script>
<fieldset>
<legend>编辑公告</legend>
<form action="" method="post" id="form">
    	    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th scope="row">账号都 类型：</th>
        <td>
            <select name="userType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['userType'],'selected' => $this->_tpl_vars['_POST']['userType']), $this);?>

            </select>
		</td>
      </tr>
      <tr>
        <th scope="row">发送给以下用户<br/>(用户之间用&quot;,&quot;号隔开)：</th>
        <td>
        <textarea name="user" id="user" style="width:400px; height:100px;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['players'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</textarea>
        <div id="userTip"></div>
        </td>
      </tr>
      <tr>
        <th scope="row">邮件标题：</th>
        <td>
        	<input type="text" class="text" name="title" id='title' value="<?php echo $this->_tpl_vars['_POST']['title']; ?>
" />
        	<div id="titleTip"></div>
        </td>
      </tr>
      <tr>
        <th scope="row">邮件内容：</th>
        <td>
        	<div>
				颜色<select id="color_select">
                	<option value="">默认</option>
                	<option value="FF0000">红色</option>
                    <option value="0000FF">蓝色</option>
                    <option value="00FF00">绿色</option>
                    <option value="FFFF00">黄色</option>
                    <option value="FF00FF">紫色</option>
                </select>
                加粗<input id="b_select" type="checkbox" value="b" />
                下划线<input id="em_select" type="checkbox" value="em" />
                <input class="btn-blue" type="button" value="添加" onclick="fontStyle($('#color_select').val(),$('#b_select').attr('checked'),$('#em_select').attr('checked'));" />
                <input class="btn-blue" type="button" value="添加超链接" onclick="linkAdd();" />
       		</div>
        	<textarea name="content" id="content" style="width:600px; height:200px;" onkeyup="view();"><?php echo ((is_array($_tmp=$this->_tpl_vars['_POST']['content'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : smarty_modifier_htmlspecialchars($_tmp)); ?>
</textarea><div id="contentTip"></div>
            <div>
            	<input class="btn-blue" type="button" value="预览" onclick="view();" />
                <div id="view"></div>
            </div>
         </td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" name='subbutton' value="提交" /></th>
      </tr>
    </table>
</form>
</fieldset>