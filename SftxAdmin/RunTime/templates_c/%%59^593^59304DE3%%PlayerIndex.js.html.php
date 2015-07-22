<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:48
         compiled from Faq/PlayerIndex.js.html */ ?>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery-treeview/jquery.treeview.min.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery-treeview/jquery.treeview.async.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery-treeview/jquery.cookie.js"></script>
<link rel="stylesheet" href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery-treeview/jquery.treeview.css" />
<script language="javascript">

function displayFaq(id){
	if(!id)return false;
	var content=$("#content");
	$.getJSON("<?php echo $this->_tpl_vars['url']['Faq_PlayerKindFaq']; ?>
",
			  {kindId:id},
			  function(data){
				content.empty();
			  	$.each(data,function(i,n){
					i++;
					content.append('<div> <font color="#FF0000">'+i+'</font> [<a href="'+n.url_edit+'">编辑</a>] [<a href="javascript:void(0)" url="'+n.url_del+'" onclick="delFaq($(this))" ">删除</a>]<br /><b>问题：</b><span>'+n.question+'</span><br /><b>回答：</b><span>'+n.answer+'</span><br /></div>');
				})
			  }
	);
}
function displayTree(id){
	if(!id)return false;
	var tree=$("#tree_view");
	tree.empty();
	tree.treeview({url:"<?php echo $this->_tpl_vars['url']['Faq_PlayerKindTree']; ?>
&Id="+id});
}

function delFaq(faqResult){
	if(confirm("确证要删除吗？")){
		var url=faqResult.attr("url");
		$.getJSON(
			url,
			function(data){
				if(data.status==1){
					faqResult.parent("div").remove();
				}else{
					alert("删除失败");
				}
			}
		);
		
	}
}
</script>