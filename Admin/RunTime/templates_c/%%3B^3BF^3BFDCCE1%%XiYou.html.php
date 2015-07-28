<?php /* Smarty version 2.6.26, created on 2013-04-07 10:36:45
         compiled from ActionGame_MasterTools/BackpackSearch/XiYou.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/BackpackSearch/XiYou.html', 32, false),)), $this); ?>
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

$(function(){
	$.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
	$("#cause").formValidator({onshow:"请输入申请理由",oncorrect:"申请理由正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入申请理由"},onerror:"申请理由不能为空"});
	
})
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
      	账号类型：
            <select name="userType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['userType'],'selected' => $this->_tpl_vars['_POST']['userType']), $this);?>

            </select>
	            账号:<input class="text" type="text" name="user" value="<?php echo $this->_tpl_vars['_POST']['user']; ?>
">
	    查询类型：<select name="backpackSearchType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['backpackSearchType'],'selected' => $this->_tpl_vars['_POST']['backpackSearchType']), $this);?>

            </select>
	            
	         <input class="btn-blue" type="submit" value="查询">
        </td>
      </tr>
      
    </table>
</form>
</fieldset>
<fieldset>
  <legend>
	背包查询 
  </legend>
<form action="<?php echo $this->_tpl_vars['URL_itemsDel']; ?>
" method="post" id ='form'>
	<input type="hidden" name="userType" value="<?php echo $this->_tpl_vars['_POST']['userType']; ?>
">
    <input type="hidden" name="user" value="<?php echo $this->_tpl_vars['_POST']['user']; ?>
">
    <table width="100%" border="0" cellpadding="3">
      <tr>
      	<th scope="col">物品名称</th>
      	<th scope="col">物品ID</th>
        <th scope="col">格子ID</th>
        <th scope="col">现有数量</th>
        <th scope="col">增加的数量</th>
      </tr>
        <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['sub']):
?>
    
              <tr>
                <td><?php echo $this->_tpl_vars['sub']['goodsName']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['goodsId']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['gid']; ?>
</td>
                <td><?php echo $this->_tpl_vars['sub']['num']; ?>
</td>
                <td>
                	- <input type="text" class="text" value="" id="item_<?php echo $this->_tpl_vars['key']; ?>
_<?php echo $this->_tpl_vars['k']; ?>
" name="itemNum[<?php echo $this->_tpl_vars['sub']['gid']; ?>
_<?php echo $this->_tpl_vars['sub']['goodsId']; ?>
]">                	
                    <input type="hidden" class="text" value="<?php echo $this->_tpl_vars['sub']['goodsName']; ?>
"  name="goodsName[<?php echo $this->_tpl_vars['sub']['gid']; ?>
_<?php echo $this->_tpl_vars['sub']['goodsId']; ?>
]">
                </td>
              </tr>
        <?php endforeach; endif; unset($_from); ?>
    
    </table>
    
    <div style="margin:10px;">扣除原因:</div>
    <div style="margin:10px;"><textarea name="cause" id="cause" style="width:500px; height:80px;"></textarea><div id="causeTip"></div></div>
    
    <div style="margin:10px;"><input type="submit" class="btn-blue" onclick="return submitCheck();" value="申请扣除" /></div>
</form>
</fieldset>


<?php endif; ?>