// JavaScript Document
function showQuality(href){
	if(href.siblings("table").length!=0)return false;//如果有table,就表示已经显示了
	var qaId=href.attr("qa_id");
	$.getJSON(
		url,
		{qa_id:qaId,c:'QualityCheck',a:'ShowQuality'},
		function(data){
			if(data.status==1){
				var dataList=data.data;
				href.after('<table width="100%" border="1" cellpadding="3"><tr><th nowrap="nowrap" scope="row">当前状态：</th><td><b>'+dataList.word_status+'</b></td></tr><tr><th nowrap="nowrap" scope="row">质检人：</th><td class="quality1">'+dataList.word_quality_user_id+'</td></tr><tr><th nowrap="nowrap" scope="row">评价：</th><td class="quality1">'+dataList.word_option_id+'</td></tr><tr><th nowrap="nowrap" scope="row">所扣分数：</th><td class="quality1">'+dataList.scores+'</td></tr><tr><th nowrap="nowrap" scope="row">质检内容：</th><td class="quality1"><span class="time">'+dataList.quality_time+'</span><br>'+dataList.quality_content+'</td></tr><tr><th nowrap="nowrap" scope="row">申诉：</th><td class="quality2">'+dataList.complain_content+'</td></tr><tr><th nowrap="nowrap" scope="rom">回复申诉：</th><td class="quality3"><span class="time">'+dataList.reply_time+'</span><br>'+dataList.reply_content+'</td></tr><tr><th nowrap="nowrap" scope="rom">复检：</th><td class="quality4"> '+dataList.word_again_user_id+' <span class="time">'+dataList.again_time+'</span><br>'+dataList.again_content+'</td></tr><td colspan="2"><input type="button" value="加入归档" class="btn-blue" onclick="location.href=\''+dataList.url_doc+'\'" /></td><tr></tr></table>');
			}else{
				alert('此条回复未经过质检');
			}
		}
	);
}

function readMail(href){
	var isRead=href.attr("is_read");
	isRead=parseInt(isRead);
	if(!isRead){
		$.ajax({
			type:'GET',
			url:url,
			async:false,
			cache:false,
			data:{c:'User',a:'Mail',doaction:'read',Id:href.attr("cur_id")}
		});
	}	
	location.href=href.attr("url");
}

function checkboxSelectAll(curBox,idName){
	var curCheck=curBox.attr("checked");
	$(":checkbox[name='"+idName+"']").attr("checked",curCheck);
}

function formOrder(order,formId,by){
	var curForm=$("#"+formId);
	$("#order").val(order);
	$("#by").val(by);
	curForm.submit();
}

function pageInput(){
	var url=$("#pageButton").attr("url");
	var page=parseInt($("#pageIndex").val());
	url=url+page
	location.href=url;
	return;
}

function allCheckBox(parentDiv,curBox){
	$("#"+parentDiv+" :checkbox").attr("checked",curBox.attr("checked"));
}

$(window).keydown(function(event){
		switch(event.keyCode){
			case 13 :{
				if(document.activeElement.id=='pageIndex'){
					pageInput();
				}
				break;
			}
		}
	}
);