<?php /* Smarty version 2.6.22, created on 2009-03-25 22:58:56
         compiled from body.tpl */ ?>
<body>
    <?php $_from = $this->_tpl_vars['bodies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['body']):
?>
        <?php if ($this->_tpl_vars['body'] == 'chains'): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "body_chains.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?>
