<?php /* Smarty version 2.6.26, created on 2013-04-08 18:10:35
         compiled from QualityCheck/QualityCount.html */ ?>
<fieldset>
	<legend>获取工单</legend>
    <form action="" method="post">
    
    <div>
    	<?php $_from = $this->_tpl_vars['user']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['u']):
?>
    	<input <?php if ($this->_tpl_vars['u']['is_post'] == 1): ?>checked="checked"<?php endif; ?> type="checkbox" name='userid[]' value='<?php echo $this->_tpl_vars['u']['Id']; ?>
'/><?php echo $this->_tpl_vars['u']['full_name']; ?>
&nbsp;&nbsp;
    	<?php endforeach; endif; unset($_from); ?>
	</div>
    选择日期：
    <input type="text" class="text" name="start_date" value="<?php echo $this->_tpl_vars['selectedTime']['start']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
    至
    <input type="text" class="text" name="end_date" value="<?php echo $this->_tpl_vars['selectedTime']['end']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
    <input type="submit" class="btn-blue" value="统计"/>
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
        <th>总数</th>
        <th>对</th>
        <th>加分</th>
        <th>错字</th>
        <th>内容不完整</th>
        <th>内容错误</th>
        <th>建议优化</th>
        <th>超时</th>
        <th>其它</th>
      </tr>
      <tr>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['count']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['true']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['Points']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['Typo']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['incomplete']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['Contents_error']; ?>
</td>
       	<td align="center"><?php echo $this->_tpl_vars['dataList']['optimization']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['Out']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['dataList']['Other']; ?>
</td>
      </tr>
    </table>
</fieldset>
