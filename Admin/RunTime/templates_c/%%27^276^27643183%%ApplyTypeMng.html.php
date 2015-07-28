<?php /* Smarty version 2.6.26, created on 2013-04-11 10:55:34
         compiled from Apply/ApplyTypeMng.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'Apply/ApplyTypeMng.html', 7, false),array('function', 'html_options', 'Apply/ApplyTypeMng.html', 13, false),)), $this); ?>
<fieldset>
<legend>审核类型管理</legend>
<form action="" method="post">
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="row" width="200">ID</th>
        <td><?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['Id'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
      </tr>
      <tr>
        <th scope="row">所属游戏</th>
        <td>
            <select name="game_type">
                <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['game_type'],'selected' => $this->_tpl_vars['data']['game_type']), $this);?>

            </select>
        </td>
      </tr>
      <tr>
        <th scope="row">类型名</th>
        <td><input type="text" class="text" name="name" value="<?php echo $this->_tpl_vars['data']['name']; ?>
"/></td>
      </tr>
      <tr>
        <th scope="row">所属列表ID</th>
        <td><input type="text" class="text" name="list_type" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['data']['list_type'])) ? $this->_run_mod_handler('default', true, $_tmp, "") : smarty_modifier_default($_tmp, "")); ?>
"></td>
      </tr>
      <tr>
        <th scope="row">&nbsp;</th>
        <td><input type="submit" class="btn-blue" value="提交"></td>
      </tr>
    </table>
</form>
</fieldset>