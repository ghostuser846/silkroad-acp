<?php /* Smarty version 2.6.22, created on 2009-03-25 22:58:56
         compiled from include_css.tpl */ ?>
<?php $_from = $this->_tpl_vars['css_files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['css_file']):
?>
    <link rel="stylesheet" type="text/css" href="../css/<?php echo $this->_tpl_vars['css_file']; ?>
">
<?php endforeach; endif; unset($_from); ?>
