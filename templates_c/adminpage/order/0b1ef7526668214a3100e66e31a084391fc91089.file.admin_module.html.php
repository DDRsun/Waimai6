<?php /* Smarty version Smarty-3.1.10, created on 2014-09-26 15:27:26
         compiled from "E:\phpStudy\www\templates\adminpage\public\admin_module.html" */ ?>
<?php /*%%SmartyHeaderCode:110795425155e6841a6-32006111%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0b1ef7526668214a3100e66e31a084391fc91089' => 
    array (
      0 => 'E:\\phpStudy\\www\\templates\\adminpage\\public\\admin_module.html',
      1 => 1408790276,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '110795425155e6841a6-32006111',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'modelist' => 0,
    'items' => 0,
    'admininfo' => 0,
    'defaultmenu' => 0,
    'Taction' => 0,
    'moduleparent' => 0,
    'myid' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.10',
  'unifunc' => 'content_5425155e707094_39278302',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5425155e707094_39278302')) {function content_5425155e707094_39278302($_smarty_tpl) {?><?php if (!is_callable('smarty_function_load_data')) include 'E:\\phpStudy\\www\\lib\\Smarty\\libs\\plugins\\function.load_data.php';
?>   <?php echo smarty_function_load_data(array('assign'=>"modelist",'table'=>"module",'limit'=>"20",'orderby'=>"id asc",'where'=>"install=1 and parent_id=0"),$_smarty_tpl);?>
 
   <?php  $_smarty_tpl->tpl_vars['items'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['items']->_loop = false;
 $_smarty_tpl->tpl_vars['myid'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['modelist']->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['items']->key => $_smarty_tpl->tpl_vars['items']->value){
$_smarty_tpl->tpl_vars['items']->_loop = true;
 $_smarty_tpl->tpl_vars['myid']->value = $_smarty_tpl->tpl_vars['items']->key;
?>  
        <?php echo smarty_function_load_data(array('assign'=>"defaultmenu",'table'=>"menu",'type'=>"one",'where'=>"moduleid='".((string)$_smarty_tpl->tpl_vars['items']->value['id'])."' and `group` = '".((string)$_smarty_tpl->tpl_vars['admininfo']->value['group'])."'",'fileds'=>"*",'orderby'=>"id asc"),$_smarty_tpl);?>
 
         <?php if (!empty($_smarty_tpl->tpl_vars['defaultmenu']->value)){?>
	  	 	  <li <?php if ($_smarty_tpl->tpl_vars['Taction']->value==$_smarty_tpl->tpl_vars['items']->value['name']||$_smarty_tpl->tpl_vars['moduleparent']->value==$_smarty_tpl->tpl_vars['items']->value['id']){?>class="on" <?php }?>>
	  	 	  	<a href="<?php echo FUNC_function(array('type'=>'url','link'=>"/adminpage/".((string)$_smarty_tpl->tpl_vars['items']->value['name'])."/module/".((string)$_smarty_tpl->tpl_vars['defaultmenu']->value['name'])),$_smarty_tpl);?>
">
	  	 	  	    <div class="lib ck<?php echo $_smarty_tpl->tpl_vars['myid']->value+1;?>
"></div>
	  	 	  	    <div class="libtitle"><?php echo $_smarty_tpl->tpl_vars['items']->value['cnname'];?>
</div>
	  	 	  	 </a> 
	  	    </li>  
	  	   <?php }?>
  <?php } ?><?php }} ?>