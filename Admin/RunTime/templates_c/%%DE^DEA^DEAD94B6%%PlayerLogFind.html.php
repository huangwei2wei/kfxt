<?php /* Smarty version 2.6.26, created on 2013-04-09 16:54:07
         compiled from GmSftx/PlayerLogFind.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/PlayerLogFind.html', 32, false),array('modifier', 'date_format', 'GmSftx/PlayerLogFind.html', 69, false),array('modifier', 'intval', 'GmSftx/PlayerLogFind.html', 145, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<style>
.AutoNewline {
    line-height: 150%;
    overflow: hidden;
    width: 200px;
    word-wrap: break-word;
}

</style>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
	function orderseach(){
		$.ajax({
			dataType:'json',
			type: "POST",
			url: "<?php echo $this->_tpl_vars['ajaxurl']; ?>
",
			data:{value:$("#ordertext").val()},
			success: function(msg){
				var json = eval(msg);
				if(0 == json.status){
					alert(json.info);
				}
				else if(1 == json.status){
					$.each(json.data,
							function(Id,name){ 
								$("#ordercontent").append('<tr><th width="500">'+Id+' '+name+'</th><td><input class="btn-blue" type="button" value="<?php echo ((is_array($_tmp='153FA67A7FB6ADA66A1FCCCABBBFAB72')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" onclick="ChooseID('+Id+')"/></td></tr>');
							}
					);
					//$("#ordercontent").after(json.data['']);
				}
			}
		});
	}
	
	function ChooseID(id){
		$("#cmdID").val(id);
		$('#ordercontent').html('');
	}
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='38D5EF83637083F3A4B8F98E59FC88D0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
	<table width="99%" border="0" cellpadding="3">
		<tr>
			<td>
			<?php echo ((is_array($_tmp='B530EF92B51FE3B4F4E7F7FDD1362EFE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<input type="text" id="ordertext" class="text">
			<input type="button" value="<?php echo ((is_array($_tmp='BEE912D79EEFB7335988C4997AA9138D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" class="btn-blue" onclick="orderseach()">
			</td>
		</tr>
	</table>

	<table width="99%" border="0" cellpadding="3" id="ordercontent">
	</table>
    <table width="99%" border="0" cellpadding="3">
      <thead>
      <tr>
        <td colspan="18">
        <form action="<?php echo $this->_tpl_vars['url']['GmSftx_UserLog']; ?>
" method="get">
            <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
            <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
            <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
            <?php echo ((is_array($_tmp='4C11190ECA9D2AE73B65430075CAC060')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" name="cmdID" id="cmdID" value="<?php echo $this->_tpl_vars['_GET']['cmdID']; ?>
" class="text">
           	<?php echo ((is_array($_tmp='069A4B89AE4E24F7718E5DF99E80B75D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID:<input type="text" name="playerId" value="<?php echo $this->_tpl_vars['_GET']['playerId']; ?>
" class="text">
            <?php echo ((is_array($_tmp='START_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" name="startTime" class="text" value="<?php echo $this->_tpl_vars['_GET']['startTime']; ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d 00:00:00') : smarty_modifier_date_format($_tmp, '%Y-%m-%d 00:00:00')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            <?php echo ((is_array($_tmp='END_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<input type="text" name="endTime" class="text" value="<?php echo $this->_tpl_vars['_GET']['endTime']; ?>
" onFocus="WdatePicker({startDate:'<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d 23:59:59') : smarty_modifier_date_format($_tmp, '%Y-%m-%d 23:59:59')); ?>
',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
       		<input type="submit" name="submit" value="<?php echo ((is_array($_tmp='BEE912D79EEFB7335988C4997AA9138D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" class="btn-blue">
        </form>
        </td>
      </tr>
      </thead>
      <tbody>
          <tr>
            <th><?php echo ((is_array($_tmp='4C11190ECA9D2AE73B65430075CAC060')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='069A4B89AE4E24F7718E5DF99E80B75D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
UID<br/><?php echo ((is_array($_tmp='00B1C011DA027AF6EC03DB52B6D9540B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>

            <th><?php echo ((is_array($_tmp='60FA49B56A6469EFE8F520924BE19FDB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br/>VIP<?php echo ((is_array($_tmp='95E0D70D1809D5267C2419EDA58E78CA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='SILVER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br/><?php echo ((is_array($_tmp='GOLD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>

            <th><?php echo ((is_array($_tmp='B3499A068C490875BD9FB7F59C231B1D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='9EAE784C8E075A00A26C9A2C09921C5A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='2E79CAFF4A48242E7FA04B7A32FE74EB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='PRESTIGE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='MILITARY_ORDERS')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='822FD0EBB736872C19FF813EBC69B3BE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='D478DA4D176B8FED99B4C1FE0B4C6DA3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>            
            <th><?php echo ((is_array($_tmp='2E409B3F292EFE6EA3779B2DE2CE2E97')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='C18E630C7BC92887BE9250E0E4DD7FCA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='0E24B3A4820E3C2C2B16E903C622F7FB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='D3C3A7463A0151153188A94D346597E2')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th><?php echo ((is_array($_tmp='908464AAC5F8A22ADAE272FDA66C1FC8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <th>蛋</th>
           	<th>血石，幻石，灵石</th>
           	<th>合成石</th>
           	<th>竞技场次数</th>
           	<th>转盘次数</th>
           	
           	<th>令牌</th>
           	<th>幻化石</th>
           	
           	
            <th><?php echo ((is_array($_tmp='OPT_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br/>
                (
                    <?php if ($this->_tpl_vars['BeiJing_time']): ?>
                        <?php echo ((is_array($_tmp='BJT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

                    <?php else: ?>
                        <?php echo ((is_array($_tmp='SERVER_TIME')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

                    <?php endif; ?>
                )
            </th>
            <th><?php echo ((is_array($_tmp='2F27863E8013382CC2530C21D82A24B1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            
          </tr>
          <?php $_from = $this->_tpl_vars['dataLiat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['vo']):
?>
          <tr>
            <td><?php echo $this->_tpl_vars['vo']['cmdId']; ?>
</td>
			<td><?php echo $this->_tpl_vars['vo']['playerId']; ?>
<br/><?php echo $this->_tpl_vars['vo']['playerName']; ?>
</td>

            <td><?php echo $this->_tpl_vars['vo']['playerLevel']; ?>
<br/><?php echo $this->_tpl_vars['vo']['viplevel']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['copper']; ?>
<br/><?php echo $this->_tpl_vars['vo']['gold']; ?>
</td>

            <td><?php echo $this->_tpl_vars['vo']['food']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['forces']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['exploit']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['prestige']; ?>
</td>
			<td><?php echo $this->_tpl_vars['vo']['token']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['imposeNum']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['extra']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['boxList']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['soul']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['breakthrough']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['totalMap']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['spar']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['pack']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['stone']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['mixstone']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['conqueretime']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['fortuneWheeltime']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['evolveStone']; ?>
</td>
            <td><?php echo $this->_tpl_vars['vo']['brand']; ?>
</td>
            <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['vo']['logTime']/1000)) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp)))) ? $this->_run_mod_handler('date_format', true, $_tmp, '%Y-%m-%d %H:%M:%S') : smarty_modifier_date_format($_tmp, '%Y-%m-%d %H:%M:%S')); ?>
</td>
            <td><div class="AutoNewline"><?php echo $this->_tpl_vars['vo']['optDetail']; ?>
</div></td>
          </tr>
          <?php endforeach; else: ?>
          <tr>
            <th colspan="25"><?php echo $this->_tpl_vars['noData']; ?>
</th>
          </tr>
          <?php endif; unset($_from); ?> 
          <tr>
            <th colspan="25" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
          </tr>
      </tbody>

    </table>
</fieldset>
<?php endif; ?>