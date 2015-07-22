<?php /* Smarty version 2.6.26, created on 2013-04-09 17:51:34
         compiled from GmSftx/SearchPlayerEmail.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'GmSftx/SearchPlayerEmail.html', 11, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>


<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
/*$(function(){
	$.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
	$("#server_id").formValidator({onshow:"<?php echo ((is_array($_tmp='SERVER_ID_Not_EXIST')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
",oncorrect:"<?php echo ((is_array($_tmp='PLEASE_INPUT_SERVER_ID_AGAIN')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"<?php echo ((is_array($_tmp='SERVER_ERROR')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
	$("#UserId").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"<?php echo ((is_array($_tmp='BOTH_SIDES_CAN_INCLUDE_BLANK')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"},onerror:"<?php echo ((is_array($_tmp='SERVER_ERROR')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
})**/

$(function(){
	$("#submit").click(function(){
		/**var server_id = $("#server_id").attr('value');
		if(server_id==''){
			alert("请选择服务器！");
			return false;
		}**/
		
		$("#form").ajaxSubmit({
			dataType:'json',
			async : false,    // 设置同步
			data:{},
			success:function(data){
				$("#show_smg").html(data);
				return false;
			}
		});
		
		
	})
	

})



</script>
<fieldset>
<legend><?php echo ((is_array($_tmp='ADD_DONTTALK_USER')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<form action="" method="post" id="form" >
<input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <table width="98%" border="0" cellpadding="3">
      <tr>
        <th scope="row">用户id：</th>
        <td><input type="text" id='uid' name='uid' class="text" value="<?php echo $this->_tpl_vars['_POST']['uid']; ?>
"/><span id="show_smg"></span></td>
      </tr>
      <tr>
        <th scope="row">平台：</th>
        <td>苹果<input type="radio" name='platformKey' value='1' checked="checked" /> 安卓<input type="radio" name='platformKey' value='2'/> </td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input id='submit' type="button" class="btn-blue" name="submit" value="<?php echo ((is_array($_tmp='SUBMIT')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /></th>
      </tr>
    </table>
</form>
</fieldset>