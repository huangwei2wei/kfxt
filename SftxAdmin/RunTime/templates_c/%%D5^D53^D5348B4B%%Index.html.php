<?php /* Smarty version 2.6.26, created on 2012-09-13 17:32:40
         compiled from GameSerList/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'GameSerList/Index.html', 36, false),array('modifier', 'truncate', 'GameSerList/Index.html', 72, false),)), $this); ?>
<script language="javascript">
<?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
var server_<?php echo $this->_tpl_vars['list']['Id']; ?>
=1;
<?php endforeach; endif; unset($_from); ?>
function setServer(serverId,doaction,status){
	$.getJSON(
		url,
		{c:'GameSerList',a:'Server',doaction:doaction,server_id:serverId},
		function(data){
			if(data.status==1){
				if(doaction=='online'){
					$("#view_online_"+serverId).html("<font color='#00CC00'><b>"+data.data+"</b></font>");
				}else{
					$('#step'+status+'_'+serverId).show();
					alert(data.msg);
				}
			}else{
				if(doaction=='online'){
					$("#view_online_"+serverId).html("<font color='#FF0000'>服务器错误</font>");
				}else{
					alert("服务器错误");
				}
			}
		}
	);
}
</script>
<fieldset>
	<legend>搜索</legend>
  <form action="" method="get" id="search">
   	  <input type="hidden" name="c" value="GameSerList" />
      <input type="hidden" name="a" value="Server" />
      <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <th scope="row">游戏类型</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['gameTypeList'],'name' => 'game_type_id','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['selectedGameTypeId']), $this);?>
</td>
        </tr>
        <tr>
          <th scope="row">运营商</th>
          <td><?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['operatorList'],'name' => 'operator_id','onclick' => "$('#search').submit()",'selected' => $this->_tpl_vars['selectedOperatorId']), $this);?>
</td>
        </tr>
          <tr>
            <th scope="row">服务器名</th>
            <td><input type="text" class="text" name="server_name" value="<?php echo $this->_tpl_vars['selectedServerName']; ?>
" /> <input type="submit" class="btn-blue" value="提交" /></td>
          </tr>
      </table>
    </form>
</fieldset>
<fieldset>
  <legend>服务器列表</legend>
<a href="<?php echo $this->_tpl_vars['url']['GameSerList_CreateCache']; ?>
">生成缓存</a>
<a href="<?php echo $this->_tpl_vars['url']['GameSerList_Add']; ?>
">添加服务器列表</a>
<table width="100%" border="0" cellpadding="3">
  <tr>
    <th>Id</th>
    <th>所属游戏</th>
    <th>运营商</th>
    <th>游戏服务器名</th>
    <th>标识</th>
    <th>服务器地址</th>
    <th>登录游戏</th>
    <th>在线人数</th>
    <th>操作</th>
  </tr>
  <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
  <tr>
    <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_game_type']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['word_operator_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['server_name']; ?>
</td>
    <td><?php echo $this->_tpl_vars['list']['marking']; ?>
</td>
    <td><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['server_url'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 30) : smarty_modifier_truncate($_tmp, 30)); ?>
</td>
    <td>
    	<?php if ($this->_tpl_vars['list']['game_type_id'] == 2): ?>
        <form target="_blank" action="<?php echo $this->_tpl_vars['list']['server_url']; ?>
php/interface.php?m=User&c=Login&a=login&__hj_dt=HtmlTemplate" method="post">
            <input type="hidden" name="Time" id="Time" value="<?php echo time(); ?>
" />      
            <input type="hidden" name="GameId" id="GameId" value="1" />     
            <input type="hidden" name="ServerId" id="ServerId" value="1" />     
            <input type="hidden" name="domainid" id="domainid" value="1" />  
        	<input type="text" class="text" name="Uname" />
            <input type="submit" class="btn-blue" value="登录" />
        </form>
        <?php endif; ?>
    </td>
    <td id="view_online_<?php echo $this->_tpl_vars['list']['Id']; ?>
"><input type="button" class="btn-blue" onclick="setServer(<?php echo $this->_tpl_vars['list']['Id']; ?>
,'online')" value="显示在线人数" /></td>
    <td>
    	<input id="step1_<?php echo $this->_tpl_vars['list']['Id']; ?>
" type="button" class="btn-blue" onclick="if(confirm('确定要操作吗?'))setServer(<?php echo $this->_tpl_vars['list']['Id']; ?>
,'stopServer',2)" value="停服通知" />
        <input id="step2_<?php echo $this->_tpl_vars['list']['Id']; ?>
" style="display:none" type="button" class="btn-red" onclick="if(confirm('确定要操作吗?'))setServer(<?php echo $this->_tpl_vars['list']['Id']; ?>
,'downline',3)" value="强制下线" />
        <input id="step3_<?php echo $this->_tpl_vars['list']['Id']; ?>
" style="display:none" type="button" class="btn-green" onclick="if(confirm('确定要操作吗?'))setServer(<?php echo $this->_tpl_vars['list']['Id']; ?>
,'startServer')" value="启动服务" />
    	<a href="<?php echo $this->_tpl_vars['list']['url_edit']; ?>
">编辑</a>
        <a href="<?php echo $this->_tpl_vars['list']['url_del']; ?>
">删除</a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><th colspan="9"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
  <?php endif; unset($_from); ?>
  <tr><td colspan="9" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
</table>
</fieldset>