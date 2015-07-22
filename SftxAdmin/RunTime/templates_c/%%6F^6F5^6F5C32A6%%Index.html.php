<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:45
         compiled from Menu/Index.html */ ?>
<center>
<a href="#">排序主菜单</a>
<a href="<?php echo $this->_tpl_vars['url']['Menu_AddMain']; ?>
">AddMainMenu</a>
<a href="<?php echo $this->_tpl_vars['url']['Menu_AddChild']; ?>
">AddChildMenu</a>
<a href="<?php echo $this->_tpl_vars['url']['Menu_CreateCache']; ?>
">CreateCache</a>
<table width="98%" border="0" cellpadding="3">
  <tr>
    <th scope="col">主菜单/子菜单</th>
    <th scope="col">描述</th>
    <th scope="col">是否显示</th>
    <th scope="col">排序 / 上级动作</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  
  <tr>
    <th><?php echo $this->_tpl_vars['list']['value']; ?>
</th>
    <th><?php echo $this->_tpl_vars['list']['name']; ?>
</th>
    <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
    <td>
    	<form action="<?php echo $this->_tpl_vars['url']['Menu_UpdateChild']; ?>
" method="post">
        	<input type="hidden" name="Id" value="<?php echo $this->_tpl_vars['list']['Id']; ?>
" />
            <input type="text" class="text" name="sort" value="<?php echo $this->_tpl_vars['list']['sort']; ?>
" />
            <input type="text" class="text" name="super_action" value="<?php echo $this->_tpl_vars['list']['super_action']; ?>
" />
            <input type="submit" class="btn-blue" value="更新"  />
        </form>
    </td>
    <td>
      <a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
      <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </td>
  </tr>
  	  
      <?php $_from = $this->_tpl_vars['list']['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childList']):
?>
      <tr>
      	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→<?php echo $this->_tpl_vars['childList']['value']; ?>
</td>
        <td><?php echo $this->_tpl_vars['childList']['name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['childList']['word_status']; ?>
</td>
        <td>
        	<form action="<?php echo $this->_tpl_vars['url']['Menu_UpdateChild']; ?>
" method="post">
            	<input type="hidden" name="Id" value="<?php echo $this->_tpl_vars['childList']['Id']; ?>
" />
                <input type="text" class="text" name="sort" value="<?php echo $this->_tpl_vars['childList']['sort']; ?>
" />
                <input type="text" class="text" name="super_action" value="<?php echo $this->_tpl_vars['childList']['super_action']; ?>
" />
                <input type="submit" class="btn-blue" value="更新"  />
            </form>
        </td>
        <td>
            <a href="<?php echo $this->_tpl_vars['childList']['url_edit']; ?>
">编辑</a>
            <a href="<?php echo $this->_tpl_vars['childList']['url_del']; ?>
">删除</a>
        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
  
  <?php endforeach; else: ?>
  <tr><th colspan="5"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
  <?php endif; unset($_from); ?>
  <tr>
    <th colspan="5">&nbsp;</th>
  </tr>
</table>

</center>