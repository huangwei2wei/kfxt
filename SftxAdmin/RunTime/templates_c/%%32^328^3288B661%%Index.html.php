<?php /* Smarty version 2.6.26, created on 2012-09-13 17:31:49
         compiled from Verify/Index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'Verify/Index.html', 13, false),array('modifier', 'truncateutf8', 'Verify/Index.html', 69, false),)), $this); ?>
<style type="text/css">
ul{	margin:0px;	padding:0px;list-style-type:none;}
ul li{margin:3px;}
</style>
<fieldset>
<legend>搜索列表</legend>
<form action="" method="get" id="formSearch">
	<input type="hidden" value="<?php echo $this->_tpl_vars['__CONTROL__']; ?>
" name="c" />
    <input type="hidden" value="<?php echo $this->_tpl_vars['__ACTION__']; ?>
" name="a" />
    <ul>
    	<li>
            转交部门：
              <select name="department_id"><option value="">部门选择</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['department'],'selected' => $this->_tpl_vars['selectedDepartment']), $this);?>
</select>
            游戏类型：<select name="game_type_id" id="game_type_id" onChange="changeType($(this).val())"><option value="">请选择游戏</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['gameType']), $this);?>
</select>
            运营商：<select name="operator_id" onchange="changeOperatorType($(this).val())" id="operator_id"><option value="">请选择运营商</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['operatorList']), $this);?>
</select>
            游戏服务器：<select name="game_server_id" id="game_server_id"><option value="">请选择游戏和运营商...</option></select>
            状态：<select name="status" id="status"><option value="">状态选择</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifyStatus']), $this);?>
</select>
            问题类型：<select name="type" id="type"><option value="">请选择游戏...</option></select>
            处理等级：<select name="level" id="level"><option value="">选择处理等级</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifyLevel']), $this);?>
</select>
        </li>
        <li>
            添加人：<select name="user_id"><option value="">选择添加人</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['users'],'selected' => $this->_tpl_vars['selectedUserId']), $this);?>
</select>  
            来源:<select name="source"><option value="">选择来源</option><?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifySource'],'selected' => $this->_tpl_vars['selectedSource']), $this);?>
</select>
            来源详细：<input type="text" class="text" name="source_detail" value="<?php echo $this->_tpl_vars['selectedSourceDetail']; ?>
" size="40" />
        </li>
        <li>
            标题：<input type="text" class="text" name="title" id="title" value="<?php echo $this->_tpl_vars['selectedTitle']; ?>
" size="40" />
            游戏玩家ID：<input type="text" class="text" id="game_user_id" value="<?php echo $this->_tpl_vars['selectedGameUserId']; ?>
" name="game_user_id"  />
            游戏玩家账号：<input type="text" class="text" id="game_user_account" name="game_user_account" value="<?php echo $this->_tpl_vars['selectedGameUserAccount']; ?>
" />
            游戏玩家呢称：<input type="text" class="text" id="game_user_nickname" name="game_user_nickname" value="<?php echo $this->_tpl_vars['selectedGameUserNickName']; ?>
" />
            <input type="submit" class="btn-blue" value="提交" />
        </li>
    </ul>
</form>
</fieldset>

<fieldset>
<legend>查证处理列表</legend>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="col">Id</th>
        <th scope="col">[处理等级]标题</th>
        <th scope="col">游戏类型 / 运营商 / 服务器 / 玩家Id/账号/呢称</th>
        <th scope="col">问题类型</th>
        <th scope="col">提交部门</th>
        <th scope="col">状态</th>
        <th scope="col">来源</th>
        <th scope="col">来源详细</th>
        <th scope="col">添加人</th>
        <th scope="col">提交时间</th>
        <th scope="col">操作</th>
      </tr>
      <?php $_from = $this->_tpl_vars['dataList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['list']):
?>
      <tr>
        <td><?php echo $this->_tpl_vars['list']['Id']; ?>
</td>
        <td class="list_title" onclick="displayDetail(<?php echo $this->_tpl_vars['list']['Id']; ?>
)"><b>[<?php echo $this->_tpl_vars['list']['word_level']; ?>
]</b>&nbsp;<?php echo $this->_tpl_vars['list']['title']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_game_type_id']; ?>
 / <?php echo $this->_tpl_vars['list']['word_operator_id']; ?>
 / <?php echo $this->_tpl_vars['list']['word_game_server_id']; ?>
 / 
        	<a href="javascript:void(0)" onclick="searchForm($(this),'game_user_id')"><?php echo $this->_tpl_vars['list']['game_user_id']; ?>
</a> / 
            <a href="javascript:void(0)" onclick="searchForm($(this),'game_user_account')"><?php echo $this->_tpl_vars['list']['game_user_account']; ?>
</a> / 
            <a href="javascript:void(0)" onclick="searchForm($(this),'game_user_nickname')"><?php echo $this->_tpl_vars['list']['game_user_nickname']; ?>
</a> </td>
        <td><?php echo $this->_tpl_vars['list']['word_type']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['word_department_id']; ?>
</td>
        <td>
          <select onchange="changeStatus($(this))" listId="<?php echo $this->_tpl_vars['list']['Id']; ?>
">
            <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['verifyStatus'],'selected' => $this->_tpl_vars['list']['status']), $this);?>

          </select>
        </td>
        <td><?php echo $this->_tpl_vars['list']['word_source']; ?>
</td>
        <td title="<?php echo $this->_tpl_vars['list']['source_detail']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['list']['source_detail'])) ? $this->_run_mod_handler('truncateutf8', true, $_tmp, 30) : smarty_modifier_truncateutf8($_tmp, 30)); ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['work_user_id']; ?>
</td>
        <td><?php echo $this->_tpl_vars['list']['create_time']; ?>
</td>
        <td>
        	<a href="<?php echo $this->_tpl_vars['list']['url_detail']; ?>
">回复</a>
            <?php if ($this->_tpl_vars['list']['work_order_id']): ?><a href="<?php echo $this->_tpl_vars['list']['url_order_detail']; ?>
">工单详情</a><?php endif; ?>
        </td>
      </tr>
      <tr>
      	<td colspan="11" style="display:none; background:#FFF" id="detail_<?php echo $this->_tpl_vars['list']['Id']; ?>
">
        	<div style="border:1px solid #CCC; padding:3px; margin:3px;">
            	详细描述：<br />
				<?php echo $this->_tpl_vars['list']['content']; ?>

            </div>
            
            <div>
                <?php $_from = $this->_tpl_vars['list']['log']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childList']):
?>
                <?php if ($this->_tpl_vars['childList']['type'] == 1): ?>
                <div style="border:1px dashed #999; padding:3px; margin:3px; background:#E9FAFE">
                    <div><b>状态修改：</b>&nbsp;[<?php echo $this->_tpl_vars['childList']['time']; ?>
]&nbsp;<b><?php echo $this->_tpl_vars['childList']['user']; ?>
</b></div>
                    <div><?php echo $this->_tpl_vars['childList']['description']; ?>
</div>
                </div>
                <?php else: ?>
                <div style="border:1px dashed #999; padding:3px; margin:3px; background:#EFEFF8">
                    <div><b>留言：</b>&nbsp;[<?php echo $this->_tpl_vars['childList']['time']; ?>
]&nbsp;<b><?php echo $this->_tpl_vars['childList']['user']; ?>
</b></div>
                    <div><?php echo $this->_tpl_vars['childList']['description']; ?>
</div>
                </div>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?>
            </div>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><th colspan="11"><?php echo $this->_tpl_vars['noData']; ?>
</th></tr>
      <?php endif; unset($_from); ?>
      <tr><td colspan="11" align="right"><?php echo $this->_tpl_vars['pageBox']; ?>
</td></tr>
    </table>
</fieldset>