<?php /* Smarty version 2.6.26, created on 2013-04-07 10:22:32
         compiled from GameOperator/VipIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'GameOperator/VipIndex.html', 25, false),)), $this); ?>



<fieldset>
<legend>
    列表
    [<a href="<?php echo $this->_tpl_vars['url']['GameOperator_CreateCacheGameOperator']; ?>
">创建缓存]</a>
    [<a href="<?php echo $this->_tpl_vars['url']['GameOperator_VipAdd']; ?>
">批量简单添加</a>]
    [<a href="<?php echo $this->_tpl_vars['url']['GameOperator_AddGameOperator']; ?>
">添加</a>]
</legend>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>游戏类型</th>
    <th>运营商</th>
    <th>URL模板</th>
    <th>VIP超时限定(普通,1-6级)<br>VIP充值量设定(普通,1-6级)</th>
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
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#666666">无</font>') : smarty_modifier_default($_tmp, '<font color="#666666">无</font>')); ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['vip_setup']['vip_timeout']; ?>
<br><?php echo $this->_tpl_vars['list']['vip_setup']['vip_pay']; ?>
</td>
    <td>
    	[<a href="<?php echo $this->_tpl_vars['list']['url_setup']; ?>
" >设置</a>]
        [<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">详细编辑</a>]
       	[<a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
" >删除</a>]
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