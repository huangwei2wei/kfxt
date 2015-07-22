<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:46
         compiled from GameOperator/VipIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'GameOperator/VipIndex.html', 8, false),array('function', 'html_checkboxes', 'GameOperator/VipIndex.html', 12, false),)), $this); ?>
<a href="<?php echo $this->_tpl_vars['url']['GameOperator_CreateCacheGameOperator']; ?>
">创建缓存</a>
<fieldset>
	<legend>增加索引</legend>
    <form action="<?php echo $this->_tpl_vars['url']['GameOperator_AddGameOperator']; ?>
" method="post">
    <table width="60%" border="0" cellpadding="3">
      <tr>
        <th scope="row">游戏</th>
        <td><?php echo smarty_function_html_radios(array('name' => 'game_type','options' => $this->_tpl_vars['gameTypeList'],'separator' => "&nbsp;"), $this);?>
</td>
      </tr>
      <tr>
        <th scope="row">运营商</th>
        <td><?php echo smarty_function_html_checkboxes(array('name' => 'operator_ids','options' => $this->_tpl_vars['operatorList'],'separator' => "&nbsp;"), $this);?>
</td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="提交" /></th>
        </tr>
    </table>
	</form>
</fieldset>

<fieldset>
<legend>列表</legend>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>游戏类型</th>
    <th>运营商</th>
    <th>VIP超时限定(普通,1-6级)</th>
    <th>VIP充值量设定(普通,1-6级)</th>
    <th>操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_game_type_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['vip_setup']['vip_timeout']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['vip_setup']['vip_pay']; ?>
</td>
    <td>
    	<a href="<?php echo $this->_tpl_vars['list']['url_setup']; ?>
" >设置</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
" >删除</a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
  	<th colspan="6"><?php echo $this->_tpl_vars['noData']; ?>
</th>
  </tr>
  <?php endif; unset($_from); ?> 
</table>
</fieldset>