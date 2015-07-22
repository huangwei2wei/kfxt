<?php /* Smarty version 2.6.26, created on 2012-09-13 18:11:58
         compiled from Stats/Detail.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'Stats/Detail.html', 18, false),array('modifier', 'intval', 'Stats/Detail.html', 130, false),)), $this); ?>
<fieldset>
<legend>统计搜索</legend>
<form action="<?php echo $this->_tpl_vars['url']['Stats_Detail']; ?>
" method="post">
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="row">时间范围：</th>
    <td>
    <input type="text" class="text" name="start_date" value="<?php echo $this->_tpl_vars['selectedTime']['start']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
    至
	<input type="text" class="text" name="end_date" value="<?php echo $this->_tpl_vars['selectedTime']['end']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/>
	</td>
  </tr>
  <?php $_from = $this->_tpl_vars['orgList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <th scope="row"><?php echo $this->_tpl_vars['list']['name']; ?>
</th>
    <td>
    	<?php if ($this->_tpl_vars['list']['user']): ?>
        <?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['list']['user'],'name' => 'check_users','selected' => $this->_tpl_vars['selectedUsers']), $this);?>

        <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
  <tr>
  </tr>
  <tr>
  	<td colspan="2">
		<input type="submit" class="btn-blue" value="统计" />
        <input type="button" class="btn-blue" value="导出EXCEL" />
    </td>
  </tr>
</table>
</form>
</fieldset>


<?php if ($this->_tpl_vars['displayTrue']): ?>
<fieldset>
<legend><b>汇总</b></legend>
<div class="option_description" align="right">
质检选项：
<?php $_from = $this->_tpl_vars['qualityOptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
<span><?php echo $this->_tpl_vars['key']; ?>
:<?php echo $this->_tpl_vars['list']; ?>
</span>&nbsp;&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>
</div>
<div style="float:left; width:39.9%;">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th scope="col">工单回复/日期</th>
        <th scope="col">总回复量</th>
        <th scope="col">超时回复</th>
        <th scope="col">未超时回复</th>
        <th scope="col">质检回复</th>
        <th scope="col">未质检回复</th>
      </tr>
      <?php $_from = $this->_tpl_vars['baseTotal']['workload']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
      <tr>
        <th scope="row"><?php echo $this->_tpl_vars['key']; ?>
</th>
        <td><?php echo $this->_tpl_vars['list']['total']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['timeout_num']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['no_timeout_num']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['quality_num']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['no_quality_num']; ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
</div>
<div style="float:right; width:59.9%">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th scope="col">质检详细/日期</th>
        <th scope="col">未申诉</th>
        <th scope="col">已经申诉</th>
        <th scope="col">申诉成功</th>
        <th scope="col">申诉失败</th>
        <th scope="col">同意质检</th>
        <th scope="col" colspan="8">质检选项</th>
        <th scope="col">加分</th>
        <th scope="col">扣分</th>
      </tr>
      <?php $_from = $this->_tpl_vars['baseTotal']['quality']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
      <tr>
        <th scope="row"><?php echo $this->_tpl_vars['key']; ?>
</th>
        <td><?php echo $this->_tpl_vars['list']['status_num']['1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['status_num']['2']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['status_num']['3']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['status_num']['4']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['status_num']['5']; ?>
</td>
        
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['1']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['2']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['3']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['4']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['5']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['6']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['7']; ?>
</span></td>
        <td><span class="option"><?php echo $this->_tpl_vars['list']['option_num']['8']; ?>
</span></td>
        
        <td><?php echo $this->_tpl_vars['list']['deduction']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['bonus']; ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
</div>
<div style="clear:both"></div>
</fieldset>


<?php $_from = $this->_tpl_vars['statsUsers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childUser']):
?>
<fieldset>
<legend><?php echo $this->_tpl_vars['childUser']['nick_name']; ?>
</legend>
<div class="option_description" align="right">
质检选项：
<?php $_from = $this->_tpl_vars['qualityOptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
<span><?php echo $this->_tpl_vars['key']; ?>
:<?php echo $this->_tpl_vars['list']; ?>
</span>&nbsp;&nbsp;
<?php endforeach; endif; unset($_from); ?>
</div>
<div style="float:left; width:39.9%;">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th scope="col">工单回复/日期</th>
        <th scope="col">总回复量</th>
        <th scope="col">超时回复</th>
        <th scope="col">未超时回复</th>
        <th scope="col">质检回复</th>
        <th scope="col">未质检回复</th>
      </tr>
      <?php $_from = $this->_tpl_vars['childUser']['workload']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
      <tr>
        <th scope="row"><?php echo $this->_tpl_vars['key']; ?>
</th>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['total'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['timeout_num'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['no_timeout_num'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['quality_num'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['no_quality_num'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
</div>
<div style="float:right; width:59.9%">
    <table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <th scope="col">质检详细/日期</th>
        <th scope="col">未申诉</th>
        <th scope="col">已经申诉</th>
        <th scope="col">申诉成功</th>
        <th scope="col">申诉失败</th>
        <th scope="col">同意质检</th>
        <th scope="col" colspan="8">质检选项</th>
        <th scope="col">加分</th>
        <th scope="col">扣分</th>
      </tr>
      <?php $_from = $this->_tpl_vars['childUser']['quality']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
      <tr>
        <th scope="row"><?php echo $this->_tpl_vars['key']; ?>
</th>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['status_num']['1'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['status_num']['2'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['status_num']['3'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['status_num']['4'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['status_num']['5'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['1'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['2'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['3'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['4'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['5'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['6'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['7'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><span class="option"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['option_num']['8'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</span></td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['deduction'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['bonus'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
</div>
<div style="clear:both"></div>
</fieldset>
<?php endforeach; endif; unset($_from); ?>
<?php endif; ?>