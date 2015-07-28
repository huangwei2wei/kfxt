<?php /* Smarty version 2.6.26, created on 2013-04-07 10:24:16
         compiled from ActionGame_OperatorTools/Patch/XiYou.html */ ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<fieldset>
	<legend>添加GM</legend>
	<script language="javascript">
    $(function(){
        $("#isAllUser").change(function(){
        	  if( $(this).attr('value') == '1'){
        		  $("#showuser").hide();
        	  }else{
        		  $("#showuser").show();
        	  }
        });
    })

	function onSubmit(){
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
    					}
    				});
    		});
    	}
    </script>
    <form id="form" action="" method="post">
    
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="row">参数：</th>
        <td>
            <input style="width:400px;" type="text" name="params" id="params" class="text"/>
        </td>
      </tr>
      <tr>
        <th colspan="2" scope="row"><input type="button" onclick="onSubmit();return false;" class="btn-blue" name="sbm" value="立即发送" /></th>
      </tr>
    </table>
    </form>
    </fieldset>