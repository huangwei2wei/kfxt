<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:54
         compiled from QualityCheck/AllReply.js.html */ ?>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
function openDialog(href){
	var id=href.attr("id");
	if($("#dialog_"+id).length){//如果找到这个id就表示打开过这个窗口
		$("#dialog_"+id).slideToggle("normal");
	}else{
		href.parent().parent().after('<tr><th colspan="13"><iframe id="dialog_'+id+'" style="display:none" marginwidth="0" width="100%" height="250" marginheight="0" frameborder="0" scrolling="auto" src="'+href.attr("url")+'"></iframe></th></tr>');
		$("#dialog_"+id).slideDown("normal");
	}
}

$(function(){
	$("form :radio").click(function(){$("form").submit();});
})
</script>