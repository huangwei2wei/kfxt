<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:37
         compiled from Index/Left.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8" />
<title>管理页面</title>
<script language="javascript" type="text/javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.js"></script>
<style type="text/css">
body {
  font-family:"微软雅黑",Verdana,"宋体", Geneva, sans-serif;
  font-size: 14px;
}

p {
  line-height: 1.5em;
}

ul.menu, ul.menu ul {
  list-style-type:none;
  margin: 0;
  padding: 0;
  width: 100%;
}

ul.menu a {
  display: block;
  text-decoration: none;	
}

ul.menu li {
	border-bottom:1px #8ba7bf solid;
}

ul.menu li a {
  background: #044d82;
  color: #fff;	
  padding: 0.5em;
  font-weight:bold;
}

ul.menu li a:hover {
  background: #07375a;
  font-weight:bold;
}

ul.menu li ul li a {
  background: #c8dae8;
  color: #16456f;
  padding-left: 20px;
  font-weight:bold;
}

ul.menu li ul li a:hover {
  background: #f2f7f9;
  border-left: 5px #09F solid;
  padding-left: 15px;
  font-weight:bold;
}

.code { border: 1px solid #ccc; list-style-type: decimal-leading-zero; padding: 5px; margin: 0; }
.code code { display: block; padding: 3px; margin-bottom: 0; }
.code li { background: #ddd; border: 1px solid #ccc; margin: 0 0 2px 2.2em; }
.indent1 { padding-left: 1em; }
.indent2 { padding-left: 2em; }
.indent3 { padding-left: 3em; }
.indent4 { padding-left: 4em; }
.indent5 { padding-left: 5em; }
</style>
<script language="javascript">
function initMenus() {
	$('ul.menu ul').hide();
	$.each($('ul.menu'), function(){
		$('#' + this.id + '.expandfirst ul:first').show();
	});
	$('ul.menu li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if($('#' + parent).hasClass('noaccordion')) {
				$(this).next().slideToggle('normal');
				return false;
			}
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				if($('#' + parent).hasClass('collapsible')) {
					$('#' + parent + ' ul:visible').slideUp('normal');
				}
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
$(document).ready(function() {initMenus();});
</script>

</head>

<body>
	<ul id="menu4" class="menu collapsible expandfirst">
   		<?php $_from = $this->_tpl_vars['menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
		<li>
			<a href="#"><?php echo $this->_tpl_vars['list']['name']; ?>
</a>
			<ul>
            	<?php $_from = $this->_tpl_vars['list']['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childList']):
?>
            	<li><a href="<?php echo $this->_tpl_vars['childList']['url']; ?>
" target="main"><?php echo $this->_tpl_vars['childList']['name']; ?>
</a></li>
                <?php endforeach; endif; unset($_from); ?>
			</ul>
		</li>
		<?php endforeach; endif; unset($_from); ?>
	</ul>

</body>
</html>