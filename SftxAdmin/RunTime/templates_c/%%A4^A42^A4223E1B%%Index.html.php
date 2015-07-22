<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:48
         compiled from GameOperator/Index.html */ ?>
<center>
<a href="<?php echo $this->_tpl_vars['url']['GameOperator_CreateCache']; ?>
">生成缓存</a>
<a href="<?php echo $this->_tpl_vars['url']['GameOperator_Add']; ?>
">添加运营商</a>
<table width="98%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>运营商</th>
    <th>操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['operator_name']; ?>
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
  	<th colspan="3"><?php echo $this->_tpl_vars['noData']; ?>
</th>
  </tr>
  <?php endif; unset($_from); ?> 
</table>
</center>