<?php
/**
 * 生成sitemap
 * @author 朱磊
 * @example 
 *  $sitemap = new Help_SiteMap ();
 *  //使用示例 $urls数组
 *  $urls = array ('http://www.biuuu.com/?p=394', 
 *  				'http://www.biuuu.com/?p=367', 
 *  				'http://www.biuuu.com/?p=364', 
 *  				'http://www.biuuu.com/?p=356', 
 *  				'http://www.biuuu.com/?p=350', 
 *  				'http://www.biuuu.com/?p=345', 
 *  				'http://www.biuuu.com/?p=331',
 *  				'http://www.biuuu.com/?p=323', 
 *  				'http://www.biuuu.com/?p=315', 
 *  				'http://www.biuuu.com/?p=311', 
 *  				'http://www.biuuu.com/?p=306', 
 *  				'http://www.biuuu.com/?p=297', 
 *  				'http://www.biuuu.com/?p=289', 
 *  				'http://www.biuuu.com/?p=285', 
 *  				'http://www.biuuu.com/?p=276', 
 *  				'http://www.biuuu.com/?p=267', 
 *  				'http://www.biuuu.com/?p=255' );
 *  //生成sitemaps
 *  $sitemap->createSitemap ( $urls, '0.8', 'Daily' );
 *
 */
class Help_SiteMap {
	/**
	 * sitemaps创建类 
	 * 优先权$priority 0-1  更新频率$changeFreq always hourly daily weekly monthly yearly never
	 * 其它重要说明：http://www.biuuu.com/?p=350
	 * @author biuuu 2009-1-24
	 * @version 1.0
	 **/
	private $filename = 'biuuu_com.xml'; //需要生成的xml文件名
	private $isXml = true; //是否生成.xml后缀文件
	private $isGZ = true; //是否生成.gz后缀文件
	

	//当前路径
	private function Path() {
		return dirname ( __FILE__ ) . '/';
	}
	
	/**
	 * 生成sitemaps
	 * array  $urls        需要生成sitemaps的URLs列表
	 * int    $changeFreq  更新频率
	 * string $priority    优先级
	 */
	public function createSitemap(array $urls, $changeFreq, $priority) {
		$xmlPath = $this->path () . $this->filename;
		$sitemapXML = $this->buildSitemaps ( $urls, $changeFreq, $priority );
		if ($this->isXml)
			$this->buildXMLFile ( $xmlPath, $sitemapXML );
		if ($this->isGZ)
			$this->buildGZFile ( $xmlPath, $sitemapXML );
		return;
	}
	
	//创建.xml文件
	private function buildXMLFile($xmlFile, $xml) {
		if (! file_exists ( $xmlFile )) {
			$fp = fopen ( $xmlFile, 'w' );
			if (! $fp) {
				echo 'create xml file fail';
				exit ();
			}
		}
		
		if (function_exists ( 'file_put_contents' )) {
			file_put_contents ( $xmlFile, $xml );
		}
		return;
	}
	
	/**
	 * 创建.gz文件
	 * 相关知识：
	 * http://www.biuuu.com/?p=403如何将xml、txt等文件类型压缩成.gz后缀文件
	 * http://www.biuuu.com/?p=394详解zlib函数库
	 **/
	private function buildGZFile($xmlFile, $xml) {
		if (function_exists ( 'gzopen' ) && function_exists ( 'gzwrite' ) && function_exists ( 'gzclose' )) {
			$gz = gzopen ( $xmlFile . '.gz', 'w' );
			gzwrite ( $gz, $xml );
			gzclose ( $gz );
		}
		return;
	}
	
	//创建sitemaps内容
	private function buildSitemaps($urls, $changeFreq, $priority) {
		$xml = '';
		$xml .= '<?xml version="1.0" encoding="UTF-8" ' . '?' . '>' . "\n";
		$xml .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		$xml .= $this->buildXMLs ( $urls, $changeFreq, $priority );
		$xml .= '</urlset>';
		return $xml;
	}
	
	//创建URLs列表
	private function buildXMLs($urls, $changeFreq, $priority) {
		if (! $urls)
			return '';
		$xml = '';
		foreach ( $urls as $url ) {
			$xml .= $this->buildXML ( $url, $changeFreq, $priority );
		}
		return $xml;
	}
	
	//创建单个URL
	private function buildXML($url, $changeFreq, $priority) {
		$xml = '';
		$xml .= "\t<url>\n";
		$xml .= "\t\t<loc>" . $this->EscapeXML ( $url ) . "</loc>\n";
		$xml .= "\t\t<lastmod>" . date ( 'Y-m-d\TH:i:s+00:00' ) . "</lastmod>\n";
		$xml .= "\t\t<changefreq>" . $changeFreq . "</changefreq>\n";
		$xml .= "\t\t<priority>" . $priority . "</priority>\n";
		$xml .= "\t</url>\n";
		return $xml;
	}
	
	//过滤字符
	private function EscapeXML($string) {
		return str_replace ( array ('&', '"', "'", '<', '>' ), array ('&amp;', '&quot;', '&apos;', '&lt;', '&gt;' ), $string );
	}

}
?>