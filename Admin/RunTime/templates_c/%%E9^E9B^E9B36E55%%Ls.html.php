<?php /* Smarty version 2.6.26, created on 2013-04-11 10:56:44
         compiled from Askform/Ls.html */ ?>
<fieldset>
	<legend>搜索列表</legend>
</fieldset>
<fieldset>
	<legend>问卷列表</legend>
    <a href="<?php echo $this->_tpl_vars['url']['Askform_Add']; ?>
">添加新问卷</a>
    <!--ID,标题，开始时间，结束时间，状态，发布人，参与调查人数-->
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th>Id</th>
        <th>标题</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>状态</th>
        <th>发布人</th>
        <th>参与调查人数</th>
        <th>操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['title']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['start_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['end_time']; ?>
</td> 
        <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_user_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['attend_count']; ?>
</td>
        <td>
        	<a href="<?php echo $this->_tpl_vars['list']['url_addoption']; ?>
">增加子项</a>
            <a href="<?php echo $this->_tpl_vars['list']['url_show']; ?>
">显示调察结果</a>
            <a onclick="return confirm('确定要删除吗?')" href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="8"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?> 
     <tr>
     	<th colspan="8" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
     </tr>
    </table>
</fieldset>