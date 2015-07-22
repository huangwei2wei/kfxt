<?php /* Smarty version 2.6.26, created on 2013-04-09 16:51:09
         compiled from GmSftx/Donttalk.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/Donttalk.html', 8, false),array('function', 'html_options', 'GmSftx/Donttalk.html', 30, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<script language="javascript">
function del(){
	if(confirm('<?php echo ((is_array($_tmp='DEL_CONFIRM')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
?')){
		$('form').submit();
	}
}
function showChild(val){
	if(val==3){
		$("#child_select").show();
	}else{
		$("#child_select").hide();
	}
}
$(function(){
	$("#search_type").change();
})
</script>

<fieldset>
	<legend><?php echo ((is_array($_tmp='SEARCH')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
    <form action="" method="get" style="margin-top:">
    	<input type="hidden" name="c" value="GmSftx" />
        <input type="hidden" name="a" value="Donttalk" />
        <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
        <select id="search_type" onchange="showChild($(this).val())" name="type"><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['optionList']['optionList'],'selelcted' => $this->_tpl_vars['selectedArr']['type']), $this);?>
</select>
        <select id="child_select" name="dataMin" style="display:none" ><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['optionList']['statusList'],'selelcted' => $this->_tpl_vars['selectedArr']['dataMin']), $this);?>
</select>
        <input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='SUBMIT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />
    </form>
</fieldset>

<fieldset>
<legend><?php echo ((is_array($_tmp='DONTTALK_LIST')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<form action="<?php echo $this->_tpl_vars['url']['GmSftx_Donttalk_Del']; ?>
" method="post" onsubmit="return checksub();">
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <td colspan="7">
    	<a href="<?php echo $this->_tpl_vars['url']['GmSftx_Donttalk_Add']; ?>
"><?php echo ((is_array($_tmp='ADD_DONTTALK_USER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
    </td>
    </tr>
  <tr>
    <th scope="col"><?php echo ((is_array($_tmp='OPERATE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='USER_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='STATUS')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='REGISTER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

    	(<?php if ($this->_tpl_vars['BeiJing_time']): ?><?php echo ((is_array($_tmp='BJT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp='SERVER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php endif; ?>)
    </th>
    <th scope="col"><?php echo ((is_array($_tmp='DONTTALK_START_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

    	(<?php if ($this->_tpl_vars['BeiJing_time']): ?><?php echo ((is_array($_tmp='BJT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp='SERVER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php endif; ?>)
    </th>
    <th scope="col"><?php echo ((is_array($_tmp='DONTTALK_END_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

    	(<?php if ($this->_tpl_vars['BeiJing_time']): ?><?php echo ((is_array($_tmp='BJT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php else: ?><?php echo ((is_array($_tmp='SERVER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php endif; ?>)
    </th>
    <th scope="col"><?php echo ((is_array($_tmp='OPERATE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col"><input type="checkbox" value="<?php echo $this->_tpl_vars['list']['id']; ?>
" name="idList[]" /></td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['uid']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['status']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['createAt']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['begin']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['end']; ?>
</td>
    <td scope="col"><a href="<?php echo $this->_tpl_vars['list']['url_release']; ?>
"><?php echo ((is_array($_tmp='FORCE_RELIEVE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a></td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="7" scope="col">
  		<?php echo $this->_tpl_vars['noData']; ?>
 	
    </th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td colspan="7" scope="col">
  		<?php echo ((is_array($_tmp='SELECT_ALL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<input type="checkbox" onclick="$(':checkbox[name=\'idList[]\']').attr('checked',$(this).attr('checked'))" />
        <input type="button" class="btn-blue" onClick="del()" value="<?php echo ((is_array($_tmp='DELETE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />  
    </td>
  </tr>
</table>
</form>
<div align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>
<?php endif; ?>