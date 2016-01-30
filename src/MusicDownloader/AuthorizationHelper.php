<?php
/**
 * Created by PhpStorm.
 * User: atepliashin
 * Date: 12/13/15
 * Time: 15:22
 */

namespace MusicDownloader;

class AuthorizationHelper extends ServiceAccessor {
    private $app_id;
    private $user_login;
    private $user_password;
    private $cookie_file = "cookie.txt";

    public function __construct($app_id, $user_login, $user_password) {
        parent::__construct();
        $this->app_id = $app_id;
        $this->user_login = $user_login;
        $this->user_password = $user_password;
        $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
        $this->curl->setOpt(CURLOPT_COOKIESESSION, true);
        $this->curl->setOpt(CURLOPT_COOKIEJAR, $this->cookie_file);
        $this->curl->setOpt(CURLOPT_COOKIEFILE, $this->cookie_file);
    }

    public function access_token() {
        $url = self::AUTH_URL_BASE . '/authorize?client_id=' . $this->app_id . '&scope=audio' .
            '&redirect_uri=' . self::AUTH_URL_BASE . '/blank.html&response_type=token&v=5.40';
        $result = $this->curl->get($url);
        if (!strpos($this->curl->responseHeaders['location'], 'act=grant_access')) {
            $doc = new \DOMDocument;
            @$doc->loadHTML($result);
            $xpath_doc = new \DOMXPath($doc);
            $next_url = $doc->getElementsByTagName("form")->item(0)->attributes->getNamedItem("action")->textContent;
            $form_data = [];
            $form_data['to'] = $xpath_doc->query('//*[@name="to"]')->item(0)->attributes->getNamedItem('value')->textContent;
            $form_data['ip_h'] = $xpath_doc->query('//*[@name="ip_h"]')->item(0)->attributes->getNamedItem('value')->textContent;
            $form_data['lg_h'] = $xpath_doc->query('//*[@name="lg_h"]')->item(0)->attributes->getNamedItem('value')->textContent;
            $form_data['_origin'] = $xpath_doc->query('//*[@name="_origin"]')->item(0)->attributes->getNamedItem('value')->textContent;
            $form_data['expire'] = 0;
            $form_data['email'] = $this->user_login;
            $form_data['pass'] = $this->user_password;
            $this->curl->setHeader('referer', $url);
            $this->curl->setHeader('origin', self::AUTH_URL_BASE);
            $this->curl->setHeader('accept', 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');
            $this->curl->setHeader('dnt', 1);
            $this->curl->setHeader('upgrade-insecure-requests', 1);
            $this->curl->setHeader('cache-control', 'max-age=0');
            $this->curl->setHeader('accept-encoding', 'gzip, deflate');
            $this->curl->setHeader('accept-language', 'en-US,en;q=0.8,ru;q=0.6');
            $this->curl->post($next_url, $form_data);
            $this->curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
        }
        $this->curl->get($this->curl->responseHeaders['location']);
        $final_url = $this->curl->responseHeaders['location'];
        $fragment = parse_url($final_url, PHP_URL_FRAGMENT);
        parse_str($fragment, $fragment_params);
        return $fragment_params['access_token'];
    }
}