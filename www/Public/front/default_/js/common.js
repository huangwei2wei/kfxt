// JavaScript Document
function setTab(m,n){
 var tli=document.getElementById("menu"+m).getElementsByTagName("li");
 var mli=document.getElementById("main"+m).getElementsByTagName("ul");
 for(i=0;i<tli.length;i++){
  tli[i].className=i==n?"hover":"";
  mli[i].style.display=i==n?"block":"none";
 }
}

function searchFaq(gameTypeId){
	$v=$('#faqkeyword').val();
	if($v==''){
		alert('请输入关键词！');
		return false;
	}
    $v=encodeURI($v);
	window.location.href='/index.php?s=/Faq/search/game_type_id/'+gameTypeId+'/keyword/'+$v;
}
function addfavorite(url, name)
{
	var ua = navigator.userAgent.toLowerCase();
	if(ua.indexOf("msie 8")>-1){
		external.AddToFavoritesBar(url,name,name);//IE8
	}else{
		try {
			window.external.addFavorite(url, name);
		} catch(e) {
			try {
				window.sidebar.addPanel(name, url, "");//firefox
			} catch(e) {
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		}
	}
    return false;
}


/**
*加载问题类型
**/
function loadQuestionTypes(gameId){
	if(gameId!=''){
		var len = questionTypes.length;
		var obj=$('#question_type');
		obj.empty();
		obj.append('<option selected="selected" value="">　　请选择问题类型　　</option>');
		for(var i=0;i<len;i++){
			if(questionTypes[i].game_type_id==gameId)
				obj.append("<option  value='"+questionTypes[i].Id+"'>"+questionTypes[i].title+"</option>");
		}
	}

}

function chooseQuestionType(qtype){
	if(qtype!=''){
		var len = questionTypes.length;
		for(var i=0;i<len;i++){
			if(questionTypes[i].Id==qtype){
				createForm(questionTypes[i].form_table);
				break;
			}
		}
	}

}

/*
加载游戏服务器列表
*/
function loadServers(gameid){
	if(gameid!=0){
		$.ajax({
			url: '/index.php?s=/Question/servers/gameid/'+gameid,
			type: 'GET',
			timeout: 10000,
			dataType: 'json',
			async:false,
			error: function(){
			},
			success: function(result){
				if(result.status==1 && result.data){
					var obj = $('#game_server_id');
					$.each(result.data,function(i,n){
						obj.append('<option value="'+n.Id+'">'+n.server_name+'</option>');
					})
				}
			}
		});
	}

}

/**
创建表单值
**/
function createForm(items){
	var obj = $('#dt_layer');
	obj.empty();
	if(items==null){
		return;
	}
	var len = items.length;
	for(var i=0;i<len;i++){
		var po = items[i];
		
		var required='';
		if(po.required==true){
			required=" class='required' ";
		}
		
		var temp="<dl><dt>";
			if(po.required==true){
				temp+="<span>*</span>";
			}
			if(po.type!='game_server_list'){
				temp+=po.title+"：";
			}else{
				temp+="服务器列表：";
			}
			temp+="</dt><dd>";
		if(po.type=='text'){
			temp+="<input  name='"+po.name+"' msg='"+po.title+"' "+required+" />";
		}else if(po.type=='select'){
			temp+="<select name='"+po.name+"' "+required+" msg='"+po.title+"' >";
			for(var j=0;j<po.options.length;j++){
				temp+="<option value='"+j+"'>"+po.options[j]+"</option>"
			}
			temp+="</select>";
		}else if(po.type=='game_server_list'){
			temp+="<select name='game_server_id' id='game_server_id' "+required+" msg='服务器列表'><option value=''>    请选择服务器列表    </option>";
			temp+="</select></dd></dl>";
			//obj.append(temp);
		}
		temp+="</dd></dl>";
		obj.append(temp);
		if(po.type=='game_server_list'){
			loadServers($('#game_type').val());
		}
	}
}

var fileI=1;
var fileCount=1;
/**
增加上传文件框
**/
function addUpFile(){
	if(fileCount<3){
		var obj = $('#upfile_list');
		var temp='<div id="file_'+fileI+'"><input type="file" style="width: 212px; height:22px" name="file_upload[];"/><span><a href="javascript:delUpFile(\'file_'+fileI+'\')" style="padding:4px 5px;margin-left:10px;">删除</a></span></div>';//
		fileI++;
		fileCount++;
		obj.append(temp);
	}else{
		alert('您最多能上传三张问题截图!');
	}
}

/**
删除上传文件框
**/
function delUpFile(inputId){
	$('#'+inputId).remove();
	fileCount--;
}

/**
 * 提交问题时搜索相关FAQ
 */

function loadLikeFaq(){
	var gameTypeId=$("#game_type").val();
	var title = $('#title').val();
	if(title=='')return ;
	$.ajax({
		url: '/index.php?s=/Faq/likeFaq',
		type: 'POST',
		timeout: 10000,
		data:{"game_type_id":gameTypeId,"title":title},
		dataType: 'json',
		async:true,
		success: function(result){
			if(result.status==1 && result.data){
				var len=result.data.length;

				if(len>0){
					$('#likefaq').css("display","block");
					var ls=$("#likefaq_ls");
					ls.empty();
					for(var i=0;i<len;i++){
						ls.append("<a target='_blank' title='"+result.data[i].question+"' href='/index.php?s=/Faq/show/Id/"+result.data[i].Id+"'>"+subStr(result.data[i].question,20)+"</a><br />");
					}
				}
			}
		}
	});
}
var Validation=function(id){
	Validation.formId=id;
	Validation.form;
	//初始化
	this.init = function(){
		Validation.form=$('#'+Validation.formId);
		//为form添加事件
		Validation.form.submit(function(){
			var inputs=Validation.form.find('.required');
			var point=true;
			if(typeof(inputs) != 'undefined'){
				inputs.each(function() {
					var jqThis=$(this);
						if($.trim(jqThis.val())==''){

							if(jqThis.attr('tagName').toLowerCase()=='select'){
								alert('请选择'+jqThis.attr('msg')+"!");
							}else{
								alert('请填写'+jqThis.attr('msg')+"!");
							}
							jqThis.focus();
							point=false;
							return false;
						}
   				 });
			}

			return point;
		});
	};
	this.init();
}

function fucCheckLength(strTemp){
	var i,sum;
	sum=0;
	for(i=0;i<strTemp.length;i++){
		if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255))
			sum=sum+1;
		else
			sum=sum+2;
	}
	return sum;
}

function subStr(s,l,d){
	if(s == undefined){
		return "";
	}
	l=l*2;
	var r = /[^\x00-\xff]/g;
	if(s.replace(r, "zz").length <= l){
		return s;    	
	} 
	var m = Math.floor(l/2);
	for(var i=m; i<s.length; i++){
		if(s.substring(0, i).replace(r, "zz").length>=l) {
			if(d==undefined){
				return s.substring(0, i) +"...";
			}else{
				return s.substring(0, i);
			}
		} 
	}
	return s;
}
