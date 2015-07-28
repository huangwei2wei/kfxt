<?php /* Smarty version 2.6.26, created on 2013-04-09 16:54:01
         compiled from GmSftx/NoticeIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/NoticeIndex.html', 8, false),array('modifier', 'date_format', 'GmSftx/NoticeIndex.html', 48, false),)), $this); ?>
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
</script>
<fieldset>

<legend><?php echo ((is_array($_tmp='NOTICE_LIST')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<form id="form" action="<?php echo $this->_tpl_vars['url']['GmSftx_PublicNotice_Del']; ?>
" method="post" onsubmit="return checksub();">
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <td colspan="9"><a href="<?php echo $this->_tpl_vars['url']['GmSftx_PublicNotice_Add']; ?>
"><?php echo ((is_array($_tmp='ADD_SYS_NOTICE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a></td>
    </tr>
  <tr>
    <th scope="col" nowrap="nowrap">S</th>
    <th scope="col" nowrap="nowrap"><?php echo ((is_array($_tmp='NOTICE_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='NOTICE_TITLE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='NOTICE_CONTENT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='URL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col" nowrap="nowrap">
    <?php echo ((is_array($_tmp='TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

    (
    	<?php if ($this->_tpl_vars['BeiJing_time']): ?>
        	<?php echo ((is_array($_tmp='BJT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

        <?php else: ?>
        	<?php echo ((is_array($_tmp='SERVER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

        <?php endif; ?>
    )
    </th>
    <th scope="col" nowrap="nowrap"><?php echo ((is_array($_tmp='INTERVAL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col" nowrap="nowrap"><input type="checkbox" value="<?php echo $this->_tpl_vars['list']['id']; ?>
" name="idList[]" /></td>
    <td scope="col" nowrap="nowrap"><?php echo $this->_tpl_vars['list']['id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['content']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['url']; ?>
</td>
    <td scope="col" nowrap="nowrap">
    	<?php echo ((is_array($_tmp='START_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<font style="font-weight:bold"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['begin'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</font><br/>
        <?php echo ((is_array($_tmp='END_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<font style="font-weight:bold"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['end'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</font><br/>
        <?php echo ((is_array($_tmp='CREATE_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<font style="font-weight:bold"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['createAt'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</font>
    </td>
    <td scope="col" nowrap="nowrap"><?php echo $this->_tpl_vars['list']['intervals']; ?>
</td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="9" scope="col">
  		<?php echo $this->_tpl_vars['noData']; ?>
 	
    </th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td colspan="9" scope="col">
  		<?php echo ((is_array($_tmp='SELECT_ALL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<input type="checkbox" onclick="$('#form :checkbox').attr('checked',$(this).attr('checked'))" /><input type="button" class="btn-blue" onClick="if(confirm('<?php echo ((is_array($_tmp='DEL_CONFIRM')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
?'))$('#form').submit();" value="<?php echo ((is_array($_tmp='DELETE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />  	
    </td>
  </tr>
</table>
</form>
</fieldset>
<?php endif; ?>