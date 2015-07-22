<?php /* Smarty version 2.6.26, created on 2013-04-08 18:10:31
         compiled from Apply/ApplyType.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Apply/ApplyType.html', 13, false),)), $this); ?>
<fieldset>
<legend>搜索</legend>

<form action="" method="get">
    <input type="hidden" name="zp" value="<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
" />
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td>
        所属游戏:
        <select name="game_type">
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['game_type'],'selected' => $this->_tpl_vars['_GET']['game_type']), $this);?>

        </select>
        所属列表ID:<input type="text" class="text" name="list_type" value="<?php echo $this->_tpl_vars['_GET']['list_type']; ?>
">
        <input type="submit" class="btn-blue" value="搜索">
        </td>
      </tr>
    </table>
</form>
</fieldset>



<fieldset>
<legend>审核类型</legend>
<a href="<?php echo $this->_tpl_vars['url']['URL_ApplyTypeAdd']; ?>
" >添加</a>
<a href="<?php echo $this->_tpl_vars['url']['URL_ApplyTypeCahce']; ?>
" >生成缓存</a>

<table width="100%" border="0" cellpadding="3">
  <tr>
    <th scope="col">ID</th>
    <th scope="col">类型名</th>
    <th scope="col">所属游戏</th>
    <th scope="col">所属列表ID</th>
    <th scope="col">操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td align="center"><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['name']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['game_type']; ?>
</td>
    <td align="center"><?php echo $this->_tpl_vars['list']['list_type']; ?>
</td>
    <td align="center">
    	[<a href="<?php echo $this->_tpl_vars['list']['URL_edit']; ?>
">编辑</a>]
        [<a onClick="return confirm('确定删除？');" href="<?php echo $this->_tpl_vars['list']['URL_del']; ?>
">删除</a>]
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?>
</table>

<table width="100%" border="0" cellpadding="3">
  <tr><td align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
</table>
</fieldset>