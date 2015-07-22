<?php /* Smarty version 2.6.26, created on 2013-04-07 10:23:11
         compiled from ActionGame_OperatorTools/ServerManagement/Add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_OperatorTools/ServerManagement/Add.html', 12, false),array('function', 'html_radios', 'ActionGame_OperatorTools/ServerManagement/Add.html', 51, false),array('modifier', 'default', 'ActionGame_OperatorTools/ServerManagement/Add.html', 19, false),array('modifier', 'trim', 'ActionGame_OperatorTools/ServerManagement/Add.html', 19, false),array('modifier', 'date_format', 'ActionGame_OperatorTools/ServerManagement/Add.html', 34, false),)), $this); ?>
<fieldset>
<legend>增加服务器</legend>
<form action="<?php echo $this->_tpl_vars['url']['GameSerList_Add']; ?>
" method="post">
<input type="hidden" name="Id" value="<?php echo $this->_tpl_vars['get']['Id']; ?>
" />
<input type="hidden" name="game_type" value="<?php echo $this->_tpl_vars['get']['__game_id']; ?>
" />
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th>运营商</th>
    <td>
    	<select name="operator_id">
        	<option value="0">-请选择-</option>
        	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList'],'selected' => $this->_tpl_vars['data']['operator_id']), $this);?>

        </select>
    </td>
  </tr>
  <tr>
    <th>服务器号</th>
    <td>
        <input type="text" class="text" name="ordinal" value='<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['data']['ordinal'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['_GET']['ordinal']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['_GET']['ordinal'])))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
' /> (填数字，一般情况下，如：http://s<font color="#FF0000">5</font>.r.uwan.com/ 填 <font color="#FF0000">5</font>)
    </td>
  </tr>
  <tr>
    <th>服务器名称</th>
    <td>
    	<input type="text" class="text" name="server_name" value='<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['data']['server_name'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['_GET']['server_name']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['_GET']['server_name'])))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
' />
    </td>
  </tr>
  <tr>
    <th>标识</th>
    <td><input type="text" class="text" name="marking" style="width:400px;" value='<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['data']['marking'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['_GET']['marking']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['_GET']['marking'])))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
'/></td>
  </tr>
  <tr>
    <th>工单时差</th>
    <td><input type="text" class="text" name="time_zone" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['time_zone'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
" /> (以当前服务器时间[<font color="#FF0000"><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</font>]为准,加小时或是减小时,例如:-8)</td>
  </tr>
  <tr>
    <th>时区标识</th>
    <td><input type="text" class="text" name="timezone" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['timezone'])) ? $this->_run_mod_handler('default', true, $_tmp, 'PRC') : smarty_modifier_default($_tmp, 'PRC')); ?>
" /> (中国:PRC,太平洋:PST8PDT)</td>
  </tr>
  <tr>
    <th>服务器地址</th>
    <td><input type="text" class="text" name="server_url" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['data']['server_url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['_GET']['server_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['_GET']['server_url'])))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
" style="width:400px;" /></td>
  </tr>
  <tr>
    <th>数据连接地址<br/>个别游戏需要添加</th>
    <td><input type="text" class="text" name="data_url" value="<?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['data']['data_url'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['_GET']['data_url']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['_GET']['data_url'])))) ? $this->_run_mod_handler('trim', true, $_tmp) : trim($_tmp)); ?>
" style="width:400px;" /></td>
  </tr>
  <tr>
    <th>接入定时器</th>
    <td>
    	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['timer'],'name' => 'timer','selected' => '0'), $this);?>
 
        (在腾讯代理的游戏中，定时调用游戏接口)
    </td>
  </tr>
  <tr>
    <th>是否批量添加
      <input type="checkbox" value="1" name="batch_add" /></th>
    <td>范围：<input type="text" size="4" name="start" class="text" value="1" /> - <input type="text" class="text" size="4" name="end" value="10" /> 需要变量的地方请用[<font color="#FF0000">{$var}</font>]</td>
  </tr>
  <tr>
    <th colspan="2"><input type="submit" class="btn-blue" value="提交" onclick="return checkSubmit();" /></th>
  </tr>
</table>
</form>
</fieldset>
<script language="javascript">
	function checkSubmit(){
		var operatorIds = $('select[name=operator_id]');
		if(operatorIds.val()>0){
			return true;
		}
		alert('请选择运营商');
		operatorIds.focus();
		return false;
	}
	<?php if ($this->_tpl_vars['_GET']['operator_name']): ?>
		var operatorIds = $('select[name=operator_id]');
		$.each(operatorIds[0],function(id,option){
			if('<?php echo $this->_tpl_vars['_GET']['operator_name']; ?>
'==option.text ){
				operatorIds.val(option.value);
				return false;
			}
		});
	<?php endif; ?>
</script>