<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:37
         compiled from Default/NavigationBar.html */ ?>
<center>
<table width="99%" border="0" cellpadding="2" >
  <tr>
    <th width="25%" scope="row">[<a href="javascript:void(0)" onclick="history.go(-1)">返回上一步</a>]&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;您现在的位置：</th>
    <td align="left">
    	<?php echo $this->_tpl_vars['nav']['lv1']; ?>
&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['nav']['lv2']; ?>

        <?php if ($this->_tpl_vars['nav']['lv3']): ?>
        &nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['nav']['lv3']; ?>

        <?php endif; ?>	
    </td>
  </tr>
</table>
</center>