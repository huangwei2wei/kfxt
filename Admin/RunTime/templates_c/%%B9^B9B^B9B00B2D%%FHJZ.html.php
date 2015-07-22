<?php /* Smarty version 2.6.26, created on 2013-04-07 15:38:58
         compiled from ActionGame_MasterTools/PlayerLog/FHJZ.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_MasterTools/PlayerLog/FHJZ.html', 10, false),array('function', 'html_options', 'ActionGame_MasterTools/PlayerLog/FHJZ.html', 84, false),)), $this); ?>
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
	var objectId =<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['objectId'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
	var eventId = <?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['eventId'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
;
	function rootLoad(){
		$.each(playerLogTypes,
			function(Id,subRoot){
				$('<option/>').attr('value',Id).text(subRoot.rootTypeName).appendTo('#objectId');
			}
		);
		if(objectId){
			$('#objectId').val(objectId);
			rootchange(objectId);
		}
	}
	
	function rootchange(chobjectId){
		$('#eventId').html('');
		$('<option/>').attr('value',0).text('-所有子类-').appendTo('#eventId');		
		if(chobjectId>0){
			$.each(playerLogTypes[chobjectId]['subTypeList'],
				function(Id,subType){
					$('<option/>').attr('value',Id).text(subType.subTypeName).appendTo('#eventId');
				}
			);	
			if(eventId)$("#eventId").val(eventId);
		}
	}

	$(function(){
		$("#LogFrom").submit(function(e){
			var user = $("#user").attr('value');
			  if(user == ''){
				  	alert('必须填写玩家');
					return false;
			  }
			  return true;
		});
		rootLoad();
	});
	
</script>

<fieldset>
  <legend>玩家操作日志 <a href="<?php echo $this->_tpl_vars['URL_LogTypeUpdate']; ?>
">更新日志类型</a></legend>
  
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
                日志类型
                <select name="objectId" id="objectId" onchange="rootchange($(this).val())">
                	<option value="0">-所有-</option>
                </select>
                <select name="eventId" id="eventId" >
                    <option value="0">-所有子类-</option>
                </select>
            </span>
            操作时间：
            <input class="text" type="text" name="StartTime" value="<?php echo $this->_tpl_vars['_GET']['StartTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            至
            <input class="text" type="text" name="EndTime" value="<?php echo $this->_tpl_vars['_GET']['EndTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-01 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            
        </td>
      </tr>
      <tr>
        <td>
            账号类型：
            <select name="userType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['userType'],'selected' => $this->_tpl_vars['_GET']['userType']), $this);?>

            </select>
            账号：<input class="text" type="text" name="user" id="user" value="<?php echo $this->_tpl_vars['_GET']['user']; ?>
">
            关键字：<input class="text" type="text" name="keywords" id="keywords" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['_GET']['keywords'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
            <input class="btn-blue" type="submit" name="submit" value="查询" >
        </td>
      </tr>
    </table>    
    
    <div>
    	<?php if ($this->_tpl_vars['playerAccount']): ?>玩家账号:<font color="#FF0000"><?php echo $this->_tpl_vars['playerAccount']; ?>
</font>,<?php endif; ?>
    	<?php if ($this->_tpl_vars['playerName']): ?>玩家昵称:<font color="#FF0000"><?php echo $this->_tpl_vars['playerName']; ?>
</font><?php endif; ?>
    </div>
</form>
    <table width="100%" border="0" cellpadding="3">
    
      <tr>
        <th>时间</th>
        <th>用户Id</th>
        <th>用户名</th>
        <th>描述</th>
        <th>日志类型</th>
      </tr>
    <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['list']):
?> 
      <tr class="here_td">
        <td align="center"><?php echo $this->_tpl_vars['list']['addTime']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['userID']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['userName']; ?>
</td>
        <td align="center"><?php echo $this->_tpl_vars['list']['description']; ?>
</td> 
        <td align="center"><?php echo $this->_tpl_vars['list']['logType']; ?>
</td>
      </tr>
    <?php endforeach; else: ?>
      <tr>
      	<th colspan="8" align="center">
            <?php if ($this->_tpl_vars['_GET']['submit']): ?>
            查无数据
            <?php else: ?>
            <font color="#FF0000">请使用"查询"按钮</font>
            <?php endif; ?>
        </th>
      </tr>
    <?php endif; unset($_from); ?>
    </table>

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
<?php endif; ?>