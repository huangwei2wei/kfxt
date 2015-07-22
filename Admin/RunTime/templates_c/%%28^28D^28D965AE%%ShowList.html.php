<?php /* Smarty version 2.6.26, created on 2013-04-09 14:26:54
         compiled from Apply/ShowList.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'Apply/ShowList.html', 48, false),array('function', 'html_options', 'Apply/ShowList.html', 79, false),array('modifier', 'default', 'Apply/ShowList.html', 131, false),array('modifier', 'nl2br', 'Apply/ShowList.html', 141, false),)), $this); ?>
<style type="text/css">
ul{
	margin:0px;
	padding:0px;
}
ul li{
	margin:3px;
	list-style-type: none;
	display:inline;
}
.isSend_0{color:#F00; font-weight:bold}
.isSend_1{color:#060; font-weight:bold}
.isSend_2{color:#666; font-weight:bold}
.AutoNewline {
    line-height: 150%;
    overflow: hidden;
    width:1000px;
    word-wrap: break-word;
}
</style>
<link href="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/skin/WdatePicker.css" rel="stylesheet" type="text/css">
<script language="javascript" src="<?php echo $this->_tpl_vars['__JS__']; ?>
/Libs/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
$(function(){
	$("#allcheck").click(function() {
		var curCheck=$(this).attr("checked");
		$(":checkbox[name='Id[]']").attr("checked",curCheck);
	 });
	$("#search :radio").click(function(){$("#search").submit();});
})
function formSubmit(curBtn){
	if(confirm('确定要执行吗?')){
		url=curBtn.attr("url");
		$("#form").attr("action",url);
		$("#form").submit();
	}
}
</script>
<fieldset>
	<legend>搜索</legend>
  <form action="" id="search" method="get">
   	  <input type="hidden" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" name="c" />
      <input type="hidden" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" name="a" />
      <table width="100%" border="0" cellpadding="3">
      	<?php if ($this->_tpl_vars['_GET']['selectgame'] == false): ?>
        <tr>
          <th  nowrap="nowrap" scope="row">游戏</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['game_type'],'selected' => $this->_tpl_vars['_GET']['game_type'],'name' => 'game_type'), $this);?>
</td>
        </tr>
        <?php endif; ?>
        <tr>
          <th nowrap="nowrap" scope="row">运营商</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['operator_id'],'selected' => $this->_tpl_vars['_GET']['operator_id'],'name' => 'operator_id'), $this);?>
</td>
        </tr>
        <tr>
          <th nowrap="nowrap" scope="row">类型</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['type'],'selected' => $this->_tpl_vars['_GET']['type'],'name' => 'type'), $this);?>
</td>
        </tr>
        <tr>
          <th nowrap="nowrap" scope="row">状态</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['is_send'],'selected' => $this->_tpl_vars['_GET']['is_send'],'name' => 'is_send'), $this);?>
</td>
        </tr>
        <tr>
          <th nowrap="nowrap" rowspan="2" scope="row">搜索</th>
          <td>
          	申请时间:
            <input type="text" class="text" name="create_time_start" value="<?php echo $this->_tpl_vars['_GET']['create_time_start']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 00:00:00',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />
             - 
             <input type="text" class="text" name="create_time_end" value="<?php echo $this->_tpl_vars['_GET']['create_time_end']; ?>
" onFocus="WdatePicker({startDate:'%y-%M-%d 23:59:59',dateFmt:'yyyy-MM-dd HH:mm:ss',alwaysUseStartDate:true})" />
            申请IP:
            <input type="text" class="text" name="apply_ip" value="<?php echo $this->_tpl_vars['_GET']['apply_ip']; ?>
" />
          </td>
        </tr>

        <tr>
          <td>
          	申请的玩家类型
            <select name="player_type">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['player_type'],'selected' => $this->_tpl_vars['_GET']['player_type']), $this);?>

            </select>          	
            <input class="text" type="text" name="player_info" value="<?php echo $this->_tpl_vars['_GET']['player_info']; ?>
" />
          	申请人
            <select onchange="$('#search').submit()" name="apply_user_id">
            	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['_GET']['apply_user_id']), $this);?>

            </select>
            审核人
              <select onchange="$('#search').submit()" name="audit_user_id">
              	<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['_GET']['audit_user_id']), $this);?>

         	  </select>
          <input type="submit" class="btn-blue" value="提交" /> 
          
         </td>
        </tr>
      </table>
    </form>
</fieldset>
<fieldset>
	<legend>列表 [<a href="<?php echo $this->_tpl_vars['url']['URL_MyCsApply']; ?>
">我的申请</a>] [<a href="<?php echo $this->_tpl_vars['url']['URL_MyCsAudit']; ?>
">我已审核</a>]</legend>
    <table width="100%" border="0" cellpadding="3">
    <form action="" method="post" id="form">
       <tr>
     	<td><input type="checkbox" onclick='$(":checkbox[name=Id[]]").attr("checked",$(this).attr("checked"));' />全选</td>
        <td colspan="11">
        	<!--input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['OperationFRG_AuditDel']; ?>
" value="删除未审核" /-->
        	<input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['accept']; ?>
" value="审核" />
            <input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['reject']; ?>
" value="拒绝" />
        </td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="col">Id</th>
        <th nowrap="nowrap" scope="col">返回标识</th>
        <th nowrap="nowrap" scope="col">申请类型</th>
        <th nowrap="nowrap" scope="col">游戏</th>
        <th nowrap="nowrap" scope="col">运营商</th>
        <th nowrap="nowrap" scope="col">服务器</th>        
        <th nowrap="nowrap" scope="col">申请人/IP</th>
        <th nowrap="nowrap" scope="col">申请时间</th>
        <th nowrap="nowrap" scope="col">审核人/IP</th>
        <th nowrap="nowrap" scope="col">审核时间</th>
        <th nowrap="nowrap" scope="col">状态</th>
        <th nowrap="nowrap" scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td nowrap="nowrap" align="center"><?php if ($this->_tpl_vars['list']['is_send'] == 0): ?><input type="checkbox" name="Id[]" value="<?php echo $this->_tpl_vars['list']['Id']; ?>
" /><?php endif; ?><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td nowrap="nowrap" align="center"><?php if ($this->_tpl_vars['list']['result_mark']): ?><?php echo $this->_tpl_vars['list']['result_mark']; ?>
<?php else: ?><font color="#666666">无</font><?php endif; ?></td>
        <td nowrap="nowrap" class="td_move" onclick="$('#detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
').toggle()"><?php echo $this->_tpl_vars['list']['type']; ?>
</td>
        <td nowrap="nowrap"><?php echo $this->_tpl_vars['list']['game_type']; ?>
</td>
        <td nowrap="nowrap"><?php echo $this->_tpl_vars['list']['operator_id']; ?>
</td>
        <td nowrap="nowrap"><?php echo $this->_tpl_vars['list']['server_id']; ?>
</td>
        <td nowrap="nowrap"><?php echo $this->_tpl_vars['list']['apply_user_id']; ?>
 <font color="#999999">/</font><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['apply_ip'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无</font>')); ?>
</td>
        <td nowrap="nowrap"><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['create_time'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无</font>')); ?>
</td>
        <td nowrap="nowrap"><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['audit_user_id'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无</font>')); ?>
 <font color="#999999">/</font><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['audit_ip'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无</font>')); ?>
</td>
        <td nowrap="nowrap"><?php echo ((is_array($_tmp=@$this->_tpl_vars['list']['send_time'])) ? $this->_run_mod_handler('default', true, $_tmp, '<font color="#999999">无</font>') : smarty_modifier_default($_tmp, '<font color="#999999">无</font>')); ?>
</td>
        <td nowrap="nowrap"><span class='isSend_<?php echo $this->_tpl_vars['list']['is_send']; ?>
' ><?php echo $this->_tpl_vars['list']['is_send_info']; ?>
</span></td>
        <td nowrap="nowrap"><?php if ($this->_tpl_vars['list']['url_view']): ?><a href="<?php echo $this->_tpl_vars['list']['url_view']; ?>
">察看详情</a><?php endif; ?></td>
      </tr>
      <tr>
      	<td colspan="12" style=" line-height:150%; padding-left:10px; padding-bottom:15px;<?php if ($this->_tpl_vars['list']['is_send'] != 0): ?>display:none;<?php endif; ?>" id="detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
">
			<div class="AutoNewline">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['list']['apply_info'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

            
            <?php if ($this->_tpl_vars['list']['is_send'] == 1): ?>
            <!--如果审核将显示审核的内容-->
            <hr size="1" />
            审核发送状态：<br />
				<?php echo $this->_tpl_vars['list']['send_result']; ?>

            <?php endif; ?>
            </div>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr>
        <td colspan="12"><?php echo $this->_tpl_vars['noData']; ?>
</td>
      </tr>
      <?php endif; unset($_from); ?>
      <tr>
     	<td><input type="checkbox" id="allcheck" />全选</td>
        <td colspan="11">
        	<!--input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['OperationFRG_AuditDel']; ?>
" value="删除未审核" /-->
        	<input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['accept']; ?>
" value="审核" />
            <input type="button" class="btn-blue" onclick="formSubmit($(this))" url="<?php echo $this->_tpl_vars['url']['reject']; ?>
" value="拒绝" />
        </td>
      </tr>
      </form>
      <tr>
        <td colspan="12" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td>
      </tr>
    </table>
    
</fieldset>