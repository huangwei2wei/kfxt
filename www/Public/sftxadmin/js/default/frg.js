// JavaScript Document
function addValue(type,id,name,key,img){
	$("input[name='"+type+"_name_"+id+"']").val(name);
	$("input[name='"+type+"["+id+"]']").val(key);
	$("input[name='"+type+"Name["+id+"]']").val(name);
	if(typeof(img)!='undefined')$("input[name='"+type+"Img["+id+"]']").val(img);
	$("#showItem").hide();
}

function showObjMenu(curEvent){
	if(!objData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(objData,function(i,n){
		showDiv.append("<br><B>"+i+"</B><br>");
		$.each(n,function(childKey,childValue){
			showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'GetObj\','+id+',\''+childValue.Name+'\',\''+i+'.'+childKey+'\')">'+childValue.Name+'</a>');
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
		showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'Tool\','+id+',\''+n.toolsname+'\',\''+n.Id+'\',\''+n.toolsimg+'\')">'+n.toolsname+'</a>');		
	});
	showDiv.show();
}

function showEffMenu(curEvent){
	if(!effData)return false;
	var showDiv=$("#showItem");
	var id=curEvent.attr("num");
	showDiv.empty();
	$.each(effData,function(i,n){
		showDiv.append("<br><B>"+i+"</B><br>");
		$.each(n,function(childKey,childValue){
			showDiv.append('<a href="javascript:void(0)" onclick="addValue(\'EffObj\','+id+',\''+childValue.Name+'\',\''+childValue.Key+'\')">'+childValue.Name+'</a>');
		});
	});
	showDiv.show();
}

function addGetCond(curBtn){
	var select='<select name="GetOpcode['+condInputNum+']"><option value="1">大于</option><option value="2">小于</option><option value="3">等于</option></select>';
	curBtn.after('<div id="cond_'+condInputNum+'" class="addline"><input type="hidden" name="GetObj['+condInputNum+']" /><input type="hidden" name="GetObjName['+condInputNum+']" />对象属性名称：<input type="text" class="text" name="GetObj_name_'+condInputNum+'" num="'+condInputNum+'" onclick="showObjMenu($(this))" /> '+select+' <input type="text" class="text" name="GetValue['+condInputNum+']" value="0" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#cond_"+'+condInputNum+').remove();\' /></div>');
	condInputNum++;
}

function addEffect(curBtn){
	var select='<select name="EffOpcode['+effectInputNum+']"><option value="1">增加</option><option value="2">减少</option><option value="3">改为</option></select>';
	curBtn.after('<div id="effect_'+effectInputNum+'" class="addline"><input type="hidden" name="EffObj['+effectInputNum+']" /><input type="hidden" name="EffObjName['+effectInputNum+']" />对象属性名称：<input num='+effectInputNum+' type="text" class="text" name="EffObj_name_'+effectInputNum+'" onclick="showEffMenu($(this))" /> '+select+' <input type="text" class="text" name="EffValue['+effectInputNum+']" value="0" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#effect_"+'+effectInputNum+').remove();\' /></div>');
	effectInputNum++;
}

function addTool(curBtn){
	curBtn.after('<div id="tool_'+toolInputNum+'" class="addline"><input type="hidden" name="Tool['+toolInputNum+']" /><input type="hidden" name="ToolName['+toolInputNum+']" /><input type="hidden" name="ToolImg['+toolInputNum+']" />对象属性名称：<input type="text" class="text" name="Tool_name_'+toolInputNum+'" num="'+toolInputNum+'" onclick="showToolMenu($(this))" /> <input type="text" class="text" name="ToolNum['+toolInputNum+']" value="0" /> <input type="button" class="btn-blue" value="删除本行" onclick=\'$("#tool_"+'+toolInputNum+').remove();\' /></div>');
	toolInputNum++;
}