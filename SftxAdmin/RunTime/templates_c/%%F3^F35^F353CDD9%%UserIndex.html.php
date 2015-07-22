<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:20
         compiled from User/UserIndex.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'User/UserIndex.html', 14, false),)), $this); ?>
<script language="javascript">
$(function(){
	$("form :radio").click(function(){$("form").submit();});
})
</script>
<fieldset>
	<legend>用户搜索</legend>
  <form action="" method="get">
      <input type="hidden" value="User" name="c"  />
        <input type="hidden" value="User" name="a" />
   	  <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <th scope="row">部门选择</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['selectDepartmentList'],'name' => 'department_id','selected' => $this->_tpl_vars['selectedDepartmentId']), $this);?>
</td>
        </tr>
        <tr>
          <th scope="row">组别选择</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['selectOrgList'],'name' => 'org_id','selected' => $this->_tpl_vars['selectedOrgId']), $this);?>
</td>
        </tr>
        <tr>
          <th scope="row">搜索选项</th>
          <td>
            账号：<input type="text" class="text" name="user_name" value="<?php echo $this->_tpl_vars['selectedUserName']; ?>
" />
            姓名搜索：<input type="text" class="text" name="nick_name" value="<?php echo $this->_tpl_vars['selectedNickName']; ?>
" />
            <input type="submit" class="btn-blue" value="提交" />
          </td>
        </tr>
      </table>
    </form>
</fieldset>

<fieldset>
	<legend>用户列表</legend>
    <a href="<?php echo $this->_tpl_vars['url']['User_Add']; ?>
">Add</a>
    <a href="<?php echo $this->_tpl_vars['url']['User_CreateCache']; ?>
">CreateCache</a>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">账号</th>
        <th scope="col">客服序号</th>
        <th scope="col">姓名</th>
        <th scope="col">部门</th>
        <th scope="col">组别</th>
        <th scope="col">职位(角色)</th>
        <th scope="col">添加时间</th>
        <th scope="col">更新时间</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['user_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['service_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['nick_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_department']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_org_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_roles']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['date_created']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['date_updated']; ?>
</td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_operator_manage']; ?>
">设置运营商优先级别</a>
            <a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
            <a onclick="return confirm('确定要删除吗?')" href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
            <a onclick="return confirm('确定要初始化吗?')" href="<?php echo $this->_tpl_vars['list']['url_Initialize']; ?>
">初始化</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="10"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
        <th colspan="10" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
      </tr>
    </table>
</fieldset>
