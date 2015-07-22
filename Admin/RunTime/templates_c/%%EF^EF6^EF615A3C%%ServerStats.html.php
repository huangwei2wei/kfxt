<?php /* Smarty version 2.6.26, created on 2013-04-08 18:13:12
         compiled from MasterFRG/ServerStats.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'MasterFRG/ServerStats.html', 7, false),)), $this); ?>
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
        <th scope="col"><?php echo ((is_array($_tmp='D316330B742EA7B420665A3DF7EC374D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='BF43439FC7777B3B07B1A872DCD5F91D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='DB46D50D2B757C1FAE3DF24A2921472C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='443A93F96B78C1CD21E01C7D7B522E8C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='D865CD29E0FA9CF494F0CE3188C9F352')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='DA8ECE14F90D4772F089640AB0C4583D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='9FC2DF4C098C78C61164DA65C38957DC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['data_0']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_1']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_2']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_3']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_4']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_5']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['data_6']; ?>
</td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
    </table>
</fieldset>
<?php endif; ?>