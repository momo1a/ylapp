<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 模板处理类
 * 这个只是在CI基础上对视图的简单封装,方便扩展.
 * 后期可以考虑加入模板/主题/布局等功能.
 *
 * @subpackage Libraries
 * @category   Libraries
 * @author     "韦明磊<nicolaslei@163.com>"
 * @version    1.0
 *
 */
class Template
{
	/**
	 * JS插入位置-head
	 * @var int
	 */
	const POS_HEAD = 1;

	/**
	 * JS插入位置-/body之前
	 * @var int
	 */
	const POS_END = 2;

	/**
	 * 要渲染的当前视图.
	 * @access protected
	 * @static
	 * @var string
	 */
	protected static $current_view;
	/**
	 * 视图文件夹
	 * @var string
	 */
	protected static $view_folder = '';

	/**
	 * 要渲染布局文件
	 * @access public
	 * @static
	 * @var string
	 */
	public static $layout = 'layout';

	/**
	 * 要带入视图的数据
	 * @access protected
	 * @static
	 * @var array
	 */
	protected static $data = array();

	/**
	 * CI超级对象实例.
	 * @access private
	 * @static
	 *
	 * @var object
	 */
	private static $ci;

	private static $asset_js = array();

	private static $asset_css = array();

	//--------------------------------------------------------------------

	/**
	 * 获取CI资源句柄,并初始化.
	 * @return void
	 */
	public function __construct($params = array())
	{
		self::$ci =& get_instance();
		self::init($params);
	}
	//end __construct()

	//--------------------------------------------------------------------

	/**
	 * 初始化.
	 * @access public
	 * @static
	 * @return void
	 */
	public static function init($params = array())
	{
		foreach ($params as $key=>$val)
		{
			if (isset(self::$$key)) self::$$key = $val;
		}
	}
	//end init()

	//--------------------------------------------------------------------

	/**
	 * 渲染模板
	 * 如果不设置当前视图,程序将自动将当前视图文件名定义为method名
	 * @access public
	 * @static
	 * @param  string $view 要使用的布局文件.用于覆盖当前默认布局文件,
	 * 						如果转入则需要手动设置current_view(如果需要的话),
	 * 						否则current_view为空.
	 * @param  bool $return 是否返回页面内容
	 * @return void
	 * 
	 * @update 增加$return参数返回html。杜嘉杰 2015-11-10 16:34:53
	 */
	public static function render($layout = NULL, $return = FALSE)
	{
		// 如果不设置新的布局文件，那就使用默认的布局文件
		if ($layout === NULL)
		{
			$layout = self::$layout;
			if (empty(self::$current_view))
			{
				self::$current_view = self::$ci->router->method;
			}
		}
		self::set(self::$ci->view_data);

		// 装载子视图css/js文件 解决layout和子模板都使用add_js/add_css加载文件时文件顺序颠倒的问题
		self::$asset_css[] = self::$asset_js[self::POS_HEAD][] = self::$asset_js[self::POS_END][] = array();

		self::$data['__VIEW_CONTENT__'] = self::content();
		// 装载layout视图css/js文件 解决layout和子模板都使用add_js/add_css加载文件时文件顺序颠倒的问题
		self::$asset_css[] = self::$asset_js[self::POS_HEAD][] = self::$asset_js[self::POS_END][] = array();
		// 加载视图
		if($return)
		{
			return self::$ci->load->view($layout, self::$data, TRUE);
		}
		else
		{
			self::$ci->load->view($layout, self::$data);
 		}
		
		
	}
	//end render()

	//--------------------------------------------------------------------

	/**
	 * 渲染子视图
	 * @author 宁天友
	 * @access public
	 * @version 2015-5-11 10:19:30
	 * @param string $view 要渲染的子视图文件.
	 * @param bool $return 是否返回html内容，默认false.
	 * @return void|string void|渲染之后的视图HTML.
	 */
	public static function sub_view_render($view, $return = FALSE)
	{
		if (empty($view) OR $view == '')
		{
			return '';
		}
		if (self::$view_folder)
		{
			$view = self::$view_folder.'/'.$view;
		}
		if($return)
		{
			return self::$ci->load->view($view, self::$ci->view_data, TRUE);
		}
		self::$ci->load->view($view, self::$ci->view_data);
	}
	//end sub_view_render()

