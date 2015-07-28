<?php /* Smarty version 2.6.26, created on 2013-04-11 10:54:39
         compiled from ActionGame_MasterTools/ItemCardApply/FHJZ.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'ActionGame_MasterTools/ItemCardApply/FHJZ.html', 106, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['display']): ?>
<fieldset>
	<legend>
    物品申请 
    [<a href="<?php echo $this->_tpl_vars['URL_itemUpdate']; ?>
">更新道具</a>]
    </legend>

<style>
	.itemInput{width:35px;height:16px;border: 1px solid red;}
</style>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
//创建道具数量填写表单
function makeInput(obj){
	var itemId = obj.attr('item_Id');
	var itemName = obj.attr('itemName');
	var str = '';
	if(obj.attr('checked')){
		str = '<input type="text" value="" class="itemInput" name="itemNum['+itemId+']" onblur="itemCollectAdd(\''+itemId+'\',\''+itemName+'\',$(this).val())" >';
		str +='<input type="hidden" name="itemName['+itemId+']" value="'+itemName+'">'; 
		$('#input_'+itemId).html(str);
		$('#input_'+itemId).children().focus();
	}else{
		$('#input_'+itemId).html('');
	}
}
//道具收集
function itemCollectAdd(itemId,itemName,num){
	if(itemId =='' || $.trim(num)=='')return false;
	var itemsCollectId = 'itemsCollect'+itemId;
	$('#'+itemsCollectId).remove();
	num = parseInt(num);
	if(num && num>0){
		var str = '<div id="'+itemsCollectId+'" >'+itemName+'('+num+') <a href="javascript:itemCollectDel('+"'"+itemId+"'"+')">删除</a></div>';
		$('#itemsCollect').append(str);
	}else{
		$('input[name=itemNum['+itemId+']]').val('');
	}
}
//在收集的道具中删除道具
function itemCollectDel(itemId){
	if(itemId =='')return false;		
	$('#checkboxItem'+itemId).attr('checked',false);
	$('#input_'+itemId).html('');
	$('#itemsCollect'+itemId).remove();
}

$(function(){
    $("#cardType").change(function(){
    	var val = $(this).attr('value');
    	if( val == '1'){
    		$("#showuser").show();
    	}else{
    		$("#showuser").hide();
    	}
    	
    	if(val == '2'){
    		$("#isShowCount").show();
    	}else{
    		$("#isShowCount").hide();
    	}
    });
    $("#isShowCount").hide();
})
$(function(){
	$.formValidator.initConfig({
		formid:"form",
		onerror:function(){return false;},
		onsuccess:function(){
			if($(":checkbox[name='server_ids[]']:checked").attr('value') == undefined){
				alert('请选择服务器！'); return false;
			}
			$('.returnTip').remove();	//去掉旧提示
			$(":checkbox[name='server_ids[]']:checked").each(function(i,n){
					var curLi=$("#server_"+n.value);
					$("#form").ajaxSubmit({
						dataType:'json',
						async : false,    // 设置同步
						data:{server_id:n.value},
						success:function(data){
							var fontColor=(data.status==1)?'#00cc00':'#ff0000';
							curLi.append("<font class='returnTip' color='"+fontColor+"'> "+data.info+"</font>");
							//alert(data.info);
						}
					});
			});
			return false;
		},
	});
	$("#cause").formValidator({onshow:"请输入申请理由",oncorrect:"申请理由正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入申请理由"},onerror:"申请理由不能为空"});
	$("#mailTitle").formValidator({onshow:"请输入标题",oncorrect:"正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入标题"},onerror:"标题不能为空"});
	$("#mailContent").formValidator({onshow:"请输入邮件内容 ",oncorrect:"正确"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"请输入邮件内容 "},onerror:"邮件内容 不能为空"});
})

</script>
<form action="" method="post" id ='form'>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th nowrap="nowrap" scope="row">类型</th>
    <td> 
		<select name="cardType" id="cardType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['cardType'],'selected' => $this->_tpl_vars['_POST']['cardType']), $this);?>

        </select>
    </td>
  </tr>
  <tr id="showuser">
    <th scope="row">多个玩家用','隔开</th>
    <td>用户类型：<select name="playerType">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['playerType'],'selected' => $this->_tpl_vars['_POST']['playerType']), $this);?>

            </select><br/>
            用户：<textarea style="width:400px; height:60px;" name="player" id = 'player'><?php echo $this->_tpl_vars['_POST']['player']; ?>
</textarea>
    </td>
  </tr>
  <tr>
    <th scope="row"> 发送邮件 </th>
    <td>
        <div class="pd">
        		邮件标题:
        		<input name="mailTitle" id="mailTitle" type="text" class="text" style="width:400px;" value="<?php echo $this->_tpl_vars['editData']['mailTitle']; ?>
" />
        		</textarea><div id="mailTitleTip"></div>
        </div>
        <div class="pd">
            <span style="vertical-align:top">邮件内容:</span>
            <textarea id="mailContent" name="mailContent" style="width:400px; height:60px;" ></textarea>
            </textarea><div id="mailContentTip"></div>
        </div>
    </td>
  </tr>

  <tr>
    <th scope="row">道具</th>
    <td>
        <table width="100%" border="0" cellpadding="3">
          <tr>
          <?php $_from = $this->_tpl_vars['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['typeId'] => $this->_tpl_vars['list']):
        $this->_foreach['foo']['iteration']++;
?>              
            <td><?php echo $this->_tpl_vars['list']['typeName']; ?>

                <div style="background:white;margin:6px;font-size:14px;border:1px dashed silver;padding:5px;width:250px;">
                    <div style="overflow:auto;height:200px; width:250px;text-align:left;">
                        <?php $_from = $this->_tpl_vars['list']['subList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['itemId'] => $this->_tpl_vars['itemName']):
?> 
                            <div style="font-size:13px; margin-top:4px;">
                                <?php echo $this->_tpl_vars['itemName']; ?>

                                <input type="checkbox" onclick="makeInput($(this));" id="checkboxItem<?php echo $this->_tpl_vars['itemId']; ?>
" item_Id="<?php echo $this->_tpl_vars['typeId']; ?>
_<?php echo $this->_tpl_vars['itemId']; ?>
" itemName="<?php echo $this->_tpl_vars['itemName']; ?>
">
                                <span id="input_<?php echo $this->_tpl_vars['typeId']; ?>
_<?php echo $this->_tpl_vars['itemId']; ?>
"></span>
                            </div>
                        <?php endforeach; endif; unset($_from); ?>
                    </div>
                </div>
            </td>
            <?php if (($this->_foreach['foo']['iteration']-1)%3 == 2): ?> 
                </tr><tr>
            <?php endif; ?>
          <?php endforeach; endif; unset($_from); ?> 

          </tr>
        </table>
    
    </td>
  </tr>
  
  <tr>
      <th>道具汇总</th>
      <td  valign="top">
        <div id="itemsCollect"></div>
      </td>
  </tr>
  <tr>
      <th scope="row">到期时间</th>
      <td><input type="text" class="text" name="invalidTime" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})"/></td>
  </tr>
  <tr id='isShowCount'>
      <th scope="row">数量</th>
      <td><input type="text" class="text" name="count" id="count"/></td>
  </tr>
  <tr>
      <th>申请原因<font color="red">*</font></th>
      <td  valign="top"><textarea name="cause" id="cause" style="width:400px; height:60px;"></textarea><div id="causeTip"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input class="btn-blue" type="submit"  value="提交申请" /></td>
  </tr>
</table>
</form>
</fieldset>
<?php endif; ?>