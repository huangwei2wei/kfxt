<?php /* Smarty version 2.6.26, created on 2013-04-07 16:14:33
         compiled from ActionGame_MasterTools/LockAccountAdd/SanFen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_MasterTools/LockAccountAdd/SanFen.html', 34, false),array('modifier', 'strtotime', 'ActionGame_MasterTools/LockAccountAdd/SanFen.html', 42, false),)), $this); ?>
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
<script language="javascript">
$(function(){
	$.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
	$("#cause").formValidator({onshow:"请输入操作原因",oncorrect:"操作原因正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入操作原因"},onerror:"操作原因不能为空"});
	$("#server_id").formValidator({onshow:"服务器ID不存在",oncorrect:"请重新输入服务器ID"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"服务器数值不正确"});
	$("#players").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"两边不能有空格"},onerror:"玩家不能为空,请确认"});
})
</script>
<fieldset>
	<legend>添加封号  [<font color="#0000FF">此操作无须审核</font>]</legend>
<form action="" method="post" id="form" >
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th scope="row">操作原因</th>
        <td><textarea name="cause" id="cause" cols="60" rows="5"></textarea><div id="causeTip"></div></td>
      </tr>
      <tr>
        <th scope="row">封号玩家<br/><font color="#FF0000">（一行一个）</font></th>
        <td>
            <div>
                <label><input type="radio" name="playerType" checked="checked" value="1" />玩家ID</label>
                <label><input type="radio" name="playerType" value="2" />账号</label>
                <label><input type="radio" name="playerType" value="3" />昵称</label>
            </div>
            <div>
        		<textarea name="players" id="players" cols="60" rows="8"><?php echo ((is_array($_tmp=@$this->_tpl_vars['players'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</textarea>
                <div id="playersTip"></div>
			</div>
        </td>
      </tr>
      <tr>
        <th scope="row">封号结束时间</th>
        <td>
        	<input type="text" class="text" name="endTime" value="<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('strtotime', true, $_tmp, '+1 day') : smarty_modifier_strtotime($_tmp, '+1 day')); ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('strtotime', true, $_tmp, '+1 day') : smarty_modifier_strtotime($_tmp, '+1 day')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
        </td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" name="sbm" value="提交" /></th>
      </tr>
    </table>
</form>
</fieldset>
<?php endif; ?>