	//--------------------------------------------------------------------

	/**
	 * 获取当前视图的内容.
	 * @access public
	 * @static
	 * @return string 渲染之后的视图.
	 */
	public static function content()
	{
		if (empty(self::$current_view) OR self::$layout == self::$current_view)
		{
			return '';
		}
		return self::$ci->load->view(self::$current_view, self::$data, TRUE);
	}
	//end content()

	//--------------------------------------------------------------------

	/**
	 * 设置当前要渲染视图的文件夹目录.
	 * @access public
	 * @static
	 * @param string $folder 视图文件夹.
	 * @return void
	 */
	public static function set_view_folder($folder)
	{
		self::$view_folder = $folder;
	}
	//end set_view_folder()

	//--------------------------------------------------------------------

	/**
	 * 设置当前要渲染的视图.
	 * @access public
	 * @static
	 * @param string $view 要渲染到内容中的视图文件.
	 * @return void
	 */
	public static function set_view($view)
	{
		if (self::$view_folder) {
			$view = self::$view_folder.'/'.$view;
		}

		self::$current_view = $view;
	}
	//end set_view()

	/**
	 * 设置页面标题
	 * @param sting $string
	 * @return void
	 */
	public static function set_title($string)
	{
		self::$data['__TITLE__'] = $string;
	}
	/**
	 * 设置页面是否使用宽屏
	 * @param bool $wide
	 */
	public static function set_wide($wide)
	{
		self::$data['__WIDE__'] = $wide;
	}
	//--------------------------------------------------------------------

	/**
	 * 设置页面关键字
	 * @param sting $string
	 * @return void
	 */
	public static function set_keywords($string)
	{
		self::$data['__KEYWORDS__'] = $string;
	}

	//--------------------------------------------------------------------

	/**
	 * 设置页面描述
	 * @param sting $string
	 * @return void
	 */
	public static function set_description($string)
	{
		self::$data['__DESCRIPTION__'] = $string;
	}

	public static function title()
	{
		return isset(self::$data['__TITLE__']) ? self::$data['__TITLE__'] : '';
	}
	public static function wide()
	{
		return isset(self::$data['__WIDE__']) ? self::$data['__WIDE__'] : false;
	}
	public static function keywords()
	{
		return isset(self::$data['__KEYWORDS__']) ? self::$data['__KEYWORDS__'] : '';
	}

	public static function description()
	{
		return isset(self::$data['__DESCRIPTION__']) ? self::$data['__DESCRIPTION__'] : '';
	}

	//--------------------------------------------------------------------

	/**
	 * 设置meta
	 * @param sting $name
	 * @param string $content
	 * @param bool $http_equiv
	 * @return void
	 */
	public static function meta($name, $content, $http_equiv=FALSE)
	{
		self::$data['_head_metas'][] = array('name'=>$name,'content'=>$content,'http_equiv'=>$http_equiv);
	}

	//--------------------------------------------------------------------

