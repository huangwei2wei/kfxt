<?php /* Smarty version 2.6.26, created on 2015-12-22 16:40:47
         compiled from ActionGame_MasterTools/PlayerLookup/XiYou.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/PlayerLookup/XiYou.html', 46, false),array('modifier', 'default', 'ActionGame_MasterTools/PlayerLookup/XiYou.html', 84, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<link href="
<?php echo $this->_tpl_vars['__JS__']; ?>

/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet"
type="text/css">
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
    账号类型:<select name="userType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['userType'],'selected' => $this->_tpl_vars['_GET']['userType']), $this);?>

            </select>
    玩家:<input class="text" type="text" name="user" id="user" value="<?php echo $this->_tpl_vars['_GET']['user']; ?>
">

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
          <th>用户Id</th>
          <th>账号</th>
          <th>昵称</th>
          <th>玩家等级</th>
          <th>玩家等级经验</th>
          <th>vip等级</th>
          <th>vip等级等级</th>
          <th>体力</th>
          <th>金币</th>
          <th>注册时间</th>
          <th>登录时间</th>
          <th>战斗力</th>
          <th>在线时长</th>
          <th>公会名</th>
        </tr>
		<?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
        <tr>
          <td align="center"><input type="checkbox" name="playerIds[]" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['id'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
"/></td>
       	  <td align="center"><?php echo $this->_tpl_vars['list']['id']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['accountName']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['nickname']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['lv']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['lvExp']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['vipLv']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['vipExp']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['power']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['gold']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['createTime']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['loginTime']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['fight']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['onLineTime']; ?>
</td>
          <td align="center"><?php echo $this->_tpl_vars['list']['guildName']; ?>
</td>
        </tr>
        <?php endforeach; else: ?>
          <tr>
            <th colspan="22" align="center">
                <?php if ($this->_tpl_vars['_GET']['submit']): ?>
             		   查无数据
                <?php else: ?>
                	<font color="#FF0000">请使用"查询"按钮</font>
                <?php endif; ?>
            </th>
          </tr>
		<?php endif; unset($_from); ?>
          <tr>
            <td colspan="22">
            	<input type="checkbox" onClick="$('input[name=playerIds[]]').attr('checked',$(this).attr('checked'))">全选
                <input type="hidden" name="fromPlayerList" value="1" />            	
            	<input type="button" class="btn-blue" value="发邮件" onclick="jumpUrl('<?php echo $this->_tpl_vars['ShortcutUrl']['SendMail']; ?>
');" />
                <input type="button" class="btn-blue" value="封号/禁言" onclick="jumpUrl('<?php echo $this->_tpl_vars['ShortcutUrl']['AddTitleOrGag']; ?>
');" />
            </td>
          </tr>
          <tr>
            <th colspan="22" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
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