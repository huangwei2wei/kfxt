<?php /* Smarty version 2.6.26, created on 2013-04-02 10:44:19
         compiled from Default/Main.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->_tpl_vars['global']['title']; ?>
</title>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/formValidator/style/validator.css" />
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/jquery.form.js" ></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/formValidator/formValidator.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/formValidator/formValidatorRegex.js"></script>
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/default/common.js"></script>
</head>
<?php if ($this->_tpl_vars['tpl']['top']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tpl']['top'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<!--css开始-->
<?php if ($this->_tpl_vars['css']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['css'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<!--css结束-->

<!--javascript开始-->
<?php if ($this->_tpl_vars['js']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['js'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<!--javascript结束-->
<body>
<!--导航条-->
<?php if ($this->_tpl_vars['tpl']['navBar']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tpl']['navBar'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<!--导航条结束-->

<?php if ($this->_tpl_vars['tpl']['body']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tpl']['body'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['tpl']['bottom']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tpl']['bottom'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
</body>
</html>