	/**
	 * 添加一个JS脚本文件
	 * @param mixd $js (array|string) JS链接
	 * @param mixd $attr (array|string) 对应的附加属性
	 * @return void
	 */
	public static function add_js($js, $position=self::POS_HEAD, $attr='')
	{
		$last_index = count(self::$asset_js[$position])-1;
		if(is_array($js)){
			$js_min = array();
			foreach ($js as $k => $item) {
				if(is_array($item)){
					self::$asset_js[$position][$last_index][] = $item;
				}elseif(is_array($attr) && isset($attr[$k])){
					self::$asset_js[$position][$last_index][] = array($item, $attr[$k]);
				}else{
					$js_min[] = $item;
				}
			}
			if( ! empty($js_min)){
				$min_js_uri = 'min/?f='.implode(",", $js_min);
				self::$asset_js[$position][$last_index][] = is_string($attr) && trim($attr) != '' ? array($min_js_uri, $attr) : $min_js_uri;
			}
		}else{
			self::$asset_js[$position][$last_index][] = is_string($attr) ? array($js, $attr) : $js;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * 添加一段JS脚本内容
	 * @author 宁天友
	 * @version 2015-11-11 17:37:46
	 * @param string|array $js_content
	 * @return void
	 */
	public static function add_js_content($js_content, $position = self::POS_HEAD)
	{
		$last_index = count(self::$asset_js[$position])-1;
		if(is_string($js_content)) $js_content = array($js_content);

		foreach ($js_content as $content) {
			self::$asset_js[$position][$last_index][] = array('content'=>$content, 'type'=>'text');
		}
	}

	//--------------------------------------------------------------------

	/**
	 * 添加一个CSS文件
	 * @param mixd $css (array|string) CSS样式链接
	 * @param mixd $attr (array|string) 对应的附加属性
	 * @return void
	 */
	public static function add_css($css, $attr=array())
	{
		$last_index = count(self::$asset_css)-1;
		if(is_array($css)){
			$css_min = array();
			foreach ($css as $k => $item) {
				if(is_array($item)){
					self::$asset_css[$last_index][] = $item;
				}elseif(is_array($attr) && isset($attr[$k])){
					self::$asset_css[$last_index][] = array($item, $attr[$k]);
				}else{
					$css_min[] = $item;
				}
			}
			if( ! empty($css_min)){
				$min_css_uri = 'min/?f='.implode(",", $css_min);
				self::$asset_css[$last_index][] = is_string($attr) && trim($attr) != '' ? array($min_css_uri, $attr) : $min_css_uri;
			}
		}else{
			self::$asset_css[$last_index][] = is_string($attr) ? array($css, $attr) : $css;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * 添加一段css脚本内容
	 * @author 宁天友
	 * @version 2015-11-11 17:37:46
	 * @param string|array $js_content
	 * @return void
	 */
	public static function add_css_content($css_content, $position = self::POS_HEAD)
	{
		$last_index = count(self::$asset_css)-1;
		if(is_string($css_content)) $css_content = array($css_content);

		foreach ($css_content as $content) {
			self::$asset_css[$last_index][] = array('content'=>$content, 'type'=>'text');
		}
	}

	//--------------------------------------------------------------------

	/**
	 * meta显示触发器
	 * @return void
	 */
	public static function trigger_meta()
	{
		$metas = '';
		foreach (self::$data['_head_metas'] as $meta)
		{
			$name = $meta['http_equiv'] ? 'http_equiv' : 'name';
			$metas .= '<meta '.$name.'="'.$meta['name'].'" content="'.$meta['content'].'">'.PHP_EOL;
		}
		echo $metas;
	}

	//--------------------------------------------------------------------

	/**
	 * JS文件显示触发器
	 * @param int $position
	 * @return void
	 */
	public static function trigger_js($position=self::POS_HEAD)
	{
		$tag_js = '';
		if (isset(self::$asset_js[$position]) AND is_array(self::$asset_js[$position]))
		{
			// 按键值倒序，解决layout和子模板都使用add_js加载文件时文件顺序颠倒的问题
			krsort(self::$asset_js[$position]);
			foreach (array_filter(self::$asset_js[$position]) as $topjs)
			{
				foreach ($topjs as $js)
				{
					if(is_array($js) && isset($js['type']) && $js['type'] == 'text')
					{
						if ( ! isset($js['content']) || trim($js['content']) == '')
						{
							// 没有内容,进入下一个循环
							continue;
						}
						$tag_js .= '<script type="text/javascript">'.PHP_EOL;
						$tag_js .= $js['content'].PHP_EOL;
						$tag_js .= '</script>'.PHP_EOL;
					}else{
						$attributes = '';
						if (is_array($js) AND count($js)>=2)
						{
							$file = $js[0];
							$attributes	= ' '.$js[1];
						}else {
							$file = $js;
						}
						$file = str_replace(config_item('domain_static'), '', $file);
						if(!preg_match("/^https?\:\/\//i", $file)){
							$file = config_item('domain_static').$file;
						}
						$tag_js .= '<script src="'.$file.(strpos($file, '?') !== FALSE ? '&' : '?').'v='.SYS_VERSION.SYS_BUILD.'" type="text/javascript"'.$attributes.'></script>'.PHP_EOL;
					}
				}
			}
		}
		echo $tag_js;
	}

	//--------------------------------------------------------------------

	/**
	 * CSS文件显示触发器
	 * @return void
	 */
	public static function trigger_css()
	{
		$tag_css = '';
		// 按键值倒序，解决layout和子模板都使用add_css加载文件时文件顺序颠倒的问题
		krsort(self::$asset_css);
		foreach (array_filter(self::$asset_css) as $topcss)
		{
			foreach ($topcss as $css)
			{
				if(is_array($css) && isset($css['type']) && $css['type'] == 'text')
				{
					if ( ! isset($css['content']) || trim($css['content']) == '')
					{
						// 没有内容,进入下一个循环
						continue;
					}
					$tag_css .= '<style>'.PHP_EOL;
					$tag_css .= $css['content'].PHP_EOL;
					$tag_css .= '</style>'.PHP_EOL;
				}else{
					$attributes = '';
					if (is_array($css) AND count($css)>=2)
					{
						$file = $css[0];
						$attributes	= ' '.$css[1];
					}else {
						$file = $css;
					}
					$file = config_item('domain_static').str_replace(config_item('domain_static'), '', $file);
					$tag_css .= '<link href="'.$file.(strpos($file, '?') !== FALSE ? '&' : '?').'v='.SYS_VERSION.SYS_BUILD.'"'.$attributes.' rel="stylesheet" />'.PHP_EOL;
				}
			}
		}
		echo $tag_css;
	}

	//--------------------------------------------------------------------

	/**
	 * 设置将在视图中呈现的数据.
	 * @access public
	 * @static
	 * @param string/array $var_name	变量名称,也可以是一个数组
	 * @param mixed  $value				要设置的值.
	 * @return void
	 */
	public static function set($var_name, $value = NULL)
	{
		if(is_array($var_name) && empty($value))
		{
			foreach($var_name as $key => $value)
			{
				self::$data[$key] = $value;
			}
		}
		else
		{
			self::$data[$var_name] = $value;
		}//end if

	}//end set()

	//--------------------------------------------------------------------

	/**
	 * 返回一个已设置的变量或者类的属性,如果没有则返回FALSE.
	 * @access public
	 * @static
	 * @param string $var_name 数据项.
	 * @return mixed 类的属性或视图的数据.
	 */
	public static function get($var_name)
	{
		// 如果是这个类的属性?
		if (isset(self::$$var_name))
		{
			return self::$$var_name;
		}
		else if (isset(self::$data[$var_name]))
		{
			return self::$data[$var_name];
		}

		return FALSE;
	}//end get()

	//--------------------------------------------------------------------

	public static function pager()
	{
		array(
				'full_tag_open'     => '<div class="pagination pagination-right"><ul>',
				'full_tag_close'    => '</ul></div>',
				'next_link'         => '&rarr;',
				'prev_link'         => '&larr;',
				'next_tag_open'     => '<li>',
				'next_tag_close'    => '</li>',
				'prev_tag_open'     => '<li>',
				'prev_tag_close'    => '</li>',
				'first_tag_open'    => '<li>',
				'first_tag_close'   => '</li>',
				'last_tag_open'     => '<li>',
				'last_tag_close'    => '</li>',
				'cur_tag_open'      => '<li class="active"><a href="#">',
				'cur_tag_close'     => '</a></li>',
				'num_tag_open'      => '<li>',
				'num_tag_close'     => '</li>',
		);
	}
	// end pager()
}
//end class

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */
