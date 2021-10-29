<?php
return [
    'contacts' => [
        'controller' => 'ContactController',
        'methods' => [
            'index' => 'get',
            'store' => 'post',
            'update' => 'put',
            'destroy' => 'delete',
        ]
    ]
];