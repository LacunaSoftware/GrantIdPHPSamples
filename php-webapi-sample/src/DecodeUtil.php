<?php

namespace Src;

class DecodeUtil {

    public static function base64UrlDecode($input) {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function fetchAndJsonEncode($url) {
        return json_decode(file_get_contents($url), true);
    }
}
