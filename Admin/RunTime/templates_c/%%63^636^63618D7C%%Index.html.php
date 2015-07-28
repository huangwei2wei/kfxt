<?php /* Smarty version 2.6.26, created on 2013-04-07 11:20:37
         compiled from Z/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Z/Index.html', 18, false),array('modifier', 'trim', 'Z/Index.html', 25, false),array('modifier', 'intval', 'Z/Index.html', 54, false),array('function', 'html_radios', 'Z/Index.html', 54, false),)), $this); ?>
<script language="javascript">
	function heighter(){
		
		var h = $('#returnData').css('height');
		h = parseInt(h.replace('px',''));
		$('#returnData').css('height',h+100+'px');
	}
</script>
<fieldset>
    <legend>http请求测试</legend>
    <form method="post" action="">
        <table width="98%" border="0" cellpadding="3">
          <tr>
            <th scope="row">发送地址</th>
            <td>
                <textarea name="sendUrl" style="width:700px; height:45px;"><?php echo $this->_tpl_vars['_POST']['sendUrl']; ?>
</textarea>
                <input type="submit" class="btn-blue" value="发送">&nbsp; time:<?php echo $this->_tpl_vars['current_time']; ?>

                <font color="#FF0000"><?php echo ((is_array($_tmp=@$this->_tpl_vars['error'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</font>
            </td>
          </tr>
          <tr>
            <th scope="row">GET数据<br><br>换行或者"&amp;"隔开<br>例如:c=Index&amp;a=Login</th>
            <td>
            	填写:
                <textarea name="getData" style="width:400px; height:150px;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['_POST']['getData'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
</textarea>
                参数分析:
                <textarea readonly style="width:400px; height:150px; background:#9FF" ><?php echo $this->_tpl_vars['getData']; ?>
</textarea>
                <input type="checkbox" name="splite" value="1" <?php if ($this->_tpl_vars['_POST']['splite']): ?>checked<?php endif; ?>>用&amp;隔开参数
            </td>
          </tr>
          <tr>
            <th scope="row">POST数据<br><br>换行或者"&amp;"隔开<br>例如:c=Index&amp;a=Login</th>
            <td>
            	填写:
            	<textarea name="postData" style="width:400px; height:150px;" ><?php echo ((is_array($_tmp=$this->_tpl_vars['_POST']['postData'])) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
</textarea>
                参数分析:
                <textarea readonly style="width:400px; height:150px; background:#9FF" ><?php echo $this->_tpl_vars['postData']; ?>
</textarea>
            </td>
          </tr>
          <tr>
            <th scope="row">拼接URL</th>
            <td>
            	<textarea style="width:900px; height:45px;"><?php echo $this->_tpl_vars['urlJoin']; ?>
</textarea>
                拼接POST<input type="checkbox" value="1" name="postDataAddToUrl" <?php if ($this->_tpl_vars['_POST']['postDataAddToUrl']): ?>checked<?php endif; ?>>
            	<?php if ($this->_tpl_vars['urlJoin']): ?>
                	<a target="_blank" href="<?php echo $this->_tpl_vars['urlJoin']; ?>
">打开新页</a>
                <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th scope="row">返回结果</th>
            <td>
            	<div>
                	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['outputTypes'],'name' => 'outputType','selected' => ((is_array($_tmp=$this->_tpl_vars['_POST']['outputType'])) ? $this->_run_mod_handler('intval', true, $_tmp) : smarty_modifier_intval($_tmp))), $this);?>

                    &nbsp;&nbsp;&nbsp;&nbsp;<a onclick="heighter();" href="javascript:void(0);">加高</a>
                </div>
            	<div><textarea id="returnData" style="width:900px; height:150px;" readonly ><?php echo $this->_tpl_vars['data']; ?>
</textarea></div>
            </td>
          </tr>
        </table>
    </form>
</fieldset>