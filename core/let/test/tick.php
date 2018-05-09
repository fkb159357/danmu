<?php
declare(ticks=1);

function tick_handler() {
    global $backtrace;
    $backtrace = debug_backtrace();
}
register_tick_function('tick_handler');



function shutdown() {
    global $backtrace;
    // do check if $backtrace contains a fatal error...
    var_dump($backtrace);
}
register_shutdown_function('shutdown');