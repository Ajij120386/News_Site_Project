<?php

function isCacheValid($file, $expiryTime){
    if(file_exists($file) && (time() - filemtime($file) < $expiryTime)){
        return true;
    }
    return false;
}


function getCache($key, $ttl = 300) {
    $file = "cache/".md5($key).".cache";
    if(file_exists($file) && (time() - filemtime($file) < $ttl)) {
        return unserialize(file_get_contents($file));
    }
    return false;
}


function setCache($key, $data) {
    $file = "cache/".md5($key).".cache";
    file_put_contents($file, serialize($data));
}

function clearCache() {
    $files = glob("cache/*");
    foreach($files as $file){
        if(is_file($file)) unlink($file);
    }
}
?>
