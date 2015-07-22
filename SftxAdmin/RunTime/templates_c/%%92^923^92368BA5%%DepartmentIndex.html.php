<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:21
         compiled from User/DepartmentIndex.html */ ?>
<center>
<a href="<?php echo $this->_tpl_vars['url']['User_DepartmentAdd']; ?>
">Add</a>
<a href="<?php echo $this->_tpl_vars['url']['User_DepartmentCreateCache']; ?>
">CreateCahe</a>
<table width="98%" border="0" cellpadding="3">
  <tr>
    <th scope="col">Id</th>
    <th scope="col">部门名称</th>
    <th scope="col">描述</th>
    <th scope="col">添加日期</th>
    <th scope="col">更新日期</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['description']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['date_created']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['date_updated']; ?>
</td>
    <td>
    	<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="6"><?php echo $this->_tpl_vars['noData']; ?>
</th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <th colspan="6" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
  </tr>
</table>
</center>