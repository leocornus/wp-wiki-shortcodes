<?php
/**
 * Plugin Name: Wiki Shortcodes 
 * Plugin URI: https://github.com/leocornus/wp-wiki-shortcodes
 * Veraion: 0.1
 * Author: Sean Chen <sean.chen@leocnro.com>
 * Description: Wiki shortcodes repository.
 */
$my_plugin_file = __FILE__;
// define some constants, make sure your constants name are unique.
define('WIKI_SHORTCODES_PLUGIN_FILE', $my_plugin_file);
define('WIKI_SHORTCODES_PLUGIN_PATH', WP_PLUGIN_DIR.'/'.basename(dirname($my_plugin_file)));
//require_once(WIKI_SHORTCODES_PLUGIN_PATH . '/simple-shortcodes.php');
require_once(WIKI_SHORTCODES_PLUGIN_PATH . '/image-shortcodes.php');
//require_once(OPSINC_PLUGIN_PATH . '/ajax-zoom-data.php');
// if the file is called directly, abort!
if(!defined('WPINC')) {
    die;
}

/**
 * register necessary resources for this plugin.
 */
add_action('init', 'register_wiki_shortcodes_resources');
function register_wiki_shortcodes_resources() {
}

/**
 * enqeue the script.
 */
add_action('wp_enqueue_scripts', 'wiki_shortcodes_resources');
function wiki_shortcodes_resources() {

    wp_enqueue_script('wprsc-galleria-classic-js');
    wp_enqueue_script('wprsc-wikistrap-js');
}

/**
 * category view short code.
 */
add_shortcode('wiki_category', 'wiki_category_func');
function wiki_category_func($atts) {

    $home_url = rtrim(network_site_url(), '/');
    list($protocal, $home_domain) = split('://', $home_url);
    // options = 
    $options = shortcode_atts(
        array(
            'category' => 'Testing',
            'base_url' => 'dev.vault.leocorn.com',
            'limit' => 100,
        ), $atts);

    extract($options);
    if($home_domain != $base_url) {
        $origin = "'origin' : '{$home_url}',";
    }

    $scripts = <<<SCRIPT
<script>
jQuery(document).ready(function($) {

    var localSite={'baseUrl':'{$base_url}',
                   {$origin}
                   'limit' : {$limit},
                   'affixOffsetTop' : 280,
                   'apiPath':'/wiki/api.php'};
    var client = new WikiStrap(localSite);
    client.getPagesInCategory('{$category}', function(err, content){
         $('#content-container').html(content);
         // set outwidth for the sidebar.
         client.syncSidenavWidth();
    });
});
</script>
SCRIPT;

    return $scripts;
}

/**
 * shortcode to load a manual in a category.
 */
add_shortcode('wiki_image_manual', 'wiki_image_manual_func');
function wiki_image_manual_func($atts) {

    $home_url = rtrim(network_site_url(), '/');
    list($protocal, $home_domain) = split('://', $home_url);
    // options = 
    $options = shortcode_atts(
        array(
            'category' => 'Screenshot',
            'base_url' => 'dev.vault.leocorn.com',
            'limit' => 10,
        ), $atts);

    extract($options);
    if($home_domain != $base_url) {
        $origin = "'origin' : '{$home_url}',";
    }

    $scripts = <<<SCRIPT
<script>
jQuery(document).ready(function($) {

    var localSite={'baseUrl':'{$base_url}',
                   {$origin}
                   'limit' : {$limit},
                   'affixOffsetTop' : 280,
                   'apiPath':'/wiki/api.php'};
    var client = new WikiStrap(localSite);
    client.getImagesInCategory('{$category}', 'list',
                               function(err, content){
         $('#content-container').html(content);
    });
});
</script>
SCRIPT;

    return $scripts;
}

/**
 * shortcode to load a wiki article.
 */
add_shortcode('wiki_article', 'wiki_article_func');
function wiki_article_func($atts) {

    $home_url = rtrim(network_site_url(), '/');
    list($protocal, $home_domain) = split('://', $home_url);
    // options = 
    $options = shortcode_atts(
        array(
            'title' => 'Main Page',
            'base_url' => 'dev.vault.leocorn.com',
            'limit' => 100,
        ), $atts);

    extract($options);
    if($home_domain != $base_url) {
        $origin = "'origin' : '{$home_url}',";
    }

    $scripts = <<<SCRIPT
<script>
jQuery(document).ready(function($) {

    var localSite={'baseUrl':'{$base_url}',
                   {$origin}
                   'limit' : {$limit},
                   'affixOffsetTop' : 280,
                   'apiPath':'/wiki/api.php'};
    var client = new WikiStrap(localSite);
    client.getArticle('{$title}', function(err, content){
         $('#content-container').html(content);
         // set outwidth for the sidebar.
         client.syncSidenavWidth();
    });
});
</script>
SCRIPT;

    return $scripts;
}
