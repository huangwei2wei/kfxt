<?php /* Smarty version 2.6.26, created on 2013-04-07 14:19:44
         compiled from ActionGame_MasterTools/ItemCard/XiYou.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ActionGame_MasterTools/ItemCard/XiYou.html', 47, false),)), $this); ?>
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
    font-size: 12px;
    line-height: 150%;
    margin-bottom: 2px;
    margin-top: 2px;
    overflow: hidden;
    width: 400px;
    word-wrap: break-word;
}
</style>
<fieldset>
	<legend>
        礼包列表
        [<a href="<?php echo $this->_tpl_vars['URL_itemCardApply']; ?>
">礼包申请</a>]
    </legend>
<form action="" method="get">
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
    礼包号:<input class="text" type="text" name="cardId" value="<?php echo $this->_tpl_vars['_GET']['cardId']; ?>
"/>
    礼包名称:<input class="text" type="text" name="cardName" value="<?php echo $this->_tpl_vars['_GET']['cardName']; ?>
"/>
    <input class="btn-blue" type="submit" name="submit" value="查询" />
</form>
<table width="100%" border="0" >
  <tr>
    <th scope="col">ID号</th>
    <th scope="col">创建时间</th>
    <th scope="col">标题</th>
    <th scope="col">内容</th>
    <th scope="col">物品</th>
    <th scope="col">类型</th>
    <th scope="col">domain</th>
    <th scope="col">是否有效</th>
    <th scope="col">有效时间</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td align="center"><?php echo $this->_tpl_vars['list']['cardId']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['createTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['content']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['goods']; ?>

    <td align="center"><?php echo $this->_tpl_vars['list']['type']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['domain']; ?>
</td>
    <td align="center"><?php if ($this->_tpl_vars['list']['isValid'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['inValidTime'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
    <td><a href="<?php echo $this->_tpl_vars['list']['URL_itemCardFailure']; ?>
" onclick="return confirm('你确定使其失效吗？')">使其失效</a></td>
  </tr>
  <?php endforeach; else: ?>
  <tr>
  	<td colspan="15" align="center">
    	<?php if ($this->_tpl_vars['connectError']): ?>
        	<?php echo $this->_tpl_vars['connectError']; ?>

    	<?php elseif ($this->_tpl_vars['_GET']['submit']): ?>
    		查无数据
        <?php else: ?>
        	<font color="#FF0000">请使用"查询"按钮</font>
        <?php endif; ?>
    </td>
  </tr>
  <?php endif; unset($_from); ?>
</table>
<div style="text-align:right"><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>

<?php endif; ?>