<?php /* Smarty version 2.6.26, created on 2013-04-08 18:10:45
         compiled from FrgGold/Card.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'FrgGold/Card.html', 2, false),array('function', 'html_options', 'FrgGold/Card.html', 12, false),array('function', 'html_radios', 'FrgGold/Card.html', 19, false),)), $this); ?>
<fieldset>
	<legend><?php echo ((is_array($_tmp='E5F71FC31E7246DD6CCC5539570471B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
    <form action="<?php echo $this->_tpl_vars['url']['FrgGold_Card']; ?>
" id="search" method="get" >
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='833517FFC6CBBAC5B2375782A2541867')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
        	<select name="operator_id">
            	<option value=""><?php echo ((is_array($_tmp='708C9D6D2AD108AB2C560530810DEAE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
...</option>
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList'],'selected' => $this->_tpl_vars['selectedOperatorId']), $this);?>

            </select>
        </td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='1CC4D9FD92460B937FC56C55E758183B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
        	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['cardStatus'],'name' => 'is_use','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['selectedIsUse']), $this);?>

        </td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='D16A5F8E5ED6D23A15EB5EC2C2F90FD4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['cardType'],'name' => 'card_type','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['selectedCardType']), $this);?>
</td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='B854970C3EA642D87C7F6B9C78935CE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['payType'],'name' => 'type','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['selectedPayType']), $this);?>
</td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='F8A6D8B063C23A7A2EECAE3BE91489A7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><input type="text" class="text" value="<?php echo $this->_tpl_vars['selectedCard']; ?>
" name="card" size="60" /></td>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='17E9C9D09D1543C09A77C3EC3FD5C2CC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><input type="text" class="text" value="<?php echo $this->_tpl_vars['selectedBatchnum']; ?>
" name="batch_num" size="60" />
        	<input type="checkbox" name="group_batch" value="1" <?php if ($this->_tpl_vars['selectedGroupBatch']): ?>checked="checked"<?php endif; ?> /><?php echo ((is_array($_tmp='B52D4EFA9F0DC71F028A58B1717051D8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

        	<input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='939D5345AD4345DBAABE14798F6AC0F1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" />
        </td>
      </tr>
      </table>
    </form>
</fieldset>

<fieldset>
<legend><?php echo ((is_array($_tmp='B1FFC40619E554C1807AEB3D153CB48B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<?php if ($this->_tpl_vars['selectedGroupBatch']): ?>

<table width="100%" border="0" cellpadding="3">
	<form action="<?php echo $this->_tpl_vars['url']['FrgGold_Card_Del']; ?>
" method="post">
    <tr>
        <th scope="col">Id</th>
        <th scope="col"><?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='6A4181483CE58CBC44A36BF1AD2DEA50')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='8AC8DA83627DF7B14DF32CDBC8D34C61')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B09F4CA805E29FB623110C695FE24951')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='D16A5F8E5ED6D23A15EB5EC2C2F90FD4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B683C82E9310494611D6A8C1DB41BD34')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp='FC92E9352345771B0CEA8487FC6671A9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B854970C3EA642D87C7F6B9C78935CE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='2B6BC0F293F5CA01B006206C2535CCBC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col"><input type="checkbox" name="batch_num[]" value="<?php echo $this->_tpl_vars['list']['batch_num']; ?>
" /><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['gold']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_apply_user_id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['batch_num']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_card_type']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['create_time']; ?>
 <br /><?php echo $this->_tpl_vars['list']['start_time']; ?>
 - <?php echo $this->_tpl_vars['list']['end_time']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_type']; ?>
</td>
    <td scope="col"><a href="<?php echo $this->_tpl_vars['list']['url_export']; ?>
"><?php echo ((is_array($_tmp='55405EA6FF6FD823FFAB7E6B10DDFA95')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
Excel</a></td>
    </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="9" scope="col">
  		<?php echo $this->_tpl_vars['noData']; ?>
 	
    </th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td colspan="11" scope="col">
  		<input type="checkbox" onclick="$(':checkbox[name=\'batch_num[]\']').attr('checked',$(this).attr('checked'))" /><?php echo ((is_array($_tmp='66EEACD93A7C1BDA93906FE908AD11A0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <input class="btn-blue" type="submit" value="<?php echo ((is_array($_tmp='2F4AADDDE33C9B93C36FD2503F3D122B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /> <b><font color="#FF0000"><?php echo ((is_array($_tmp='F6349C441652F0E053DCDE768D5C3DB8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
： <?php echo ((is_array($_tmp='BC48069A68DC8314E697A6983660A371')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font></b>
    </td>
  </tr>
  </form>
  <tr>
    <td align="right" colspan="9" scope="col">
  		<?php echo $this->_tpl_vars['pageBox']; ?>
 	
    </td>
  </tr>
</table>

<?php else: ?>


<table width="100%" border="0" cellpadding="3">
	<form action="<?php echo $this->_tpl_vars['url']['FrgGold_Card_Del']; ?>
" method="post">
    <tr>
        <th scope="col">Id</th>
        <th scope="col"><?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='6A4181483CE58CBC44A36BF1AD2DEA50')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='8F8ED0E3AB533211CE82DD1E4B1C22BB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='8AC8DA83627DF7B14DF32CDBC8D34C61')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B09F4CA805E29FB623110C695FE24951')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='D16A5F8E5ED6D23A15EB5EC2C2F90FD4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B854970C3EA642D87C7F6B9C78935CE9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='B683C82E9310494611D6A8C1DB41BD34')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp='FC92E9352345771B0CEA8487FC6671A9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='3FEA7CA76CDECE641436D7AB0D02AB1B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th scope="col"><?php echo ((is_array($_tmp='9FC979667D08426FB1FC65BA9C435ADF')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='7035C62FB00576FED9B3A1F2B7D48E6C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <br /> IP / <?php echo ((is_array($_tmp='19FCB9EB2594059036DFEDE5F4EC53E8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
    </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col"><input type="checkbox" name="card[]" value="<?php echo $this->_tpl_vars['list']['card']; ?>
" /><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['gold']; ?>
</td>
    <td scope="col"><font color="#FF0000"><?php echo $this->_tpl_vars['list']['card']; ?>
</font></td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_apply_user_id']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['batch_num']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_card_type']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_type']; ?>
</td>
    <td scope="col"><b><?php echo $this->_tpl_vars['list']['create_time']; ?>
</b><br /><?php echo $this->_tpl_vars['list']['start_time']; ?>
 - <?php echo $this->_tpl_vars['list']['end_time']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_is_use']; ?>
</td>
    <td scope="col"><?php echo $this->_tpl_vars['list']['word_use_server_id']; ?>
 / <?php echo $this->_tpl_vars['list']['user_name']; ?>
 <br /> <?php echo $this->_tpl_vars['list']['user_ip']; ?>
 / <?php echo $this->_tpl_vars['list']['use_time']; ?>
</td>
    </tr>
  <?php endforeach; else: ?>
  <tr>
    <th colspan="11" scope="col">
  		<?php echo $this->_tpl_vars['noData']; ?>
 	
    </th>
  </tr>
  <?php endif; unset($_from); ?>
  <tr>
    <td colspan="11" scope="col">
  		<input type="checkbox" onclick="$(':checkbox[name=\'card[]\']').attr('checked',$(this).attr('checked'))" /><?php echo ((is_array($_tmp='66EEACD93A7C1BDA93906FE908AD11A0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 
        <input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='0CD91F4718E3195F74E12E3B396801E9')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" name="csv" />
        <input class="btn-blue" type="submit" onclick="return confirm('确定删除？');" value="<?php echo ((is_array($_tmp='2F4AADDDE33C9B93C36FD2503F3D122B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /> <b><font color="#FF0000"><?php echo ((is_array($_tmp='F6349C441652F0E053DCDE768D5C3DB8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
： <?php echo ((is_array($_tmp='BC48069A68DC8314E697A6983660A371')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font></b>
    </td>
  </tr>
  </form>
  <tr>
    <td align="right" colspan="11" scope="col">
  		<?php echo $this->_tpl_vars['pageBox']; ?>
 	
    </td>
  </tr>
</table>

<?php endif; ?>

</fieldset>