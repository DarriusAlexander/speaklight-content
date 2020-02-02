<?php
if (isset($_GET['logged_in']) && $_GET['logged_in'] == '1') {
    echo '<div class="notice notice-error tenweb_manager_notice" style="padding: 10px;">'
        . __("You already have 10Web account, please log in.", TENWEB_LANG)
        . '</div>';
}
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&display=swap" rel="stylesheet">

<div id="tenweb_cache_message" class="updated hidden is-dismissible"></div>
<div class="twebman twebman-page twebman-login" id="twebman-login">

    <div class="tenweb-cache-wrap">
        <div class="tenweb-cache-text">
            <div class="tenweb-wrap-text-title">10Web Cache</div>
            <div class="tenweb-wrap-text-desc">We use NGINX FastCGI Static Page Caching to cache everything from pages to feeds to 301-redirects on sub-domains.<br class="line-break" /> It helps your site to load super fast for your non-logged-in visitors.</div>
        </div>
        <div class="tenweb-cache-buttons">
            <a href="<?php echo $tenweb_hosting_tools_page ?>" target="_blank" class="tenweb-cache-manage-button">manage cache</a>
            <a onclick="tenwebCachePurge(); " class="tenweb-cache-clear-button">clear cache</a>
        </div>
    </div>
</div>