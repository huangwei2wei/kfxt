<?php /* Smarty version 2.6.26, created on 2013-04-11 10:24:44
         compiled from ActionGame_MasterTools/SendMail/Default.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'lang', 'ActionGame_MasterTools/SendMail/Default.html', 8, false),array('modifier', 'default', 'ActionGame_MasterTools/SendMail/Default.html', 42, false),array('function', 'html_radios', 'ActionGame_MasterTools/SendMail/Default.html', 42, false),)), $this); ?>
<?php if ($this->_tpl_vars['tplServerSelect']): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['tplServerSelect'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php if ($this->_tpl_vars['display']): ?>

<fieldset>
	<legend><?php echo ((is_array($_tmp='F4F76AD4FCD0E14CD14F8AFBCC95FA6E')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</legend>
	<script language="javascript">
    $(function(){
        $.formValidator.initConfig({formid:"form",onerror:function(){return false;}});
        $("#server_id").formValidator({onshow:"<?php echo ((is_array($_tmp='C566CA59602C7C5C0D3FE5E18ADE447D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID<?php echo ((is_array($_tmp='D7D11654A707D7E7B661C90295E58B20')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
",oncorrect:"<?php echo ((is_array($_tmp='124D617A6875038C81E03DEC5E2501F4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID"}).regexValidator({regexp:"intege1",datatype:"enum",onerror:"<?php echo ((is_array($_tmp='BA2DD64CC4F6DDF27CB1526EFB449E2D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
        $("#cause").formValidator().inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"<?php echo ((is_array($_tmp='752F8926DB21639D9F8ECDC7BF1D7159')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"},onerror:"<?php echo ((is_array($_tmp='855F36FA7498C388CF6FF6A0DD6B3143')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
        $("#MsgTitle").formValidator({onshow:"<?php echo ((is_array($_tmp='EA63C4DC8F4020500FC0DBFBDB16D404')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
",oncorrect:"<?php echo ((is_array($_tmp='5D04FE17DEB779C0EE90DC533FCDD2C1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"<?php echo ((is_array($_tmp='EA63C4DC8F4020500FC0DBFBDB16D404')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"},onerror:"<?php echo ((is_array($_tmp='1DF7810C9F146426FCC2D187BB87D738')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
        $("#MsgContent").formValidator({onshow:"<?php echo ((is_array($_tmp='FA967BD016F6A501D1D651D4F42642A1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
",oncorrect:"<?php echo ((is_array($_tmp='51CD9E88214F5135626C0CFB95CDEFF4')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"<?php echo ((is_array($_tmp='FA967BD016F6A501D1D651D4F42642A1')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"},onerror:"<?php echo ((is_array($_tmp='48E93DB8F2E59D91DB5EE7B439AFD9CE')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
        $("#users").formValidator({onshow:"<?php echo ((is_array($_tmp='1952027BDDA8E0106DBADDE43CA86F4D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID,<?php echo ((is_array($_tmp='2F7919916A82FDCC3FECDDC266B9D57B')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
','<?php echo ((is_array($_tmp='F3BD3B485F83DD0327F19576EC0BC134')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
",oncorrect:"<?php echo ((is_array($_tmp='315F61935B8933FF105D5FA610D202C3')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"}).inputValidator({min:1,empty:{leftempty:false,rightempty:false,emptyerror:"<?php echo ((is_array($_tmp='1952027BDDA8E0106DBADDE43CA86F4D')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID"},onerror:"<?php echo ((is_array($_tmp='1FD02A90C38333BADC226309FEA6FECB')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
ID<?php echo ((is_array($_tmp='281BADA7259F6BCB836F62AA0B3E8F48')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
"});
    })
    </script>
    <form id="form" action="" method="post">
    <input type="hidden" name="server_id" id="server_id" value="<?php echo $this->_tpl_vars['selectedServerId']; ?>
"/>
    <table width="100%" border="0" cellpadding="3">
      <tr>
        <th scope="row"><?php echo ((is_array($_tmp='2088BDE458445E265704715AA8DACE5A')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td>
            <textarea name="cause" id="cause" style="width:400px; height:60px;"></textarea>
            <div id="causeTip"></div>
        </td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='A53DB51463C2D8EC4AE21144C4579A15')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><input name="title" id="MsgTitle" type="text" class="text" style="width:400px;"/><div id="MsgTitleTip"></div></td>
      </tr>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='B87B77561E776367E6756E11EA652217')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
</th>
        <td><textarea name="content" id="MsgContent" style="width:400px; height:80px;"></textarea><div id="MsgContentTip"></div></td>
      </tr>
      <?php if (! $this->_tpl_vars['_GET']['lock']): ?>
      <tr>
        <th nowrap="nowrap" scope="row"><?php echo ((is_array($_tmp='C67DADA4A1A6B2CD747F6777D64CC7E7')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
<br/>(<?php echo ((is_array($_tmp='D032C46935BDA9DE97AD416319EB02D2')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
&quot;,&quot;<?php echo ((is_array($_tmp='F3BD3B485F83DD0327F19576EC0BC134')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
)</th>
        <td>
        	<div>
            <?php echo smarty_function_html_radios(array('options' => $this->_tpl_vars['playerTypes'],'name' => 'userType','selected' => ((is_array($_tmp=@$this->_tpl_vars['userTypeSelect'])) ? $this->_run_mod_handler('default', true, $_tmp, 3) : smarty_modifier_default($_tmp, 3))), $this);?>

            </div>
        	<textarea name="users" id="users" style="width:400px; height:100px;"><?php echo $this->_tpl_vars['users']; ?>
</textarea>
        	<div id="usersTip"></div>
        </td>
      </tr>
      <?php endif; ?>
      <tr>
        <th colspan="2" scope="row"><input type="submit" class="btn-blue" name="sbm" value="<?php echo ((is_array($_tmp='1176C5B5B255C95320D0FA9AFA835824')) ? $this->_run_mod_handler('lang', true, $_tmp) : smarty_modifier_lang($_tmp)); ?>
" /></th>
      </tr>
    </table>
    </form>
    </fieldset>
<?php endif; ?>


