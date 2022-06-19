<?php

function getUnique($arr){

    for ($i = 0; $i < count($arr); $i++) {
        if ($arr[$i] == request()->getClientIp()) {
            $t = false;
            break;
        }
    }
}

?>
