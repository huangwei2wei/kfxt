<?php /* Smarty version 2.6.26, created on 2013-04-08 18:12:31
         compiled from WorkOrder/Index.js.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'WorkOrder/Index.js.html', 15, false),)), $this); ?>
<script language="javascript">
$(function(){ 
	$("#display_user").click(function(){$(".display_service").toggle();});
	$(".radio").click(function(){$("#form1").submit();});
	$(".order_title").hover(function(){$(this).addClass("td_move")},function(){$(this).removeClass("td_move")});
	$(".order_title").one('click',function(){
		var curTd=$(this);
		$.getJSON(
			$(this).attr("url"),
			function(data){
				if(data.status==1){
					var tmpStr='';
					$.each(data.data,function(i,n){
						if(n.qa==0){
							tmpStr+='<div style="background:#FFE1E1; border:1px dashed #CCC; padding:10px; padding-top:3px; margin:10px;">'+n.create_time+'&nbsp;<font style="font-weight:bold"><?php echo ((is_array($_tmp='47DADC0EB7DB960790F0E9926724AB63')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：</font><br />'+n.content+'</div>';
						}else{
							tmpStr+='<div style="background:#D9FFDC; border:1px dashed #CCC; padding:10px; padding-top:3px; margin:10px; margin-left:50px;">';
							if(n.is_timeout==1)tmpStr+='<font color="#FF0000"><b><?php echo ((is_array($_tmp='0E7B6CB416D3EC8D8ECBAC75EAC4F513')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</b></font> ';
							if(n.is_quality!=0){
								tmpStr+='<a qa_id="'+n.Id+'" href="javascript:void(0)" onclick="showQuality($(this))"><?php echo ((is_array($_tmp='6FB49C8C71853B074DFF6030CE588C4C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>&nbsp;';
							}else{
								tmpStr+='<font color="#999999">[<?php echo ((is_array($_tmp='10C4744BCF74D883D9303E4111A17466')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
]</font>&nbsp;';
							}
							
							tmpStr+=n.word_reply_name+'&nbsp;'+n.create_time+'&nbsp;<font style="font-weight:bold"><?php echo ((is_array($_tmp='1EDFF073D48E05B9E79BB7516DA23A6E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：</font><br />'+n.content+'</div>';

						}
					});
					curTd.parent().after("<tr style='background:#FFF' id='"+curTd.attr("dialogId")+"'><td colspan='8'>"+tmpStr+"</td></tr>");
				}
			}
		);
	});
	
	$(".order_title").click(function(){
		var id=$(this).attr("dialogId");
		$("#"+id).toggle();
	})
})

function searchForm(curHref){
	$("#user_nickname").val(curHref.html());
	$("#form1").submit();
}

function addSearchUser(serviceId){
	$(":checkbox[value='"+serviceId+"']").attr("checked","checked");
	$("form").submit();
}
</script>
<style type="text/css">
.display_service{
	display:none;
}
</style>