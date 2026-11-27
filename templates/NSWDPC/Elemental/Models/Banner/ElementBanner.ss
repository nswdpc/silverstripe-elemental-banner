<div class="banner" style="background-image: url('{$Image.URL}')">
    <h2>{$Title}</h2>
    {$HTML}
    <% with $DerivedBannerLink %>
        <p><a href="{$URL}" rel="noopener noreferrer"<% if $OpenInNew %> target="_blank"<% end_if %>>{$Title}</a></p>
    <% end_with %>
</div>
