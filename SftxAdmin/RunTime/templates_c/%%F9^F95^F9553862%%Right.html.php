<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:37
         compiled from Index/Right.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'Index/Right.html', 57, false),)), $this); ?>
<fieldset>
  <legend>我的个人资料</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td height="22" colspan="4" scope="row">
        	常用链接：
            <a target="_blank" href=" http://mail.cndw.com/">工作邮箱</a>、
            
            <a target="_blank" href=" http://passport.uwan.com/">PassPort</a>、
            
            <a target="_blank" href=" http://work.uwan.com/myjob/index.asp">MyJob</a>、
            
            <a target="_blank" href=" http://work.uwan.com/buglist/members.asp">大亨buglist</a>、
            
            <a target="_blank" href=" http://gm.bto.uwan.com/">大亨回复后台</a>、
            
            <a target="_blank" href=" http://bbs.uwan.com/">大亨国服论坛</a>、
            
            <a target="_blank" title="大亨海外官网" href=" http://bto.dovogame.com/index.html">海外官网</a>、
            
            <a target="_blank" title="大亨海外论坛" href=" http://btobbs.dovogame.com/index.php">海外论坛</a>、
            
            <a target="_blank" title="大亨海外Support后台" href=" http://bto.dovogame.com/gmdovo/login.php">Support后台</a>
        </td>
      </tr>
      <tr>
        <th width="114" scope="row">姓名</th>
        <td width="548">
        	<?php echo $this->_tpl_vars['userClass']['_nickName']; ?>

        	<?php if ($this->_tpl_vars['userClass']['_serviceId']): ?>
            (<?php echo $this->_tpl_vars['userClass']['_serviceId']; ?>
)
            <?php endif; ?>
            、<?php echo $this->_tpl_vars['userClass']['word_department']; ?>

        </td>
        <th width="135">所在角色</th>
        <td width="529"><?php echo $this->_tpl_vars['userClass']['word_roles']; ?>
</td>
      </tr>
      <tr>
        <th width="114" scope="row">负责的运营商</th>
        <td>
        	<?php $_from = $this->_tpl_vars['userOeratorIds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
            <?php echo $this->_tpl_vars['list']; ?>
&nbsp;&nbsp;
            <?php endforeach; else: ?>
            暂无可处理的运营商
            <?php endif; unset($_from); ?>
        </td>
        <th>负责的VIP等级</th>
        <td>
			<?php echo $this->_tpl_vars['userClass']['word_vip']; ?>

        </td>
      </tr>
      <tr>
        <td height="69" colspan="2" valign="top">
        	<b>公告</b>
            <ol>
            <?php $_from = $this->_tpl_vars['userClass']['bulletin_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
            	<li><?php echo $this->_tpl_vars['list']['word_is_read']; ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
) <?php echo $this->_tpl_vars['list']['title']; ?>
</li>
            <?php endforeach; else: ?>
            	<li><?php echo $this->_tpl_vars['noData']; ?>
</li>
            <?php endif; unset($_from); ?>
            </ol>
        </td>
        <td colspan="2" valign="top">
        	<b>工作交接</b>
            <ol>
            <?php $_from = $this->_tpl_vars['userClass']['work_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
            	<li><?php echo $this->_tpl_vars['list']['word_is_read']; ?>
 (<?php echo ((is_array($_tmp=$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
)  <?php echo $this->_tpl_vars['list']['title']; ?>
</li>
            <?php endforeach; else: ?>
            	<li><?php echo $this->_tpl_vars['noData']; ?>
</li>
            <?php endif; unset($_from); ?>
          </ol>
        </td>
      </tr>
    </table>
</fieldset>