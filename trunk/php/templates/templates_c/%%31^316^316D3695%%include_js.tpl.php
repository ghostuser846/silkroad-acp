<?php /* Smarty version 2.6.22, created on 2009-03-25 21:56:15
         compiled from include_js.tpl */ ?>
<?php $_from = $this->_tpl_vars['js_scripts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['js_script']):
?>
    <script type="text/javascript" src="../js/<?php echo $this->_tpl_vars['js_script']; ?>
"></script>
<?php endforeach; endif; unset($_from); ?>
