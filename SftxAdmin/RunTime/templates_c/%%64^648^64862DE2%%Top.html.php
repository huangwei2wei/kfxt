<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:37
         compiled from Default/Top.html */ ?>
<script language="javascript">
var url="<?php echo $this->_tpl_vars['__ROOT__']; ?>
/sftxadmin.php";
$(function(){
	$("tr").live('mouseover',function(){$(this).addClass("hover");});
	$("tr").live('mouseout',function(){$(this).removeClass("hover");});
	$(":text").live('mouseover',function(){$(this).addClass("focus")});
	$(":text").live('mouseout',function(){$(this).removeClass("focus")});
	$("textarea").live('mouseover',function(){$(this).addClass("focus")});
	$("textarea").live('mouseout',function(){$(this).removeClass("focus")});
	$("tr:even").addClass("alt");
	$("#loadding").bind('ajaxStart',function(){
		$("body").css("cursor","wait");
		$(this).fadeIn("slow");
	}).bind('ajaxStop',function(){
		$("body").css("cursor","auto");
		$(this).fadeOut("slow");
	});
})
</script>
<style type="text/css">
<!--
.user_info{
	background:#FFF;
	border-bottom:1px solid #CCC;
	border-right:1px solid #CCC;
	border-top:1px solid #666;
	border-left:1px solid #666;
}
.search_option{
	background:#FFF;
	border-bottom:1px solid #CCC;
	border-right:1px solid #CCC;
	border-top:1px solid #666;
	border-left:1px solid #666;
}
.text{
	background:#FFF;
	border-bottom:1px solid #CCC;
	border-right:1px solid #CCC;
	border-top:1px solid #666;
	border-left:1px solid #666;
}
.focus{
	-moz-box-shadow:0 1px 1px rgba(196,196,196,0.5);
	-webkit-box-shadow:0 1px 1px rgba(196,196,196,0.5);
	box-shadow:0 1px 1px rgba(196,196,196,0.5);
	-webkit-focus-ring-color:none;
	border-bottom:1px solid #CCC;
	border-right:1px solid #CCC;
	border-top:1px solid #666;
	border-left:1px solid #666;
	background-color:#FFFFF0;
}
.alt{background:#FFF}
body,td,th {font-family: Verdana, Consolas, Geneva, sans-serif;font-size: 12px; color:#191919}
body { margin:0px; padding:0px;}
a {font-size: 12px;color: #09F;}
a:link {text-decoration: none;color: #06F;}
a:visited {text-decoration: none;color: #06F;}
a:hover {text-decoration: underline;color: #000000;}
a:active {text-decoration: none;color: #06F;}
table{margin:2px;border-collapse: collapse; border:0px;}
table th{
	background:#B8DEFA; 
	color:#2B2B2B; 
	border:1px solid #FFF;
	-moz-border-radius: 5px;
	-khtml-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
}
table tr{background:#EEFAFF; border-bottom:1px dashed #B5DAFF;}
input{
	padding:2px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 2px;
	font-family: Verdana, Consolas, Geneva, sans-serif;
	font-size: 12px;
}
.hover{background:#DFEFFF}
select{	
	border-bottom:1px solid #ccc;
	border-right:1px solid #ccc;
	border-top:1px solid #666;
	border-left:1px solid #666;
	padding:2px;
	-moz-border-radius: 3px;
	-khtml-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	}
fieldset{border:1px solid #999;}
.td_move{background:#71B8FF;cursor:pointer;}
#loadding{position:fixed;background:#09F; color:#FFF; padding:4px; width:25%; text-align:center; margin:auto 35%; display:none;}
textarea{
	font-size:12px; 
	background:#FFF; 
	border:1px solid #999;	
	-moz-border-radius: 5px;
	-khtml-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;}
.time{color:#09F;margin:1px;padding:1px;}
.vote{border-collapse:inherit; background:#E1F5FF; border:2px solid #0CF;}
form{margin:0px; padding:0px; border:0px;}

/* Buttons
---------------------------------------------------------------------*/

.btn {
	display: inline-block;
	padding: 5px 10px;
	color: #777 !important;
	text-decoration: none;
	font-weight: bold;
	font-size: 11px;
	font-family: Verdana, Tahoma, Arial, sans-serif;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	border-radius: 4px;
	text-shadow: 0 1px 1px rgba(255,255,255,0.9);
	position: relative;
	cursor: pointer;
	border:1px solid #ccc !important;
	background:#fff url("<?php echo $this->_tpl_vars['__IMG__']; ?>
/default/btn-overlay.png") repeat-x !important;
}
.btn:hover, .btn:focus, .btn:active {
	outline:medium none;
	border:1px solid #329ECC !important;
	opacity:0.9;
	-khtml-opacity: .9;
	-moz-opacity: 0.9;
	-moz-box-shadow:0 0 5px rgba(82, 168, 236, 0.5);
	-webkit-box-shadow: 0 0 5px rgba(82, 168, 236, 0.5);
	box-shadow: 0 0 5px rgba(82, 168, 236, 0.5);
}

.btn-green {
	color: #fff !important;
	text-shadow: 0 1px 1px rgba(0,0,0,0.25);
	border:1px solid #749217 !important;
	background-color: #6AB620 !important;
}
.btn-green:hover, .btn-green:focus, .btn-green:active {
	-moz-box-shadow:0 0 5px rgba(116, 146, 23, 0.9);
	-webkit-box-shadow: 0 0 5px rgba(116, 146, 23, 0.9);
	box-shadow: 0 0 5px rgba(116, 146, 23, 0.9);
	border:1px solid #749217 !important;
}

.btn-blue {
	color: #fff !important;
	text-shadow: 0 1px 1px rgba(0,0,0,0.25);
	border:1px solid #2D69AC !important;
	background-color: #3C6ED1 !important;
}
.btn-blue:hover, .btn-blue:focus, .btn-blue:active {
	-moz-box-shadow:0 0 5px rgba(71, 131, 243, 0.9);
	-webkit-box-shadow:0 0 5px rgba(71, 131, 243, 0.9);
	box-shadow: 0 0 5px rgba(71, 131, 243, 0.9);
	border:1px solid #2D69AC !important;
}

.btn-red {
	color: #fff !important;
	text-shadow: 0 1px 1px rgba(0,0,0,0.25);
	border:1px solid #AE2B2B !important;
	background-color: #D22A2A !important;
}
.btn-red:hover, .btn-red:focus, .btn-red:active {
	-moz-box-shadow:0 0 5px rgba(174, 43, 43, 0.9);
	-webkit-box-shadow:0 0 5px rgba(174, 43, 43, 0.9);
	box-shadow: 0 0 5px rgba(174, 43, 43, 0.9);
	border:1px solid #AE2B2B !important;
}

.btn-special {
	font-size:110%;
	width: 210px;
}

-->
</style>
<!--[if IE 6]>  
<style type="text/css">  
table td{border:1px solid #333;}
table th{border:1px solid #333;}
fileset{width:100%; border:1px solid #333;}
html{overflow:hidden;}  
body{height:100%;overflow:auto;}  
#loadding {
    position: absolute;
    bottom: auto;
    clear: both;
    top:expression(eval(document.compatMode &&
        document.compatMode=='CSS1Compat') ?
        documentElement.scrollTop
        +(documentElement.clientHeight-this.clientHeight) - 1
        : document.body.scrollTop
        +(document.body.clientHeight-this.clientHeight) - 1);
}
</style>  
<![endif]-->  
<div id="loadding">正在请求,请稍等...</div>