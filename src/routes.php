<?php

/**
 * Procesa el callback recibido desde ShipIt
 */
Route::any(config('shipit.callback_url', 'callback/shipit'), function () {

    return ShipIt::receiveCallback();

});