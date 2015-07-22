<?php /* Smarty version 2.6.26, created on 2013-04-07 10:18:18
         compiled from Index/Right.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'Index/Right.html', 33, false),array('modifier', 'date_format', 'Index/Right.html', 60, false),)), $this); ?>
<?php if ($this->_tpl_vars['showErrorLogFiles'] && $this->_tpl_vars['errorLogFiles']): ?>
<script>
	function errorLogDel(fileName,ek){
		$.ajax({
			dataType:'json',	
			type: 'GET',
			data:{fileName:fileName},
			url: '<?php echo $this->_tpl_vars['url']['ErrorLogDel']; ?>
',
			success:function(json){
				if(0 == json.status){
					alert(json.info);
				}
				else if(1 == json.status){
					$('#errorFile'+ek).remove();
				}
			}
		});
	}
	

</script>
<fieldset><legend>ErrorLogFiles [<a
	onclick="return confirm('确定删除全部？');" href="<?php echo $this->_tpl_vars['url']['ErrorLogDelAll']; ?>
">删除全部</a>]</legend>
<?php $_from = $this->_tpl_vars['errorLogFiles']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ek'] => $this->_tpl_vars['logFile']):
?>
<div id="errorFile<?php echo $this->_tpl_vars['ek']; ?>
"> [<a href="javascript:void(0);"
	onclick="errorLogDel('<?php echo $this->_tpl_vars['logFile']; ?>
','<?php echo $this->_tpl_vars['ek']; ?>
');">删除</a>] <a
	target="_blank" href="<?php echo $this->_tpl_vars['url']['ErrorLogShow']; ?>
&fileName=<?php echo $this->_tpl_vars['logFile']; ?>
"
id=""><?php echo $this->_tpl_vars['logFile']; ?>
</a> <br>
</div>
<?php endforeach; endif; unset($_from); ?></fieldset>
<?php endif; ?>

<fieldset><legend><?php echo ((is_array($_tmp='PERCONALPROFILE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<table width="100%" border="0" cellpadding="3">
	<tr>
		<td height="22" colspan="4" scope="row"><?php echo ((is_array($_tmp='LANGSELECT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:
		<?php $_from = $this->_tpl_vars['lang']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?> <input type="button"
			class="<?php if ($this->_tpl_vars['key'] == $this->_tpl_vars['curLangId']): ?>btn-red<?php else: ?>btn-blue<?php endif; ?>"
		onclick="location.href='<?php echo $this->_tpl_vars['list']['url_lang']; ?>
'" value="<?php echo $this->_tpl_vars['list']['lang']; ?>
"
		/> <?php endforeach; endif; unset($_from); ?></td>
	</tr>
	<tr>
		<th width="114" scope="row"><?php echo ((is_array($_tmp='MYNAME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
		<td width="548"><?php echo $this->_tpl_vars['userClass']['_nickName']; ?>
 <?php if ($this->_tpl_vars['userClass']['_serviceId']): ?>
		(<?php echo $this->_tpl_vars['userClass']['_serviceId']; ?>
) <?php endif; ?> 、<?php echo $this->_tpl_vars['userClass']['word_department']; ?>

		</td>
		<th width="135"><?php echo ((is_array($_tmp='ROLE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
		<td width="529"><?php echo $this->_tpl_vars['userClass']['word_roles']; ?>
</td>
	</tr>
	<tr>
		<th width="114" scope="row">当前状态</th>
		<td><?php echo $this->_tpl_vars['displaycontent']; ?>
</td>
		<th>buglist处理区</th>
		<td><a href='/admin.php?c=Verify&a=Index'>进入buglist处理区</a></td>
	</tr>
	<tr>
		<td height="69" colspan="2" valign="top"><b><?php echo ((is_array($_tmp='PUBLICNOTICE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</b>
		<ol>
			<?php $_from = $this->_tpl_vars['userClass']['bulletin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
			<li><?php echo $this->_tpl_vars['list']['word_is_read']; ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
)
			<?php echo $this->_tpl_vars['list']['title']; ?>
</li>
			<?php endforeach; else: ?>
			<li><?php echo ((is_array($_tmp='NODATA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</li>
			<?php endif; unset($_from); ?>
		</ol>
		</td>
		<td colspan="2" valign="top"><b><?php echo ((is_array($_tmp='HANDOVER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</b>
		<ol>
			<?php $_from = $this->_tpl_vars['userClass']['work_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
			<li><?php echo $this->_tpl_vars['list']['word_is_read']; ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
)
			<?php echo $this->_tpl_vars['list']['title']; ?>
</li>
			<?php endforeach; else: ?>
			<li><?php echo ((is_array($_tmp='NODATA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</li>
			<?php endif; unset($_from); ?>
		</ol>
		</td>
	</tr>
	<!--
      <tr>
        <td colspan="4" >
        	<b>当前在线用户：</b>
            <ul style="margin:3px; padding:3px; list-style:none;">
            <?php $_from = $this->_tpl_vars['onLineUsers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?>
	<li style="display: inline"><?php echo $this->_tpl_vars['list']; ?>
 [<font color="#00cc00"><?php echo ((is_array($_tmp='ONLINE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>]</li>
	<?php endforeach; endif; unset($_from); ?>
	</ul>
	</td>
	</tr>
	-->
</table>
</fieldset>