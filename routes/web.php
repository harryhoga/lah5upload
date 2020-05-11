<?php

use Encore\lah5upload\Http\Controllers\lah5uploadController;

Route::get('lah5upload', lah5uploadController::class . '@index');
Route::post('h5upload_info', lah5uploadController::class . '@info');
