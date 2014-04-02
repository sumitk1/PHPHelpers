<?php
    # Connect to memcache:
    global $memcache;
    $memcache = new Memcache;

    # Gets key / value pair into memcache ... called by mysql_query_cache()
    function getCache($key) {
        global $memcache;
        return ($memcache) ? $memcache->get($key) : false;
    }

    # Puts key / value pair into memcache ... called by mysql_query_cache()
    function setCache($key,$object,$timeout = 60) {
        global $memcache;
        return ($memcache) ? $memcache->set($key,$object,MEMCACHE_COMPRESSED,$timeout) : false;
    }


?>