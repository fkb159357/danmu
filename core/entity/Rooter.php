<?php
class Rooter extends DIEntity {
    
    static function isRooter($passport){
        $rooter = supertable('Rooter')->find(compact('passport'));
        return !! $rooter;
    }
    
}