<?php
    # Connect to memcache:
    global $memcache;
    $memcache = new Memcache;

    # Gets key / value pair into memcache ... called by mysql_query_cache()
    function getCache($key) {
        global $memcache;
        return ($memcache) ? $memcache->get($key) : false;
    }

?>