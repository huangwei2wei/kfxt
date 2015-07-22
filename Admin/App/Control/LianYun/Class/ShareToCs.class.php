<?php
class Control_ShareToCs extends LianYun {
	/**
	 * Util_Msg
	 * @var Util_Msg
	 */
	private $_utilMsg;
	
	private $_utilRbac;
	
	/**
	 * 运营商详细信息
	 * @var Model_LyOperatorInfo
	 */
	private $_modelLyOperatorInfo;
	
	/**
	 * 联运的奖励进度记录
	 * @var Model_LyShareInfo
	 */
	private $_modelLyShareInfo;
	
	
	public function __construct(){
		$this->_createView();
		$this->_createUrl();
		$this->_utilMsg=$this->_getGlobalData('Util_Msg','object');
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
	}
	
	private function _createUrl(){
		$this->_url['operator_info']=Tools::url(CONTROL,'OperatorInfo',array('zp'=>'LianYun'));
		
		$this->_url['share_info']=Tools::url(CONTROL,'ShareInfo',array('zp'=>'LianYun'));
		$this->_url['share_info_add']=Tools::url(CONTROL,'ShareInfoEdit',array('doaction'=>'add','zp'=>'LianYun'));
		
		$this->_view->assign('url',$this->_url);
	}
	
	public function actionOperatorInfoMini(){
		$_GET['page'] = intval(max(1,$_GET['page']));
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes[0] = '全部';
		$operators = $this->_getGlobalData ( 'operator_list' );
		$operators = Model::getTtwoArrConvertOneArr($operators,'Id','operator_name');

		//谁出客服
		$CsSupply = array('0' => '我方','1' => '对方');
		//谁出服务器
		$ServerSupply = array('0' => '我方','1' => '对方');

		$this->_modelLyOperatorInfo = $this->_getGlobalData ( 'Model_LyOperatorInfo', 'object' );
		
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelLyOperatorInfo->tName());
		
		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		if($_GET['game_type_id'] > 0){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
		}
		//《--选择
		$selected['game_type_id'] = $_GET['game_type_id'];
		//选择--》
		
