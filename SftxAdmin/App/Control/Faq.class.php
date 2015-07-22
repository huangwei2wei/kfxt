<?php
/**
 * FAQ模块
 * @author php-朱磊
 *
 */
class Control_Faq extends Control {

	/**
	 * Model_Sysconfig
	 * @var Model_Sysconfig
	 */
	private $_modelSysconfig;

	/**
	 * Model_PlayerFaq
	 * @var Model_PlayerFaq
	 */
	private $_modelPlayerFaq;

	/**
	 * Model_PlayerKindFaq
	 * @var Model_PlayerKindFaq
	 */
	private $_modelPlayerKindFaq;
	
	/**
	 * Model_GameFaq
	 * @var Model_GameFaq
	 */
	private $_modelGameFaq;
	
	/**
	 * Model_GameKindFaq
	 * @var Model_GameKindFaq
	 */
	private $_modelGameKindFaq;

	/**
	 * Model_ServiceFaq
	 * @var Model_ServiceFaq
	 */
	private $_modelServiceFaq;

	/**
	 * Model_ServiceKindFaq
	 * @var Model_ServiceKindFaq
	 */
	private $_modelServiceKindFaq;

	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;

	/**
	 * Model_PlayerFaqKeywords
	 * @var Model_PlayerFaqKeywords
	 */
	private $_modelPlayerFaqKeywords;
	
	/**
	 * faq分类
	 * @var array
	 */
	private $_faqKind;

	public function __construct() {
		$this->_createView ();
		$this->_createUrl ();
		$this->_utilMsg = $this->_getGlobalData ( 'Util_Msg', 'object' );
		$this->_modelPlayerFaq = $this->_getGlobalData ( 'Model_PlayerFaq', 'object' );
		$this->_modelPlayerKindFaq = $this->_getGlobalData ( 'Model_PlayerKindFaq', 'object' );
		$this->_modelServiceFaq = $this->_getGlobalData ( 'Model_ServiceFaq', 'object' );
		$this->_modelServiceKindFaq = $this->_getGlobalData ( 'Model_ServiceKindFaq', 'object' );
		$this->_modelSysconfig = $this->_getGlobalData ( 'Model_Sysconfig', 'object' );

		#------更改faq主分类------#
		$this->_faqKind = Model::getTtwoArrConvertOneArr ( $this->_modelSysconfig->getValueToCache ( 'game_type' ), 'Id', 'name' );
		#------更改faq主分类------#
	}

