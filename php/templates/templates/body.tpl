<body>
    {foreach from=$bodies item=body}
        {if $body == "chains"}
            {include file="body_chains.tpl"}
        {/if}
    {/foreach}
</body>
</html>

