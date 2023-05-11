<?php

use Pkly\I18Next\I18n;
use Pkly\I18Next\Plugin\JsonLoader;
use Pkly\I18Next\Plugin\LanguageDetector;

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
} else {
    $langs = 'en';
}
I18n::get([
    'lng'                   =>  $langs,
    'fallbackLng'           =>  null
])->useModule(new JsonLoader([
    'json_resource_path'    => __DIR__ . '/../resources/lang/{{lng}}/{{ns}}.json'
]))->useModule(new LanguageDetector([
    'query'             =>  \Pkly\I18Next\Plugin\Detector\Query::class,
    'cookie'            =>  \Pkly\I18Next\Plugin\Detector\Cookie::class
], [
    'lookupQuery'       => 'i18n_lng',
    'lookupCookie'      => 'i18next'
]))->init();
if (isset($_COOKIE['i18next'])){
    I18n::get()->changeLanguage($_COOKIE['i18next']);
}