	private function _createUrl() {
		$this->_url ['Faq_PlayerAdd'] = Tools::url ( CONTROL, 'PlayerAdd' );
		$this->_url ['Faq_PlayerEdit'] = Tools::url ( CONTROL, 'PlayerEdit' );
		$this->_url ['Faq_PlayerKindAdd'] = Tools::url ( CONTROL, 'PlayerKindAdd' );
		$this->_url ['Faq_PlayerKindTree'] = Tools::url ( CONTROL, 'PlayerKindTree' );
		$this->_url ['Faq_PlayerCreateCache'] = Tools::url ( CONTROL, 'PlayerCreateCache' );
		$this->_url ['Faq_PlayerKindFaq'] = Tools::url ( CONTROL, 'PlayerKindFaq' );
		$this->_url ['Faq_PlayerKindIndex'] = Tools::url ( CONTROL, 'PlayerKindIndex' );
		$this->_url ['Faq_PlayerIndex_ratio']=Tools::url(CONTROL,'PlayerIndex',array('doaction'=>'ratio'));
		$this->_url ['Faq_PlayerIndex_ratioedit']=Tools::url(CONTROL,'PlayerIndex',array('doaction'=>'ratioedit'));

		$this->_url ['Faq_ServiceAdd'] = Tools::url ( CONTROL, 'ServiceAdd' );
		$this->_url ['Faq_ServiceKindAdd'] = Tools::url ( CONTROL, 'ServiceKindAdd' );
		$this->_url ['Faq_ServiceCreateCache'] = Tools::url ( CONTROL, 'ServiceCreateCache' );
		$this->_url['Faq_PlayerKindIndex_keywords']=Tools::url(CONTROL,'PlayerKindIndex',array('doaction'=>'keywords'));

		$this->_url['Faq_GameFaq_kindindex']=Tools::url(CONTROL, 'GameFaq',array('doaction'=>'kindindex'));
		$this->_url['Faq_GameFaq_ratio']=Tools::url(CONTROL, 'GameFaq',array('doaction'=>'ratio'));
		$this->_url['Faq_GameFaq_kindtree']=Tools::url(CONTROL, 'GameFaq',array('doaction'=>'kindtree'));
		$this->_url['Faq_GameFaq_ratioedit']=Tools::url(CONTROL, 'GameFaq',array('doaction'=>'ratioedit'));
		
		$this->_url ['uploadImgUrl'] = Tools::url ( 'Default', 'ImgUpload' );
		$this->_url ['updateGameFapImgUrl']=Tools::url('Default','ImgUpload',array('type'=>'GameFaq'));
		$this->_view->assign ( 'url', $this->_url );
	}
	#--------------------------------------客服中心FAQ--------------------------------------#
	/**
	 * ajax json
	 * 增加玩家faq分类
	 */
	public function actionPlayerKindAdd() {
		if (! $this->_isAjax())
			return false;
		if ($this->_modelPlayerKindFaq->add ( array ('game_type_id' => $_GET ['game_type_id'], 'name' => $_GET ['data'] ) )) {
			$this->_returnAjaxJson( array ('status' => 1 ) );
		}
	}

	/**
	 * 玩家FAQ分类编辑
	 */
	public function actionPlayerKindEdit() {
		if (! Tools::isAjax ())
			return false;
		if ($this->_modelPlayerKindFaq->update ( array ('name' => $_GET ['data'] ), "Id={$_GET['Id']}" )) {
			$this->_returnAjaxJson ( array ('status' => 1 ) );
		}
	}

	/**
	 * 删除玩家FAQ分类
	 */
	public function actionPlayerKindDel() {
		if ($this->_modelPlayerKindFaq->deleteById ( $_GET ['Id'] )) {
			$this->_modelPlayerFaq->deleteByKindId($_GET['Id']);	//删除所有这个分类的FAQ
			$this->_utilMsg->showMsg ( false );
		} else {
			$this->_utilMsg->showMsg ( '删除失败', - 2 );
		}
	}

	/**
	 * faq分类管理
	 */
	public function actionPlayerKindIndex() {
		$this->_modelPlayerFaqKeywords=$this->_getGlobalData('Model_PlayerFaqKeywords','object');
		switch ($_GET['doaction']){
			case 'keywords' :{//关键字
				$data=$this->_modelPlayerFaqKeywords->add($_POST);
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
				break;
			}
			default:{
				$dataList = array ();
				foreach ( $this->_faqKind as $key => $value ) {
					$childList = $this->_modelPlayerKindFaq->findByGameTypeId ( $key );
					if (count ( $childList )) {
						foreach ( $childList as &$childValue ) {
							$childValue ['url_edit'] = Tools::url ( CONTROL, 'PlayerKindEdit', array ('Id' => $childValue ['Id'] ) );
							$childValue ['url_del'] = Tools::url ( CONTROL, 'PlayerKindDel', array ('Id' => $childValue ['Id'] ) );
						}
					}
					$dataList [] = array (
									'game_type' => $value,
									'game_type_id' => $key,
									'url_add' => Tools::url ( CONTROL, 'PlayerKindAdd', array ('game_type_id' => $key ) ), 'childList' => $childList ? $childList : null );
				}
				$this->_view->assign ( 'js', $this->_view->get_curJs () );
				$this->_view->assign ( 'dataList', $dataList );
				$this->_view->assign('keywords',$this->_modelPlayerFaqKeywords->findAll());
				$this->_utilMsg->createNavBar();
				$this->_view->display ();
				break;
			}
		}
	}

