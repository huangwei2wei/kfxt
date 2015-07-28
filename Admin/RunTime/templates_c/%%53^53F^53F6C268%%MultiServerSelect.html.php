<?php /* Smarty version 2.6.26, created on 2013-04-07 10:24:16
         compiled from BaseZp/MultiServerSelect.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'BaseZp/MultiServerSelect.html', 55, false),)), $this); ?>
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
var gameServerList=eval(<?php echo $this->_tpl_vars['gameServerList']; ?>
);
var selectedOperatorId='<?php echo $this->_tpl_vars['selectedOperatorId']; ?>
';
var selectedServerId='<?php echo $this->_tpl_vars['selectedServerId']; ?>
';
function chageServer(operatorId){
	operatorId=parseInt(operatorId);
	var gameServer=$("#gameServer");
	gameServer.empty();
	gameServer.append("<li><input type='checkbox' checked onclick='selectAll($(this))'>全选</li><br>");
	$.each(gameServerList,function(i,n){
		if(operatorId===0){
			gameServer.append("<li><input type='checkbox' operator_id='"+n.operator_id+"' name='server_ids[]' checked value='"+n.Id+"' />"+n.full_name+'</li>');
		}else{
			if(n.operator_id==operatorId){
				var checked = '';
				if(n.Id == selectedServerId){
					checked = 'checked';
				}
				gameServer.append("<li id='server_"+n.Id+"'><input type='checkbox' name='server_ids[]' operator_id='"+n.operator_id+"' value='"+n.Id+"'"+checked+" />"+n.server_name+'</li>');
			}
		}
	});
	$("#operator_id").val(operatorId);
}

function selectAll(check){
	var curCheck=check.attr("checked");
	$(":checkbox[name='server_ids[]']").attr("checked",curCheck);
}

$(function(){
	if(selectedOperatorId)$("#operator").val(selectedOperatorId).change();
	$("#allcheck").click(function() {
		var curCheck=$(this).attr("checked");
		$("#list :checkbox").attr("checked",curCheck);
	 });
})
</script>
<fieldset>
	<legend>服务器选择</legend>
    
    <select id="operator" onChange="chageServer($(this).val())">
    	<option value="">请选择运营商</option>
        <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList']), $this);?>

		<!--option value="0">所有</option-->
    </select>
    <input type="button" class="btn-blue" value="显示/隐藏服务器列表" onclick="$('#gameServer').slideToggle('fast')" />
    <ul id="gameServer" style="padding:5px; margin:5px;"></ul>
</fieldset>