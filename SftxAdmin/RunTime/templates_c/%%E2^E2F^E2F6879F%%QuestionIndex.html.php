<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:18
         compiled from SysManagement/QuestionIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'SysManagement/QuestionIndex.html', 18, false),)), $this); ?>
<fieldset>
	<legend>列表</legend>
    <a href="<?php echo $this->_tpl_vars['url']['SysManagement_QuestionAdd']; ?>
">增加</a>
    <a href="<?php echo $this->_tpl_vars['url']['SysManagement_QuestionCreateCache']; ?>
">生成缓存</a>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th>Id</th>
        <th>所属游戏</th>
        <th>问题名称</th>
        <th>问题表单</th>
        <th>操作</th>
      </tr>
    <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['game_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['form_table'])) ? $this->_run_mod_handler('truncate', true, $_tmp) : smarty_modifier_truncate($_tmp)); ?>
</td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_question_form']; ?>
">问题表单编辑</a>
            <a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
            <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="5"><?php echo $this->_tpl_vars['noData']; ?>
</td></tr>
    <?php endif; unset($_from); ?>
    </table>
</fieldset>