<?php /* Smarty version 2.6.26, created on 2013-04-08 18:10:22
         compiled from ActionGame_MasterTools/PointDel/GongFu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'ActionGame_MasterTools/PointDel/GongFu.html', 10, false),array('modifier', 'default', 'ActionGame_MasterTools/PointDel/GongFu.html', 68, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<script>
	function checkedPlayer(){
		var player = $('input[name=players]').val();
		if($.trim(player) == ''){
			alert('<?php echo ((is_array($_tmp='B69B2BFB68B37AE18F2331FC70E582B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
');
			return false;
		}
		var postData = {};
		if($('#playerType1').attr('checked')){
			postData = {playerId:player};
		}
		else if($('#playerType2').attr('checked')){
			postData = {playerName:player};
		}
		else if($('#playerType3').attr('checked')){
			postData = {accountName:player};
		}
		$.ajax({
			dataType:'json',	
			type: 'GET',
			data:postData,
			url: '<?php echo $this->_tpl_vars['URL_playerQuery']; ?>
',
			success:function(jsonData){
				if(1 == jsonData.status){
					var playerInfo = jsonData['data']['dataList'];
					var playerName = '';
					var asset = '';
					var gold = '';
					var goldTicke = '';
					if(playerInfo){
						playerName = playerInfo[0]['playerName'];
						asset = playerInfo[0]['asset'];
						gold = playerInfo[0]['gold'];
						goldTicke = playerInfo[0]['goldTicke'];
					}else{
						alert('<?php echo ((is_array($_tmp='BA58B9967C3DC3B87E8C7EF10D1B7B92')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
');
					}
					$('#playerNameInfo').html(playerName);
					$('#assetInfo').html(asset);
					$('#goldInfo').html(gold);
					$('#goldTicketInfo').html(goldTicke);
				}
				else{
					alert(jsonData.info);
				}
			}
		});
	}
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='5C82BBE40DF1197A287610F9A4227B81')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
	<form action=""  method="post">
        <table width="600" border="0" cellpadding="3">
          <tr>
            <th scope="row" width="80"><?php echo ((is_array($_tmp='069A4B89AE4E24F7718E5DF99E80B75D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID</th>
            <td>
            	<div>
                    <label><input type="radio" id="playerType1" name="playerType" checked="checked" value="1" /><?php echo ((is_array($_tmp='069A4B89AE4E24F7718E5DF99E80B75D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID</label>
                    <label><input type="radio" id="playerType2" name="playerType" value="2" /><?php echo ((is_array($_tmp='7035C62FB00576FED9B3A1F2B7D48E6C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</label>
                    <label><input type="radio" id="playerType3" name="playerType" value="3" /><?php echo ((is_array($_tmp='23EB0E6024B9C5E694C18344887C4FE7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</label>
                </div>
                <div style="margin-top:5px;">
                    <input type="text" class="text" style="width:300px;" name="players" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['players'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
" />
                    <input type="button" class="btn-blue" value="<?php echo ((is_array($_tmp='4328677C29493531E3469538CED9C541')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" onclick="checkedPlayer();" />
                	<span id="playerNameInfo" style="color:#F00"></span>
                </div>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php echo ((is_array($_tmp='A59C190B19758E577ADCAA244ED53706')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <td> - <input type="text" class="text" name="asset" value="" /> <span id="assetInfo" style="color:#F00"></span></td>
          </tr>
          <tr>
            <th scope="row"><?php echo ((is_array($_tmp='22962E31B7F0C2B3BB7C4E7444C904B3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <td> - <input type="text" class="text" name="gold" value="" /> <span id="goldInfo" style="color:#F00"></span></td>
          </tr>
          <tr>
            <th scope="row"><?php echo ((is_array($_tmp='C5E03794DA38DEC31D05CB002BF700BE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
            <td> - <input type="text" class="text" name="goldTicket" value="" /> <span id="goldTicketInfo" style="color:#F00"></span></td>
          </tr>
          <tr>
            <th scope="row">&nbsp;</th>
            <td><input type="submit" class="btn-blue" name="sbm" value="<?php echo ((is_array($_tmp='327094A9DDDF9E37726CAFACEB9A77B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /></td>
          </tr>
        </table>
    </form>
</fieldset>
<?php endif; ?>