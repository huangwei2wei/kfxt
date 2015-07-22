<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:22
         compiled from User/RolesIndex.html */ ?>
<center>
<a href="<?php echo $this->_tpl_vars['url']['User_RolesAdd']; ?>
">增加角色</a>
<table width="98%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>角色(唯一)</th>
    <th>角色名</th>
    <th>创建时期</th>
    <th>更新日期</th>
    <th>操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['role_value']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['role_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['date_created']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['date_updated']; ?>
</td>
    <td>
   		<a href="<?php echo $this->_tpl_vars['list']['url_edit_perm']; ?>
">修改权限</a>
    	<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><th colspan="6"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
  <?php endif; unset($_from); ?>
  <tr><th colspan="6" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th></tr>
</table>

</center>