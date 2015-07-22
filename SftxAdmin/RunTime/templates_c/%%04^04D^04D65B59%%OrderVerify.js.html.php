<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:51
         compiled from Verify/OrderVerify.js.html */ ?>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/kindeditor/kindeditor.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.form.js"></script>
<script language="javascript" type="text/javascript">
KE.init({id:'content',imageUploadJson : '<?php echo $this->_tpl_vars['url']['uploadImgUrl']; ?>
',afterCreate:function(id){KE.util.focus(id)}});
var gameServerList=eval(<?php echo $this->_tpl_vars['gameServerList']; ?>
);
var verifyType=eval(<?php echo $this->_tpl_vars['verifyType']; ?>
);


var selectedGameTypeId='<?php echo $this->_tpl_vars['selectedGameTypeId']; ?>
';

var selectedOperatorId='<?php echo $this->_tpl_vars['selectedOperatorId']; ?>
';

var selectedServerId='<?php echo $this->_tpl_vars['selectedServerId']; ?>
';


function processJson(data){
	if(data.status==1){
		alert(data.msg);
		parent.$("#verify").attr("src",data.href);
	}else{
		alert(data.msg);
	}
}

function changeType(gameTypeId){//选择游戏之后找到问题类型和游戏服务器列表
    var operatorId=$("#operator_id").val();
    if(gameTypeId!=""){
        var askType=$("#type");
		askType.empty();
		for(j in verifyType[gameTypeId]){
			askType.append("<option value='"+j+"'>"+verifyType[gameTypeId][j]+"</option>");
		}
    }

    if(gameTypeId!="" && operatorId!=""){
        var gameServerSelect=$("#game_server_id");
        gameServerSelect.empty();
        $.each(gameServerList,function(i,n){
            if(n.game_type_id==gameTypeId && n.operator_id==operatorId){
                gameServerSelect.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
            }
        })
    }
}

function changeOperatorType(operatorId){//输出服务器列表
	var gameTypeId=$("#game_type").val();
	if(gameTypeId!="" && operatorId!=""){
        var gameServerSelect=$("#game_server_id");
        gameServerSelect.empty();
        $.each(gameServerList,function(i,n){
            if(n.game_type_id==gameTypeId && n.operator_id==operatorId){
                gameServerSelect.append("<option value='"+n.Id+"'>"+n.server_name+"</option>");
            }
        })
	}
}

$(function(){
	if(selectedGameTypeId)$("#game_type").val(selectedGameTypeId).change();
	if(selectedOperatorId)$("#operator_id").val(selectedOperatorId).change();
	if(selectedServerId)$("#game_server_id").val(selectedServerId).change();
});
</script>