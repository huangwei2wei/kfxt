// JavaScript Document

function addValue(type,id,name,key){
	$("input[name='"+type+"Id["+id+"]']").val(key);
	//$("#"+type+"Name"+id).val(name);
	$("input[name='"+type+"Name["+id+"]']").val(name);
	$("#showItem").hide();
}

function showToolMenu(curEvent){
	if(!itemData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(itemData,function(i,n){
		showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'Item\','+id+',\''+n+'\','+i+');">'+n+'</a>  ');		//<a href="javascript:void(0)" onclick="addValue(\'Item\',id,n,i);">'+n+'</a>
	});
	showDiv.show();
}

function addTool(curBtn){
	curBtn.after('<div id="item_'+itemInputNum+'" class="addline"><input type="hidden" name="ItemId['+itemInputNum+']" /> 道具名:<input type="text" class="text" name="ItemName['+itemInputNum+']" num="'+itemInputNum+'" onclick="showToolMenu($(this))" /><input type="text" class="text" name="ItemNum['+itemInputNum+']" value="0" /><input type="button" class="btn-blue" value="删除本行" onclick=\'$("#item_"+'+itemInputNum+').remove();\' /></div>');
	itemInputNum++;
}