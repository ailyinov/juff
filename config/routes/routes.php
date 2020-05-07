<?php

$routeDispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/[page/{page:\d+}]', ['\Juff\Controller\TaskController', 'tasksList']);
    $r->addRoute(['GET', 'POST'], '/edit/{task_id:\d+}', ['\Juff\Controller\TaskController', 'edit', ['edit']]);
    $r->addRoute(['GET', 'POST'], '/auth', ['\Juff\Controller\AuthController', 'logIn']);
    $r->addRoute('GET', '/log-out', ['\Juff\Controller\AuthController', 'logOut', ['logout']]);
    $r->addRoute(['GET', 'POST'], '/add', ['\Juff\Controller\TaskController', 'add']);
});
