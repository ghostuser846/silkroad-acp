<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
    {if isset($app_page_name)}
        <title>{$app_name} - {$app_page_name}</title>
    {else}
        <title>{$app_name}</title>
    {/if}
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    {if isset($js_scripts)}
        {include file="include_js.tpl"}
    {/if}
    {if isset($css_files)}
        {include file="include_css.tpl"}
    {/if}
    </head>

