<?php

namespace Encore\lah5upload;

use Encore\Admin\Extension;

class lah5upload extends Extension
{
    public $name = 'lah5upload';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../resources/assets';

    public $menu = [
        'title' => 'LAH5Upload',
        'path'  => 'lah5upload',
        'icon'  => 'fa-gears',
    ];
}
