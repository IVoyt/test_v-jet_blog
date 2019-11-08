<?php

    return [
        //  key intended for uri
        //      curly braces intended for parameter
        //      e.g. 'post/{id}' means that {id} is a variable
        //  value intended for action path
        //      where pre-last element stands for Controller
        //      and last element stands for action/method name
        //      e.g. post/index has 2 elements
        //      element 1 (post) stands for controller named "PostController"
        //      element 2 (index) stands for method named "index" of PostController
        //
        //      if value has more than 2 elements, then elements before last two ones will mean path to controller->action
        '/' => 'post/index',
        'posts' => 'post/index',
        'posts?page={page}' => 'post/index',
        'posts?page={page}&limit={limit}' => 'post/index',
        'post/add-comment' => 'post/add-comment',
        'post/create' => 'post/create',
        'post/{id}' => 'post/view',
    ];