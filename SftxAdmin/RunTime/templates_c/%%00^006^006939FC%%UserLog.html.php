<?php /* Smarty version 2.6.26, created on 2012-09-13 17:50:41
         compiled from GmSftx/UserLog.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'GmSftx/UserLog.html', 46, false),array('modifier', 'date_format', 'GmSftx/UserLog.html', 67, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
function chageSearch(curVal){
		var searchSpan=$("#search");
		searchSpan.empty();
		switch(curVal){
			case '1':{//id
			}
			case '2':{//呢称
				searchSpan.append(' <input type="text" class="text" value="<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
" name="dataMin" />');
				break;
			}
			case '3':{
				searchSpan.append('<input type="text" class="text" name="dataMin" onFocus="WdatePicker({startDate:\'<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
\',dateFmt:\'yyyy-MM-dd HH:mm:ss\',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
" /> 至 <input type="text" class="text" name="dataMax" onFocus="WdatePicker({startDate:\'<?php echo $this->_tpl_vars['selectedArr']['dataMax']; ?>
\',dateFmt:\'yyyy-MM-dd HH:mm:ss\',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedArr']['dataMax']; ?>
" />');
				break;
			}
		}
}
$(function(){
	$("#searchType").change();
})
</script>
<fieldset>
	<legend>类型选择</legend>
    <a href="<?php echo $this->_tpl_vars['url']['GmSftx_UserLog_Copper']; ?>
">银币操作记录</a>
    <a href="<?php echo $this->_tpl_vars['url']['GmSftx_UserLog_Gold']; ?>
">金币操作记录</a>
    <a href="<?php echo $this->_tpl_vars['url']['GmSftx_UserLog_Food']; ?>
">粮食操作记录</a>
</fieldset>

<fieldset>
	<legend>用户查询</legend>
    <table width="98%" border="0" cellpadding="3">
      <thead>
      <tr>
        <td colspan="7">
        <form action="<?php echo $this->_tpl_vars['url']['GmSftx_UserLog']; ?>
" method="get">
            <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
            <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
            <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
            搜索类型：<select id="searchType" name="type" onchange="chageSearch($(this).val())"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['optionList'],'selected' => $this->_tpl_vars['selectedArr']['type']), $this);?>
</select>
            <span id="search"></span>
            <input type="submit" class="btn-blue" value="查找">
        </form>
        </td>
      </tr>
      </thead>
      <tbody>
          <tr>
            <th>ID</th>
            <th>用户Id</th>
            <th>操作时间</th>
            <th>消费前金币</th>
            <th>消费后金币</th>
            <th>操作类型</th>
            <th>操作子类型</th>
          </tr>
          <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
          <tr>
            <td><?php echo $this->_tpl_vars['vo']['id']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['userId']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['logTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['oldValue']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['newValue']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['type']; ?>
</td> 
            <td><?php echo $this->_tpl_vars['vo']['subType']; ?>
</td>
          </tr>
          <?php endforeach; else: ?>
          <tr>
            <th colspan="7"><?php echo $this->_tpl_vars['noData']; ?>
</th>
          </tr>
          <?php endif; unset($_from); ?> 
          <tr>
            <th colspan="7" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
          </tr>
      </tbody>
    </table>
</fieldset>
<?php endif; ?>