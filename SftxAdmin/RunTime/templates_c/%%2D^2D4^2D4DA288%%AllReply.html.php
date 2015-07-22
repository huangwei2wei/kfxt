<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:54
         compiled from QualityCheck/AllReply.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'QualityCheck/AllReply.html', 9, false),array('modifier', 'truncateutf8', 'QualityCheck/AllReply.html', 43, false),)), $this); ?>
<fieldset>
	<legend>任务选择</legend>
        <form action="<?php echo $this->_tpl_vars['url']['QualityCheck_MyQualityTask']; ?>
" method="get">
        <input type="hidden" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" name="c" />
        <input type="hidden" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" name="a" />
        <table width="100%" border="0" cellpadding="3">
          <tr>
            <th scope="row"> 质检类型：</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['qualityOptions'],'selected' => $this->_tpl_vars['selectedQualityOption'],'name' => 'quality_option'), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row">申诉状态</th>
            <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['statusOptions'],'selected' => $this->_tpl_vars['selectedStatusOption'],'name' => 'status_option'), $this);?>
</td>
          </tr>
          <tr>
            <th scope="row" colspan="2"><input type="submit" class="btn-blue" value="提交" /></th>
          </tr>
        </table>
        </form>
</fieldset>

<fieldset>
  <legend>所有被质检的回复
    </legend><table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">质检选项</th>
        <th scope="col">质检内容</th>
        <th scope="col">质检时间</th>
        <th scope="col">申诉内容</th>
        <th scope="col">申诉时间</th>
        <th scope="col">处理回复</th>
        <th scope="col">处理时间</th>
        <th scope="col">申诉状态</th>
        <th scope="col">分数</th>
        <th scope="col">质检人</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_option_id']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['quality_content'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 50) : smarty_modifier_truncateutf8($_tmp, 50)); ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['quality_time']; ?>
</td>
        <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['complain_content'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 50) : smarty_modifier_truncateutf8($_tmp, 50)); ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['complain_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['reply_content']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['reply_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_status']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['scores']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_quality_user_id']; ?>
</td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">察看详细</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th colspan="12"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
        <th colspan="12" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</th>
      </tr>
    </table>

</fieldset>