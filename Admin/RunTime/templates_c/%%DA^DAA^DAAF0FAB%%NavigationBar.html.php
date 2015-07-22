<?php /* Smarty version 2.6.26, created on 2013-04-02 10:44:19
         compiled from Default/NavigationBar.html */ ?>
<fieldset style="padding:2px">
	<legend><?php echo $this->_tpl_vars['L_NaviBar']; ?>
</legend>
<table width="100%" border="0" cellpadding="2" style="margin:0px;" >
  <tr>
    <th width="25%" scope="row">[<a href="javascript:void(0)" onclick="history.go(-1)"><?php echo $this->_tpl_vars['L_GoBack']; ?>
</a>]&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['L_MyLocation']; ?>
:</th>
    <td align="left">
    	<?php echo $this->_tpl_vars['nav']['lv1']; ?>
&nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['nav']['lv2']; ?>

        <?php if ($this->_tpl_vars['nav']['lv3']): ?>
        &nbsp;&nbsp;&nbsp;>>&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['nav']['lv3']; ?>

        <?php endif; ?>	
    </td>
  </tr>
</table>
</fieldset>