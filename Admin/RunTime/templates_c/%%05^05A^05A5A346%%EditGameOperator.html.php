<?php /* Smarty version 2.6.26, created on 2013-04-07 10:22:34
         compiled from GameOperator/EditGameOperator.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'GameOperator/EditGameOperator.html', 42, false),array('modifier', 'default', 'GameOperator/EditGameOperator.html', 61, false),)), $this); ?>
<script language="javascript">
	function gameChange(gameId){
		$.ajax({
			dataType:'json',	
			type: 'GET',
			data:{game_id:gameId},
			url: '<?php echo $this->_tpl_vars['url']['GameOperator_OperatorExtParam']; ?>
',
			success:function(json){
				if(0 == json.status){
					alert(json.info);
				}
				else if(1 == json.status){
					$('#td_extParam').html('');
					var i = 0;
					$.each(json.data,
						function(Id,input){ 
							i++;
							if($.trim(input[2]) == ''){
								input[2] == 'text';
							}
							$('#td_extParam').append('<div style="margin:5px;">'+input[1]+':<input type="'+input[2]+'" class="text" style="width:400px;" name="ext['+input[0]+']" value="'+input[3]+'" ></div>');
						}
					);
					if(i==0){
						$('#td_extParam').html('无');
					}
				}
			}
		});		
	}
	
</script>

<fieldset>
	<legend>增加游戏的运营商</legend>
    <form action="" id="search" method="post">
        <table width="100%" border="0" cellpadding="3">
          <tr>
            <th scope="row" nowrap>游戏</th>
            <td>
            <?php if ($this->_tpl_vars['isAdd']): ?>
            	<?php echo smarty_function_html_radios(array('name' => 'game_type','options' => $this->_tpl_vars['gameTypeList'],'onclick' => "gameChange($(this).val());",'separator' => "&nbsp;"), $this);?>

            <?php else: ?>
            	<?php echo $this->_tpl_vars['dataObject']['game_type']; ?>

            <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th scope="row" nowrap>运营商</th>
            <td>
            <?php if ($this->_tpl_vars['isAdd']): ?>
            	<?php echo smarty_function_html_radios(array('name' => 'operator_id','options' => $this->_tpl_vars['operatorList'],'separator' => "&nbsp;"), $this);?>

            <?php else: ?>
            	<?php echo $this->_tpl_vars['dataObject']['operator_id']; ?>

            <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th scope="row" nowrap>URL</th>
            <td>
                <input type="text" class="text" style="width:400px;" name="url" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['dataObject']['url'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
">
                
                例如 http://s<font color="#FF0000">{$var}</font>.app27805.qqopenapp.com/sljadm/
            </td>
          </tr>
          <tr>
            <th scope="row" nowrap>超时时间</th>
            <td>
                <input type="text" class="text" style="width:400px;" name="vip_timeout" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['dataObject']['vip_timeout'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"> VIP超时限定(普通,1-6级 用","隔开)
            </td>
          </tr>
          <tr>
            <th scope="row" nowrap>充值分段</th>
            <td>
                <input type="text" class="text" style="width:400px;" name="vip_pay" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['dataObject']['vip_pay'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
"> VIP等级设定[充值量](普通,1-6级 用","隔开)
            </td>
          </tr>
          <tr id="tr_extParam">
          	<th scope="row" nowrap>附加参数</th>
          	<td id="td_extParam">
            <?php $_from = $this->_tpl_vars['inputData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['input']):
?>
            	<div style="margin:5px;"><?php echo $this->_tpl_vars['input']['1']; ?>
:<input type="<?php echo $this->_tpl_vars['input']['2']; ?>
" class="text" style="width:400px;" name="ext[<?php echo $this->_tpl_vars['input']['0']; ?>
]" value="<?php echo $this->_tpl_vars['dataObject']['ext'][$this->_tpl_vars['input']['0']]; ?>
" ></div>
            <?php endforeach; else: ?>
            	无
            <?php endif; unset($_from); ?>            	
            </td>
          </tr>
          <tr>
            <th colspan="2" scope="row"><input type="submit" class="btn-blue" value="提交" /></th>
          </tr>
        </table>
	</form>
</fieldset>