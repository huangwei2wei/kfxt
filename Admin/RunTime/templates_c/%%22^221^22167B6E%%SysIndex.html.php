<?php /* Smarty version 2.6.26, created on 2013-04-07 10:18:26
         compiled from SysManagement/SysIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncateutf8', 'SysManagement/SysIndex.html', 18, false),)), $this); ?>
<center>
<div>
<a href="<?php echo $this->_tpl_vars['url']['SysManagement_SysSetupCreateCache']; ?>
">生成缓存文件</a>
</div>
<table width="98%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>配置名称唯一标识</th>
    <th>配置说明</th>
    <th>配置值</th>
    <th>操作</th>
  </tr>
<?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['config_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['config_value'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 50) : smarty_modifier_truncateutf8($_tmp, 50)); ?>
</td>
    <td>
    	<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </td>
  </tr>
<?php endforeach; else: ?>
	<tr><td colspan="5"><?php echo $this->_tpl_vars['noData']; ?>
</td></tr>
<?php endif; unset($_from); ?>
</table>
</center>