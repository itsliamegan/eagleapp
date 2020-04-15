<?php

use Janus\Facades\Router;

//----------------------------------------------------------
// Routes
//----------------------------------------------------------

Router::get('/login', 'Auth\Create');
Router::post('/login', 'Auth\Store');
Router::post('/logout', 'Auth\Destroy');

Router::get('/', 'Index');

Router::get('/schedules/{date}', 'Schedules\Show');

Router::get('/import', 'Import\Index');
Router::post('/import', 'Import\Store');

Router::get('/admin', 'Admin\Index');
Router::post('/admin/schedules/normal', 'Admin\Schedules\StoreNormal');
