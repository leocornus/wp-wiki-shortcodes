<?php

/**
 * shortcode to load a manual in a category.
 */
add_shortcode('wiki_image_album', 'wiki_image_album_func');
function wiki_image_album_func($atts) {

    $home_url = rtrim(network_site_url(), '/');
    $home_domain = explode('://', $home_url)[1];
    // options = 
    $options = shortcode_atts(
        array(
            'category' => 'Screenshot',
            'base_url' => 'dev.vault.leocorn.com',
            'height' => 400,
            'width' => '100%',
            'limit' => 10,
        ), $atts);

    extract($options);
    if($home_domain != $base_url) {
        $origin = "'origin' : '{$home_url}',";
    }

    $scripts = <<<SCRIPT
<div id="galleria-album"></div>
<script>
jQuery(document).ready(function($) {

    var localSite={'baseUrl':'{$base_url}',
                   {$origin}
                   'limit' : {$limit},
                   'affixOffsetTop' : 280,
                   'apiPath':'/wiki/api.php'};
    var client = new WikiStrap(localSite);
    client.getImagesInCategory('{$category}', 'galleria',
                               function(err, content){
         $('#galleria-album').html(content);
         Galleria.configure({
             height: ${height},
             width: '100%'
         });
         Galleria.run('.galleria');
    });
});
</script>
SCRIPT;

    return $scripts;
}
