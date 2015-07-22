<?php /* Smarty version 2.6.26, created on 2013-04-09 10:22:59
         compiled from Group/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'Group/Index.html', 13, false),array('modifier', 'intval', 'Group/Index.html', 32, false),)), $this); ?>
<style type="text/css">
ul{
	margin:0px;
	padding:0px;
	list-style:none;
}
li{
	width:200px;
	display:inline-block;
}
</style>
<fieldset>
	<legend><?php echo ((is_array($_tmp='INDEX_LEGEND')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
[<a href="<?php echo $this->_tpl_vars['url']['Group_Add']; ?>
"><?php echo ((is_array($_tmp='URL_GROUP_ADD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>]
[<a href="<?php echo $this->_tpl_vars['cacheurl']; ?>
">生成缓存</a>]
[<a href="<?php echo $this->_tpl_vars['cacheuser']; ?>
">生成用户缓存</a>]
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col"><?php echo ((is_array($_tmp='th_1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_2')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_6')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    <th scope="col"><?php echo ((is_array($_tmp='th_7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <th rowspan="2" nowrap="nowrap"><?php echo $this->_tpl_vars['list']['_roomName']; ?>
</th>
    <td><?php echo $this->_tpl_vars['list']['in_room']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['userClassNum']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['_orderNum']['incomplete'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)); ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_entrance']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_exit']; ?>
</td>
    <td>
    	<a href="<?php echo $this->_tpl_vars['list']['url_monitor']; ?>
"><?php echo ((is_array($_tmp='INDEX_URL_MONITOR')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_edit_server']; ?>
"><?php echo ((is_array($_tmp='INDEX_URL_EDIT_SERVER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
    	<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
"><?php echo ((is_array($_tmp='EDIT')) ? $this->_run_mod_handler('lang', true, $_tmp, 'Common') : smarty_modifier_lang($_tmp, 'Common')); ?>
</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_rest']; ?>
"><?php echo ((is_array($_tmp='INDEX_URL_REST')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
        <a onclick="return confirm('<?php echo ((is_array($_tmp='INDEX_URL_INITALIZE_CONFIRM')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
')" href="<?php echo $this->_tpl_vars['list']['url_Initialize']; ?>
"><?php echo ((is_array($_tmp='INDEX_URL_INITALIZE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
    </td>
  </tr>
  <tr>
  	<td colspan="6">
    	<?php echo ((is_array($_tmp='SERVER_LIST')) ? $this->_run_mod_handler('lang', true, $_tmp, 'Common') : smarty_modifier_lang($_tmp, 'Common')); ?>
：<input type="button" class="btn-blue" value="<?php echo ((is_array($_tmp='INDEX_SWITCH')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" onclick="$(this).siblings('ul').toggle();" /><br />
        <ul style="display:none">
    	<?php $_from = $this->_tpl_vars['list']['server_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['serverList']):
?>
        <li><?php echo $this->_tpl_vars['serverList']['full_name']; ?>
</li>
        <?php endforeach; else: ?>
        <li><font color="#666666"><?php echo ((is_array($_tmp='NOT_SERVER_LIST')) ? $this->_run_mod_handler('lang', true, $_tmp, 'Common') : smarty_modifier_lang($_tmp, 'Common')); ?>
</font></li>
        <?php endif; unset($_from); ?>
        </ul>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="8"><?php echo $this->_tpl_vars['noData']; ?>
</th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td align="right" colspan="8"></td>
  </tr>
</table>
</fieldset>