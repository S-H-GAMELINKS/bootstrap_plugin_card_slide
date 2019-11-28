<?php
/**
 * Plugin Name: Bootstrap Plugin Card Slide
 */

 require_once("phpQuery-onefile.php");

 function get_plugin_name($url) {
    $html = file_get_contents($url);
    $plugin_name = phpQuery::newDocument($html)->find(".plugin-title")->text();
    return $plugin_name;
 }

 function get_plugin_img($url) {
    $html = file_get_contents($url);
    $img_url = phpQuery::newDocument($html)->find(".plugin-icon")->text("src");

    return preg_replace("/plugin-icon/", "card-img-top", $img_url);;
 }

 function get_plugin_description($url) {
    $html = file_get_contents($url);
    $plugin_description = phpQuery::newDocument($html)->find("#tab-description")->find("p")->eq(0)->text();
    return substr($plugin_description, 30);
 }

 function set_plugin_card($values) {
    $plugins = [];

    foreach($values as $value) {
        $url = "https://ja.wordpress.org/plugins/$value/";

        $plugin_name = get_plugin_name($url);

        $img_url = get_plugin_img($url);

        $plugin_description = get_plugin_description($url);

        $str = "
            <div class=\"col-4\">
                <div class=\"card m-2\">
                    $img_url
                    <div class=\"card-body\">
                        <h5 class=\"card-title\">$plugin_name</h5>
                        <a href=\"$url\" class=\"btn btn-primary\">Check Plugin</a>
                    </div>
                </div>
            </div>
        ";


        $plugins[] = $str;
    }

    return $plugins;
 }

 function set_plugin_slide($plugins){

    $result = "
        <div id=\"carouselExampleControls\" class=\"carousel slide\" data-ride=\"carousel\">
            <div class=\"carousel-inner\">
                <div class=\"carousel-item active\">
                    <div class=\"row w-100\">                        
                            $plugins[0]
                            $plugins[1]
                            $plugins[2]
                    </div>
                </div>
    ";

    if(count($plugins) >= 4) {
        for($i = 3; $i < count($plugins) - 1;) {
            $first = $plugins[$i];
            $i++;
            $second = $plugins[$i];
            $i++;
            $third = $plugins[$i];
            $i++;

            $result .= "            
                <div class=\"carousel-item\">
                    <div class=\"row w-100\">
                            $first
                            $second
                            $thrid
                    </div>
                </div>
            ";
        }
    }

    $result .= "</div></div>";

    return $result;
 }

 function plugin_card_slide($atts) {

    $values = explode(" ", $atts["name"]);

    $plugins = set_plugin_card($values);

    $result = set_plugin_slide($plugins);

    return $result;
 }

 add_shortcode('plugin_card_slide', 'plugin_card_slide');
