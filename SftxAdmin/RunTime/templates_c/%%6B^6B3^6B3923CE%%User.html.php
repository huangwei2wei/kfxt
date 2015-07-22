<?php /* Smarty version 2.6.26, created on 2012-09-13 17:50:41
         compiled from GmSftx/User.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'GmSftx/User.html', 48, false),)), $this); ?>
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
			case '2': 
			case '1':{
				searchSpan.append('<input type="text" class="text" name="dataMin" value="<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
">');
				break;
			}
			case '7':{
				searchSpan.append('<input type="text" class="text" name="dataMin" onFocus="WdatePicker({startDate:\'\',dateFmt:\'yyyy-MM-dd HH:mm:ss\',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
"> 至 <input type="text" class="text" name="dataMax" onFocus="WdatePicker({startDate:\'\',dateFmt:\'yyyy-MM-dd HH:mm:ss\',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedArr']['dataMax']; ?>
" >');
				break;
			}
			default :{
				searchSpan.append('<input type="text" class="text" name="dataMin" value="<?php echo $this->_tpl_vars['selectedArr']['dataMin']; ?>
"> 至 <input type="text" class="text" name="dataMax" id="end" value="<?php echo $this->_tpl_vars['selectedArr']['dataMax']; ?>
" >');
			}
		}
}

function jumpUrl(url){
	form=$("#form");
	form.attr("action",url);
	form.submit();
}

$(function(){
	$("#search_select").change()
})
</script>
<fieldset>
  <legend>用户查询</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td colspan="10">
        <form action=""  method="get" onsubmit="return checksub();">
        	<input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
            <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
            <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
            <select name="type" id="search_select" onchange="chageSearch($(this).val())">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['optionList']['optionList'],'selected' => $this->_tpl_vars['selectedArr']['type']), $this);?>

            </select>
            <span id="search">
            </span>
            <select name="pageSize"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['optionList']['pagesizeList'],'selected' => $this->_tpl_vars['selectedArr']['pageSize']), $this);?>
</select>
            <input type="submit" class="btn-blue" name="submit" value="查找">
        </form>    
        </td>
        </tr>
      <tr>
        <th>用户Id</th>
        <th>角色名</th>
        <th>级别</th>
        <th>银币</th>
        <th>系统金币</th>
        <th>用户金币</th>
        <th>威望</th>
        <th>军令</th>
        <th>军工</th>
        <th>粮食</th>
      </tr>
      <form action="" id="form" method="post">
      <input type="hidden" name="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
      <tr>
        <td><input type="checkbox" name="idList[]" value="<?php echo $this->_tpl_vars['vo']['id']; ?>
" /><?php echo $this->_tpl_vars['vo']['id']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['name']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['level']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['copper']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['sysGold']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['userGold']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['prestige']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['vo']['token']; ?>
</td>
        <td><?php echo $this->_tpl_vars['vo']['exploit']; ?>
</td>
        <td><?php echo $this->_tpl_vars['vo']['food']; ?>
</td>
        </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="10"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?> 
      <tr>
      	<td><input type="checkbox" onclick="$(':checkbox[name=\'idList[]\']').attr('checked',$(this).attr('checked'))" />全选</td>
        <td colspan="9">
        	<!--input type="button" class="btn-blue" value="发送奖励" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['MasterFRG_RewardBefore']; ?>
')" /-->
            <!--input type="button" class="btn-blue" value="踢人" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['MasterFRG_KickUser']; ?>
')" /-->
            <input type="button" class="btn-blue" value="发消息" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['GmSftx_SendMsg']; ?>
')" />
            <input type="button" class="btn-blue" value="禁言" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['GmSftx_Donttalk_Add']; ?>
')" />
            <input type="button" class="btn-blue" value="封号" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['GmSftx_LockUser_Add']; ?>
')" />
            <!--input type="button" class="btn-blue" value="增加教官" onclick="jumpUrl('<?php echo $this->_tpl_vars['url']['MasterFRG_Drillmaster_Add']; ?>
')" /-->
        </td>
      </tr>
      </form>
      <tr>
        <th colspan="10" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
      </tr>
    </table>
</fieldset>
<?php endif; ?>