<?php /* Smarty version 2.6.26, created on 2013-04-09 16:54:13
         compiled from GmSftx/EmailList.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'GmSftx/EmailList.html', 26, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<fieldset>
  <legend>邮件列表  [<a href="<?php echo $this->_tpl_vars['URL_sendMultiEmail']; ?>
">多服发邮件</a>]
    </legend>
<table width="100%" border="0" cellpadding="3">
    <tr>
     	<th scope="col">Id</th>
        <th scope="col">邮件标题</th>
        <th scope="col">邮件内容</th>
        <th scope="col">创建时间</th>
        <th scope="col">开始时间</th>
        <th scope="col">结束时间</th>
        <th scope="col">状态</th>
        <th scope="col">间隔时间（天）</th>
        <th scope="col">操作</th>
    </tr>
    <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
    <tr>
   		<td><?php echo $this->_tpl_vars['list']['id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['content']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['createAt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['begin'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
     	<td><?php echo $this->_tpl_vars['list']['status']; ?>
</td>
     	<td><?php echo $this->_tpl_vars['list']['intervals']; ?>
</td>
     	<td><a href="<?php echo $this->_tpl_vars['list']['URL_delEmail']; ?>
">删除</a></td>
    </tr>  
    <?php endforeach; else: ?>
        <tr>
          <td align="center" colspan="10">暂无数据</td>
        </tr>
    <?php endif; unset($_from); ?>
</table>
<div><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>
<?php endif; ?>