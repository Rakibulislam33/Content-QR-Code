<?php 

/*
Plugin Name: Content QR Code
description: Generate QR codes for your content.
Version: 1.0.0
Author: Rakibul Islam
Author URI: httpps://github.com/rakibuli33
License: GPLv2 or later
Text Domain: content-qr-code
*/ 

if(!defined('ABSPATH')){
    exit;
}

class Content_QR_Code{

    public function __construct(){
       add_filter('the_content', array($this, 'append_qr_code'));
    }


    public function append_qr_code($content){
        if(is_singular('post')){
            $url = get_permalink();
            $qr_code_url = 'https://quickchart.io/qr?text=' . urlencode($url);
            
            $qr_html = '<div class="content-qr-code" style="position:fixed; bottom:20px; right:20px; z-index:9999;">
                <img src="' . esc_url($qr_code_url) . '" alt="QR Code" style="width:100px; height:100px;">
                        </div>';
        }
        return $content . $qr_html;
    }
}

new Content_QR_Code();