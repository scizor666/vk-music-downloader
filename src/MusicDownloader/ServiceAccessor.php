<?php
/**
 * Created by PhpStorm.
 * User: atepliashin
 * Date: 12/13/15
 * Time: 17:19
 */

namespace MusicDownloader;

use Curl\Curl;

abstract class ServiceAccessor {
    const API_URL_BASE = 'https://api.vk.com';
    const AUTH_URL_BASE = 'https://oauth.vk.com';

    protected $curl;

    public function __construct() {
        $this->curl = new Curl();
        $this->curl->setOpt(CURLOPT_VERBOSE, true);
    }
}