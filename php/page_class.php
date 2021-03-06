<?php

/**

 * 此类实现分页

 * @author 	yangjian

 * @version	1.2

 * @link 	http://webssky.com

 */

class page {

	/**

	 * @param 	int		$page_size 

	 * num of every page (每页显示记录数)

	 */

	private $page_size;	

	/**

	 * @param 	int 	rows_num

	 * total page (总记录数)

	 */

	private $rows_num;

	/**

	 * @param 	int 	$page_num

	 * 总页数

	 */

	private $page_num;

	/**

	 * @param 	String	$page_url

	 * 页面url地址

	 */

	private	$page_url;

	/**

	 * @param	String	$limit

	 * 查询的记录的limit

	 */

	private $limit = ' limit 0,10';

	/**

	 * @param 	int		$page_now

	 * 当前页

	 */

	private	$page_now;

	

	/**

	 * @param	int		$list_page_num;

	 * 要列出的页面数

	 */

	private $list_page_num = 10;

	

	/**

	 * 构造函数

	 * @param 	int $pagesize	每页显示记录数

	 * @param	String	$query_str	用户传入的查询参数

	 */

	public function __construct($rows_num,$page_size=10,$page_now,$query_str='') {

		$this->rows_num = $rows_num;

		$this->page_size = $page_size;

		$this->page_num = ceil($this->rows_num/$this->page_size);

		$this->page_now = $this->format_page_now(intval($page_now));

		$this->page_url = $this->get_url($query_str);

		$this->limit = $this->get_limit();	

	}

	

	/**

	 * 设置列表页数

	 */

	public function set_list_page($list_page) {

		$list_page = intval($list_page);

		if ( $list_page > 1 ) {

			$this->list_page_num = $list_page;	

		}

	}

	

	/**

	 * get formated page_now

	 * 格式化当前页

	 */

	private function format_page_now($pageNow) {

		if ( $pageNow == '' || $pageNow == 0 ) {

			$pageNow = 1;

		} else if ( $pageNow > $this->page_num ) {

			$pageNow = $this->page_num;

		}

		return $pageNow;

	}

	

	private function get_limit() {

		return ' limit '.$this->page_size * ($this->page_now-1).','.$this->page_size.'';	

	}

	

	private function __get($args){

			if($args=="limit")

				return $this->limit;

			else

				return '';

		}

	

	/**

	 * 获取当前页面的url

	 */

	private function get_url($query_str) {

		$url = $_SERVER['REQUEST_URI'];

		if ( strpos($url,'?') === FALSE ) {

			$url .= '?'.$query_str;

		} else {

			$url .= '&'.$query_str;	

		}

		//解析url

		$parse_url = parse_url($url);

		//过滤url中的参数，然后重组url

		if ( isset($parse_url['query']) ) {

			//解析查询字符串

			parse_str($parse_url['query'],$query_arr);

			//print_r($query_arr);

			//删除page参数

			unset($query_arr['pageNow']);

			//http_bulid_query	生成 URL-encode 之后的请求字符串

			$url = $parse_url['path'].'?'.http_build_query($query_arr);

			

		}

		return $url;

	}

	

	/**

	 * 上一页

	 */

	private function page_prev() {

		if ( $this->page_now <= 1 ) {

			return '<li class="disabled"><a href="#">PREV</a></li>';

		} else {

			return '<li><a href="'.$this->page_url.'&pageNow='.($this->page_now-1).'">PREV</a></li>';

		}		

	}

	

	/**

	 * 首页

	 */

	private function page_first() {

		if ( $this->page_now > 1 ) {

			return '<li><a href="'.$this->page_url.'&pageNow=1">首页</a></li>';

		} else {

			return '<li class="disabled"><a href="#">首页</a></li>';

		}	

	}

	

	/**

	 * 页面列表

	 */

	private function page_list() {

		$link_page = '';

		//获取中间页

		$center_page = floor($this->list_page_num/2);

		//列表的前半部分

		for ( $i = $center_page; $i >= 1; $i-- ) {

			$pageNow = $this->page_now - $i;

			if ( $pageNow < 1 ) continue; 

			$link_page .= '<li><a href="'.$this->page_url.'&pageNow='.$pageNow.'">'.$pageNow.'</a></li>';

		}

		//当前页

		$link_page .= '<li class="active"><a href="#">'.$this->page_now.'</a></li>';

		//后半部分

		for ( $i=1; $i < $center_page; $i++ ) {

			$pageNow = $this->page_now+$i;

			if ( $pageNow > $this->page_num ) break;

			$link_page .= '<li><a href="'.$this->page_url.'&pageNow='.$pageNow.'">'.$pageNow.'</a></li>';

		}

		return $link_page;

	}

	

	/**

	 * 尾页

	 */

	private function last_page() {

		if ( $this->page_now < $this->page_num ) {

			return '<li><a href="'.$this->page_url.'&pageNow='.$this->page_num.'" class="page_text">尾页</a></li>';

		} else {

			return '<li class="disabled"><a href="#">尾页</a></li>';

		}		

	}

	

	/**

	 * 下一页

	 */

	private function page_next() {

		if ( $this->page_now >= $this->page_num ) {

			return '<li class="disabled"><a href="#">NEXT</a></li>';

		} else {

			return '<li><a href="'.$this->page_url.'&pageNow='.($this->page_now+1).'">NEXT</a></li>';

		}		

	}

	

	public function show_page_handle() {

		$html = '<ul class="pagination">';

		$html .= '<li class="disabled"><a href="#">总记录:'.$this->rows_num.'</a></li>';

        $html .= $this->page_prev();

		$html .= $this->page_first();

		$html .= $this->page_list();

		$html .= $this->page_next();

        $html .= $this->last_page();

		$html .= '</ul>';

		return $html;

	}

	

}

?>