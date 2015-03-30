{def 
     $metadata = seo_metadata($attribute.object.main_node_id)
}
{if concat($metadata.title, $metadata.description, $metadata.keywords)|ne('')}
<table class="text-left list">
    <col width="150" />
	<tr>
	    <th>{'Title'|i18n( 'design/standard/content/datatype' )}</th>
	    <td>{cond($attribute.content.title, $attribute.content.title|wash(), concat('<i>', $metadata.title|wash(), '</i>'))}</td>
	</tr>
	<tr>
	    <th>{'Description'|i18n( 'design/standard/content/datatype' )}</th>
	    <td>{cond($attribute.content.description, $attribute.content.description|wash(), concat('<i>', $metadata.description|wash(), '</i>'))}</td>
    </tr>
    {if ezini('GeneralSettings', 'UseMetaKeywords', 'owseo.ini')|ne('false')}
	<tr>
	    <th>{'Keywords'|i18n( 'design/standard/content/datatype' )}</th>
        <td>{cond($attribute.content.keywords, $attribute.content.keywords|wash(), concat('<i>', $metadata.keywords|wash(), '</i>'))}</td>
	</tr>
    {/if}
</table>
{/if}