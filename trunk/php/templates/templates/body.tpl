<body>
    {foreach from=$bodies item=body}
        {if $body == "chains"}
            <div id="body_keeper">
            {include file="body_chains.tpl"}
        {/if}
        {if $body == "main_menu"}
            {include file="body_main_menu.tpl"}
        {/if}
        {if $body == "empty_space_with_page_name"}
            {include file="page_name.tpl"}
        {/if}
    {/foreach}

