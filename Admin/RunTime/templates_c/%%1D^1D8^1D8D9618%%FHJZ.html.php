<?php /* Smarty version 2.6.26, created on 2013-04-11 17:27:45
         compiled from ActionGame_OperatorTools/NoticeAdd/FHJZ.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'htmlspecialchars', 'ActionGame_OperatorTools/NoticeAdd/FHJZ.html', 100, false),array('modifier', 'date_format', 'ActionGame_OperatorTools/NoticeAdd/FHJZ.html', 109, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
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
			return false;
		},
	});
	$("#message").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"公告不能为空,请确认"});
	$("#intervalTime").formValidator({onshow:"请输入时间间隔",oncorrect:"输入正确"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"时间间隔不正确"});
	$("#title").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"公告标题不能为空,请确认"});

	
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
	var obj = $('#message');
	obj.val(obj.val()+font1+b1+em1+'请输入文字'+em2+b2+font2);
	view()
}
function linkAdd(){
	var obj = $('#message');
	obj.val(obj.val()+'<a target="_blank" href="event:请输入地址">请输入内容</a>');
	view()
}
function view(){
	$('#view').html($('#message').val());
}
</script>
<fieldset>
<legend>编辑公告</legend>
<form action="" method="post" id="form">
    	    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th scope="row">标题</th>
        <td><input type="text" class="text" name="title" value="<?php echo $this->_tpl_vars['_POST']['title']; ?>
" id="title" /> <div id="titleTip"></div></td>
      </tr>
      
      <tr>
        <th scope="row">公告</th>
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
        	<textarea name="message" id="message" style="width:600px; height:200px;" onkeyup="view();"><?php echo ((is_array($_tmp=$this->_tpl_vars['_POST']['Message'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : smarty_modifier_htmlspecialchars($_tmp)); ?>
</textarea><div id="messageTip"></div>
            <div>
            	<input class="btn-blue" type="button" value="预览" onclick="view();" />
                <div id="view"></div>
            </div>
         </td>
      </tr>
      <tr>
        <th scope="row">开始时间</th>
        <td><input type="text" class="text" name="startTime" value="<?php echo $this->_tpl_vars['_POST']['beginTime']; ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=$this->_tpl_vars['dataList']['Start_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
        </td>
      </tr>
      <tr>
        <th scope="row">结束时间</th>
        <td><input type="text" class="text" name="endTime" value="<?php echo $this->_tpl_vars['_POST']['endTime']; ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=$this->_tpl_vars['dataList']['End_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
      </tr>
      <tr>
        <th scope="row">时间间隔</th>
        <td><input type="text" class="text" name="intervalTime" value="<?php echo $this->_tpl_vars['_POST']['intervalTime']; ?>
" id="intervalTime" /> 秒<div id="intervalTimeTip"></div></td>
      </tr>
      
      <tr>
        <th scope="row">Url</th>
        <td><input type="text" class="text" name="url" value="<?php echo $this->_tpl_vars['_POST']['url']; ?>
"/> </td>
      </tr>
      <tr>
        <th scope="row">是否显示</th>
        <td>
        	<input type="radio" value='false' name='isShow' checked="checked"/> 否
        	<input type="radio" value='true' name='isShow'/> 是
        	
		</td>
      </tr>
      
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" name='subbutton' value="提交" /></th>
      </tr>
    </table>
</form>
</fieldset>
<?php endif; ?>