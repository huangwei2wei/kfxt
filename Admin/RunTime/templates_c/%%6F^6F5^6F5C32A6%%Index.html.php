<?php /* Smarty version 2.6.26, created on 2013-04-09 16:29:53
         compiled from Menu/Index.html */ ?>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.form.js"></script>
<script language="javascript">
function onSubmit(form){
	form.ajaxSubmit({
		dataType:'json',
		success:function(data){
			alert(data.msg);
		}}
	);
}
</script>
<fieldset>
	<legend>菜单列表</legend>
<a href="<?php echo $this->_tpl_vars['url']['Menu_AddMain']; ?>
">AddMainMenu</a>
<a href="<?php echo $this->_tpl_vars['url']['Menu_AddChild']; ?>
">AddChildMenu</a>
<a href="<?php echo $this->_tpl_vars['url']['Menu_CreateCache']; ?>
">CreateCache</a>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">主菜单/子菜单</th>
    <th scope="col">中文名称 / 英文名称</th>
    <th scope="col">是否显示</th>
    <th scope="col">排序 / 上级动作</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  
  <tr>
  	<form onsubmit="onSubmit($(this));return false;" action="<?php echo $this->_tpl_vars['url']['Menu_UpdateChild']; ?>
" method="post">
    <th><?php echo $this->_tpl_vars['list']['value']; ?>
</th>
    <th><input type="text" class="text" name="name" value="<?php echo $this->_tpl_vars['list']['name']; ?>
" /> / <input type="text" class="text" name="name_2" value="<?php echo $this->_tpl_vars['list']['name_2']; ?>
" /></th>
    <th><input type="checkbox" name="status" <?php if ($this->_tpl_vars['list']['status']): ?>checked="checked"<?php endif; ?> value="1" /></th>
    <th>
        <input type="hidden" name="Id" value="<?php echo $this->_tpl_vars['list']['Id']; ?>
" />
        <input type="text" class="text" name="sort" value="<?php echo $this->_tpl_vars['list']['sort']; ?>
" />
        <input type="text" class="text" name="super_action" value="<?php echo $this->_tpl_vars['list']['super_action']; ?>
" />
        <input type="submit" class="btn-blue" value="更新"  />
    </th>
    </form>
    <th>
      <a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
      <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </th>
  </tr>
  	  
      <?php $_from = $this->_tpl_vars['list']['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childList']):
?>
      <tr>
      	<form onsubmit="onSubmit($(this));return false;" action="<?php echo $this->_tpl_vars['url']['Menu_UpdateChild']; ?>
" method="post">
      	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→<?php echo $this->_tpl_vars['childList']['value']; ?>
</td>
        <td align="center"><input type="text" class="text" name="name" value="<?php echo $this->_tpl_vars['childList']['name']; ?>
" /> / <input type="text" class="text" name="name_2" value="<?php echo $this->_tpl_vars['childList']['name_2']; ?>
" /></td>
        <td align="center"><input type="checkbox" name="status" <?php if ($this->_tpl_vars['childList']['status']): ?>checked="checked"<?php endif; ?> value="1" /></td>
        <td align="center">
            <input type="hidden" name="Id" value="<?php echo $this->_tpl_vars['childList']['Id']; ?>
" />
            <input type="text" class="text" name="sort" value="<?php echo $this->_tpl_vars['childList']['sort']; ?>
" />
            <input type="text" class="text" name="super_action" value="<?php echo $this->_tpl_vars['childList']['super_action']; ?>
" />
            <input type="submit" class="btn-blue" value="更新"  />
        </td>
        <td align="center">
            <a href="<?php echo $this->_tpl_vars['childList']['url_edit']; ?>
">编辑</a>
            <a href="<?php echo $this->_tpl_vars['childList']['url_del']; ?>
">删除</a>
        </td>
        </form>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
  
  <?php endforeach; else: ?>
  <tr><th colspan="5"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
  <?php endif; unset($_from); ?>
</table>
</fieldset>