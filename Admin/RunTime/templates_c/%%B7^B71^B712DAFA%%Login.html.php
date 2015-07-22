<?php /* Smarty version 2.6.26, created on 2013-04-07 10:18:11
         compiled from Index/Login.html */ ?>
<center>
    <form style="margin-top:200px;;" action="<?php echo $this->_tpl_vars['url']['Index_Login']; ?>
" method="post">
    <table width="60%" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <th colspan="2" scope="row">动网先锋客服管理系统</th>
        </tr>
      <tr>
        <th scope="row">用户名</th>
        <td><input type="text" class="text" name="user_name" /></td>
      </tr>
      <tr>
        <th scope="row">密码</th>
        <td><input type="password" class="text" name="password" /></td>
      </tr>
      <tr>
        <th scope="row">验证码</th>
        <td><input class="text" name="verify_code" type="text" value="" maxLength=4 size=10 /> <img style="margin:0px; padding:0px;" align="absmiddle"  src="<?php echo $this->_tpl_vars['url']['Default_VerifyCode']; ?>
" /></td>
      </tr>
      <tr>
        <th colspan="2" align="center" scope="row"><input type="submit" value="提交" class="btn-blue" /></th>
      </tr>
    </table>
    </form>
</center>