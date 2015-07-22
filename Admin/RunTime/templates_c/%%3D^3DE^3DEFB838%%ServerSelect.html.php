<?php /* Smarty version 2.6.26, created on 2013-04-08 18:13:12
         compiled from MasterFRG/ServerSelect.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'MasterFRG/ServerSelect.html', 8, false),array('function', 'html_options', 'MasterFRG/ServerSelect.html', 41, false),)), $this); ?>
<script language="javascript">
var gameServerList=eval(<?php echo $this->_tpl_vars['gameServerList']; ?>
);
var selectedOperatorId='<?php echo $this->_tpl_vars['selectedOperatorId']; ?>
';
var selectedServerId='<?php echo $this->_tpl_vars['selectedServerId']; ?>
';
function chageServer(operatorId){
	var gameServer=$("#gameServer");
	gameServer.empty();
	gameServer.append("<option value=''><?php echo ((is_array($_tmp='SERVER_SELECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>");
	$.each(gameServerList,function(i,n){
		if(n.operator_id==operatorId)gameServer.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
	});
}

/**
 *<?php echo ((is_array($_tmp='54DF5C26137857FD8719619FB8650D9E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

 */
function checksub(){
	if(!$("#server_id").val()){
		alert("<?php echo ((is_array($_tmp='PLEASE_SELECT_SERVER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
");
		return false;
	}else{
		return true;
	}
}

function chageServerId(serverId){
	$("#server_id").val(serverId);
	$("#server_name").html($("#gameServer").find("option:selected").text());
	location.href=url+"?c=<?php echo $this->_tpl_vars['__CONTROL__']; ?>
&a=<?php echo $this->_tpl_vars['__ACTION__']; ?>
&server_id="+serverId;
}

$(function(){
	if(selectedOperatorId)$("#operator").val(selectedOperatorId).change();
	if(selectedServerId)$("#gameServer").val(selectedServerId);
})
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='SERVER_SELECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
    <select id="operator" onChange="chageServer($(this).val())">
    	<option value=""><?php echo ((is_array($_tmp='PLEASE_SELECT_OPERATOR')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList']), $this);?>

    </select>
    <select id="gameServer" onChange="chageServerId($(this).val())">
    	<option value=""><?php echo ((is_array($_tmp='PLEASE_SELECT_SERVER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
...</option>
    </select>
    <span><?php echo ((is_array($_tmp='CURRENT_SERVER_SELECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<span id="server_name" style="color:#F00"><?php if ($this->_tpl_vars['selectedServername']): ?><?php echo $this->_tpl_vars['selectedServername']; ?>
<?php else: ?><?php echo ((is_array($_tmp='WITHOUT_SERVER_SELECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<?php endif; ?></span> (<?php echo $this->_tpl_vars['current_time_zone']; ?>
)</span>
	<?php if ($this->_tpl_vars['selectedServerUrl']): ?><?php echo $this->_tpl_vars['selectedServerUrl']; ?>
<?php endif; ?>
</fieldset>

<?php if ($this->_tpl_vars['errorConn']): ?>
<div style="color:#000; border:1px dashed #333; padding:3px; margin:3px; color:#000; font-weight:bold; text-align:center; background:#FFECEC;"><?php echo ((is_array($_tmp='CONNECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
[<font color="#FF0000"><?php echo $this->_tpl_vars['selectedServername']; ?>
</font>]<?php echo ((is_array($_tmp='SERVER_FAIL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</div>
<?php endif; ?>