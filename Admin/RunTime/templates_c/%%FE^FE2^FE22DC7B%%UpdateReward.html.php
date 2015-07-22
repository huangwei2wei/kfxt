<?php /* Smarty version 2.6.26, created on 2013-04-09 16:54:09
         compiled from GmSftx/UpdateReward.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/UpdateReward.html', 18, false),)), $this); ?>
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

<script type="text/javascript">
function checkuserid(){
	var ids = document.getElementById("user").value;
	if($.trim(ids)!==''){
		$.ajax({
			type: 'get',
			url: "<?php echo $this->_tpl_vars['checkurl']; ?>
&ids="+ids,
			success:function(data, textStatus){	
				var myObject = eval('('+data+')');
				if($.trim(myObject.noExist) != ''){
					window.alert('<?php echo ((is_array($_tmp='D7D11654A707D7E7B661C90295E58B20')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:'+myObject.noExist);
				}else{
					alert('<?php echo ((is_array($_tmp='1207F119D13B6C40FC209F3DB80951D0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
');
				}
			}
		});	
	}else{
		alert('<?php echo ((is_array($_tmp='F12CA9580BD9861D372828330A1662AC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
');
	}
}
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='E5168E97E79AFFA99EE71FE81F6734C1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 [<a href="<?php echo $this->_tpl_vars['URL_updateRewardConfig']; ?>
">参数配置</a>]</legend>
<form action="" method="post">
    <table width="98%" border="0" cellpadding="3">
      <tr>
      	<td>
        	<?php echo ((is_array($_tmp='2088BDE458445E265704715AA8DACE5A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<br/>
            <textarea style="width:550px; height:80px;" name="cause"></textarea>
        </td>
      </tr>
      <tr>
        <td>
            <div><?php echo ((is_array($_tmp='D340E9A6B9AA11A2303C75F880B24B8A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
            <select name="usertype">
                <option value="1"><?php echo ((is_array($_tmp='1FD02A90C38333BADC226309FEA6FECB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID</option>
                <option value="2"><?php echo ((is_array($_tmp='819767ADA1805B0F0117F10967B94D4D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
            </select>
            </div>
            <div style=" padding:5px;padding-left:10px; "><textarea style="width:550px; height:80px;" name="user" id='user'></textarea></div>
            <div style=" padding-left:10px;"><?php echo ((is_array($_tmp='A1F5777D70231C616DF0A2E602F6EE8D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
","<?php echo ((is_array($_tmp='BBC56D684360D07586ED94A558CF33C1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<input type="button" value="<?php echo ((is_array($_tmp='3AE24D3D7929A7A968DFA814AE81E3E2')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" onclick='checkuserid()' class="btn-blue"/></div>

        </td>
      </tr>
      <tr>
        <td>
            <?php echo ((is_array($_tmp='08ECA7BB612A12C5EF8879F6BB9E2B30')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
            <select name="goldtype">
                <option value="1"><?php echo ((is_array($_tmp='SYS_GOLE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
                <option value="2"><?php echo ((is_array($_tmp='USER_GOLD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
            </select>
            <?php echo ((is_array($_tmp='CB1399566A84ADE345662204F1376121')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" class="text" name="gold" value="">
           
        </td>
      </tr>
      <tr>
        <td>

             
            <?php $_from = $this->_tpl_vars['dataConfig']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['field'] => $this->_tpl_vars['cname']):
        $this->_foreach['foo']['iteration']++;
?>
        		<?php echo $this->_tpl_vars['cname']; ?>
:<input type="text" class="text" name="<?php echo $this->_tpl_vars['field']; ?>
" value="">   
                <?php if (($this->_foreach['foo']['iteration']-1) % 4 == 3): ?>
                <br>
        		<?php endif; ?>
        	<?php endforeach; endif; unset($_from); ?>
        </td>
      </tr>
      <tr>
      	<td>
        	<?php echo ((is_array($_tmp='DE24BFB740EF7421E00B7346EA704E3E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID:<input type="text" style="width:600px;" class="text" name="goodsId" value="">
            <?php echo ((is_array($_tmp='4B61E6F7DD0DFCC3AFD11C8DAB1FEFBE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
id=5<?php echo ((is_array($_tmp='D0D4799B152841975477E3A8C9F9BA1F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
，id=6<?php echo ((is_array($_tmp='5C603DE728BE6731D179FD7AA5F9270D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
3<?php echo ((is_array($_tmp='930882BB0A8C7B53565DE4F4D59F5CA7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
，<?php echo ((is_array($_tmp='40B2C1D511B9A693DDAAD6F046DF740E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：5,6,6,6
        </td>
      </tr>
      <tr>
        <td>
        	<div style="padding:2px;">            
                <?php echo ((is_array($_tmp='45EC69D516418E907C1AE7347ADFD1C5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
                <select name="isSentMail">
                	<option value="0"><?php echo ((is_array($_tmp='675317466D6F0FDE947C02D81D2EE789')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>
                    <option value="1"><?php echo ((is_array($_tmp='1535FCFA4CB8E4D467127154977E9788')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</option>                    
                </select>
            </div>
            <div style="padding:2px;">
            	<?php echo ((is_array($_tmp='00F7F1404D8CC8314C41EDB4D4F6685E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" style="width:500px;" class="text" name="title" value="">
            </div>
            <div style="padding:2px; height:80px;">
            	<?php echo ((is_array($_tmp='42516993D82605002CA3BFECD66EEABE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
                <textarea name="contents" style="width:500px; height:80px;"></textarea>
            </div>
        </td>
      </tr>
      <tr>
        <td align="center">
            <input type="submit" value="<?php echo ((is_array($_tmp='SUBMIT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" class="btn-blue">
        </td>
      </tr>
    </table>
</form>
</fieldset>
<?php endif; ?>