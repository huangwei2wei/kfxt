<?php /* Smarty version 2.6.26, created on 2013-04-08 18:09:36
         compiled from ActionGame_MasterTools/PlayerLog/GongFu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_MasterTools/PlayerLog/GongFu.html', 10, false),array('modifier', 'lang', 'ActionGame_MasterTools/PlayerLog/GongFu.html', 55, false),)), $this); ?>
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
<script language="javascript">
var playerLogTypes = <?php echo $this->_tpl_vars['playerLogTypes']; ?>
;
var rootid =<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['rootid'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
var typeid = <?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['typeid'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;

$(function(){
	
});

function rootLoad(){
	$.each(playerLogTypes,
		function(Id,subRoot){
			$('<option/>').attr('value',Id).text(subRoot.rootTypeName).appendTo('#rootid');
		}
	);
	if(rootid){
		$('#rootid').val(rootid);
		rootchange(rootid);
	}
}

function rootchange(chRootId){
	$('#typeid').html('');
	$('<option/>').attr('value',0).text('-所有子类-').appendTo('#typeid');		
	if(chRootId>0){
		$.each(playerLogTypes[chRootId]['subTypeList'],
			function(Id,subType){
				$('<option/>').attr('value',Id).text(subType.subTypeName).appendTo('#typeid');
			}
		);	
		if(typeid)$("#typeid").val(typeid);
	}
}
</script>
<style>
	.AutoNewline {
		font-size:12px;
		line-height:150%;
		overflow: hidden;
		width: 400px;
		word-wrap:break-word;
		margin-bottom:2px;
		margin-top:2px;
	}
</style>

<fieldset>
  <legend><?php echo ((is_array($_tmp='38D5EF83637083F3A4B8F98E59FC88D0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <a href="<?php echo $this->_tpl_vars['URL_LogTypeUpdate']; ?>
"><?php echo ((is_array($_tmp='B3279A44B553B72F55A8D036D7991707')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a></legend>
  
<form action=""  method="get" id="LogFrom">
	<input type="hidden" name="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <input type="hidden" name="zp" value="<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
" />
    <input type="hidden" name="__game_id" value="<?php echo $this->_tpl_vars['__GAMEID__']; ?>
" />
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <input type="hidden" name="LogId" id="LogId" value="0">
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td>
            <span style="padding-right:20px;">
                <?php echo ((is_array($_tmp='12918823E5D499370B67691981B095EA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

                <select name="rootid" id="rootid" onchange="rootchange($(this).val())">
                	<option value="0">-<?php echo ((is_array($_tmp='9A7B52FC8659F1786907FE93EFA85BF7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
-</option>
                </select>
                <select name="typeid" id="typeid" >
                    <option value="0">-<?php echo ((is_array($_tmp='091E369AC93019DB80A1C1A5BAB03FCF')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
-</option>
                </select>
            </span>
            
            
            <?php echo ((is_array($_tmp='7E951D56D99D790EC4383BFB7B187DDD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：
            <input class="text" type="text" name="StartTime" value="<?php echo $this->_tpl_vars['_GET']['StartTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            <?php echo ((is_array($_tmp='981CBE312B52B35B48065B9B44BA00E5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

            <input class="text" type="text" name="EndTime" value="<?php echo $this->_tpl_vars['_GET']['EndTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            IP:<input class="text" type="text" name="ip" id="ip" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['ip'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
        </td>
      </tr>
      <tr>
        <td>
            <?php echo ((is_array($_tmp='069A4B89AE4E24F7718E5DF99E80B75D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID：<input type="text" class="text" name="playerId" id="playerId" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['playerId'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
        	<?php echo ((is_array($_tmp='7035C62FB00576FED9B3A1F2B7D48E6C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<input class="text" type="text" name="account" id="account" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['account'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
            <?php echo ((is_array($_tmp='577C73C20A347C709FBEAA11306916C0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<input class="text" type="text" name="name" id="name" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['name'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
            <?php echo ((is_array($_tmp='CFB5F18C43753AD5329348D626BD3739')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：<input class="text" type="text" name="keywords" id="keywords" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['keywords'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
            <input class="btn-blue" type="submit" onclick="return checkSubmit();" name="submit" value="<?php echo ((is_array($_tmp='BEE912D79EEFB7335988C4997AA9138D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" >
            
        </td>
      </tr>
    </table>    
    
    <div>
    	<?php if ($this->_tpl_vars['playerAccount']): ?><?php echo ((is_array($_tmp='F6685D1959CB7D93BE372469C5BB1E65')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<font color="#FF0000"><?php echo $this->_tpl_vars['playerAccount']; ?>
</font>,<?php endif; ?>
    	<?php if ($this->_tpl_vars['playerName']): ?><?php echo ((is_array($_tmp='AB581AA0560E5EE2B91A1B2003B15FAA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
:<font color="#FF0000"><?php echo $this->_tpl_vars['playerName']; ?>
</font><?php endif; ?>
    </div>
    
    <table width="100%" border="0" cellpadding="3">
    
      <tr>
        <th>ID</th>
        <th><?php echo ((is_array($_tmp='464F3D4EA36EDA2F87D65F7B2F4564CF')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
Id</th>
        <th>gold<br>asset</th>
        <th>exp</th>
        <th><?php echo ((is_array($_tmp='95E0D70D1809D5267C2419EDA58E78CA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='77E0CA7A67BFA6FC3143B21A711255A0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th><?php echo ((is_array($_tmp='7E951D56D99D790EC4383BFB7B187DDD')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br><?php echo ((is_array($_tmp='2B6BC0F293F5CA01B006206C2535CCBC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
IP</th>

        <th><?php echo ((is_array($_tmp='4C4631B0A6CA021E1929DC26758CF90F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br><?php echo ((is_array($_tmp='D0771A42BBC49A6941F59913FCDA35E3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th width="400"><?php echo ((is_array($_tmp='2E2F703146311CB82F2F6826C242CE71')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
      </tr>
    <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?> 
    
      <tr class="here_td">
        <td align="center"><?php echo $this->_tpl_vars['list']['id']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['playerId']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['gold']; ?>
<br><?php echo $this->_tpl_vars['list']['asset']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['exp']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['level']; ?>
</td>
		<td align="center"><?php echo $this->_tpl_vars['list']['goldTicke']; ?>
</td>
        <td align="center" style="font-size:10px;"><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['addTime'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999"></font>') : smarty_modifier_default($_tmp, '<font color="#999999"></font>')); ?>
<br><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['ip'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999"></font>') : smarty_modifier_default($_tmp, '<font color="#999999"></font>')); ?>
</td> 

        <td align="center">
        	<a style="font-size:11px;" href="javascript:void(0)" onclick="logTypeSearch('<?php echo $this->_tpl_vars['list']['objectId']; ?>
',0);"><?php echo $this->_tpl_vars['list']['rootType']; ?>
</a>    
            <br>
            <a style="font-size:11px;" href="javascript:void(0)" onclick="logTypeSearch('<?php echo $this->_tpl_vars['list']['eventId']; ?>
','<?php echo $this->_tpl_vars['list']['objectId']; ?>
');"><?php echo $this->_tpl_vars['list']['subType']; ?>
</a>            
        </td> 
        <td><div class="AutoNewline"><?php echo $this->_tpl_vars['list']['params']; ?>
</div></td>  
      </tr>
    <?php endforeach; else: ?>
      <tr>
      	<th colspan="8" align="center">
            <?php if ($this->_tpl_vars['_GET']['submit']): ?>
            <?php echo ((is_array($_tmp='48E07E7DEAE53593B6FB5F4315CF0D1F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>

            <?php else: ?>
            <font color="#FF0000"><?php echo ((is_array($_tmp='F98214970C09E9F6FB18AD06420B8402')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"<?php echo ((is_array($_tmp='BEE912D79EEFB7335988C4997AA9138D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"<?php echo ((is_array($_tmp='FA966345577BA81AF19408F203DB968F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>
            <?php endif; ?>
        </th>
      </tr>
    <?php endif; unset($_from); ?>
    </table>
</form>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
 </th>
  </tr>
</table>
<?php if ($this->_tpl_vars['connectError']): ?>
<div style="color:#F00;"><?php echo $this->_tpl_vars['connectError']; ?>
</div>
<?php endif; ?>
</fieldset>
<script language="javascript">
	rootLoad();
</script>
<?php endif; ?>