<?php /* Smarty version 2.6.26, created on 2012-09-13 17:51:05
         compiled from GmSftx/LockIP.html */ ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<script language="javascript">
function del(){
	if(confirm('确定要删除吗?')){
		$('form').submit();
	}
}

</script>
<fieldset>
<legend>锁定IP列表</legend>
<form action="<?php echo $this->_tpl_vars['url']['GmSftx_LockIP_Del']; ?>
" method="post" onsubmit="return checksub();">
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <td colspan="5">
    	<a href="<?php echo $this->_tpl_vars['url']['GmSftx_LockIP_Add']; ?>
">增加封号用户</a>
    </td>
    </tr>
  <tr>
    <th scope="col">操作</th>
    <th scope="col">封锁IP</th>
    <th scope="col">封IP时间</th>
    <th scope="col">封IP束时间</th>
    <th scope="col">操作</th>
    </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col"><input type="checkbox" value="<?php echo $this->_tpl_vars['list']['id']; ?>
" name="idList[]" /></td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['ip']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['begin']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['end']; ?>
</td>
    <td scope="col"><a href="<?php echo $this->_tpl_vars['list']['url_release']; ?>
">强制解禁</a></td>
    </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="5" scope="col">
  		<?php echo $this->_tpl_vars['noData']; ?>
 	
    </th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td colspan="5" scope="col">
  		选择所有<input type="checkbox" onclick="$(':checkbox[name=\'idList[]\']').attr('checked',$(this).attr('checked'))" /><input type="button" class="btn-blue" onClick="del()" value="删除" />  	
    </td>
  </tr>
  <tr>
    <td colspan="5" align="right" scope="col">
  		<?php echo $this->_tpl_vars['pageBox']; ?>

    </td>
  </tr>
</table>
</form>
</fieldset>
<?php endif; ?>