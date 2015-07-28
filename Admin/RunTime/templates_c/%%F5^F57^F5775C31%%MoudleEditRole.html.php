<?php /* Smarty version 2.6.26, created on 2013-04-08 18:08:18
         compiled from Prem/MoudleEditRole.html */ ?>
<fieldset>
	<legend>权限编辑</legend>
    <form action="" method="post">
    <input type="hidden" name="role_value" value="<?php echo $this->_tpl_vars['_GET']['role_value']; ?>
" />
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
            <th scope="col">操作</th>
            <th scope="col">模块值</th>
            <th scope="col">模块名</th>
          </tr>
          <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
          <tr>
            <td align="center"><input type="checkbox" name="value[]" <?php if ($this->_tpl_vars['list']['selected']): ?>checked<?php endif; ?> value="<?php echo $this->_tpl_vars['list']['value']; ?>
" /></td>
            <td align="center"><?php if ($this->_tpl_vars['list']['selected']): ?><a href="<?php echo $this->_tpl_vars['list']['url_edit_act']; ?>
"><?php echo $this->_tpl_vars['list']['value']; ?>
</a><?php else: ?><?php echo $this->_tpl_vars['list']['value']; ?>
<?php endif; ?></td>
            <td align="center"><?php echo $this->_tpl_vars['list']['name']; ?>
</td>
          </tr>
          <?php endforeach; endif; unset($_from); ?>
          <tr>
            <th colspan="3"><input type="submit" value="提交" class="btn-blue" /></th>
          </tr>
      </table>
    </form>
</fieldset>