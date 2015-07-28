<?php /* Smarty version 2.6.26, created on 2013-04-02 10:44:19
         compiled from BaseZp/ServerSelect.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'BaseZp/ServerSelect.html', 40, false),)), $this); ?>
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
	gameServer.append("<option value=''>服务器选择</option>");
	$.each(gameServerList,function(i,n){
		if(n.operator_id==operatorId)gameServer.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
	});
}
/**
 *检测是否能提交
 */
function checksub(){
	if(!$("#server_id").val()){
		alert("请选选择服务器");
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
&zp=<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
&__game_id=<?php echo $this->_tpl_vars['_GET']['__game_id']; ?>
&server_id="+serverId;
}

$(function(){
	if(selectedOperatorId)$("#operator").val(selectedOperatorId).change();
	if(selectedServerId)$("#gameServer").val(selectedServerId);
})
</script>
<fieldset>
	<legend>服务器选择</legend>
    <select id="operator" onChange="chageServer($(this).val())">
    	<option value="">请选择运营商</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList']), $this);?>

    </select>
    <select id="gameServer" onChange="chageServerId($(this).val())">
    	<option value="">请先选择服务器...</option>
    </select>
    <span>当前选择服务器：<span id="server_name" style="color:#F00"><?php if ($this->_tpl_vars['selectedServername']): ?><?php echo $this->_tpl_vars['selectedServername']; ?>
 (<?php echo $this->_tpl_vars['current_time_zone']; ?>
)<?php else: ?>未选择服务器<?php endif; ?></span></span>
</fieldset>

<?php if ($this->_tpl_vars['errorConn']): ?>
<div style="color:#000; border:1px dashed #333; padding:3px; margin:3px; color:#000; font-weight:bold; text-align:center; background:#FFECEC;">连接[<font color="#FF0000"><?php echo $this->_tpl_vars['selectedServername']; ?>
</font>]游戏服务器失败</div>
<?php endif; ?>