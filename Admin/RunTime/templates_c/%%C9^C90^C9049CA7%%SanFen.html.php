<?php /* Smarty version 2.6.26, created on 2013-04-07 16:58:52
         compiled from ActionGame_MasterTools/OnLine/SanFen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'ActionGame_MasterTools/OnLine/SanFen.html', 7, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<fieldset>
<legend><?php echo ((is_array($_tmp='DC77F8A9FE92D3310E0595B28EA437DC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <th colspan="7" scope="col"><?php echo $this->_tpl_vars['onlineUserNum']; ?>
</th>
      </tr>
      <tr>
        <th scope="col">在线用户（人）</th>
      </tr>
      <tr>
        <td><?php echo $this->_tpl_vars['data']; ?>
</td>
      </tr>
    </table>
</fieldset>
<?php endif; ?>