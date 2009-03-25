<?php /* Smarty version 2.6.22, created on 2009-03-25 07:57:57
         compiled from header.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
    <?php if (isset ( $this->_tpl_vars['app_page_name'] )): ?>
        <title><?php echo $this->_tpl_vars['app_name']; ?>
 - <?php echo $this->_tpl_vars['app_page_name']; ?>
</title>
    <?php else: ?>
        <title><?php echo $this->_tpl_vars['app_name']; ?>
</title>
    <?php endif; ?>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <?php if (isset ( $this->_tpl_vars['js_scripts'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include_js.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    <?php if (isset ( $this->_tpl_vars['css_files'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "include_css.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php endif; ?>
    </head>
