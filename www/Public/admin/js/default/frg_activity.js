// JavaScript Document
function addValue(type,id,name,key,img){
	if(type=='AffectedTools'){
		$("input[name='Activity["+type+"]["+id+"][toolsid]']").val(key);
		$("input[name='Activity["+type+"]["+id+"][toolsname]']").val(name);
	}else{
		$("input[name='Activity["+type+"]["+id+"][PropName]']").val(key);
		$("input[name='Activity["+type+"]["+id+"][PropDesc]']").val(name);
	}

	$("#showItem").hide();
}

function showObjMenu(curEvent){
	if(!objData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(objData,function(i,n){
		showDiv.append("<br><B>"+n.Name+"</B><br>");
		$.each(n.Config,function(childKey,childValue){
			showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'AcceptCond\','+id+',\''+childValue.Name+'\',\''+i+'.'+childKey+'\')">'+childValue.Name+'</a>');
		});
	});
	showDiv.show();
}

function showToolMenu(curEvent){
	if(!toolData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(toolData,function(i,n){
		showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'AffectedTools\','+id+',\''+n.toolsname+'\',\''+n.Id+'\',\''+n.toolsimg+'\')">'+n.toolsname+'</a>');		
	});
	showDiv.show();
}

function showEffMenu(curEvent){
	if(!objData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(objData,function(i,n){
		showDiv.append("<br><B>"+n.Name+"</B><br>");
		$.each(n.Config,function(childKey,childValue){
			showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'AffectedProperties\','+id+',\''+childValue.Name+'\',\''+i+'.'+childKey+'\')">'+childValue.Name+'</a>');
		});
	});
	showDiv.show();
}

function addGetCond(curBtn){
	var select='<select name="Activity[AcceptCond]['+condInputNum+'][Opcode]"><option value="1">大于</option><option value="2">小于</option><option value="3">等于</option></select>';
	curBtn.after('<div id="cond_'+condInputNum+'" class="addline"><input type="hidden" name="Activity[AcceptCond]['+condInputNum+'][PropName]" />对象属性名称：<input type="text" class="text" name="Activity[AcceptCond]['+condInputNum+'][PropDesc]" num="'+condInputNum+'" onclick="showObjMenu($(this))" /> '+select+' <input type="text" class="text" name="Activity[AcceptCound]['+condInputNum+'][Num]" value="0" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#cond_"+'+condInputNum+').remove();\' /></div>');
	condInputNum++;
}

function addEffect(curBtn){
	var select='<select name="Activity[AffectedProperties]['+effectInputNum+'][Opcode]"><option value="1">增加</option><option value="2">减少</option><option value="3">改为</option></select>';
	curBtn.after('<div id="effect_'+effectInputNum+'" class="addline"><input type="hidden" name="Activity[AffectedProperties]['+effectInputNum+'][PropName]" />对象属性名称：<input num='+effectInputNum+' type="text" class="text" name="Activity[AffectedProperties]['+effectInputNum+'][PropDesc]" onclick="showEffMenu($(this))" /> '+select+' <input type="text" class="text" name="Activity[AffectedProperties]['+effectInputNum+'][Num]" value="0" /> 获得几率 <input type="text" class="text" value="1000" name="Activity[AffectedProperties]['+effectInputNum+'][Rate]" /> 获得后忽略其它属性 <input type="checkbox" value="1" name="Activity[AffectedProperties]['+effectInputNum+'][Unique]" /> 有效期 <input type="text" class="text" value="0" name="Activity[AffectedProperties]['+effectInputNum+'][Expire]" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#effect_"+'+effectInputNum+').remove();\' /></div>');
	effectInputNum++;
}

function addTool(curBtn){
	curBtn.after('<div id="tool_'+toolInputNum+'" class="addline"><input type="hidden" name="Activity[AffectedTools]['+toolInputNum+'][toolsid]" />对象属性名称：<input type="text" class="text" num="'+toolInputNum+'" name="Activity[AffectedTools]['+toolInputNum+'][toolsname]" onclick="showToolMenu($(this))" /> <input type="text" class="text" name="Activity[AffectedTools]['+toolInputNum+'][Num]" value="0" /> 获得几率 <input type="text" class="text" value="1000" name="Activity[AffectedTools]['+toolInputNum+'][Rate]" /> 获得后忽略其它属性 <input type="checkbox" value="1" name="Activity[AffectedTools]['+toolInputNum+'][Unique]" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#tool_"+'+toolInputNum+').remove();\' /></div>');
	toolInputNum++;
}