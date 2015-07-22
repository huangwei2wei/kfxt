<?php /* Smarty version 2.6.26, created on 2013-04-07 10:23:06
         compiled from ActionGame_OperatorTools/ServerManagement/Default.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'ActionGame_OperatorTools/ServerManagement/Default.html', 2, false),array('modifier', 'intval', 'ActionGame_OperatorTools/ServerManagement/Default.html', 53, false),array('modifier', 'default', 'ActionGame_OperatorTools/ServerManagement/Default.html', 57, false),array('modifier', 'truncate', 'ActionGame_OperatorTools/ServerManagement/Default.html', 58, false),array('function', 'html_radios', 'ActionGame_OperatorTools/ServerManagement/Default.html', 11, false),)), $this); ?>
<fieldset>
	<legend><?php echo ((is_array($_tmp='E5F71FC31E7246DD6CCC5539570471B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
  <form action="" method="get" id="search">
   	  <input type="hidden" name="c" value="OperatorTools" />
      <input type="hidden" name="a" value="ServerManagement" />
      <input type="hidden" name="zp" value="ActionGame" />
      <input type="hidden" name="__game_id" value="<?php echo $this->_tpl_vars['get']['__game_id']; ?>
" />
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['operatorList'],'name' => 'operator_id','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['get']['operator_id']), $this);?>
</td>
        </tr>
        <tr>
          <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='48FEC81C5CF76DDA609C1DB09DBE801D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['timer'],'name' => 'timer','selected' => $this->_tpl_vars['get']['timer'],'onclick' => "$('#search').submit()"), $this);?>
</td>
        </tr>
          <tr>
            <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='E5F71FC31E7246DD6CCC5539570471B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <td>
            ID:<input type="text" class="text" name="Id" value="<?php echo $this->_tpl_vars['get']['Id']; ?>
" />
            <?php echo ((is_array($_tmp='B425713F0D82F61D992F068D136B71FA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" class="text" name="server_name" value="<?php echo $this->_tpl_vars['get']['server_name']; ?>
" />
            <?php echo ((is_array($_tmp='45235F6C8FA2F6BBB0D5B69742EB1076')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" class="text" name="marking" value="<?php echo $this->_tpl_vars['get']['marking']; ?>
" />
            URL:<input type="text" class="text" name="server_url" value="<?php echo $this->_tpl_vars['get']['server_url']; ?>
" />
            <input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='939D5345AD4345DBAABE14798F6AC0F1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />
            </td>
          </tr>
      </table>
    </form>
</fieldset>
<fieldset>
  <legend><?php echo ((is_array($_tmp='045677D08976BA2C328E374549F38E54')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
[<a href="<?php echo $this->_tpl_vars['cacheurl']; ?>
"><?php echo ((is_array($_tmp='304AFC3FFE8791D14752D68F57620F5C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>]
[<a href="<?php echo $this->_tpl_vars['addurl']; ?>
"><?php echo ((is_array($_tmp='9FE510CC0F346168659549CB53E0FFDC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>]
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th><?php echo ((is_array($_tmp='9D1AF8A250315FB503099EA5C384E1E7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='CE00B3927EFF976B963DFA0401B5E0C4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='B425713F0D82F61D992F068D136B71FA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='45235F6C8FA2F6BBB0D5B69742EB1076')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='0A8FF2A88C6B44914A68D91883010286')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='1220B05A052977A05451A3ECBAF8BD21')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='5D57821D7E851B35F235735179F598D9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='FEC3D6E4792AC745850D2622F016E8CD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th><?php echo ((is_array($_tmp='2B6BC0F293F5CA01B006206C2535CCBC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_game_type']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_operator_name']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['ordinal'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['server_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['marking']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['time_zone']; ?>
</td>
    <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['timezone'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">no</font>') : smarty_modifier_default($_tmp, '<font color="#999999">no</font>')); ?>
</td>
    <td title="<?php echo $this->_tpl_vars['list']['server_url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['server_url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 70) : smarty_modifier_truncate($_tmp, 70)); ?>
</td>
    <td>
    	<?php if ($this->_tpl_vars['list']['timer']): ?>
        <?php echo ((is_array($_tmp='CC42DD3170FDF36BDC2B0F58AB23EB84')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

        <?php else: ?>
        <font color="#999999"><?php echo ((is_array($_tmp='B15D91274E9FC68608C609999E0413FA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>
        <?php endif; ?>
    </td>
    <td>
    	[<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
"><?php echo ((is_array($_tmp='95B351C86267F3AEDF89520959BCE689')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>]
        [<a onclick="return confirm('<?php echo ((is_array($_tmp='187D1FE0248DC951304185D455EC5437')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
?')" href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
"><?php echo ((is_array($_tmp='2F4AADDDE33C9B93C36FD2503F3D122B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>]
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><th colspan="11"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
  <?php endif; unset($_from); ?>
  <tr><td colspan="11" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
</table>
</fieldset>