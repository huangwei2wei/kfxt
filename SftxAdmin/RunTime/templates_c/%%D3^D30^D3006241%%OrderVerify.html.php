<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:51
         compiled from Verify/OrderVerify.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Verify/OrderVerify.html', 37, false),)), $this); ?>
<?php if ($this->_tpl_vars['dataList']): ?>
<fieldset>
<legend>查证结果</legend>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">状态</th>
    <th scope="col">问题类型</th>
    <th scope="col">处理等级</th>
    <th scope="col">提交部门</th>
    <th scope="col">标题</th>
    <th scope="col">提交时间</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_type']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_level']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_department_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['create_time']; ?>
</td>
    <td><a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">察看详情</a></td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>
</fieldset>
<?php endif; ?>
    
<fieldset>
<legend>添加处理方案</legend>
	<form action="<?php echo $this->_tpl_vars['url']['Verify_OrderVerify']; ?>
" id="verify_form" method="post">
    <input type="hidden" value="<?php echo $this->_tpl_vars['workOrderId']; ?>
" name="work_order_id" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="row">所属游戏/运营商/服务器</th>
        <td>
            <select name="game_type_id" id="game_type" onChange="changeType($(this).val())"><option value="">请选择游戏</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gameType']), $this);?>
</select>
            <select name="operator_id"  onchange="changeOperatorType($(this).val())" id="operator_id"><option value="">请选择运营</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList']), $this);?>
</select>
            <select name="game_server_id" id="game_server_id"><option value="">请选择游戏...</option></select>
        </td>
      </tr>
      <tr>
        <th scope="row">玩家资料</th>
        <td>
        	玩家Id：<input type="text" class="text" name="game_user_id" value="<?php echo $this->_tpl_vars['gameUserId']; ?>
"  /> 
            玩家账号：<input type="text" class="text" name="game_user_account" value="<?php echo $this->_tpl_vars['userAccount']; ?>
" /> 
            玩家呢称：<input type="text" class="text" name="game_user_nickname" value="<?php echo $this->_tpl_vars['userNickname']; ?>
" />
        </td>
      </tr>
      <tr>
        <th scope="row">问题类别</th>
        <td><select name="type" reg="^\w+$" tip="请择选" id="type"><option value="">请选择游戏...</option></select></td>
      </tr>
      <tr>
        <th scope="row">级别</th>
        <td><select name="level"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifyLevel']), $this);?>
</select></td>
      </tr>
      <tr>
        <th scope="row">来源</th>
        <td>
        	<select name="source"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifySource']), $this);?>
</select>
            来源详细：<input type="text" class="text" name="source_detail" size="60" />
        </td>
      </tr>
      <tr>
        <th scope="row">提交部门</th>
        <td><select name="department_id"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['department']), $this);?>
</select></td>
      </tr>
      <tr>
        <th scope="row">状态</th>
        <td><select name="status"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifyStatus']), $this);?>
</select></td>
      </tr>

      <tr>
        <th scope="row" rowspan="2">描述</th>
        <td>
		标题：
        <input type="text" class="text" name="title"  /></td>
      </tr>
      <tr>
        <td>
           	<a href="javascript:void(0)" onclick="KE.create('content')">加载编辑器</a>
            <a href="javascript:void(0)" onclick="KE.remove('content')">卸载编辑器</a>
        	<br />
        	<textarea name="content" id="content" cols="60" rows="12"></textarea>
       	</td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="提交" /></th>
      </tr>
    </table>
  </form>
</fieldset>