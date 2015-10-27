<?php
date_default_timezone_set('America/Recife');
$conf = new Ra_Configuration();
//ONLINE
// $conf->base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/';

//ONLINE
// $conf->base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/qi/';


//DEV
$conf->base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/slack/';
$conf->index_page = '';
return $conf;