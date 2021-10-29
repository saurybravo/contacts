<?php
return [
    'contacts' => [
        'controller' => 'ContactController',
        'methods' => [
            'index' => 'get',
            'show' => 'get',
            'store' => 'post',
            'update' => 'put',
            'destroy' => 'delete',
        ]
    ]
];