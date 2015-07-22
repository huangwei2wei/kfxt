<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:48
         compiled from Faq/PlayerIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Faq/PlayerIndex.html', 9, false),)), $this); ?>
<fieldset id="main">
	<legend>FAQ管理</legend>
	<div id="kind">
    	[<a href="<?php echo $this->_tpl_vars['url']['Faq_PlayerKindIndex']; ?>
">FAQ分类,关键字管理</a>]
        [<a href="<?php echo $this->_tpl_vars['url']['Faq_PlayerIndex_ratio']; ?>
">FAQ点击率管理</a>]
    <br />
    	<select onchange="displayTree($(this).val())">
        	<option value="">请选择游戏</option>
        	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gameTypeKind']), $this);?>

        </select>
    	<ul id="tree_view">
        </ul>
    </div>
    
    <div id="content">
    </div>
    <div style="clear:both"></div>
</fieldset>

