<?php /* Smarty version 2.6.26, created on 2013-04-09 16:54:04
         compiled from GmSftx/GoodsLog.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/GoodsLog.html', 11, false),array('modifier', 'date_format', 'GmSftx/GoodsLog.html', 46, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>

</script>
<fieldset>
  <legend><?php echo ((is_array($_tmp='ITEM_SEARCH')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td colspan="7">
        <form action=""  method="get" onsubmit="return checksub();">
        	<input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
            <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
            <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
           <?php echo ((is_array($_tmp='ITEM_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
            <input type="text" class="text" name="goodsId" value="<?php echo $this->_tpl_vars['selected']['goodsId']; ?>
" />
            <?php echo ((is_array($_tmp='USERID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
            <input type="text" class="text" name="playerId" value="<?php echo $this->_tpl_vars['selected']['playerId']; ?>
" />
            <?php echo ((is_array($_tmp='DETAILED_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
            <input type="text" class="text" name="detailId" value="<?php echo $this->_tpl_vars['selected']['detailId']; ?>
" />
			<input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='SEARCH')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
">
        </form>    
        </td>
        </tr>
     	 <tr>
            <th><?php echo ((is_array($_tmp='ITEM_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='USERID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='DETAILED_ID')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='OLD_LEVEL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='NEW_LEVEL')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='QUANTITY')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
          </tr>
          <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
          <tr>
            <td><?php echo $this->_tpl_vars['vo']['goodsId']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['playerId']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['detailId']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['oldLevel']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['newLevel']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['count']; ?>
</td>
            <td><?php echo ((is_array($_tmp=$this->_tpl_vars['vo']['generalTime']/1000)) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
          </tr>
          <?php endforeach; else: ?>
          	<?php if ($this->_tpl_vars['error']): ?>
               <tr>
                <td colspan="7"><?php echo $this->_tpl_vars['error']; ?>
</td>
              </tr>
            <?php else: ?>
              <tr>
                <th colspan="7"><?php echo $this->_tpl_vars['noData']; ?>
</th>
              </tr>
            <?php endif; ?>

          <?php endif; unset($_from); ?> 
          <tr>
            <th colspan="7" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
          </tr>
    </table>
</fieldset>
<?php endif; ?>