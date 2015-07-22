<?php /* Smarty version 2.6.26, created on 2013-04-11 17:57:59
         compiled from ActionGame_OperatorTools/NoticeEdit/FHJZ.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_OperatorTools/NoticeEdit/FHJZ.html', 49, false),array('modifier', 'htmlspecialchars', 'ActionGame_OperatorTools/NoticeEdit/FHJZ.html', 74, false),array('modifier', 'date_format', 'ActionGame_OperatorTools/NoticeEdit/FHJZ.html', 83, false),array('function', 'html_radios', 'ActionGame_OperatorTools/NoticeEdit/FHJZ.html', 110, false),)), $this); ?>
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
	$.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
	$("#message").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"公告标题不能为空,请确认"});
	$("#interval").formValidator({onshow:"请输入时间间隔",oncorrect:"输入正确"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"时间间隔不正确"});
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
	obj.val(obj.val()+'<a href="event:请输入地址">请输入内容</a>');
	view()
}
function view(){
	$('#view').html($('#message').val());
}
</script>
<fieldset>
<legend>编辑公告</legend>
<form action="" method="post" id="form">
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
<input type="hidden" name="id" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['selected']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
" />
    	    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th scope="row">标题</th>
        <td><input type="text" class="text" name="title" value="<?php echo $this->_tpl_vars['info']['title']; ?>
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
        	<textarea name="message" id="message" style="width:600px; height:200px;" onkeyup="view();"><?php echo ((is_array($_tmp=$this->_tpl_vars['info']['content'])) ? $this->_run_mod_handler('htmlspecialchars', true, $_tmp) : smarty_modifier_htmlspecialchars($_tmp)); ?>
</textarea><div id="messageTip"></div>
            <div>
            	<input class="btn-blue" type="button" value="预览" onclick="view();" />
                <div id="view"></div>
            </div>
         </td>
      </tr>
      <tr>
        <th scope="row">开始时间</th>
        <td><input type="text" class="text" name="startTime" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['startTime']/1000)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['startTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
        </td>
      </tr>
      <tr>
        <th scope="row">结束时间</th>
        <td><input type="text" class="text" name="endTime" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['endTime']/1000)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=$this->_tpl_vars['info']['endTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
      </tr>
      <tr>
        <th scope="row">时间间隔</th>
        <td><input type="text" class="text" name="intervalTime" value="<?php echo $this->_tpl_vars['info']['intervalTime']; ?>
" id="interval" /> 毫秒<div id="intervalTip"></div></td>
      </tr>
      
      
	 <tr>
        <th scope="row">Url</th>
        <td><input style="width:340px;" ="text" class="text" name="url" value="<?php echo $this->_tpl_vars['info']['url']; ?>
"/> </td>
      </tr>
      <tr>
        <th scope="row">是否显示</th>
        <td>
        <?php if ($this->_tpl_vars['list']['isShow']): ?>
            <input type="radio" value='false' name='isShow' /> 否
        	<input type="radio" value='true' name='isShow' checked="checked"/> 是
		<?php else: ?>
		    <input type="radio" value='false' name='isShow' checked="checked"/> 否
        	<input type="radio" value='true' name='isShow'/> 是
		<?php endif; ?>
        	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['isShow'],'name' => 'isShow','selected' => ((is_array($_tmp=@$this->_tpl_vars['info']['isShow'])) ? $this->_run_mod_handler('default', true, $_tmp, false) : smarty_modifier_default($_tmp, false))), $this);?>

		</td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" name='subbutton' value="提交" /></th>
      </tr>
    </table>
</form>
</fieldset>
<?php endif; ?>