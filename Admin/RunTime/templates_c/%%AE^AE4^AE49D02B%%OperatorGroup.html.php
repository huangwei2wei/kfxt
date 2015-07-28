<?php /* Smarty version 2.6.26, created on 2013-04-08 18:13:14
         compiled from MasterFRG/OperatorGroup.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'MasterFRG/OperatorGroup.html', 11, false),)), $this); ?>

<fieldset>
	<legend>运营商分组管理</legend>
    <?php $_from = $this->_tpl_vars['operatorGroup']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['k'] => $this->_tpl_vars['operatorList']):
?>
        <?php if ($this->_tpl_vars['k'] === 'no_group'): ?>
            <fieldset>
                <legend>未分组</legend>
                <form action="" method="post">
                	<input type="hidden" name="doaction" value="add" />
                    <div>
                        <?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['operatorList'],'name' => 'operators','separator' => ' '), $this);?>

                    </div>
                    <input class="btn-blue" type="submit" value="新增分组" />
                </form>
            </fieldset>
        <?php else: ?>
            <fieldset>
                <legend><?php echo $this->_tpl_vars['k']; ?>
</legend>
                <div>
                    <?php $_from = $this->_tpl_vars['operatorList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['optName']):
?>
                    	<label style="color:#F00"><?php echo $this->_tpl_vars['optName']; ?>
</label> |
                    <?php endforeach; endif; unset($_from); ?>
                </div>
                <form action="" method="post">
                	<input type="hidden" name="doaction" value="del" />
                    <input type="hidden" name="operatorId" value="<?php echo $this->_tpl_vars['k']; ?>
" />
                    <input class="btn-blue" type="submit" value="删除" />
                </form>
                </fieldset>
         <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
</fieldset>

