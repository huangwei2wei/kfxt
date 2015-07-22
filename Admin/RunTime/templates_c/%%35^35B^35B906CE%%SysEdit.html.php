<?php /* Smarty version 2.6.26, created on 2013-04-07 10:18:28
         compiled from SysManagement/SysEdit.html */ ?>
<center>
<form method="post" action="<?php echo $this->_tpl_vars['url']['SysManagement_SysSetupEdit']; ?>
">
<input type="hidden" value="<?php echo $this->_tpl_vars['list']['Id']; ?>
" name="Id" />
<table width="98%" border="0" cellpadding="3">
  <tr>
    <td>Id：</td>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
  </tr>
  <tr>
    <td>唯一标识：</td>
    <td>
    	<input type="text" class="text" name="config_name" value="<?php echo $this->_tpl_vars['list']['config_name']; ?>
" />
    </td>
  </tr>
  <tr>
    <td>配置说明：</td>
    <td><textarea name="title"><?php echo $this->_tpl_vars['list']['title']; ?>
</textarea></td>
  </tr>
  <tr>
    <td>配置值(中文)：</td>
    <td><textarea cols="100" name="config_value" rows="20"><?php echo $this->_tpl_vars['list']['config_value']; ?>
</textarea></td>
  </tr>
  <tr>
    <td>配置值(英文)：</td>
    <td><textarea cols="100" name="config_value_2" rows="20"><?php echo $this->_tpl_vars['list']['config_value_2']; ?>
</textarea></td>
  </tr>
  <tr>
    <td><input type="submit" class="btn-blue" value="提交" /></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</center>