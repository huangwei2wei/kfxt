<?php /* Smarty version 2.6.26, created on 2013-04-09 16:46:54
         compiled from Menu/EditChild.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Menu/EditChild.html', 7, false),)), $this); ?>
<center>
<form action="<?php echo $this->_tpl_vars['url']['Menu_EditChild']; ?>
" method="post">
<input type="hidden" value="<?php echo $this->_tpl_vars['id']; ?>
" name="Id" />
<table width="60%" border="0" cellpadding="3">
  <tr>
    <th scope="row">所属主菜单</th>
    <td><select name="parent_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['mainList'],'selected' => $this->_tpl_vars['defaultParentId']), $this);?>
</select></td>
  </tr>
  <tr>
    <th scope="row">标识</th>
    <td><input type="text" class="text" name="value" value="<?php echo $this->_tpl_vars['data']['value']; ?>
" /></td>
  </tr>
  <tr>
    <th scope="row">名称</th>
    <td><input type="text" class="text" name="name" value="<?php echo $this->_tpl_vars['data']['name']; ?>
" /></td>
  </tr>
  <tr>
    <th scope="row">是否显示</th>
    <td><input type="checkbox" name="status" value="1" <?php if ($this->_tpl_vars['data']['status'] == 1): ?>checked="checked"<?php endif; ?> /></td>
  </tr>
  <tr>
    <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="提交" /></th>
    </tr>
</table>
</form>
</center>