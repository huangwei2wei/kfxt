<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:49
         compiled from Verify/Index.js.html */ ?>
<script language="javascript">
<?php if ($this->_tpl_vars['selectedGameTypeId']): ?>var selectedGameTypeId=<?php echo $this->_tpl_vars['selectedGameTypeId']; ?>
;<?php endif; ?>
<?php if ($this->_tpl_vars['selectedOperatorId']): ?>var selectedOperatorId=<?php echo $this->_tpl_vars['selectedOperatorId']; ?>
;<?php endif; ?>
<?php if ($this->_tpl_vars['selectedGameServerId']): ?>var selectedGameServerId=<?php echo $this->_tpl_vars['selectedGameServerId']; ?>
;<?php endif; ?>
<?php if ($this->_tpl_vars['selectedStatus']): ?>var selectedStatus=<?php echo $this->_tpl_vars['selectedStatus']; ?>
;<?php endif; ?>
<?php if ($this->_tpl_vars['selectedType']): ?>var selectedType=<?php echo $this->_tpl_vars['selectedType']; ?>
;<?php endif; ?>
<?php if ($this->_tpl_vars['selectedLevel']): ?>var selectedLevel=<?php echo $this->_tpl_vars['selectedLevel']; ?>
;<?php endif; ?>

var gameServerList=eval(<?php echo $this->_tpl_vars['gameServerList']; ?>
);
var verifyType=eval(<?php echo $this->_tpl_vars['verifyTypeJson']; ?>
);

function displayDetail(id){
	if(!id)return false;
	$("#detail_"+id).toggle();
}

$(function(){
	$(".list_title").hover(
		function(){$(this).addClass("td_move")},
		function(){$(this).removeClass("td_move")}
	);

	if(typeof(selectedGameTypeId)!='undefined')$("#game_type_id").val(selectedGameTypeId).change();
	if(typeof(selectedOperatorId)!='undefined')$("#operator_id").val(selectedOperatorId).change();
	if(typeof(selectedGameServerId)!='undefined')$("#game_server_id").val(selectedGameServerId).change();
	if(typeof(selectedStatus)!='undefined')$("#status").val(selectedStatus);
	if(typeof(selectedType)!='undefined')$("#type").val(selectedType);
	if(typeof(selectedLevel)!='undefined')$("#level").val(selectedLevel);
})

function changeStatus(curSelect){
	var value=curSelect.val();
	var name=curSelect.find("option:selected").text();
	if(confirm("确定改变为"+name+"状态吗?")){
		var id=curSelect.attr("listId");
		$.getJSON(
			"<?php echo $this->_tpl_vars['url']['Verify_ChangeStatus']; ?>
",
			{Id:id,status:value},
			function(data){
				alert(data.msg);
			}
		);
	}
}

function changeType(gameTypeId){//选择游戏之后找到问题类型和游戏服务器列表
    var operatorId=$("#operator_id").val();
    if(gameTypeId!=""){
        var askType=$("#type");
		askType.empty();
		askType.append("<option value=''>请选问题类型</option>");
		for(j in verifyType[gameTypeId]){
			askType.append("<option value='"+j+"'>"+verifyType[gameTypeId][j]+"</option>");
		}
    }

    if(gameTypeId!="" && operatorId!=""){
        var gameServerSelect=$("#game_server_id");
        gameServerSelect.empty();
		gameServerSelect.append("<option value=''>请选择服务器列表</option>");
        $.each(gameServerList,function(i,n){
            if(n.game_type_id==gameTypeId && n.operator_id==operatorId){
                gameServerSelect.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
            }
        })
    }
}

function changeOperatorType(operatorId){//输出服务器列表
	var gameTypeId=$("#game_type_id").val();
	if(gameTypeId!="" && operatorId!=""){
        var gameServerSelect=$("#game_server_id");
        gameServerSelect.empty();
		gameServerSelect.append("<option value=''>请选择服务器列表</option>");
        $.each(gameServerList,function(i,n){
            if(n.game_type_id==gameTypeId && n.operator_id==operatorId){
                gameServerSelect.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
            }
        })
	}
}

function searchForm(curHref,formId){
	$("#"+formId).val(curHref.html());
	$("#formSearch").submit();
}
</script>