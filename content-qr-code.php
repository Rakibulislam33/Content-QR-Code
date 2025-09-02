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

if( !defined('ABSPATH')) {
    exit;
}

class Content_QR_Code {

    public function __construct() {
        add_filter('the_content', array($this, 'append_qr_code'));
    }


    public function append_qr_code($content) {
        if (!is_singular()) {
            return $content;
        }

        $post_type = get_post_type();
        $allowed_post_types = apply_filters('qrcode_allowed_post_types', ['post', 'page']);
        if (!in_array($post_type, $allowed_post_types)) {
            return $content;
        }

        $url = get_permalink();

        // Filters
        $size = apply_filters('qrcode_size', 150);
        $margin = apply_filters('qrcode_margin', 2);
        $dark = apply_filters('qrcode_dark_color', '000000');
        $light = apply_filters('qrcode_light_color', 'ffffff');
        $label = apply_filters('qrcode_label', 'Scan Me');
        // $ecLevel = apply_filters('qrcode_ec_level', 'M');
        $logo_url = apply_filters('qrcode_logo_url', '');
        $position = apply_filters('qrcode_position', 'right'); // left or right

        $qr_url = 'https://quickchart.io/qr?';
        $params = [
            'text' => $url,
            'size' => $size,
            'margin' => $margin,
            'dark' => $dark,
            'light' => $light,
        ];

        if (!empty($logo_url)) {
            $params['centerImageUrl'] = $logo_url;
        }

        $qr_url .= http_build_query($params);

        $side = ($position === 'left') ? 'left: 20px;' : 'right: 20px;';

        $qr_html = '<div style="
            position: fixed;
            bottom: 20px;
            ' . esc_html($side) . '
            z-index: 9999;
            text-align: center;
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;">
                <div style="margin-bottom: 5px;">' . esc_html($label) . '</div>
                <img src="' . esc_url($qr_url) . '" width="' . intval($size) . '" height="' . intval($size) . '" alt="QR Code"/>
            </div>';

        return $content . $qr_html;

    }
}

new Content_QR_Code();