<?php /* Smarty version 2.6.26, created on 2012-09-13 17:51:53
         compiled from ServiceTools/Index.html */ ?>
<script language="javascript">
function userRead(curButton){
	var id=curButton.attr("cur_id");
	$.getJSON(
		url,
		{c:'ServiceTools',a:'NoticeShow',doaction:'read',Id:id},
		function(){
			curButton.remove();
		}
	);
}
</script>
<fieldset>
	<legend>公告列表</legend>
    <div>
    	<a href="<?php echo $this->_tpl_vars['url']['ServiceTools_Add']; ?>
">添加新公告</a>
    </div>
    <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col"><font size="+3"><?php echo $this->_tpl_vars['list']['date']; ?>
</font></th>
      </tr>
      <?php $_from = $this->_tpl_vars['list']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childList']):
?>
      <tr>
        <td style="background:#666"><font size="+1" color="#FFFFFF"><b><?php echo $this->_tpl_vars['childList']['word_kind']; ?>
</b></font></td>
      </tr>
      <tr>
        <td>
        	<font size="+1"><b><?php echo $this->_tpl_vars['childList']['title']; ?>
</b></font> [<font color="#FF6600"><?php echo $this->_tpl_vars['childList']['create_time']; ?>
 , <?php echo $this->_tpl_vars['childList']['word_user_id']; ?>
</font>]  <a href="<?php echo $this->_tpl_vars['childList']['url_edit']; ?>
">编辑</a> <a href="<?php echo $this->_tpl_vars['childList']['url_del']; ?>
">删除 </a>
            
            <?php if ($this->_tpl_vars['childList']['user_no_read']): ?>
            <input type="button" class="btn-blue" value="我已阅读" cur_id='<?php echo $this->_tpl_vars['childList']['Id']; ?>
' onclick="userRead($(this))" />
            <?php endif; ?>
            
            <br />
            <div style="margin-left:10px; margin-top:5px;"><?php echo $this->_tpl_vars['childList']['content']; ?>
</div>	
            <div style="margin:5px; padding:2px; background:#D6E8F1">
            	<label>未阅读用户：</label>
                <?php echo $this->_tpl_vars['childList']['word_not_read']; ?>

            </div>		
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <th scope="col"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
      <?php endif; unset($_from); ?>
    </table><br /><br />
    <?php endforeach; else: ?>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col"><?php echo $this->_tpl_vars['noData']; ?>
</th>
      </tr>
    </table>
    <?php endif; unset($_from); ?>
    <div align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</div>
</fieldset>