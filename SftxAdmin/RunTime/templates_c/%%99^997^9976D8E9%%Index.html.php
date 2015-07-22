<?php /* Smarty version 2.6.26, created on 2012-09-13 18:11:55
         compiled from QualityCheck/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'QualityCheck/Index.html', 4, false),array('function', 'html_checkboxes', 'QualityCheck/Index.html', 5, false),array('modifier', 'truncateutf8', 'QualityCheck/Index.html', 34, false),)), $this); ?>
<fieldset>
	<legend>获取工单</legend>
    <form action="<?php echo $this->_tpl_vars['url']['QualityCheck_Index']; ?>
" method="post">
    <div><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['orgs'],'onclick' => "viewUser($(this).val())",'name' => 'org','selected' => $this->_tpl_vars['selectedOrg']), $this);?>
</div>
    <div id="userList"><?php echo smarty_function_html_checkboxes(array('name' => 'users','options' => $this->_tpl_vars['selectedUsers'],'selected' => $this->_tpl_vars['selectedUsersOption']), $this);?>
</div>
    选择日期：
    <input type="text" class="text" name="start_date" value="<?php echo $this->_tpl_vars['selectedTime']['start']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
    至
    <input type="text" class="text" name="end_date" value="<?php echo $this->_tpl_vars['selectedTime']['end']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
    抽取数量：<input type="text" class="text" value="10" name="num" />
    <input type="submit" class="btn-blue" value="获取工单" onclick="return confirm('确定要获取工单吗?')" />
    </form>
    <?php if ($this->_tpl_vars['addOrderDeatil']): ?>
    您获取了<font color="#FF0000"><b><?php echo $this->_tpl_vars['addOrderDeatil']['num']; ?>
</b></font>个工单，ID分别为[ <em><?php echo $this->_tpl_vars['addOrderDeatil']['addIds']; ?>
</em> ]
    <?php endif; ?>
</fieldset>

<fieldset>
	<legend>工单列表</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th>Id</th>
        <th>[工单状态]标题</th>
        <th>游戏 / 运营商 / 服务器 / VIP等级<br />玩家信息</th>
        <th>工单来源<br />问题类型</th>
        <th>提问 / 回复数</th>
        <th>处理人</th>
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
，<font color="#666666">呢称：</font><?php echo $this->_tpl_vars['list']['user_nickname']; ?>
，<font color="#666666">充值量：</font><?php echo $this->_tpl_vars['list']['money']; ?>

        </td>
        <td><?php echo $this->_tpl_vars['list']['word_source']; ?>
<br /><?php echo $this->_tpl_vars['list']['word_question_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['question_num']; ?>
 / <?php echo $this->_tpl_vars['list']['answer_num']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_owner_user_id']; ?>
</td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">工单详情</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="9"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
      <tr><td align="right" colspan="9"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
    </table>
</fieldset>
