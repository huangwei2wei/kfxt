<?php /* Smarty version 2.6.26, created on 2013-04-09 16:46:02
         compiled from GmSftx/RewardRank.html */ ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<fieldset>
<legend>搜索</legend>

<form action="" method="get">
    <input type="hidden" name="zp" value="<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
" />
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <input type="hidden" name="server_id" value="<?php echo $this->_tpl_vars['_GET']['server_id']; ?>
" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td>
        用户Id:<input type="text" class="text" name="playerId" value="<?php echo $this->_tpl_vars['_GET']['playerId']; ?>
">
        活动Id:<input type="text" class="text" name="activityId" value="<?php echo $this->_tpl_vars['_GET']['activityId']; ?>
">
        奖励Id:<input type="text" class="text" name="rewardId" value="<?php echo $this->_tpl_vars['_GET']['rewardId']; ?>
">
        <input type="submit" class="btn-blue" value="搜索">
        </td>
      </tr>
    </table>
</form>
</fieldset>

<fieldset>
<legend>结果</legend>

<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">用户Id</th>
    <th scope="col">活动Id</th>
    <th scope="col">奖励Id</th>
    <th scope="col">年份</th>
    <th scope="col">分数</th>
    <th scope="col">是否领取</th>
    <th scope="col">领奖时间</th>
  </tr>
  <?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['playerId']; ?>
</td>
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['activityId']; ?>
</td>
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['rewardId']; ?>
</td>
    
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['year']; ?>
</td>
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['mark']; ?>
</td>
    
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['isReceive']; ?>
</td>
    <td scope="col" align='center'><?php echo $this->_tpl_vars['list']['time']; ?>
</td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>

</fieldset>
