<?php /* Smarty version 2.6.26, created on 2013-04-07 15:59:38
         compiled from ActionGame_MasterTools/LockAccount/SanFen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_MasterTools/LockAccount/SanFen.html', 34, false),)), $this); ?>
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
	
	
</script>
<fieldset>
	<legend>
   		封号记录 
        [<a href="<?php echo $this->_tpl_vars['URL_lockAccountLocalLog']; ?>
">本地日志数据</a>]
    	[<a href="<?php echo $this->_tpl_vars['URL_lockAccountInGame']; ?>
">游戏内生效数据</a>]
        [<a href="<?php echo $this->_tpl_vars['URL_lockAccountAdd']; ?>
">增加封号</a>]
    </legend>
	
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">玩家ID</th>
    <th scope="col">账号</th>
    <th scope="col">昵称</th>
    <th scope="col">原因</th>
    <th scope="col">操作描述</th>
    <th scope="col">操作人</th>
    <th scope="col">操作时间</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['game_user_id']; ?>
</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['game_user_account'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无记录</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无记录</font>')); ?>
</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['game_user_nickname'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无记录</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无记录</font>')); ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['cause']; ?>
</td>
    <td>
    	<?php if ($this->_tpl_vars['list']['startTime']): ?>
    	从<?php echo $this->_tpl_vars['list']['startTime']; ?>

    	<?php endif; ?>
    
    	<?php if ($this->_tpl_vars['list']['sub_type'] == 0): ?>
    		封号至 <?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['endTime'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无记录</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无记录</font>')); ?>

        <?php elseif ($this->_tpl_vars['list']['sub_type'] == 1): ?>
        	解除封号
        <?php else: ?>
            <?php echo $this->_tpl_vars['list']['sub_type']; ?>

        <?php endif; ?>
    </td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['user_id'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无记录</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无记录</font>')); ?>
</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无记录</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无记录</font>')); ?>
</td>
    <td>
    	<?php if ($this->_tpl_vars['list']['sub_type'] != 1): ?>
        [<a onclick="return confirm('确定解除？')" href="<?php echo $this->_tpl_vars['list']['URL_del']; ?>
">解除</a>]
        <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>

    <div style="float:right"><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>
<?php endif; ?>