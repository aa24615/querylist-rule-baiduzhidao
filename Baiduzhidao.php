<?php
	/**
	 *  QueryList Rule Baiduzhidao
	 * Created by Malcolm.
	 * Date: 2021/4/25  15:59
	 */

	namespace QL\Ext;

	use QL\Contracts\PluginContract;
	use QL\QueryList;

	class Baiduzhidao implements PluginContract
	{
		const API = 'https://zhidao.baidu.com/search';
		const RULES = [
			'title' => [ '.dt>a' , 'text' ] ,
			'link'  => [ '.dt>a' , 'href' ],
			'best_answer' => ['.answer','text','-.i-answer-text']
		];
		const RANGE = '.list>.dl';
		protected $ql;
		protected $keyword;
		protected $pageNumber = 10;
		protected $httpOpt = [
			'headers' => [
				'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36' ,
				'Accept-Encoding' => 'gzip, deflate, br' ,
				'Referer'         => 'https://www.baidu.com' ,
				'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' ,
				'Accept-Language' => 'en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7'
			]
		];

		public function __construct ( QueryList $ql , $pageNumber ) {
			$this->ql = $ql->rules( self::RULES )
			               ->range( self::RANGE );
			$this->pageNumber = $pageNumber;
		}

		public static function install ( QueryList $queryList , ...$opt ) {
			$name = $opt[0] ?? 'baiduzhidao';
			$queryList->bind( $name , function ( $pageNumber = 10 )
			{
				return new Baiduzhidao( $this , $pageNumber );
			} );
		}

		public function setHttpOpt ( array $httpOpt = [] ) {
			$this->httpOpt = $httpOpt;
			return $this;
		}

		public function search ( $keyword ) {
			$this->keyword = $keyword;
			return $this;
		}

		public function page ( $page = 1 , $realURL = false ) {
			return $this->query( $page )
			            ->query()
			            ->getData( function ( $item ) use ( $realURL )
			            {
				            if ( isset( $item['title'] ) && $item['title'] ) {
					            $encode = mb_detect_encoding( $item['title'] , array( "ASCII" , 'UTF-8' , "GB2312" , "GBK" , 'BIG5' ) );
					            $item['title'] = iconv( $encode , 'UTF-8' , $item['title'] );
				            }
				            $realURL && $item['link'] = $this->getRealURL( $item['link'] );
				            return $item;
			            } );
		}

		protected function query ( $page = 1 ) {
			$this->ql->get( self::API , [
				'word' => $this->keyword ,
				'rn'   => $this->pageNumber ,
				'ie'   => 'utf-8' ,
				'pn'   => $this->pageNumber * ($page - 1)
			] , $this->httpOpt );
			return $this->ql;
		}

		/**
		 * 得到百度跳转的真正地址
		 * @param $url
		 * @return mixed
		 */
		protected function getRealURL ( $url ) {
			if ( empty( $url ) ) {
				return $url;
			}
			$header = get_headers( $url , 1 );
			if ( strpos( $header[0] , '301' ) || strpos( $header[0] , '302' ) ) {
				if ( is_array( $header['Location'] ) ) {
					//return $header['Location'][count($header['Location'])-1];
					return $header['Location'][0];
				} else {
					return $header['Location'];
				}
			} else {
				return $url;
			}
		}

		public function getCountPage () {
			$count = $this->getCount();
			$countPage = ceil( $count / $this->pageNumber );
			return $countPage;
		}

		public function getCount () {
			$count = 0;
			$text = $this->query( 1 )
			             ->find( '.nums' )
			             ->text();
			if ( preg_match( '/[\d,]+/' , $text , $arr ) ) {
				$count = str_replace( ',' , '' , $arr[0] );
			}
			return (int) $count;
		}

	}