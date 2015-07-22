<?php /* Smarty version 2.6.26, created on 2013-04-08 18:08:21
         compiled from Prem/MoudleEditAct.html */ ?>
<fieldset>
	<legend>权限更改 [<font color="#FF0000"><?php echo $this->_tpl_vars['_GET']['role_value']; ?>
</font>]</legend>
    <form action="" method="post">
    <input type="hidden" name="role_value" value="<?php echo $this->_tpl_vars['_GET']['role_value']; ?>
" />
    <input type="hidden" name="moudle" value="<?php echo $this->_tpl_vars['_GET']['value']; ?>
" />
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
          	<th scope="col">权限</th>
            <th scope="col">动作</th>
            <th scope="col">动作名称</th>
            <th scope="col">是否显示</th>
          </tr>
          <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['actKey'] => $this->_tpl_vars['actList']):
?>
          <tr>
          	<td align="center"><input type="checkbox" name="value[]" <?php if ($this->_tpl_vars['actList']['selected']): ?>checked<?php endif; ?> value="<?php echo $this->_tpl_vars['actList']['value']; ?>
" /></td>
            <th><?php echo $this->_tpl_vars['actList']['value']; ?>
</th>
            <th><?php echo $this->_tpl_vars['actList']['name']; ?>
</th>
            <th><?php if ($this->_tpl_vars['actList']['display']): ?>是<?php else: ?>否<?php endif; ?></th>
          </tr>
              <?php if ($this->_tpl_vars['actList']['class_methods']): ?>
              <?php $_from = $this->_tpl_vars['actList']['class_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childListKey'] => $this->_tpl_vars['childList']):
?>
              <tr>
              	<td align="center"><input type="checkbox" name="value[]" <?php if ($this->_tpl_vars['childList']['selected']): ?>checked<?php endif; ?> value="<?php echo $this->_tpl_vars['actList']['value']; ?>
_<?php echo $this->_tpl_vars['childList']['value']; ?>
" /></td>
                <td align="center"><?php echo $this->_tpl_vars['childList']['value']; ?>
</td>
                <td align="center"><?php echo $this->_tpl_vars['childList']['name']; ?>
</td>
                <td align="center"><?php if ($this->_tpl_vars['childList']['display']): ?>是<?php else: ?>否<?php endif; ?></td>
              </tr>
              <?php endforeach; endif; unset($_from); ?>
              <?php endif; ?>
          <?php endforeach; else: ?>
          <tr>
            <th colspan="4"><?php echo $this->_tpl_vars['noData']; ?>
</th>
          </tr>
          <?php endif; unset($_from); ?>
          <tr>
            <th colspan="4"><input type="submit" class="btn-blue" value="提交" /></th>
          </tr>
      </table>
      </form>
</fieldset>