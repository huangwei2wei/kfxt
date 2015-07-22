<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:25
         compiled from Prem/AddAct.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Prem/AddAct.html', 9, false),)), $this); ?>
<fieldset>
	<legend>增加方法</legend>
    <form action="<?php echo $this->_tpl_vars['url']['Control_AddAct']; ?>
" method="post">
    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th>所属控制器</th>
        <td>
            <select name="control_id" onchange="displayActionList($(this).val())">
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['controlList']), $this);?>

            </select>
        </td>
      </tr>
      <tr>
        <th>动作名</th>
        <td><input type="text" class="text" name="action_name" /></td>
      </tr>
      <tr>
        <th>是否所有用户</th>
        <td>
            <input type="radio" name="all_role" value="1" />所有
            <input type="radio" name="all_role" checked value="0" />受角色控制
        <input type="submit" class="btn-blue" value="提交" /></td>
      </tr>
      </table>
    </form>
</fieldset>
<fieldset>
	<legend>方法列表</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th>Id</th>
        <th>控制器</th>
        <th>动作名</th>
        <th>角色控制</th>
        <th>操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['actionList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_control']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['value']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_allow']; ?>
</td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
            <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="5"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
    </table>
</fieldset>