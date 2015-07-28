<?php /* Smarty version 2.6.26, created on 2013-04-07 16:14:08
         compiled from ActionGame_MasterTools/RechargeRecord/SanFen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/RechargeRecord/SanFen.html', 26, false),)), $this); ?>
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
<script>
	$(function(){
		$('#pageSize').val('<?php echo $this->_tpl_vars['_GET']['pageSize']; ?>
');
	});
</script>
<fieldset>
  <legend>搜索</legend>
  
<form action=""  method="get">
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <input type="hidden" name="zp" value="<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
" />
    <input type="hidden" name="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <input type="hidden" name="__game_id" value="<?php echo $this->_tpl_vars['__GAMEID__']; ?>
" />  
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td>
         账号类型:<select name="playerType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['playerType'],'selected' => $this->_tpl_vars['_GET']['playerType']), $this);?>

            </select>
        账号:<input class="text" type="text" name="player" value="<?php echo $this->_tpl_vars['_GET']['player']; ?>
">
            状态:
            <select name="state">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['state'],'selected' => $this->_tpl_vars['_GET']['state']), $this);?>

            </select>
        </td>
      </tr>
      <tr>
        <td>
            订单号:<input class="text" type="text" name="transactionId" value="<?php echo $this->_tpl_vars['_GET']['transactionId']; ?>
">
            充值时间:
			<input class="text" type="text" name="loginBeginTime" value="<?php echo $this->_tpl_vars['_GET']['loginBeginTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">
            -
            <input class="text" type="text" name="loginEndTime" value="<?php echo $this->_tpl_vars['_GET']['loginEndTime']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})">

            <input class="btn-blue" type="submit" name="sbm" value="查询">
        </td>
      </tr>
    </table>
</form>


</fieldset>

<fieldset>
  <legend>充值列表 [本页充值总额：<font color="#FF0000"><?php echo $this->_tpl_vars['pageMoneyTotal']; ?>
</font>]</legend>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">玩家ID</th>
    <th scope="col">支付流水号</th>
    <th scope="col">amt</th>
    <th scope="col">扣取抵用卷总金额（Q点）</th>
    <th scope="col">扣取游戏币总金额（Q点）</th>
    <th scope="col">玩家名</th>
    <th scope="col">从哪里进来的</th>
    <th scope="col">物品名称</th>
    <th scope="col">价格(Q点)</th>
    <th scope="col">数量</th>
    <!-- th scope="col">物品信息</th-->
    <th scope="col">大区ID</th>
    <th scope="col">充值时间</th>
    <th scope="col">充值状态</th>
    <th scope="col">确认操作返回码</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['playerId']; ?>
</td>        
        <td><?php echo $this->_tpl_vars['list']['billno']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['amt']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['pubacctPayamtCoins']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['payamtCoins']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['playerName']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['platform']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['goodsName']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['price']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['num']; ?>
</td>
        <!-- td><?php echo $this->_tpl_vars['list']['payitem']; ?>
</td-->
        <td><?php echo $this->_tpl_vars['list']['zoneid']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['ts']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['state']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['ret']; ?>
</td>
      </tr>
  <?php endforeach; else: ?>
  	<?php if ($this->_tpl_vars['ConnectErrorInfo']): ?>
      <tr>
        <td align="center" colspan="9"><?php echo $this->_tpl_vars['ConnectErrorInfo']; ?>
</td>
      </tr>
  	<?php else: ?>
      <tr>
        <td align="center" colspan="9">暂无数据</td>
      </tr>
  	<?php endif; ?>
  <?php endif; unset($_from); ?>
  <tr>
    <th align="right" scope="col" colspan="9"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
  </tr>
</table>

</fieldset>
<?php endif; ?>