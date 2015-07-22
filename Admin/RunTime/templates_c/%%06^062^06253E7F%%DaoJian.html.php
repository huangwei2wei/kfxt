<?php /* Smarty version 2.6.26, created on 2013-04-09 17:04:48
         compiled from ActionGame_MasterTools/BackpackSearch/DaoJian.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/BackpackSearch/DaoJian.html', 48, false),array('modifier', 'default', 'ActionGame_MasterTools/BackpackSearch/DaoJian.html', 64, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script>

function submitCheck(){
	if($.trim($('[name=cause]').val())==''){
		alert('申请原因不能为空');
		return false;
	}
	/***var itemsList = $('input[name^=itemNum]');
	var len = itemsList.length;	
	var value = 0;
	for(var i=0;i<len;i++){
		value = parseInt(itemsList[i].value);
		if(itemsList[i].type=='text' && (!value || value<=0) ){
			$('#'+itemsList[i].id+'_type').remove();
			$('#'+itemsList[i].id+'_position').remove();
			$('#'+itemsList[i].id+'_id').remove();
			$('#'+itemsList[i].id+'_name').remove();
			$('#'+itemsList[i].id).remove();
		}
	}**/
	return true;
}

</script>
<fieldset>
  <legend>搜索</legend>
  
<form action=""  method="post">
	
    
    <input type="hidden" name="c" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" />
    <input type="hidden" name="a" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" />
    <input type="hidden" name="zp" value="<?php echo $this->_tpl_vars['__PACKAGE__']; ?>
" />
    <input type="hidden" name="__game_id" value="<?php echo $this->_tpl_vars['__GAMEID__']; ?>
" />
    <input type="hidden" name="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <table width="100%" border="0" cellpadding="3">
      <tr>
      <td>
      	玩家类型：
            <select name="userType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['userType'],'selected' => $this->_tpl_vars['_POST']['userType']), $this);?>

            </select>
	            玩家:<input class="text" type="text" name="user" value="<?php echo $this->_tpl_vars['_POST']['user']; ?>
">
	         <input class="btn-blue" type="submit" value="查询">
        </td>
      </tr>
      
    </table>
</form>


</fieldset>
<fieldset>
  <legend>
	背包查询
  		<?php if ($this->_tpl_vars['UserID']): ?>玩家ID:<?php echo $this->_tpl_vars['UserID']; ?>
&nbsp;&nbsp;玩家账号:<?php echo $this->_tpl_vars['UserName']; ?>
&nbsp;&nbsp;玩家昵称:<?php echo $this->_tpl_vars['NickName']; ?>
<?php endif; ?>
    <font color="#FF0000"><?php echo ((is_array($_tmp=@$this->_tpl_vars['playerSelect']['1'])) ? $this->_run_mod_handler('default', true, $_tmp, '') : smarty_modifier_default($_tmp, '')); ?>
</font>
  </legend>
<form action="<?php echo $this->_tpl_vars['URL_itemsDel']; ?>
" method="post">
	<input type="hidden" name="userType" value="<?php echo $this->_tpl_vars['_POST']['userType']; ?>
">
    <input type="hidden" name="user" value="<?php echo $this->_tpl_vars['_POST']['user']; ?>
">
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col"> 角色ID</th>
        <th scope="col">背包号</th>
        <th scope="col">物品名称</th>
        <th scope="col">位置</th>
        <th scope="col">物品ID</th>
        <th scope="col">类型</th>
        <th scope="col">现有数量</th>
        <th scope="col">扣除数量</th>
      </tr>
        <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['sub']):
?>
              <tr>
                <td><?php echo $this->_tpl_vars['sub']['playerId']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['backpack']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['name']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['positionId']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['propsId']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['goodsType']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['count']; ?>
</td>
                <td>
                	-<input type="text" class="text" value=""  name="itemNum[<?php echo $this->_tpl_vars['key']; ?>
]">
                    <input type="hidden" class="text" value="<?php echo $this->_tpl_vars['sub']['goodsId']; ?>
"  name="goodsId[<?php echo $this->_tpl_vars['key']; ?>
]">
                    <input type="hidden" class="text" value="<?php echo $this->_tpl_vars['sub']['goodsType']; ?>
"  name="goodsType[<?php echo $this->_tpl_vars['key']; ?>
]">
                    <input type="hidden" class="text" value="<?php echo $this->_tpl_vars['sub']['propsId']; ?>
"  name="propsId[<?php echo $this->_tpl_vars['key']; ?>
]">
                    <input type="hidden" class="text" value="<?php echo $this->_tpl_vars['sub']['name']; ?>
"  name="name[<?php echo $this->_tpl_vars['key']; ?>
]">
                </td>
              </tr>
        <?php endforeach; endif; unset($_from); ?>
    
    </table>
    
    <div style="margin:10px;">原因:</div>
    <div style="margin:10px;"><textarea name="cause" style="width:500px; height:80px;"></textarea></div>
    <div style="margin:10px;"><input type="submit" class="btn-blue" onclick="return submitCheck();" value="申请扣除" /></div>
</form>
</fieldset>


<?php endif; ?>