<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:54
         compiled from QualityCheck/All.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'QualityCheck/All.html', 15, false),array('function', 'html_radios', 'QualityCheck/All.html', 25, false),array('modifier', 'truncateutf8', 'QualityCheck/All.html', 71, false),)), $this); ?>
<fieldset>
	<legend>搜索条件</legend>
  <form action="" method="get">
	<input type="hidden" value="QualityCheck" name="c" />
    <input type="hidden" value="MyTask" name="a" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td colspan="2" scope="row"><input type="button" class="btn-blue" id="display_user" value="显示/隐藏所有用户" /></td>
      </tr>
      <?php $_from = $this->_tpl_vars['orgList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr class="display_service">
        <th scope="row"><?php echo $this->_tpl_vars['list']['name']; ?>
</th>
        <td>
            <?php if ($this->_tpl_vars['list']['user']): ?>
            <?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['list']['user'],'name' => 'service_ids','selected' => $this->_tpl_vars['selectedServiceIds']), $this);?>

            <?php else: ?>
            <font color="#666666">暂无客服</font>
            <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
      <tr>
        <th scope="row">游戏类型</th>
        <td>
        	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['gameTypeList'],'name' => 'game_type_id','selected' => $this->_tpl_vars['selectedGameTypeId']), $this);?>

        </td>
      </tr>
      <tr>
        <th scope="row">运营商</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['operatorList'],'name' => 'operator_id','selected' => $this->_tpl_vars['selectedOperatorId']), $this);?>
</td>
      </tr>
      <tr>
        <th scope="row">分类选择</th>
        <td>
            <?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['workOrderStatusArr'],'name' => 'order_status','selected' => $this->_tpl_vars['selectedOrderStatus']), $this);?>

        </td>
      </tr>
      <tr>
        <th scope="row">等级选择</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['vipLevel'],'name' => 'vip_level','selected' => $this->_tpl_vars['selectedVipLevel']), $this);?>
</td>
      </tr>
      <tr>
      	<th scope="row">搜索选项</th>
        <td>
            标题 <input type="text" class="text" name="title" id="title" value="<?php echo $this->_tpl_vars['selectedTitle']; ?>
" />
            玩家呢称 <input type="text" class="text" name="user_nickname" id="user_nickname" value="<?php echo $this->_tpl_vars['selectedUserNickname']; ?>
" />
         	账号名搜索 <input type="text" class="text" name="user_account" id="user_account" value="<?php echo $this->_tpl_vars['selectedUserAccount']; ?>
" />
       	<input type="submit" class="btn-blue" value="提交"  /></td>
      </tr>
    </table>
    </form>
</fieldset>

<fieldset>
	<legend>所有被质检的工单</legend>
	<table width="100%" border="0" cellpadding="3">
      <tr>
        <th>Id</th>
        <th>[工单状态]标题</th>
        <th>游戏 / 运营商 / 服务器 / VIP等级<br />玩家信息</th>
        <th>工单来源<br />问题类型</th>
        <th>提问 / 回复数</th>
        <th>处理人</th>
        <th>质检人</th>
        <th>操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td class="order_title" url="<?php echo $this->_tpl_vars['list']['url_dialog']; ?>
" dialogId="detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
" title="<?php echo $this->_tpl_vars['list']['title']; ?>
">
        	 <?php echo ((is_array($_tmp=$this->_tpl_vars['list']['title'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 20) : smarty_modifier_truncateutf8($_tmp, 20)); ?>
<br />
             [<?php echo $this->_tpl_vars['list']['word_status']; ?>
] (<?php echo $this->_tpl_vars['list']['create_time']; ?>
)
        </td>
        <td>
            <?php echo $this->_tpl_vars['list']['word_game_type']; ?>
 / <?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
 /	<?php echo $this->_tpl_vars['list']['word_game_server_id']; ?>
 / <font color="#666666">VIP:</font> <b><?php echo $this->_tpl_vars['list']['vip_level']; ?>
</b><br />
            <font color="#666666">账号：</font><?php echo $this->_tpl_vars['list']['user_account']; ?>
，<font color="#666666">呢称：</font></font><a href="javascript:void(0)" onclick="searchForm($(this))"><?php echo $this->_tpl_vars['list']['user_nickname']; ?>
</a>，<font color="#666666">充值量：</font><?php echo $this->_tpl_vars['list']['money']; ?>

        </td>
        <td><?php echo $this->_tpl_vars['list']['word_source']; ?>
<br /><?php echo $this->_tpl_vars['list']['word_question_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['question_num']; ?>
 / <?php echo $this->_tpl_vars['list']['answer_num']; ?>
</td>
        <td><a href="javascript:void(0)" onclick="addSearchUser(<?php echo $this->_tpl_vars['list']['owner_user_id']; ?>
)"><?php echo $this->_tpl_vars['list']['word_owner_user_id']; ?>
</a></td>
        <td><a href="javascript:void(0)" onclick="addSearchUser(<?php echo $this->_tpl_vars['list']['quality_id']; ?>
)"><?php echo $this->_tpl_vars['list']['word_quality_id']; ?>
</a></td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">工单详情</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="10"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
      <tr><td align="right" colspan="10"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
    </table>
</fieldset>