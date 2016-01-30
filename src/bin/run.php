<?php

require __DIR__ . "/../../vendor/autoload.php";

use MusicDownloader\AuthorizationHelper;
use MusicDownloader\AudioGrabber;
use \Symfony\Component\Yaml\Yaml;


$yaml = new Yaml();
$props = $yaml->parse(file_get_contents(__DIR__ . "/../../app_properties.yaml"));
$app_id = $props['application_id'];
$user_id = $props['user_id'];
$user_login = $props['user_login'];
$user_password = $props['user_password'];
$target_directory = $props['target_directory'];

$auth_helper = new AuthorizationHelper($app_id, $user_login, $user_password);
$access_token = $auth_helper->access_token();

$audio_grabber = new AudioGrabber($access_token, $user_id);
$records = $audio_grabber->all_records();
$audio_grabber->save_records($records, $target_directory);