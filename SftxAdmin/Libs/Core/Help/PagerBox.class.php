<?php
/**
 * 分页类
 * @author 朱磊
 * @example
 * $totalNum = 101; //总数
 * $page = isset ( $_GET ['page'] ) ? $_GET ['page'] : 1; //当前页数
 * $perPage = 10; //每页显示记录
 * $url = $_SERVER['PHP_SELF'];//
 * $length = 5;//每页显示步长
 * $class = 'pages';//分页器CSS样式
 * $pagerBox = new PagerBox.class ( );
 * echo $pagerBox->getPager ( $totalNum, $page, $url, $perPage, $length, $class, TRUE );
 *
 */
class Help_PagerBox {
	/**
	 * 分页器
	 * @author biuuu 2009-3-5
	 * @copyright www.biuuu.com
	 */
	const PRE = '上一页';
	const NEXT = '下一页';
	
	/**
	 * 获取分页
	 *
	 * @param int $page 当前页
	 * @param int $totalNum    总页数
	 * @param int $perPage     每页显示记录数
	 * @param string $url      页面跳转URL
	 * @param int $length      页面步长值
	 * @param string $class    分页器样式
	 * @param bool $iscss      是否需要内置CSS
	 * @return html
	 */
	public function getPager($totalNum, $page, $url, $perPage, $length = '5', $class = 'pages', $default = FALSE) {
		$pageNum = ceil ( $totalNum / $perPage );
		$currentNum = ($page) ? $page : 1;
		$html = '';
		$html .= ($default) ? $this->getDefaultCss () : '';
		$html .= '<div class="' . $class . '">';
		$html .= $this->getPreHTML ( $page, $url );
		$html .= $this->getPages ( $page, $url, $currentNum, $length, $pageNum );
		$html .= $this->getNextHTML ( $page, $url, $pageNum );
		$html .= '</div>';
		return $html;
	}
	
	/**
	 * 获取上一页HTML
	 *
	 * @param int $page
	 * @param string $url
	 * @return html
	 */
	private function getPreHTML($page, $url) {
		$html = '';
		if (($page - 1) == 0) {
			$html .= $this->getHTML ( self::PRE );
		} else {
			$html .= $this->getHTML ( self::PRE, TRUE, $this->getURL ( $url, ($page - 1) ) );
		}
		return $html;
	}
	
	/**
	 * 获取页HTML
	 *
	 * @param int $page
	 * @param string $url
	 * @param int $currentNum
	 * @param int $pageLength
	 * @param int $pageNum
	 * @return html
	 */
	private function getPages($page, $url, $currentNum, $length, $pageNum) {
		$html = '';
		$start = ceil ( $page / $length );
		$start = ($start - 1) * $length + 1;
		$end = $start + $length - 1;
		$end = ($end > $pageNum) ? $pageNum : $end;
		
		for($i = $start; $i <= $end; $i ++) {
			if ($currentNum == $i) {
				$html .= $this->getHTML ( $i );
				continue;
			}
			$html .= $this->getHTML ( $i, TRUE, $this->getURL ( $url, $i ) );
		}
		return $html;
	}
	
	/**
	 * 获取下一页HTML
	 *
	 * @param int $page
	 * @param string $url
	 * @param int $pageNum
	 * @return html
	 */
	private function getNextHTML($page, $url, $pageNum) {
		$html = '';
		if (($page - $pageNum) == 0) {
			$html .= $this->getHTML ( self::NEXT );
		} else {
			$html .= $this->getHTML ( self::NEXT, TRUE, $this->getURL ( $url, ($page + 1) ) );
		}
		return $html;
	}
	
	/**
	 * 获取分页HTML
	 *
	 * @param string $text
	 * @param bool $isUrl
	 * @param string $url
	 * @param string $class
	 * @return html
	 */
	private function getHTML($text, $isUrl = FALSE, $url = '', $class = '') {
		if ($isUrl) {
			return $this->getAHtml ( $text, $url );
		}
		return $this->getSpanHtml ( $text, $class );
	}
	/**
	 * 创建URL
	 *
	 * @param string $url
	 * @param int $page
	 * @return url
	 */
	private function getURL($url, $page) {
		return $url . '?page=' . $page;
	}
	/**
	 * 创建span元素
	 *
	 * @param string $text
	 * @param string $class
	 * @return html
	 */
	private function getSpanHtml($text, $class = '') {
		$class = ($class) ? 'class=' . $class : '';
		return ' <span ' . $class . '>' . $text . '</span>';
	}
	/**
	 * 创建a元素
	 *
	 * @param string $text
	 * @param string $url
	 * @return html
	 */
	private function getAHtml($text, $url) {
		return ' <a href="' . $url . '">' . $text . '</a>';
	}
	/**
	 * 获取默认CSS样式
	 *
	 * @return css
	 */
	private function getDefaultCss() {
		$css = '<style type="text/css">';
		$css .= '.pages {margin:15px auto 0 auto;padding-right:40px;text-align:left;}
				 .pages a, .pages span {display:inline-block;*display:inline;zoom:1;padding:0px 6px;height:21px;line-height:21px;font-size:12px;font-weight:100;background:#F5F8FF;overflow:hidden;}
				 .pages a {border:1px solid #D8E0ED;}
				 .pages span {border:1px solid #dddddd;background:#FFFFFF;color:#999999;}
				 a{text-decoration:none;color:#666666;}
				 a:hover{text-decoration:underline;color:#0657b2;}';
		$css .= '</style>';
		return $css;
	}

}

?>