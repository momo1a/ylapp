<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 | 配置需要缓存的请求
 |--------------------------------------------------------------------------
 | 如:
 |
 | $config['cache.requests']['www'] = array(
 |		'mpg' => array('index' => array('cache_key'=>array('segments'=>array(2,3,4),'user_id'=>TRUE), 'cache_time'=>60))
 | );
 | 
 | 说明：
 | mpg:控制器mpg的index方法将使用缓存,同时使用URI的第2,3,4部分('segment'=>array(2,3,4))和用户ID('user_id'=>TRUE)作为缓存KEY的一部分.
 | 该方式使用与同一个方法根据参数的不同保存各自的缓存数据,比如：为每个分页创建不同的缓存
 | 
 | 注意：
 | 如果cache_key配置无法满足KEY的需求,可以通过控制器的$request_cache_keys参数设置你的KEY,
 | 或者重写YL_Controller::_request_cache_key方法定义你的KEY
 */
/*$config['cache.requests']['www'] = array(
	'home' => array(
		//省钱达人月排行数据缓存时间
		'month_rank' => array('cache_time'=>5),
		//省钱达人总排行数据缓存时间
		'total_rank' => array('cache_time'=>5)
	),
);*/