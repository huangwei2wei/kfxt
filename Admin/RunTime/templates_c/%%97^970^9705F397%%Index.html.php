<?php /* Smarty version 2.6.26, created on 2013-04-08 18:12:31
         compiled from WorkOrder/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'WorkOrder/Index.html', 18, false),array('modifier', 'truncateutf8', 'WorkOrder/Index.html', 155, false),array('modifier', 'default', 'WorkOrder/Index.html', 159, false),array('function', 'html_checkboxes', 'WorkOrder/Index.html', 46, false),array('function', 'html_radios', 'WorkOrder/Index.html', 56, false),array('function', 'html_options', 'WorkOrder/Index.html', 75, false),)), $this); ?>
<style type="text/css">
.operator label{
	display:inline-block;
	width:100px;
}
</style>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
var timer=eval(<?php echo $this->_tpl_vars['timeInterval']; ?>
);
var forTime;
function doTime(){
	$.each(timer,function(i,n){
		if(n.time>0){
			minutes=Math.floor(n.time/60);
			seconds=Math.floor(n.time%60);
			if(minutes<20){
				msg="<font color='red'>"+minutes+"<?php echo ((is_array($_tmp='DAF783C8CDF97643B4D2B4EC6E5B8F21')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"+seconds+"<?php echo ((is_array($_tmp='0C1FEC657F7865DED377B43250A015FC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>";
			}else{
				msg="<font color='#00CC00'>"+minutes+"<?php echo ((is_array($_tmp='DAF783C8CDF97643B4D2B4EC6E5B8F21')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"+seconds+"<?php echo ((is_array($_tmp='0C1FEC657F7865DED377B43250A015FC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>";
			}
			
			n.time--;
		}else{
			msg='<font color="#ff0000" style="font-size:14px"><?php echo ((is_array($_tmp='0E7B6CB416D3EC8D8ECBAC75EAC4F513')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>';
		}
		$("#show_time_"+n.div).html(msg);
	})
}
forTime=setInterval("doTime()",1000);
</script>
<fieldset>
	<legend><?php echo ((is_array($_tmp='13FE8D8C74CB94357D8E105686778DFE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
  <form action="" method="get" id='form1'>
	<input type="hidden" value="WorkOrder" name="c" />
    <input type="hidden" value="Index" name="a" />
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <td colspan="2" scope="row"><input type="button" class="btn-blue" id="display_user" value="<?php echo ((is_array($_tmp='4D775D4CD79E2ED6A2FC66FD1E7139C8')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
/<?php echo ((is_array($_tmp='C3BCEC9A5311F7CD5F42B087737B8AD7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /></td>
      </tr>
      <?php $_from = $this->_tpl_vars['orgList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr class="display_service">
        <th nowrap="nowrap" scope="row"><?php echo $this->_tpl_vars['list']['name']; ?>
</th>
        <td>
            <?php if ($this->_tpl_vars['list']['user']): ?>
            <?php echo smarty_function_html_checkboxes(array('options' => $this->_tpl_vars['list']['user'],'name' => 'service_ids','selected' => $this->_tpl_vars['selectedServiceIds']), $this);?>

            <?php else: ?>
            <font color="#666666"><?php echo ((is_array($_tmp='41AFB9772E880242FF7A37E95570630A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font>
            <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; endif; unset($_from); ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='498CA9F72EDDB38DEA714E436A0AC57B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
        	<?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['gameTypeList'],'name' => 'game_type_id','selected' => $this->_tpl_vars['selectedGameTypeId'],'class' => 'radio'), $this);?>

        </td>
      </tr>
      <?php if ($this->_tpl_vars['qType']): ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='78B3126BD9E9DF389AB21858C30489BB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['qType'],'selected' => $this->_tpl_vars['selectedQtype'],'name' => 'question_type','class' => 'radio'), $this);?>
</td>
      </tr>
      <?php endif; ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td class="operator"><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['operatorList'],'name' => 'operator_id','selected' => $this->_tpl_vars['selectedOperatorId'],'class' => 'radio'), $this);?>
</td>
      </tr>
      <?php if ($this->_tpl_vars['gameOptServerLists']): ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='C566CA59602C7C5C0D3FE5E18ADE447D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td class="operator">
        	<select name="server_id">
            	<option value="" > -请选择- </option>
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gameOptServerLists'],'selected' => $this->_tpl_vars['_GET']['server_id']), $this);?>

            </select>
        </td>
      </tr>
      <?php endif; ?>
      
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='85C6DB559826C1036D846B8F5F5F6143')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
            <?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['workOrderStatusArr'],'name' => 'order_status','selected' => $this->_tpl_vars['selectedOrderStatus'],'class' => 'radio'), $this);?>
	|	
            <?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['verify'],'name' => 'is_verify','selected' => $this->_tpl_vars['selectedIsVerify'],'class' => 'radio'), $this);?>

        </td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='50A6E2E1F9601D5472ED37B20A41839A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['vipLevel'],'name' => 'vip_level','selected' => $this->_tpl_vars['selectedVipLevel'],'class' => 'radio'), $this);?>
</td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='F380730B8D457992B0ECA7DC110898A0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['roomList'],'name' => 'room_id','selected' => $this->_tpl_vars['selectedRoomId'],'class' => 'radio'), $this);?>
</td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='F3E64523D27D6C7936F03B63C50743F3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['evArr'],'selected' => $this->_tpl_vars['selectedEv'],'name' => 'evaluation_status','class' => 'radio'), $this);?>
</td>
      </tr>
      <?php if ($this->_tpl_vars['badev_display']): ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='247D7692F6DD568F789C57C3BD8A2D0F')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['badEvArr'],'selected' => $this->_tpl_vars['selectedBadEv'],'name' => 'evaluation_desc','class' => 'radio'), $this);?>
</td>
      </tr>
      <?php endif; ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='CD649F76D42355E6AEF72227F9152FEB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
        	<input name="start_time" type="text" class="text" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedStartTime']; ?>
" size="25"/> - 
            <input name="end_time" type="text" class="text" onFocus="WdatePicker({startDate:'%y-%M-%d 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" value="<?php echo $this->_tpl_vars['selectedEndTime']; ?>
" size="25"/>
        </td>
      </tr>
      <tr>
      	<th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='32C65D8D7431E76029678EC7BB73A5AB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><input name="title" type="text" class="text" id="title" value="<?php echo $this->_tpl_vars['selectedTitle']; ?>
" size="60" /></td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='5344B448B0C7B24677BB2B39D8277C98')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
        ID <input type="text" class="text" name="Id" value="<?php echo $this->_tpl_vars['selectedId']; ?>
" /> 
        <?php echo ((is_array($_tmp='1FC581B4EBF9123FD6EF9EB06A9D460E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 <input type="text" class="text" name="user_nickname" id="user_nickname" value="<?php echo $this->_tpl_vars['selectedUserNickname']; ?>
" />
		<?php echo ((is_array($_tmp='A62EF326801450211051F4E56D37FA4E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
  <input type="text" class="text" name="user_account" id="user_account" value="<?php echo $this->_tpl_vars['selectedUserAccount']; ?>
" />
		<input type="submit" class="btn-blue" value="<?php echo ((is_array($_tmp='939D5345AD4345DBAABE14798F6AC0F1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"  /></td>
      </tr>
    </table>
    </form>
</fieldset>


<fieldset>

<legend><?php echo ((is_array($_tmp='4729DAECF82BF2CACFA6042F514D5161')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
<form action='<?php echo $this->_tpl_vars['statusurl']; ?>
' method="post" id='formdeal' name='formdeal'>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th nowrap="nowrap">Id</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='54259D6BA1709F0B64613CE78FA08D7E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap">[<?php echo ((is_array($_tmp='54259D6BA1709F0B64613CE78FA08D7E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
]<?php echo ((is_array($_tmp='32C65D8D7431E76029678EC7BB73A5AB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='CCC283935D96ABAA8E596AAE9E7460A7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap">Bug状态</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='BA08216F13DD1742157412386EEE1225')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='F82BEDDCD7005F7F0ECA4C96999A865B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='C566CA59602C7C5C0D3FE5E18ADE447D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='5BFE13275EDC2C552F7BD2F030EA0C78')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / VIP<?php echo ((is_array($_tmp='95E0D70D1809D5267C2419EDA58E78CA')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp='08D8CEE29B07022D38A163EF42F0DAD5')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='0502F4F4045C9B6883BFA1FF7D9DC1B0')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br /><?php echo ((is_array($_tmp='D5A57C1C4DC6F04018E0F73B658B35B2')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='57FE12BFC3E50489BC8CCD4FFBD45B46')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
 / <?php echo ((is_array($_tmp='416F5354782E109F7D69AA42311AEA14')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='4C8C9D4F5D2B6A3A36445D5AF7141003')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <th nowrap="nowrap"><?php echo ((is_array($_tmp='2B6BC0F293F5CA01B006206C2535CCBC')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><input type="checkbox" name='ids[]' value='<?php echo $this->_tpl_vars['list']['Id']; ?>
'><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td align="center" nowrap="nowrap">
        <?php if ($this->_tpl_vars['list']['is_vip'] == 1): ?>VIP<?php endif; ?>
        <font style="font-size:14px; font-family:'<?php echo ((is_array($_tmp='971F54E2A4D4F1678001C5817A748739')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
';"><?php echo $this->_tpl_vars['list']['word_status']; ?>
</font>
        
        </td>
        <td class="order_title" url="<?php echo $this->_tpl_vars['list']['url_dialog']; ?>
" dialogId="detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
" title="<?php echo $this->_tpl_vars['list']['title']; ?>
">
        	 <font style="font-size:14px; font-family:'<?php echo ((is_array($_tmp='971F54E2A4D4F1678001C5817A748739')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
';"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['title'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 20) : smarty_modifier_truncateutf8($_tmp, 20)); ?>
</font> 	[<?php echo $this->_tpl_vars['list']['word_ev']; ?>
] <font color="#999999"><?php echo $this->_tpl_vars['list']['word_ev_desc']; ?>
</font><br />
             <?php echo $this->_tpl_vars['list']['create_time']; ?>

             <?php if ($this->_tpl_vars['list']['time_out_true']): ?><font color="red" style="font-size:14px"><?php echo ((is_array($_tmp='0E7B6CB416D3EC8D8ECBAC75EAC4F513')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</font><?php else: ?><span id="show_time_<?php echo $this->_tpl_vars['list']['Id']; ?>
"></span><?php endif; ?>
        </td>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['word_verify_status'])) ? $this->_run_mod_handler('default', true, $_tmp, '无') : smarty_modifier_default($_tmp, '无')); ?>
</td>
        <td>
            <?php echo $this->_tpl_vars['list']['word_game_type']; ?>
 / <?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
 /	<?php echo $this->_tpl_vars['list']['word_game_server_id']; ?>
 / <?php echo $this->_tpl_vars['list']['word_room_id']; ?>
 / <font color="#666666">VIP:</font> <b><?php echo $this->_tpl_vars['list']['vip_level']; ?>
</b><br />
            <font color="#666666"><?php echo ((is_array($_tmp='7035C62FB00576FED9B3A1F2B7D48E6C')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：</font><?php echo $this->_tpl_vars['list']['user_account']; ?>
，<font color="#666666">UID：</font><?php echo $this->_tpl_vars['list']['game_user_id']; ?>
，<font color="#666666"><?php echo ((is_array($_tmp='3EAAA36CCA7996391D410481FCF63A80')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：</font><a href="javascript:void(0)" onclick="searchForm($(this))"><?php echo $this->_tpl_vars['list']['user_nickname']; ?>
</a>，<font color="#666666"><?php echo ((is_array($_tmp='E7867F285D4C647A8B67951234868761')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
：</font><?php echo $this->_tpl_vars['list']['money']; ?>

        </td>
        <td><?php echo $this->_tpl_vars['list']['word_source']; ?>
<br /><?php echo $this->_tpl_vars['list']['word_question_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['question_num']; ?>
 / <?php echo $this->_tpl_vars['list']['answer_num']; ?>
</td>
        <td><a href="<?php echo $this->_tpl_vars['list']['url_reply_detail']; ?>
" ><?php echo $this->_tpl_vars['list']['word_owner_user_id']; ?>
</a></td>
        <td>
            <a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
"><?php echo ((is_array($_tmp='8627BFD5E5462A1BE765DE05F0FB0D5E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</a>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="10"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
      <tr>
      	<td colspan="3" align='left'>
      	<input type="checkbox" name='allcheck' onclick="$('input[name=ids[]]').attr('checked',$(this).attr('checked'))">全选
      	<input type="radio" value='1' name='status' checked="checked"/>待处理&nbsp;
      	<input type="radio" value='2' name='status'/>处理中&nbsp;
      	<input type="radio" value='3' name='status'/>已答复&nbsp;
      	<input type="radio" value='4' name='status'/>玩家已删除&nbsp;
      	<input type="button" value='修改状态' class="btn-blue" onclick="if(confirm('确定修改?'))$('#formdeal').submit();">
      	</td>
      	<td align="right" colspan="7"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
    </table>
    </form>
</fieldset>