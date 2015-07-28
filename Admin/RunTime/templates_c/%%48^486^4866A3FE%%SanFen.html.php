<?php /* Smarty version 2.6.26, created on 2013-04-07 16:14:10
         compiled from ActionGame_MasterTools/PlayerLookup/SanFen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'ActionGame_MasterTools/PlayerLookup/SanFen.html', 83, false),array('modifier', 'date_format', 'ActionGame_MasterTools/PlayerLookup/SanFen.html', 93, false),)), $this); ?>
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
<script language="javascript" >
	function jumpUrl(url){
		var playerIdsForm = $('#playerIdsForm');
		if(url == ''){
			alert('empty url');
			return false;
		}else{
			playerIdsForm.attr('action',url);
			playerIdsForm.submit();
		}
	}
	/**$(function(){
		$("#form").submit(function() {
				var playerId = $("#playerId").attr('value');
				var accountName = $("#accountName").attr('value');
				var playerName = $("#playerName").attr('value');
				if(playerId + accountName+ playerName == ''){
					alert("Id,账号,昵称必选择一项");
					return false;
				}
		});
	});**/
	
</script>

<fieldset>
  <legend>用户查询</legend>
  
<form action=""  method="get" id='form'>
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
    玩家ID:<input class="text" type="text" name="playerId" id="playerId" value="<?php echo $this->_tpl_vars['_GET']['playerId']; ?>
">
    玩家账号:<input class="text" type="text" name="accountName" id="accountName" value="<?php echo $this->_tpl_vars['_GET']['accountName']; ?>
">
    玩家昵称:<input class="text" type="text" name="playerName" id="playerName" value="<?php echo $this->_tpl_vars['_GET']['playerName']; ?>
">
    <br>
    注册时间:
    <input class="text" type="text" name="regBeginTime" value="<?php echo $this->_tpl_vars['_GET']['regBeginTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
    -
    <input class="text" type="text" name="regEndTime" value="<?php echo $this->_tpl_vars['_GET']['regEndTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
    
    
    登录时间:
    <input class="text" type="text" name="loginBeginTime" value="<?php echo $this->_tpl_vars['_GET']['loginBeginTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
    -
    <input class="text" type="text" name="loginEndTime" value="<?php echo $this->_tpl_vars['_GET']['loginEndTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
    <input class="btn-blue"  type="submit" name="sbm" value="查询">
</form>

<form action="" id="playerIdsForm" method="post">
      <table width="100%" border="0" >
        <tr>
          <th>选择</th>
          <th>玩家帐号</th>
          <th>玩家角色ID	</th>
          <th>玩家角色名</th>
          <th>玩家等级</th>
          <th>元宝余额</th>
          <th>总充值金额</th>
          <th>礼券</th>
          <th>银币余额</th>
          <th>等级</th>
          <th>玩家角色创建日期</th>
          <th>玩家角色最后登录日期</th>
          <th>帮派名称</th>
          <th>帮派等级</th>
          <th>钻等级</th>
          <th>钻</th>
          <th>年费用户</th>
        </tr>
        
		<?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
        <tr>
          <td align="center"><input type="checkbox" name="playerIds[]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['playerId'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
"/></td>
       	  <td align="center"><?php echo $this->_tpl_vars['list']['account']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['playerId']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['playerName']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['playerLevel']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['ingots']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['payTotal']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['sysIngots']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['coins']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['vip']; ?>
</td>
          <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['createDate']/1000)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
          <td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['lastLogin']/1000)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['legionName']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['legionLevel']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['diamondLevel']; ?>
</td>
          <td align="center"><?php if ($this->_tpl_vars['list']['flatType'] == 1): ?>黄钻<?php elseif ($this->_tpl_vars['list']['flatType'] == 10): ?>蓝钻<?php else: ?>无<?php endif; ?></td>
          <td align="center"><?php if ($this->_tpl_vars['list']['yearDiamond'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
    
        </tr>
        <?php endforeach; else: ?>
          <tr>
            <th colspan="11" align="center">
                <?php if ($this->_tpl_vars['_GET']['submit']): ?>
                查无数据
                <?php else: ?>
                <font color="#FF0000">请使用"查询"按钮</font>
                <?php endif; ?>
            </th>
          </tr>
		<?php endif; unset($_from); ?>
          <tr>
            <td colspan="11">
            	<input type="checkbox" onClick="$('input[name=playerIds[]]').attr('checked',$(this).attr('checked'))">全选
                <input type="hidden" name="fromPlayerList" value="1" />            	
            	<input type="button" class="btn-blue" value="发邮件" onclick="jumpUrl('<?php echo $this->_tpl_vars['ShortcutUrl']['SendMail']; ?>
');" />
            	<input type="button" class="btn-blue" value="禁言" onclick="jumpUrl('<?php echo $this->_tpl_vars['ShortcutUrl']['SilenceAdd']; ?>
');" />
                <input type="button" class="btn-blue" value="封号" onclick="jumpUrl('<?php echo $this->_tpl_vars['ShortcutUrl']['LockAccountAdd']; ?>
');" />
               
            </td>
          </tr>
          <tr>
            <th colspan="11" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
          </tr>
      </table>
</form>
<?php if ($this->_tpl_vars['connectError']): ?>
<div style="color:#F00;"><?php echo $this->_tpl_vars['connectError']; ?>
</div>
<?php endif; ?>
</fieldset>
<?php endif; ?>