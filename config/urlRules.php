<?php

return [
    'mgmt/events/<id:\d+>/delete' => 'mgmt/events-mgmt/delete',
    'mgmt/events/<id:\d+>/update' => 'mgmt/events-mgmt/update',
    'mgmt/events/<id:\d+>'        => 'mgmt/events-mgmt/view',
    'mgmt/events/create'          => 'mgmt/events-mgmt/create',
    'mgmt/events'                 => 'mgmt/events-mgmt/index',

    'mgmt' => 'mgmt/management/index',

    'user/login'  => 'user/login',
    'user/logout' => 'user/logout',
    'user'        => 'user/index',

    'events/<id:\d+>' => 'site/event',

    '' => 'site/index',
];
