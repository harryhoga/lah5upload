<?php

use Hoga\lah5upload\Http\Controllers\lah5uploadController;

Route::get('lah5upload', lah5uploadController::class . '@index');
Route::post('lah5upload_info', lah5uploadController::class . '@info');
