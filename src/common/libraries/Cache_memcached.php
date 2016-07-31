<?php
/**
 * memcached缓存类
 * CI自带的类对windows支持不好，但其最新的开发版已完善了这个问题，也就是此文件了。
 * 将来系统升级新版CI的话，建议删除此文件
 * 
 * 可选配置文件：memcached（若没有则默认为本机）
 * 
 * @author 温守力
 * @version 13.6.22
 * 
 */
class Cache_memcached {

    /**
     * Holds the memcached object
     *
     * @var object
     */
    protected $_memcached;

    /**
     * Memcached configuration
     *
     * @var array
     */
    protected $_memcache_conf = array('default' => array('default_host' => '127.0.0.1', 'default_port' => 11211, 'default_weight' => 1));

    /**
     * Setup memcached.
     *
     * @return  bool
     */
    function __construct() {
        // Try to load memcached server info from the config file.
        $CI = &get_instance();

        if ($CI -> config -> load('memcached', TRUE, TRUE)) {
            if (is_array($CI -> config -> config['memcached'])) {
                $this -> _memcache_conf = NULL;

                foreach ($CI->config->config['memcached'] as $name => $conf) {
                    $this -> _memcache_conf[$name] = $conf;
                }
            }
        }

        if (class_exists('Memcached', FALSE)) {
            $this -> _memcached = new Memcached();
        } elseif (class_exists('Memcache', FALSE)) {
            $this -> _memcached = new Memcache();
        } else {
            show_error('Failed to create object for Memcached Cache; extension not loaded?');
            return FALSE;
        }

        foreach ($this->_memcache_conf as $name => $cache_server) {
            if (!array_key_exists('hostname', $cache_server)) {
                $cache_server['hostname'] = $this -> _memcache_conf['default']['default_host'];
            }

            if (!array_key_exists('port', $cache_server)) {
                $cache_server['port'] = $this -> _memcache_conf['default']['default_port'];
            }

            if (!array_key_exists('weight', $cache_server)) {
                $cache_server['weight'] = $this -> _memcache_conf['default']['default_weight'];
            }

            if (get_class($this -> _memcached) === 'Memcache') {
                // Third parameter is persistance and defaults to TRUE.
                $this -> _memcached -> addServer($cache_server['hostname'], $cache_server['port'], TRUE, $cache_server['weight']);
            } else {
                $this -> _memcached -> addServer($cache_server['hostname'], $cache_server['port'], $cache_server['weight']);
            }
        }

        return TRUE;
    }

    /**
     * Fetch from cache
     *
     * @param   mixed   unique key id
     * @return  mixed   data on success/false on failure
     */
    public function get($id) {
        $data = $this -> _memcached -> get($id);

        return is_array($data) ? $data[0] : FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Save
     *
     * @param   string  unique identifier
     * @param   mixed   data being cached
     * @param   int time to live
     * @return  bool    true on success, false on failure
     */
    public function save($id, $data, $ttl = 60) {
        if (get_class($this -> _memcached) === 'Memcached') {
            return $this -> _memcached -> set($id, array($data, time(), $ttl), $ttl);
        } elseif (get_class($this -> _memcached) === 'Memcache') {
            return $this -> _memcached -> set($id, array($data, time(), $ttl), 0, $ttl);
        }

        return FALSE;
    }

    // ------------------------------------------------------------------------

    /**
     * Delete from Cache
     *
     * @param   mixed   key to be deleted.
     * @return  bool    true on success, false on failure
     */
    public function delete($id) {
        return $this -> _memcached -> delete($id);
    }

    // ------------------------------------------------------------------------

    /**
     * Clean the Cache
     *
     * @return  bool    false on failure/true on success
     */
    public function clean() {
        return $this -> _memcached -> flush();
    }

    // ------------------------------------------------------------------------

    /**
     * Cache Info
     *
     * @return  mixed   array on success, false on failure
     */
    public function cache_info() {
        return $this -> _memcached -> getStats();
    }

    // ------------------------------------------------------------------------

    /**
     * Get Cache Metadata
     *
     * @param   mixed   key to get cache metadata on
     * @return  mixed   FALSE on failure, array on success.
     */
    public function get_metadata($id) {
        $stored = $this -> _memcached -> get($id);

        if (count($stored) !== 3) {
            return FALSE;
        }

        list($data, $time, $ttl) = $stored;

        return array('expire' => $time + $ttl, 'mtime' => $time, 'data' => $data);
    }

}

/* End of file Cache_memcached.php */
/* Location: ./system/libraries/Cache/drivers/Cache_memcached.php */
