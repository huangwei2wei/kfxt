<?php /* Smarty version 2.6.26, created on 2013-04-08 18:10:40
         compiled from FrgAudit/Audit.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'FrgAudit/Audit.html', 21, false),array('modifier', 'nl2br', 'FrgAudit/Audit.html', 88, false),array('function', 'html_radios', 'FrgAudit/Audit.html', 36, false),array('function', 'html_options', 'FrgAudit/Audit.html', 47, false),)), $this); ?>
<style type="text/css">
ul{
	margin:0px;
	padding:0px;
}
ul li{
	margin:3px;
	list-style-type: none;
	display:inline;
}
</style>
<script language="javascript">
$(function(){
	$("#allcheck").click(function() {
		var curCheck=$(this).attr("checked");
		$(":checkbox[name='Id[]']").attr("checked",curCheck);
	 });
	$("#search :radio").click(function(){$("#search").submit();});
})
function formSubmit(curBtn){
	if(confirm('<?php echo ((is_array($_tmp='4CEC2A2B2D19F8C1705FB195B1CE60F8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
?')){
		url=curBtn.attr("url");
		$("#form").attr("action",url);
		$("#form").submit();
	}
}
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='E5F71FC31E7246DD6CCC5539570471B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
  <form action="" id="search" method="get">
   	  <input type="hidden" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" name="c" />
      <input type="hidden" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" name="a" />
      <table width="100%" border="0" cellpadding="3">
        <tr>
          <th scope="row"><?php echo ((is_array($_tmp='226B0912184333C81BABF2F1894EC0C1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['auditType'],'selected' => $this->_tpl_vars['selectedType'],'name' => 'type'), $this);?>
</td>
        </tr>
        <tr>
          <th scope="row"><?php echo ((is_array($_tmp='3FEA7CA76CDECE641436D7AB0D02AB1B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['sendType'],'selected' => $this->_tpl_vars['selectedSend'],'name' => 'send'), $this);?>
</td>
        </tr>
        <tr>
          <th scope="row"><?php echo ((is_array($_tmp='E5F71FC31E7246DD6CCC5539570471B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          <td><?php echo ((is_array($_tmp='03DC497D0E2A591D16D95289F6B36A5D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

          	<input class="text" type="text" name="user_info" value="<?php echo $this->_tpl_vars['user_info']; ?>
" />
          	<?php echo ((is_array($_tmp='8AC8DA83627DF7B14DF32CDBC8D34C61')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

            <select onchange="$('#search').submit()" name="apply_user_id"><option value=""><?php echo ((is_array($_tmp='708C9D6D2AD108AB2C560530810DEAE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['selectedApplyUserId']), $this);?>
</select>
            ，<?php echo ((is_array($_tmp='82DA895CD022775EDF8CCB40B8F79E8F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 || <?php echo ((is_array($_tmp='7CA02F99BF6FCEC154785CF4352CACF6')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

              <select onchange="$('#search').submit()" name="audit_user_id">
              <option value=""><?php echo ((is_array($_tmp='708C9D6D2AD108AB2C560530810DEAE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
              <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['selectedAuditUserId']), $this);?>

          </select>
          <input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='939D5345AD4345DBAABE14798F6AC0F1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /> 
          <input type="submit" name="xls" class="btn-blue" value="<?php echo ((is_array($_tmp='FD103EA9F27C8FDA025C249A22A87D78')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
EXCEL" /></td>
        </tr>
      </table>
    </form>
</fieldset>
<fieldset>
	<legend><?php echo ((is_array($_tmp='3712972D84ADF48ACBD6AD24B4D75AD0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
    <table width="100%" border="0" cellpadding="3">
    <form action="" method="post" id="form">
      <tr>
        <th scope="col">Id</th>
        <th scope="col"><?php echo ((is_array($_tmp='719E1BFF45284FB514DB649B4AD7EBD6')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='8AC8DA83627DF7B14DF32CDBC8D34C61')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / IP</th>
        <th scope="col"><?php echo ((is_array($_tmp='C566CA59602C7C5C0D3FE5E18ADE447D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='82DA895CD022775EDF8CCB40B8F79E8F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 || <?php echo ((is_array($_tmp='7CA02F99BF6FCEC154785CF4352CACF6')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / IP</th>
        <th scope="col"><?php echo ((is_array($_tmp='5BA072C60BAD0E857EA4A9520D8D288A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='3FEA7CA76CDECE641436D7AB0D02AB1B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='CF13B1A67881F454AF469AA874F087E5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
  || <?php echo ((is_array($_tmp='ED9C26FBCCAD3A058C19209BF0DE52AB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='2B6BC0F293F5CA01B006206C2535CCBC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><input type="checkbox" name="Id[]" value="<?php echo $this->_tpl_vars['list']['Id']; ?>
" /><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td class="td_move" onclick="$('#detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
').toggle()"><?php echo $this->_tpl_vars['list']['word_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_apply_user_id']; ?>
 / <?php echo $this->_tpl_vars['list']['apply_ip']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_server_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_audit_user_id']; ?>
 / <?php echo $this->_tpl_vars['list']['audit_ip']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['create_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_is_send']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['send_time']; ?>
</td>
        <td><?php if ($this->_tpl_vars['list']['url_view']): ?><a href="<?php echo $this->_tpl_vars['list']['url_view']; ?>
"><?php echo ((is_array($_tmp='C15491DFBBEB7A1559B7BA594B40D082')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a><?php endif; ?></td>
      </tr>
      <tr>
      	<td colspan="9" <?php if ($this->_tpl_vars['list']['is_send'] == 1): ?>style="display:none"<?php endif; ?> id="detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
"><?php echo ((is_array($_tmp='C5AE2E7BD2CB8F79F3DD66FA7212B58B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<br>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['cause'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

            
            <?php if ($this->_tpl_vars['list']['is_send'] == 1): ?>
            <!--<?php echo ((is_array($_tmp='E48F29D4C3A6372CC117F6B8175B13D6')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
-->
            <hr size="1" />
            <?php echo ((is_array($_tmp='93278C6BB2039F76939A67CCD33D9E34')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<br />
				<?php echo $this->_tpl_vars['list']['send_result']; ?>

            <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <td colspan="9"><?php echo $this->_tpl_vars['noData']; ?>
</td>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
     	<td><input type="checkbox" id="allcheck" /><?php echo ((is_array($_tmp='66EEACD93A7C1BDA93906FE908AD11A0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</td>
        <td colspan="8">
        	<!--input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['OperationFRG_AuditDel']; ?>
" value="<?php echo ((is_array($_tmp='1EF84829E0071AC9EF3C9CB6ECA6DC58')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /-->
        	<input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['accept']; ?>
" value="<?php echo ((is_array($_tmp='CF13B1A67881F454AF469AA874F087E5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />
            <input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['reject']; ?>
" value="<?php echo ((is_array($_tmp='7173F80900EA2FF9FEC6568115611305')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />
        </td>
      </tr>
      </form>
      <tr>
        <td colspan="9" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td>
      </tr>
    </table>
    
</fieldset>