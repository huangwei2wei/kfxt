<?php /* Smarty version 2.6.26, created on 2013-04-08 18:07:44
         compiled from User/UserManagerOperator.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'User/UserManagerOperator.html', 25, false),)), $this); ?>
<script language="javascript">
var gameOperatorIndex=eval(<?php echo $this->_tpl_vars['gameOperatorIndex']; ?>
);
function displayOperator(gameTypeId){
	var operator=$("#operatorList");
	operator.empty();
	$.each(gameOperatorIndex,function(i,n){
		if(n.game_type_id==gameTypeId){
			operator.append('<input type="checkbox" name=operator_id[] value='+n.operator_id+' />'+n.word_operator_id+'&nbsp;');
		}
	});
}

$(function(){
	$("#gameTypeList :radio").first().click();
});
</script>
<fieldset>
	<legend>增加运营商</legend>
    <form action="<?php echo $this->_tpl_vars['url']['User_UserAddOperator']; ?>
" method="post">
    	<input type="hidden" name="doaction" value="addOperator" />
    	<input type="hidden" value="<?php echo $this->_tpl_vars['userId']; ?>
" name="user_id" />
        <table width="98%" border="0" cellpadding="3">
          <tr>
            <th width="15%" scope="row">游戏类型</th>
            <td id="gameTypeList"><?php echo smarty_function_html_radios(array('name' => 'game_type_id','onclick' => "displayOperator($(this).val())",'options' => $this->_tpl_vars['gameTypeList'],'separator' => "&nbsp;"), $this);?>
</td>
          </tr>
          <tr>
            <th width="20%" scope="row">运营商<input type="checkbox" onClick="$('input[name=operator_id[]]').attr('checked',$(this).attr('checked'))">全选</th>
            <td id="operatorList">&nbsp;</td>
          </tr>
          <tr>
            <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="增加" /></th>
          </tr>
        </table>
    </form>
</fieldset>

<fieldset>
	<legend>所能管理的运营商</legend>
    <form action="<?php echo $this->_tpl_vars['url']['User_SortUserOperator']; ?>
" method="post">
    <input type="hidden" value="<?php echo $this->_tpl_vars['userId']; ?>
" name="user_id" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">优先级</th>
        <th scope="col">[游戏]运营商名称</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['userOperatorList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><input type="text" class="text" value="<?php echo $this->_tpl_vars['list']['priority_level']; ?>
" name="priority_level[<?php echo $this->_tpl_vars['list']['operator_id']; ?>
]" /></td>
        <td><b>[<?php echo $this->_tpl_vars['list']['word_game_type_id']; ?>
]</b>  <?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
</td>
        <td>[<a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>]</td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="3"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
      <?php if ($this->_tpl_vars['userOperatorList']): ?>
      <tr><td colspan="3"><input type="submit" class="btn-blue" value="排序" /></td></tr>
      <?php endif; ?>
    </table>
	</form>
</fieldset>