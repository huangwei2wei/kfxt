<?php /* Smarty version 2.6.26, created on 2013-04-08 18:07:40
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
        <th nowrap="nowrap" scope="col">Id</th>
        <th nowrap="nowrap" scope="col">账号</th>
        <th nowrap="nowrap" scope="col">姓名 / 职位(角色) / 客服序号<br />部门 / 组别</th>
        <th nowrap="nowrap" scope="col">添加时间<br />更新时间</th>
        <th nowrap="nowrap" scope="col">状态</th>
        <th nowrap="nowrap" scope="col">个人权限数量</th>
        <th nowrap="nowrap" scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['user_name']; ?>
</td>
        <td title="act:<?php echo $this->_tpl_vars['list']['act']; ?>
" align="center"><?php echo $this->_tpl_vars['list']['nick_name']; ?>
 /  [<?php echo $this->_tpl_vars['list']['word_roles']; ?>
] /  <?php echo $this->_tpl_vars['list']['service_id']; ?>
<br />
          <?php echo $this->_tpl_vars['list']['word_department']; ?>
 / <?php echo $this->_tpl_vars['list']['word_org_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['date_created']; ?>
<br /><?php echo $this->_tpl_vars['list']['date_updated']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
        <td align="center" title="act:<?php echo $this->_tpl_vars['list']['act']; ?>
"><?php echo $this->_tpl_vars['list']['act_count']; ?>
</td>
        <td>
        	[<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>]
            [<a onclick="return confirm('确定要删除吗?')" href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">永久删除</a>]
            [<a href="<?php echo $this->_tpl_vars['list']['url_act']; ?>
">增加权限</a>]  
            <br />
            [<a onclick="return confirm('确定要改变状态吗?')" href="<?php echo $this->_tpl_vars['list']['url_close']; ?>
">停用/启用</a>]
            [<a onclick="return confirm('确定要清空吗?')" href="<?php echo $this->_tpl_vars['list']['url_clear_order']; ?>
">清空工单队列</a>]
            [<a onclick="return confirm('确定要清空吗?')" href="<?php echo $this->_tpl_vars['list']['url_clear_quality_check']; ?>
">清空质检任务</a>] 
            [<a href="<?php echo $this->_tpl_vars['list']['url_operator_manage']; ?>
">设置运营商优先级别</a>]          
            [<a onclick="return confirm('确定要初始化吗?')" href="<?php echo $this->_tpl_vars['list']['url_Initialize']; ?>
">初始化</a>]
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="7"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
        <th colspan="7" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
      </tr>
    </table>
</fieldset>
