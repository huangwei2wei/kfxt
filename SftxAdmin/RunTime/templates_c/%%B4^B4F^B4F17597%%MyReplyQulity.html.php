<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:54
         compiled from MyTask/MyReplyQulity.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'MyTask/MyReplyQulity.html', 9, false),array('modifier', 'truncateutf8', 'MyTask/MyReplyQulity.html', 45, false),)), $this); ?>
<fieldset>
<legend>搜索类型</legend>
    <form action="" method="get">
        <input type="hidden" value="MyTask" name="c" />
        <input type="hidden" value="MyReplyQulity" name="a" />
        <table width="100%" border="0" cellpadding="3">
          <tr>
            <th scope="row">类型</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['qualityOptions'],'selected' => $this->_tpl_vars['selectedOption'],'name' => 'option'), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row">申诉状态</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['qualityStatus'],'selected' => $this->_tpl_vars['selectedStatus'],'name' => 'status'), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row">扣分选择</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['scores'],'name' => 'scores','selected' => $this->_tpl_vars['selectedSource']), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row">超时选择</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['timeout'],'name' => 'is_timeout','selected' => $this->_tpl_vars['selectedTimeout']), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><input type="submit" class="btn-blue" value="提交" /></th>
          </tr>
        </table>
    </form>
</fieldset>
<fieldset>
  <legend>我被质检过的回复</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">回复内容</th>
        <th scope="col">回复时间</th>
        <th scope="col">质检人</th>
        <th scope="col">评价类型</th>
        <th scope="col">当前状态</th>
        <th scope="col">分数</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['content'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 30) : smarty_modifier_truncateutf8($_tmp, 30)); ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['create_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_quality_user_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_option_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['scores']; ?>
</td>
        <td>
        	<!--<a href="javascript:void(0)" url="<?php echo $this->_tpl_vars['list']['url_dialog']; ?>
" id="<?php echo $this->_tpl_vars['list']['Id']; ?>
" onclick="openDialog($(this))">提问回复详情</a>-->
            <a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">察看详细</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="8"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
        <td colspan="8" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td>
      </tr>
    </table>
</fieldset>