	/**
	 * 玩家faq管理
	 */
	public function actionPlayerIndex() {
		switch ($_GET['doaction']){
			case 'ratioedit' :{//编辑点击率
				$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
				$data=$this->_modelPlayerFaq->ratioEdit($_POST);
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
				break;
			}
			case 'ratio' :{	//点击率
				$this->_modelPlayerFaq=$this->_getGlobalData('Model_PlayerFaq','object');
				$this->_loadCore('Help_SqlSearch');
				$helpSqlSearch=new Help_SqlSearch();
				$helpSqlSearch->set_tableName($this->_modelPlayerFaq->tName());
				if ($_GET['game_type_id']!=''){
					$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
					$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
				}
				$conditions=$helpSqlSearch->get_conditions();
				$helpSqlSearch->set_orderBy('ratio desc');
				$helpSqlSearch->setPageLimit($_GET['page']);
				$sql=$helpSqlSearch->createSql();
				$dataList=$this->_modelPlayerFaq->select($sql);
				if ($dataList){
					foreach ($dataList as &$list){
						$list['word_game_type_id']=$this->_faqKind[$list['game_type_id']];
						$list['question']=strip_tags($list['question']);
						$list['answer']=strip_tags($list['answer']);
					}
					$this->_view->assign('dataList',$dataList);
					$this->_loadCore('Help_Page');
					$helpPage=new Help_Page(array('total'=>$this->_modelPlayerFaq->findCount($conditions),'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$this->_view->assign('game_type',$this->_faqKind);
				$this->_view->assign ( 'gameTypeKind', $this->_faqKind );
				$this->_utilMsg->createNavBar();
				$this->_view->set_tpl(array('body'=>'Faq/PlayerFaqRatio.html'));
				$this->_view->display ();
				break;
			}
			default:{//显示主页
				$this->_view->assign ( 'gameTypeKind', $this->_faqKind );
				$this->_view->assign ( 'css', $this->_view->get_curCss () );
				$this->_view->assign ( 'js', $this->_view->get_curJs () );
				$this->_utilMsg->createNavBar();
				$this->_view->display ();
			}
		}

	}

	/**
	 * ajax json
	 * 根据游戏ID显示游戏分类
	 */
	public function actionPlayerKindTree() {
		if (!$this->_isAjax())
			return false;
		$value = $this->_faqKind [$_GET ['Id']];
		$key = $_GET ['Id'];
		if (! $value)
			return false;
		$jsonData = array ();
		$i = 0;
		$childKind = $this->_modelPlayerKindFaq->findByGameTypeId ( $key );
		$childKindNum=count($childKind);
		$jsonData [$i] = array ('text' => "$value($childKindNum)", 'expanded' => 1, 'classes' => 'important' );
		if ($childKindNum) {
			$jsonData [$i] ['children'] = array ();
			foreach ( $childKind as $childValue ) {
				$addUrl=Tools::url(CONTROL,'PlayerAdd',array('kind_id'=>$childValue['Id'],'game_type_id'=>$_GET['Id']));
				$jsonData [$i] ['children'] [] = array ('text' => "<a href='javascript:void(0)' onclick='displayFaq({$childValue['Id']})'>{$childValue['name']}</a>({$childValue['count']}) [<a href='{$addUrl}'>增加FAQ</a>]" );
			}
		}
		$this->_returnAjaxJson($jsonData);
	}

	/**
	 * ajax json
	 * 跟据分类id显示faq
	 */
	public function actionPlayerKindFaq() {
		if (! $_GET ['kindId'])
			return false;
		$dataList = $this->_modelPlayerFaq->findByKindId ( $_GET ['kindId'] );
		foreach ($dataList as &$value){
			$value['url_edit']=Tools::url(CONTROL,'PlayerEdit',array('Id'=>$value['Id']));
			$value['url_del']=Tools::url(CONTROL,'PlayerDel',array('Id'=>$value['Id'],'kind_id'=>$value['kind_id']));
			if (strpos($value['question'],'\\'))$value['question']=str_replace('\\','',$value['question']);
			if (strpos($value['answer'],'\\'))$value['answer']=str_replace('\\','',$value['answer']);
		}
		$this->_returnAjaxJson($dataList);
	}

	public function actionPlayerAdd() {
		if ($this->_isPost ()) {
			if ($this->_modelPlayerFaq->add ( $_POST )) {
				$this->_modelPlayerKindFaq->update(array('count'=>'count+1'),"Id={$_POST['kind_id']}");	//数量加1
				$this->_utilMsg->showMsg ( false );
			} else {
				$this->_utilMsg->showMsg ( '增加失败', - 2 );
			}
		} else {
			$gameTypes = $this->_faqKind;
			$kindList = $this->_modelPlayerKindFaq->findById ( $_GET ['kind_id'] );
			$this->_view->assign ( 'gameType', array ('Id' => $_GET ['game_type_id'], 'name' => $gameTypes [$_GET ['game_type_id']] ) );
			$this->_view->assign ( 'kindList', $kindList );
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	/**
	 * 玩家FAQ编辑
	 */
	public function actionPlayerEdit() {
		if ($this->_isPost ()) {
			$updateArr = array ('question' => $_POST ['question'], 'answer' => $_POST ['answer'] );
			if ($this->_modelPlayerFaq->update ( $updateArr, "Id={$_POST['Id']}" )) {
				$this->_utilMsg->showMsg ( false, 1, Tools::url ( CONTROL, 'PlayerIndex', array ('kind_id' => $_POST ['kind_id'], 'game_type_id' => $_POST ['game_type_id'] ) ) );
			} else {
				$this->_utilMsg->showMsg ( '增加失败', - 2 );
			}
		} else {
			$faqList = $this->_modelPlayerFaq->findById ( $_GET ['Id'] );
			$faqList ['question'] = str_replace ( "\\", '', $faqList ['question'] );
			$faqList ['answer'] = str_replace ( "\\", '', $faqList ['answer'] );
			$gameTypes = $this->_faqKind;
			$kindList = $this->_modelPlayerKindFaq->findById ( $_GET ['kind_id'] );
			$this->_view->assign ( 'gameType', array ('Id' => $_GET ['game_type_id'], 'name' => $gameTypes [$_GET ['game_type_id']] ) );
			$this->_view->assign ( 'kindList', $kindList );
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			$this->_view->assign ( 'id', $_GET ['Id'] );
			$this->_view->assign ( 'faqList', $faqList );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	/**
	 * FAQ删除
	 */
	public function actionPlayerDel() {
		if (!$this->_isAjax())return false;
		if ($this->_modelPlayerFaq->deleteById ( $_GET ['Id'] )) {
			$this->_modelPlayerKindFaq->update(array('count'=>'count-1'),"Id={$_GET['kind_id']}");	//数量减1
			$this->_returnAjaxJson(array('status'=>1));
		}
	}

	#--------------------------------------客服中心FAQ--------------------------------------#


	#--------------------------------------客服FAQ--------------------------------------#
	/**
	 * 客服faq管理
	 */
	public function actionServiceIndex() {
		$gameTypes = $this->_faqKind;
		$kindList = $this->_modelServiceKindFaq->findAll ();
		foreach ( $kindList as &$value ) {
			$value ['word_game_type_id'] = $gameTypes [$value ['game_type_id']];
			$value ['url_add'] = Tools::url ( CONTROL, 'ServiceAdd', array ('kind_id' => $value ['Id'], 'game_type_id' => $value ['game_type_id'] ) );
			$value ['url_view_faq'] = Tools::url ( CONTROL, ACTION, array ('kind_id' => $value ['Id'], 'game_type_id' => $value ['game_type_id'] ) );
		}
		if ($_GET ['kind_id'] && $_GET ['game_type_id']) { //如果Id有值那么将显示列表
			$faqList = $this->_modelPlayerFaq->findByKindId ( $_GET ['kind_id'] );
			foreach ( $faqList as &$value ) {
				$value ['question'] = str_replace ( "\\", '', $value ['question'] );
				$value ['answer'] = str_replace ( "\\", '', $value ['answer'] );
			}
			$faqGameType = $gameTypes [$_GET ['game_type_id']];
			$faqKind = $this->_modelServiceKindFaq->findById ( $_GET ['kind_id'] );
			$faqKind = $faqKind ['name'];
			$this->_view->assign ( 'faqList', $faqList );
			$this->_view->assign ( 'faqGameType', $faqGameType );
			$this->_view->assign ( 'faqKind', $faqKind );
			$this->_view->assign ( 'displayFaq', true );
		}
		$this->_view->assign ( 'faqList', $faqList );
		$this->_view->assign ( 'kindList', $kindList );
		$this->_utilMsg->createNavBar();
		$this->_view->display ();
	}

	public function actionServiceKindAdd() {
		if ($this->_isPost ()) {
			if ($this->_modelServiceKindFaq->add ( array ('game_type_id' => $_POST ['game_type_id'], 'name' => $_POST ['name'] ) )) {
				$this->_utilMsg->showMsg ( '增加分类成功', 1, Tools::url ( CONTROL, 'ServiceIndex' ) );
			} else {
				$this->_utilMsg->showMsg ( '增加分类失败', - 2 );
			}
		} else {
			$this->_view->assign ( 'gameTypes', $this->_faqKind );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	public function actionServiceAdd() {
		if ($this->_isPost ()) {
			if ($this->_modelServiceFaq->add ( $_POST )) {
				$this->_utilMsg->showMsg ( false );
			} else {
				$this->_utilMsg->showMsg ( '增加失败', - 2 );
			}
		} else {
			$gameTypes = $this->_faqKind;
			$kindList = $this->_modelServiceKindFaq->findById ( $_GET ['kind_id'] );
			$this->_view->assign ( 'gameType', array ('Id' => $_GET ['game_type_id'], 'name' => $gameTypes [$_GET ['game_type_id']] ) );
			$this->_view->assign ( 'kindList', $kindList );
			$this->_view->assign ( 'js', $this->_view->get_curJs () );
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	public function actionServiceEdit() {
		if ($this->_isPost ()) {

		} else {
			$this->_utilMsg->createNavBar();
			$this->_view->display ();
		}
	}

	public function actionServiceDel() {

	}

	#--------------------------------------客服FAQ--------------------------------------#
	
	
	#--------------------------------------游戏FAQ--------------------------------------#
	/**
	 * 游戏FAQ,用于显示在游戏里面的faq
	 */
	public function actionGameFaq(){
		switch ($_GET['doaction']){
			case 'kinddel' :{//删除
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				if ($this->_modelGameKindFaq->deleteById ( $_GET ['Id'] )) {
					$this->_modelGameFaq=$this->_getGlobalData('GameFaq','object');
					$this->_modelGameFaq->deleteByKindId($_GET['Id']);	//删除所有这个分类的FAQ
					$this->_utilMsg->showMsg ( false );
				} else {
					$this->_utilMsg->showMsg ( '删除失败', - 2 );
				}
				break;
			}
			case 'kindedit' :{//编辑
				if (! $this->_isAjax())
					return false;
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				if ($this->_modelGameKindFaq->update ( array ('name' => $_GET ['data'] ), "Id={$_GET['Id']}" )) {
					$this->_returnAjaxJson ( array ('status' => 1 ) );
				}
				break;
			}
			case 'kindadd' :{//增加分类
				if (! $this->_isAjax())
				return false;
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				if ($this->_modelGameKindFaq->add ( array ('game_type_id' => $_GET ['game_type_id'], 'name' => $_GET ['data'] ) )) {
					$this->_returnAjaxJson( array ('status' => 1 ) );
				}
				break;
			}
			case 'kindindex' :{//分类
				$dataList = array ();
				foreach ( $this->_faqKind as $key => $value ) {
					$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
					$childList = $this->_modelGameKindFaq->findByGameTypeId ( $key );
					if (count ( $childList )) {
						foreach ( $childList as &$childValue ) {
							$childValue ['url_edit'] = Tools::url ( CONTROL, ACTION, array ('Id' => $childValue ['Id'],'doaction'=>'kindedit' ) );
							$childValue ['url_del'] = Tools::url ( CONTROL, ACTION, array ('Id' => $childValue ['Id'],'doaction'=>'kinddel' ) );
						}
					}
					$dataList [] = array (
									'game_type' => $value,
									'game_type_id' => $key,
									'url_add' => Tools::url ( CONTROL, ACTION, array ('game_type_id' => $key,'doaction'=>'kindadd' ) ), 
									'childList' => $childList ? $childList : null );
				}
				$this->_view->assign ( 'dataList', $dataList );
				$this->_utilMsg->createNavBar();
				$this->_view->set_tpl(array('body'=>'Faq/GameFaqkindindex.html'));
				$this->_view->display ();
				break;
			}
			case 'add' :{//增加
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				if ($this->_isPost ()) {
					if ($this->_modelGameFaq->add ( $_POST )) {
						$this->_modelGameKindFaq->update(array('count'=>'count+1'),"Id={$_POST['kind_id']}");	//数量加1
						$this->_utilMsg->showMsg ( false );
					} else {
						$this->_utilMsg->showMsg ( '增加失败', - 2 );
					}
				} else {
					$gameTypes = $this->_faqKind;
					$kindList = $this->_modelGameKindFaq->findById ( $_GET ['kind_id'] );
					$this->_view->assign ( 'gameType', array ('Id' => $_GET ['game_type_id'], 'name' => $gameTypes [$_GET ['game_type_id']] ) );
					$this->_view->assign ( 'kindList', $kindList );
					$this->_view->set_tpl(array('body'=>'Faq/GameFaqadd.html'));
					$this->_utilMsg->createNavBar();
					$this->_view->display ();
				}
				break;
			}
			case 'edit' :{//编辑
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				if ($this->_isPost ()) {
					$updateArr = array ('question' => $_POST ['question'], 'answer' => $_POST ['answer'] );
					if ($this->_modelGameFaq->update ( $updateArr, "Id={$_POST['Id']}" )) {
						$this->_utilMsg->showMsg ( false, 1, Tools::url ( CONTROL, ACTION));
					} else {
						$this->_utilMsg->showMsg ( '增加失败', - 2 );
					}
				} else {
					$faqList = $this->_modelGameFaq->findById ( $_GET ['Id'] );
					$faqList ['question'] = str_replace ( "\\", '', $faqList ['question'] );
					$faqList ['answer'] = str_replace ( "\\", '', $faqList ['answer'] );
					$gameTypes = $this->_faqKind;
					$kindList = $this->_modelGameKindFaq->findById ( $_GET ['kind_id'] );
					$this->_view->assign ( 'gameType', array ('Id' => $_GET ['game_type_id'], 'name' => $gameTypes [$_GET ['game_type_id']] ) );
					$this->_view->assign ( 'kindList', $kindList );
					$this->_view->assign ( 'id', $_GET ['Id'] );
					$this->_view->assign ( 'faqList', $faqList );
					$this->_view->set_tpl(array('body'=>'Faq/GameFaqedit.html'));
					$this->_utilMsg->createNavBar();
					$this->_view->display ();
				}
				break;
			}
			case 'del' :{//删除
				if (!$this->_isAjax())return false;
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				if ($this->_modelGameFaq->deleteById ( $_GET ['Id'] )) {
					$this->_modelGameKindFaq->update(array('count'=>'count-1'),"Id={$_GET['kind_id']}");	//数量减1
					$this->_returnAjaxJson(array('status'=>1));
				}
				break;
			}
			case 'ratioedit' :{//编辑点击率
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				$data=$this->_modelGameFaq->ratioEdit($_POST);
				$this->_utilMsg->showMsg($data['msg'],$data['status'],$data['href']);
				break;
			}
			case 'ratio' :{//点击率
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				$this->_loadCore('Help_SqlSearch');
				$helpSqlSearch=new Help_SqlSearch();
				$helpSqlSearch->set_tableName($this->_modelGameFaq->tName());
				if ($_GET['game_type_id']!=''){
					$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
					$this->_view->assign('selectedGameTypeId',$_GET['game_type_id']);
				}
				$conditions=$helpSqlSearch->get_conditions();
				$helpSqlSearch->set_orderBy('ratio desc');
				$helpSqlSearch->setPageLimit($_GET['page']);
				$sql=$helpSqlSearch->createSql();
				$dataList=$this->_modelGameFaq->select($sql);
				if ($dataList){
					foreach ($dataList as &$list){
						$list['word_game_type_id']=$this->_faqKind[$list['game_type_id']];
						$list['question']=strip_tags($list['question']);
						$list['answer']=strip_tags($list['answer']);
						$list['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$list['Id'],'kind_id'=>$list['kind_id'],'doaction'=>'edit'));
					}
					$this->_view->assign('dataList',$dataList);
					$this->_loadCore('Help_Page');
					$helpPage=new Help_Page(array('total'=>$this->_modelGameFaq->findCount($conditions),'perpage'=>PAGE_SIZE));
					$this->_view->assign('pageBox',$helpPage->show());
				}
				$this->_view->assign('game_type',$this->_faqKind);
				$this->_view->assign ( 'gameTypeKind', $this->_faqKind );
				$this->_utilMsg->createNavBar();
				$this->_view->set_tpl(array('body'=>'Faq/GameFaqratio.html'));
				$this->_view->display ();
				break;
			}
			case 'kindtree' :{	//显示分类
				if (!$this->_isAjax())
					return false;
				$value = $this->_faqKind [$_GET ['Id']];
				$key = $_GET ['Id'];
				if (! $value)
					return false;
				$jsonData = array ();
				$i = 0;
				$this->_modelGameKindFaq=$this->_getGlobalData('Model_GameKindFaq','object');
				$childKind = $this->_modelGameKindFaq->findByGameTypeId ( $key );
				$childKindNum=count($childKind);
				$jsonData [$i] = array ('text' => "$value($childKindNum)", 'expanded' => 1, 'classes' => 'important' );
				if ($childKindNum) {
					$jsonData [$i] ['children'] = array ();
					foreach ( $childKind as $childValue ) {
						$addUrl=Tools::url(CONTROL,ACTION,array('kind_id'=>$childValue['Id'],'game_type_id'=>$_GET['Id'],'doaction'=>'add'));
						$jsonData [$i] ['children'] [] = array ('text' => "<a href='javascript:void(0)' onclick='displayFaq({$childValue['Id']})'>{$childValue['name']}</a>({$childValue['count']}) [<a href='{$addUrl}'>增加FAQ</a>]" );
					}
				}
				$this->_returnAjaxJson($jsonData);
			}
			case 'viewfaq' :{//显示faq
				if (! $_GET ['kindId'])
					return false;
				$this->_modelGameFaq=$this->_getGlobalData('Model_GameFaq','object');
				$dataList = $this->_modelGameFaq->findByKindId ( $_GET ['kindId'] );
				foreach ($dataList as &$value){
					$value['url_edit']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'kind_id'=>$value['kind_id'],'doaction'=>'edit'));
					$value['url_del']=Tools::url(CONTROL,ACTION,array('Id'=>$value['Id'],'kind_id'=>$value['kind_id'],'doaction'=>'del'));
					if (strpos($value['question'],'\\'))$value['question']=str_replace('\\','',$value['question']);
					if (strpos($value['answer'],'\\'))$value['answer']=str_replace('\\','',$value['answer']);
				}
				$this->_returnAjaxJson($dataList);
				break;
			}
			default:{//faq主页显示
				$this->_view->assign ( 'gameTypeKind', $this->_faqKind );
				$this->_utilMsg->createNavBar();
				$this->_view->display ();
				break;
			}
		}
	}
	#--------------------------------------游戏FAQ-------------------------------------#
}