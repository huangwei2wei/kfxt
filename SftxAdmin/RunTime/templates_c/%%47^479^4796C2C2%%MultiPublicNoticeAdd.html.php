<?php /* Smarty version 2.6.26, created on 2012-09-13 17:51:07
         compiled from GmSftx/MultiPublicNoticeAdd.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'GmSftx/MultiPublicNoticeAdd.html', 28, false),array('modifier', 'strtotime', 'GmSftx/MultiPublicNoticeAdd.html', 34, false),)), $this); ?>
<form action="" method="post" id="form">
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
<script language="javascript">
$(function(){
	$.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
	$("#title").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"公告标题不能为空,请确认"});
	$("#content").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"公告内容不能为空,请确认"});
	$("#interval").formValidator({onshow:"请输入时间间隔",oncorrect:"输入正确"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"时间间隔不正确"});
})
</script>
<fieldset>
<legend>新增游戏公告</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="row">公告标题</th>
        <td><input type="text" class="text" name="title" id="title" /><div id="titleTip"></div></td>
      </tr>
      <tr>
        <th scope="row">公告内容</th>
        <td><textarea name="content" cols="40" rows="8" id="content"></textarea><div id="contentTip"></div></td>
      </tr>
      <tr>
        <th scope="row">开始时间</th>
        <td><input type="text" class="text" name="begin" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
        	当前系统时间： <font color="#FF0000"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</font>
        </td>
      </tr>
      <tr>
        <th scope="row">结束时间</th>
        <td><input type="text" class="text" name="end" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('strtotime', true, $_tmp, '+1 week') : smarty_modifier_strtotime($_tmp, '+1 week')); ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('strtotime', true, $_tmp, '+1 week') : smarty_modifier_strtotime($_tmp, '+1 week')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
      </tr>
      <tr>
        <th scope="row">时间间隔</th>
        <td><input type="text" class="text" name="interval" id="interval" /> 分钟<div id="intervalTip"></div></td>
      </tr>
      <tr>
        <th scope="row">URL</th>
        <td><input type="text" class="text" name="url" size="80" /></td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="提交" /></th>
      </tr>
    </table>
</fieldset>
</form>
