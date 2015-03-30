{*?template charset=UTF-8*}
{cache-block keys=array($cache_uri)}
    {default enable_help=true() enable_link=true() canonical_link=true()}
    
    {def $seo_metadata = seo_metadata($module_result.node_id)}

    {if $seo_metadata.title}
        <title>{$seo_metadata.title|wash}</title>
    {else}
        {if is_set($module_result.content_info.persistent_variable.site_title)}
            {set scope=root site_title=$module_result.content_info.persistent_variable.site_title}
        {else}
        {let name=Path
             path=$module_result.path
             reverse_path=array()}
          {if is_set($pagedata.path_array)}
            {set path=$pagedata.path_array}
          {elseif is_set($module_result.title_path)}
            {set path=$module_result.title_path}
          {/if}
          {section loop=$:path}
            {set reverse_path=$:reverse_path|array_prepend($:item)}
          {/section}
        
        {set-block scope=root variable=site_title}
        {section loop=$Path:reverse_path}{$:item.text|wash}{delimiter} / {/delimiter}{/section} | {$site.title|wash}
        {/set-block}
        
        {/let}
        {/if}
    
        <title>{$site_title}</title>
    {/if}
        {if and(is_set($#Header:extra_data),is_array($#Header:extra_data))}
          {section name=ExtraData loop=$#Header:extra_data}
          {$:item}
          {/section}
        {/if}
    
        {* check if we need a http-equiv refresh *}
        {if $site.redirect}
        <meta http-equiv="Refresh" content="{$site.redirect.timer}; URL={$site.redirect.location}" />
    
        {/if}
        {foreach $site.http_equiv as $key => $item}
            <meta name="{$key|wash}" content="{$item|wash}" />
    
        {/foreach}
        {foreach $site.meta as $key => $item}
            {if and($key|eq('description'), $seo_metadata.description)}
                <meta name="{$key|wash}" content="{$seo_metadata.description|wash}" />
            {elseif and($key|eq('keywords'), $seo_metadata.keywords)}
                {if ezini('GeneralSettings', 'UseMetaKeywords', 'owseo.ini')|ne('false')}
                    <meta name="{$key|wash}" content="{$seo_metadata.keywords|wash}" />
                {/if}
            {else}
                {if is_set( $module_result.content_info.persistent_variable[$key] )}
                    <meta name="{$key|wash}" content="{$module_result.content_info.persistent_variable[$key]|wash}" />
                {else}
                    <meta name="{$key|wash}" content="{$item|wash}" />
                {/if}
            {/if}
        {/foreach}
    
        <meta name="MSSmartTagsPreventParsing" content="TRUE" />
        <meta name="generator" content="eZ Publish" />
    
    {if $canonical_link}
        {include uri="design:canonical_link.tpl"}
    {/if}
    
    {if $enable_link}
        {include uri="design:link.tpl" enable_help=$enable_help enable_link=$enable_link}
    {/if}
    
    {/default}
{/cache-block}