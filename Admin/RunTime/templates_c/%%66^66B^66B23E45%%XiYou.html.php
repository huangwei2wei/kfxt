<?php /* Smarty version 2.6.26, created on 2013-04-11 16:27:17
         compiled from ActionGame_OperatorTools/Notice/XiYou.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ActionGame_OperatorTools/Notice/XiYou.html', 22, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>

<fieldset>
  <legend>公告查询 [<a href="<?php echo $this->_tpl_vars['URL_noticeAdd']; ?>
">添加</a>]</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">ID</th>
        <th scope="col">标题(滚动内容)</th>
        <th scope="col">开始时间</th>
        <th scope="col">结束时间</th>
        <th scope="col">相隔(秒)</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['message']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['startTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['endTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['intervalTime']; ?>
</td>
        <td>
        	[<a onclick="return confirm('确定删除？');" href="<?php echo $this->_tpl_vars['list']['URL_del']; ?>
">删除</a>]
        	[<a href='<?php echo $this->_tpl_vars['list']['URL_edit']; ?>
'>编辑</a>]
        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
<div style="text-align:right"><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>
<?php endif; ?>