		$helpSqlSearch->set_orderBy('Id desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();
		
		$dataList=$this->_modelLyOperatorInfo->select($sql);
		$urlLenLimit = 25;
		foreach($dataList as &$value){
			$value['game_type_id'] = isset($gameTypes[$value['game_type_id']])?$gameTypes[$value['game_type_id']]:'-';
			$value['operator_id'] = isset($operators[$value['operator_id']])?$operators[$value['operator_id']]:'-';
			$value['cs_supply'] = isset($CsSupply[$value['cs_supply']])?$CsSupply[$value['cs_supply']]:'-';
			$value['server_supply'] = isset($ServerSupply[$value['server_supply']])?$ServerSupply[$value['server_supply']]:'-';
		
			$value['website_url_show'] = strlen($value['website_url'])>$urlLenLimit?substr($value['website_url'],0,$urlLenLimit).'...':$value['website_url'];
			$value['forum_url_show'] = strlen($value['forum_url'])>$urlLenLimit?substr($value['forum_url'],0,$urlLenLimit).'...':$value['forum_url'];
			$value['datum_url_show'] = strlen($value['datum_url'])>$urlLenLimit?substr($value['datum_url'],0,$urlLenLimit).'...':$value['datum_url'];
			
		}
		
		$conditions=$helpSqlSearch->get_conditions();
		$total = $this->_modelLyOperatorInfo->findCount($conditions);
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		$this->_view->assign('pageBox',$helpPage->show());
		
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_view->assign('selected',$selected);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	
	public function actionShareInfo(){
		$_GET['game_type_id'] = intval($_GET['game_type_id']);
		$_GET['title'] = trim($_GET['title']);
		$_GET['content'] = trim($_GET['content']);
		$_GET['page'] = intval(max(1,$_GET['page']));
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$gameTypes[0] = '全部';
		$this->_modelLyShareInfo = $this->_getGlobalData('Model_LyShareInfo','object');
		$users=$this->_getGlobalData('user_index_id');
		$this->_loadCore('Help_SqlSearch');
		$helpSqlSearch=new Help_SqlSearch();
		$helpSqlSearch->set_tableName($this->_modelLyShareInfo->tName());

		if($_GET['game_type_id'] > 0){
			$helpSqlSearch->set_conditions("game_type_id={$_GET['game_type_id']}");
			$selected['game_type_id'] = $_GET['game_type_id'];
		}
		if(!empty($_GET['title']) ){
			$helpSqlSearch->set_conditions("title like '%{$_GET['title']}%'");
			$selected['title'] = $_GET['title'];
		}
		if(!empty($_GET['content']) ){
			$helpSqlSearch->set_conditions("content like '%{$_GET['content']}%'");
			$selected['content'] = $_GET['content'];
		}
		$helpSqlSearch->set_orderBy('update_time desc');
		$helpSqlSearch->setPageLimit($_GET['page'],PAGE_SIZE);
		$sql=$helpSqlSearch->createSql();		
		$dataList=$this->_modelLyShareInfo->select($sql);
		
		
		
		foreach($dataList as &$sub){
			$sub['user_id'] = isset($users[$sub['user_id']])?$users[$sub['user_id']]:'-';
			$sub['game_type_id'] = isset($gameTypes[$sub['game_type_id']])?$gameTypes[$sub['game_type_id']]:'-';
			$sub['update_time'] = date('Y-m-d H:i:s',$sub['update_time']);
			$sub['create_time'] = date('Y-m-d H:i:s',$sub['create_time']);
			$sub['url_edit'] = Tools::url(CONTROL,'ShareInfoEdit',array('doaction'=>'edit','zp'=>'LianYun','Id'=>$sub['Id']));
			$sub['url_del'] = Tools::url(CONTROL,'ShareInfoEdit',array('doaction'=>'del','zp'=>'LianYun','Id'=>$sub['Id']));
			
		}
		
		$conditions=$helpSqlSearch->get_conditions();
		$total = $this->_modelLyShareInfo->findCount($conditions);
		$this->_loadCore('Help_Page');
		$helpPage=new Help_Page(array('total'=>$total,'perpage'=>PAGE_SIZE));
		
		$this->_view->assign('pageBox',$helpPage->show());
		$this->_view->assign('dataList',$dataList);
		$this->_view->assign('selected',$selected);
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	public function actionShareInfoEdit(){
		switch ($_GET['doaction']){
			case 'add' :{
				$this->_shareInfoAdd();
				break ;
			}
			case 'del' :{
				$this->_shareInfoDel();
				break ;
			}
			case 'edit' :{
				$this->_shareInfoEdit();
				break ;
			}
			default :{
				$this->_utilMsg->showMsg('不存在子方法！',-1);
			}
		}
	}
	
	private function _shareInfoAdd(){	
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		if($this->_isPost()){
			if(!array_key_exists($_POST['game_type_id'],$gameTypes) ){
				$this->_utilMsg->showMsg('所选游戏不存在',-1);
			}
			if('' == trim($_POST['title'])){
				$this->_utilMsg->showMsg('标题不能为空',-1);
			}
			if('' == trim($_POST['content'])){
				$this->_utilMsg->showMsg('内容不能为空',-1);
			}
			$this->_modelLyShareInfo = $this->_getGlobalData('Model_LyShareInfo','object');
			$AddData = Tools::fieldFilter('game_type_id,title,content',$_POST);
			$userClass=$this->_utilRbac->getUserClass();
			$AddData['user_id']	= $userClass['_id'];
			$AddData['update_time'] = CURRENT_TIME;
			$AddData['create_time'] = CURRENT_TIME;
			if($this->_modelLyShareInfo->add($AddData)){
				$this->_utilMsg->showMsg('添加成功',1,$this->_url['share_info'],1);
			}else{
				$this->_utilMsg->showMsg('添加失败',-1);
			}			
		}
		
		$data['content']="活动：至尊黑衣人3-全服<br />
&nbsp;&nbsp; 发放完毕：360、人人（座驾已发放、车未发放）、开心<br />
&nbsp;&nbsp; 未发放完毕：pps、51pk<br />
<br />
活动：至尊黑衣人3-全服<br />
&nbsp;&nbsp; 发放完毕：360、人人（座驾已发放、车未发放）、开心<br />
&nbsp;&nbsp; 未发放完毕：pps、51pk<br />
<br />
活动：至尊黑衣人3-全服<br />
&nbsp;&nbsp; 发放完毕：360、人人（座驾已发放、车未发放）、开心<br />
&nbsp;&nbsp; 未发放完毕：pps、51pk<br />";
		$this->_view->assign('data',$data);
		$this->_view->assign('game_type_id',$gameTypes);		
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
	private function _shareInfoDel(){
		$this->_modelLyShareInfo = $this->_getGlobalData('Model_LyShareInfo','object');
		if($this->_modelLyShareInfo->delById(intval($_GET['Id'])) ){
			$this->_utilMsg->showMsg('删除成功',1,$this->_url['share_info'],1);
		}else{
			$this->_utilMsg->showMsg('删除失败',-1);
		}
	}
	
	private function _shareInfoEdit(){
		$_GET['Id'] = intval($_GET['Id']);
		$gameTypes=$this->_getGlobalData('game_type');
		$gameTypes=Model::getTtwoArrConvertOneArr($gameTypes,'Id','name');
		$this->_modelLyShareInfo = $this->_getGlobalData('Model_LyShareInfo','object');
		
		if($this->_isPost()){
			if(!array_key_exists($_POST['game_type_id'],$gameTypes) ){
				$this->_utilMsg->showMsg('所选游戏不存在',-1);
			}
			if('' == trim($_POST['title'])){
				$this->_utilMsg->showMsg('标题不能为空',-1);
			}
			if('' == trim($_POST['content'])){
				$this->_utilMsg->showMsg('内容不能为空',-1);
			}
			$AddData = Tools::fieldFilter('game_type_id,title,content',$_POST);
			$userClass=$this->_utilRbac->getUserClass();
			$AddData['user_id']	= $userClass['_id'];
			$AddData['update_time'] = CURRENT_TIME;
			if($this->_modelLyShareInfo->update($AddData,"Id = {$_GET['Id']}")){
				$this->_utilMsg->showMsg('修改成功',1,$this->_url['share_info'],1);
			}else{
				$this->_utilMsg->showMsg('修改失败',-1);
			}
		}
		$data = $this->_modelLyShareInfo->findById($_GET['Id']);
		$this->_view->assign('data',$data);
		$this->_view->assign('game_type_id',$gameTypes);
		$this->_utilMsg->createPackageNavBar();
		$this->_view->display();
	}
	
}