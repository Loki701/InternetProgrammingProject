<?php
$router->get('/api/users', 'UserController@getUsers');
$router->post('/api/users', 'UserController@createUser');
$router->post('/api/login', 'UserController@authenticateUser');
?>