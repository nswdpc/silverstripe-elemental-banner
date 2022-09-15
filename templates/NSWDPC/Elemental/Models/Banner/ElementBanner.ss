<div class="banner" style="background-image: url({$Image.URL})">
    <h2>{$Title}</h2>
    {$HTML}
    <% with $BannerLink %>
        <p><a href="{$LinkURL}">{$Title}</a></p>
    <% end_with %>
</div>
