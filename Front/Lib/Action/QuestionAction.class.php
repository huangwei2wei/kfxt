<?php
/**
 * 客服中心-问题页面
 * @author 陈成禧
 */
class QuestionAction extends SafetyAction{
	
	public function _initialize(){
		parent::_initialize();
		$this->mainTpl = 'common:main';	//指定使用主模板
		if (is_callable(array(&$this,'left1'))){
			$this->left1();
		}
	}
	/**
	 * 队列随机数范围
	 * @var array
	 */
	private $_queueRand=array(
		0=>array(4,10),
		1=>array(2,4),
		2=>array(1,4),
		3=>array(1,2),
		4=>array(0,1),
		5=>array(0,1),
		6=>array(0,1),
		7=>array(0,2),
		8=>array(1,3),
		9=>array(3,8),
		10=>array(4,8),
		11=>array(5,11),
		12=>array(4,9),
		13=>array(5,11),
		14=>array(7,15),
		15=>array(6,13),
		16=>array(6,14),
		17=>array(6,13),
		18=>array(5,11),
		19=>array(4,10),
		20=>array(4,10),
		21=>array(4,9),
		22=>array(4,10),
		23=>array(4,8),
	);
	
	/**
	 * 首页，提交问题
	 */
	public function index(){
		$game_type = $this->getSysConfig("game_type");
		unset($game_type[7]);	//幻世仙征、双龙诀没有问题类型，暂时屏蔽
		unset($game_type[3]);
		$this->assign("game_type",$game_type);	
		//显示所有问题类型
		$questonTypes = $this->getQuestionTypes(0);
		
		if ($_GET['game_type_id'])$this->assign('selectedGameTypeId',$_GET['game_type_id']);
		if ($_GET['q_type'])$this->assign('selectedQType',$_GET['q_type']);
		$this->assign("questionTypes",$questonTypes);
		//$this->getCount($this->uwanName);
		$this->display();
	}
	
	/**
	 * 问题类型列表
	 * @param gtype 游戏类型 int
	 */
	public function typels(){
		$gtype=$_GET['gtype'];
		if(!$gtype){
			$gtype=1;
		}
		$this->ajaxReturn($this->getQuestionTypes($gtype));
		
	}
	
	/**
	 * 取得问题类型所对应的动态表单
	 * @param $qtype 问题类型
	 */
	public function form(){
		$qtype = $_GET['qtype'];
		if($qtype){
			//echo $qtype;
			$this->ajaxReturn($this->getQuestionForm($qtype));
		}else{
			$this->ajaxReturn(null,null,0);
		}
	}
	
	/**
	 * 服务器列表
	 * @param $gameid 游戏ID
	 */
	public function servers(){
		
		
		$game_type = $this->getSysConfig("game_type");
		if(!isset($game_type[$_GET['gameid']])){
			$this->ajaxReturn(null,null,0);
		}
		//$gameid = $game_type[$_GET['gameid']]['UwanGameId'];
		$gameid=$_GET['gameid'];
		if($gameid){
			import('@.Util.ServerSelect');
			$serverSelect=new ServerSelect();
			$this->ajaxReturn($serverSelect->getUserCreateServers($gameid));
		}else{
			$this->ajaxReturn(null,null,0);
		}
	}
	
	/**
	 * 官网服务器列表
	 * @param $gameid 游戏ID
	 */
	public function oplist(){
		$gameid=$_GET['gameid'];
		if($gameid){
			$this->ajaxReturn($this->getOperatorList($gameid));
		}else{
			$this->ajaxReturn(null,null,0);
		}
	}
	
	/**
	 * 问题列表
	 */
	public function ls(){
		load('extend');
		$status=$_GET['status'];
		if(! isset($status)){
			//不限制状态
			$status=0;
		}
		//待评价
		$ev=$_GET['ev'];
		
		$filter=array();
		$filter['user_account']=$this->uwanName;
		$filter['operator_id']=C('WE_OPERATOR');
		$filter['source']=1;//官网来源
		if($status==0){
			$filter['status']=array(1,2,'or');
		}else{
			$filter['status']=$status;
		}
		if(isset($ev)){
			//必须已处理才能评价
			$filter['evaluation_status']=$ev;
			$filter['status']=C('HAVE_DO');
		}
		$dao = M('work_order');
		import("ORG.Util.Page"); 
		
		$count = $dao->where($filter)->count();
		
		$Page  = new Page($count,10);
		$show       = $Page->show(); // 分页显示输出   
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性   
		$list = $dao->where($filter)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();   
		
		$gameType=$this->getSysConfig('game_type');

		$this->assign('gameTypes',$gameType);
		
		$this->assign('list',$list); // 赋值数据集   
		$this->assign('page',$show); // 赋值分页输出   
		//问题分类
		$this->assign( 'typeMap',$this->getQuestionTypeMap());
		//问题状态

		$this->assign("questionStatus",$this->getSysConfig("workorder_status"));
		
		//评价状态
		$this->assign("playerEvaluation",$this->getSysConfig("player_evaluation"));

		//问题统计
		$this->getCount($this->uwanName);

		
		$this->display();
		
	}
	
	/**
	 * 返回用户详细资料
	 * @param string $userAccount
	 * @param int $serverId
	 * @param int $gameTypeId
	 */
	private function _getUserResult($userAccount,$serverId,$GameId){
		$defaultData = array(			
			'user_id'=>0,
			'user_account'=>$userAccount,
			'user_nickname'=>'未建立角色',
			'money_total'=>'0',
			'money_month'=>'0',
			'register_date'=>C('CURRENT_TIME'),
			'ip'=>''
		);
		if (!$GameId || !$serverId)return $defaultData;

		$key='GameUserResult_'.$GameId.'_'.$serverId.'_'.$userAccount;
		$userData=S($key);
		if (!$userData){
			import('@.Util.GameUserManage');
			$gameUserManage=new GameUserManage();
			$gameUserManage->setUserResult($userAccount,$serverId,$GameId);
			$userData=$gameUserManage->getUserResult();
			if ($userData)S($key,$userData,60*60);	//缓存1小时
		}
		return $userData?$userData:$defaultData;
	}
	
	/**
	 * 保存问题
	 */
	public function save(){
		if(trim($_POST['title']) =='' && trim($_POST['content'])==''){
			$this->error('问题标题或详细描述不能为空！');
		}
		$game_type = $this->getSysConfig("game_type");
		$_POST['game_type'] = intval($_POST['game_type']);
		if(!array_key_exists($_POST['game_type'],$game_type)){
			$this->error('游戏ID错误！');
		}
		
		#------检查问题类型的合法性和必须填写字段------#
		$_POST['question_type'] = intval($_POST['question_type']);
		$questonTypes = $this->getQuestionTypes($_POST['game_type']);
		$questionType = array();
		foreach($questonTypes as $sub){
			if($sub['Id'] == $_POST['question_type']){
				$questionType = $sub;
				break;
			}
		}
		if(empty($questionType)){
			$this->error('问题类型错误！');
		}
		if($questionType['form_table']){
			foreach($questionType['form_table'] as $sub){
				if($sub['required'] === true && empty($_POST[$sub['name']])){
					$errorInfo = $sub['title']?"“{$sub['title']}”":$sub['name'];
					$this->error($errorInfo.'错误！');
				}
			}
		}
		#------检查问题类型的合法性和必须填写字段------#
		
		#------增加工单到队列------#
		if ($_FILES['file_upload']){//文件上传
			import('ORG.Net.UploadFile');
			$upload=new UploadFile();
			$upload->allowExts=array('jpg','gif','png','jpeg');
			$upload->savePath=C('UPLOAD_PATH').'/Player/'.date('Ymd').'/';
			$upload->saveRule='time';
			if (!file_exists($upload->savePath))mkdir($upload->savePath,0755,true);
			$upload->upload();
			$info=$upload->getUploadFileInfo();
			$_POST['upload_img']=array();
			if($info){
				foreach ($info as $imgPath){
					$imgPath='/Upload/Player/'.date('Ymd').'/'.$imgPath['savename'];
					array_push($_POST['upload_img'],$imgPath);
				}
			}
		}
		$_POST['title']=stripcslashes($_POST['title']);
		$_POST['content']=stripcslashes($_POST['content']);
		$_POST['user_account']=$this->uwanName;
		$_POST['operator_id']=C("WE_OPERATOR");
		$_POST['game_id']=$_POST['game_type'];
		$_POST['source']=1;	//官网提问
		import('@.Util.WebService');
		$webService=new WebService();
		$webService->setUrl(C('SEND_ORDER_URL'));
		$webService->setGet(array('c'=>'InterfaceWorkOrder','a'=>'QuestionSave'));

		//$UwanGameId = $game_type[$_POST['game_id']]['UwanGameId'];
		$sendArr=$this->_getUserResult($_POST['user_account'],$_POST['game_server_id'],$_POST['game_id']);
		if (!$_POST['game_server_id']){
			$gameToserver = array(	//'游戏id'=>'对应该游戏的平台id'
				1=>100,2=>200,5=>20,9=>21,8=>22
			);
			if(key_exists($_POST['game_id'],$gameToserver)){
				$_POST['game_server_id'] = $gameToserver[$_POST['game_id']];
			}
		}
		$curTime=C('CURRENT_TIME');
		if($sendArr && is_array($sendArr)){
			$sendArr=array_merge($sendArr,$_POST);
		}else{
			$sendArr=$_POST;
		}
		$sendArr['_sign']=md5(C('SEND_KEY').$curTime);
		$sendArr['_verifycode']=$curTime;
		$webService->setPost($sendArr);
		$webService->sendData();
		$backParams=$webService->getRaw();
		$backParams=json_decode($backParams);
		if ($backParams->status==1){
			$this->assign("jumpUrl",U("Question/ls"));
			$queue = $this->_getQueue($_POST['game_type'],$backParams->data->room_id);
			$this->success("您的问题已受理，此问题的等候队列：第 {$queue} 位，请耐心等侍。");
		}else {
			$this->error('提问失败,请联系管理员');
		}
	}
	
	/**
	 * AJAX加问题内容
	 */
	public function qacontent(){
		$id=$_GET['id'];
		if($id){
			$filter['work_order_id']=$id;
			$dao = M('work_order_qa');
			$arr=$dao->where($filter)->order("id asc")->select();
			if($arr && is_array($arr)){
				$this->ajaxReturn($arr);
			}else{
				$this->ajaxReturn(null,"查询数据失败",0);
			}
		}else{
			$this->ajaxReturn(null,"参数不正确",0);
		}
	}
	
	/*
	 * 问题交谈明细
	 */
	public function detail(){
		$id=intval($_GET['id']);
		if($id){
			$this->assign("id",$id);
			$orderDao = M("work_order");
			$workOrder = $orderDao->find($id);
			if($workOrder){
				if ($workOrder['user_account']!=$this->uwanName || $workOrder['source']!=1)$this->error('系统错误！');	//如果不是这个玩家的问题或这个问题不是来源于官网,就出错
				
				//游戏类型
				$gameTypes=$this->getSysConfig("game_type");
				$workOrder['game_type_name']=$gameTypes[$workOrder['game_type']]['name'];

				//问题类型
				$questionTypes=$this->getQuestionTypes($workOrder['game_type']);
				if($questionTypes){
					foreach($questionTypes as $qtype){
						if($qtype['Id']==$workOrder['question_type']){
							$workOrder['question_type_name']=$qtype['title'];
							break;
						}
					}
				}
				//所在服务器
				if($workOrder['game_server_id']!=null){
					import('@.Util.ServerSelect');
					$utilServerSelect=new ServerSelect();
					$servers = $utilServerSelect->getGameServers($workOrder['game_type']);
					if($servers){
						foreach($servers as $server){
							if($server['Id']==$workOrder['game_server_id']){
								$workOrder['game_server_name']=$server['server_name'];
								break;
							}
						}
					}
				}
				//动态表单
				$actionForm=$this->getQuestionForm($workOrder['question_type']);
				if($actionForm){
					//动态表单所填值
					$workDetailDao = M("work_order_detail");
					$detail = $workDetailDao->where("work_order_id={$id}")->find();
					//echo $workDetailDao->getLastSql();
					if($detail){
						$value=unserialize($detail['content']);
						//print_r($value);
						if($value){
							$formValue=$value['form_detail'];
							//print_r($formValue);
							if($formValue){
								$actionFormArray=array();//动态表单值数据
								foreach ($actionForm as $form) {
									if($form['type']!='game_server_list'){
										switch ($form ['type']) {
										case 'select' :
											{
												$actionFormArray [$form['title']] = $form ['options'] [$formValue [$form['name']]];
												break;
											}
										default :
											{
												$actionFormArray [$form ['title']] = $formValue [$form ['name']];
												break;
											}
										}
									}
								}
							}
							$workOrder['actionFormArray']=$actionFormArray;
						}
					}
				}
				$this->assign("workOrder",$workOrder);
				//交谈列表
				$dao = M('work_order_qa');
				$filter['work_order_id']=$id;
				$filter['not_sendmsg']=0;	//屏蔽客服回复而不发出的对话
				$list=$dao->where($filter)->order("id asc")->select();
				$this->assign("list",$list);
				//评价状态
				$this->assign("playerEvaluation",$this->getSysConfig("player_evaluation"));
				$this->assign('serivceUsers',$this->_getServiceUser());
				$this->getCount($this->uwanName);
				$this->display();
			}else{
				$this->error("参数错误！");
			}
		}else{
			$this->error("参数错误！");
		}
	}
	
	/**
	 * 增加回复
	 */
	public function addqa(){
		import('@.Util.WebService');
		$webService=new WebService();
		$webService->setUrl(C('SEND_ORDER_URL'));		
		$webService->setGet(array('c'=>'InterfaceWorkOrder','a'=>'Reply'));//追问地址
		$curTime=C('CURRENT_TIME');
		$sendArr['id']=intval($_POST['id']);
		$workOrderDao=M('work_order');
		$data=$workOrderDao->find($sendArr['id']);
		if ($data['user_account']!=$this->uwanName)$this->error('提问失败,参数错误');	//防止参数注入
		$sendArr['content']=stripcslashes($_POST['content']);
		$sendArr['_sign']=md5(C('SEND_KEY').$curTime);
		$sendArr['_verifycode']=$curTime;
		$webService->setPost($sendArr);
		$webService->sendData();
		$backParams=$webService->getRaw();
		$backParams=json_decode($backParams);
		if ($backParams->status==1){
			$this->assign("jumpUrl",U("Question/detail/id/{$sendArr['id']}"));
			$this->success("您的问题已受理，此问题的等候队列：第 ".$this->_getQueue($data['game_type'],$data['room_id'])." 位，请耐等侍。");
		}else {
			$this->ajaxReturn(null,'追问错误,请重试',0);
			$this->error("提交失败!");
		}
	}
	
	/**
	 * 意见、评价
	 */
	public function opinion(){
		$id=$_GET['id'];
		$ev=$_POST['ev'];
		$des=$_POST['ev_des'];

		$dao=M("work_order");
		$vo=$dao->find($id);
		if($vo){
			$vo['evaluation_status']=$ev;
			if ($ev==3)$vo['evaluation_desc']=$des;
			$dao->save($vo);
			if(isset($des)){
				//评差时
				$dtDao = M("work_order_detail");
				$dtVo = $dtDao->find($id);
				if($dtVo){
					$content=unserialize($dtVo['content']);
					$content['other']=array('ev'=>$des);
					$dtVo['content']=serialize($content);
				}
			}
			$this->ajaxReturn(null,null,1);
		}else{
			$this->ajaxReturn(null,null,0);
		}
	}
	
	/**
	 * 删除
	 */
	public function del(){
		$ids=$_POST['Id'];
		$dao=M('work_order');
		$dao->execute("update __TABLE__ set status=4 where Id in (".implode(',',$_POST['Id']).")");
		$this->success('删除成功');
	}
	
	
	/**
	 * 获取客服数组key=用户名,value=客服编号 
	 * @return array
	 */
	private function _getServiceUser(){
		$key='serivce_users';
		$users=S($key);
		if (!$users){
			$dao=M('user');
			$allUser=$dao->where('service_id != ""')->select();
			$users=array();
			foreach ($allUser as $value){
				$users[$value['Id']]=$value['service_id'];
			}
			if ($users)S($key,$users,60*60*12);
		}
		return $users;
	}
	
	/**
	 * 取得各种问题的数量
	 */
	private function getCount($userAccount){
		$dao = M('work_order');
			//待处理问题数
		$map['user_account']=$userAccount;
		$map['operator_id']=C('WE_OPERATOR');
		$map['status']=array(1,2,'or');
		$map['source']=1;
		
		$this->assign("waitCount",$dao->where($map)->count());
		//已处理问题数
		$map['status']=array('eq',C('HAVE_DO'));
		$this->assign("haveCount",$dao->where($map)->count());
		//待评价数
		$map['status']=array('eq',C('HAVE_DO'));
		$map['evaluation_status']=0;
		$this->assign("evCount",$dao->where($map)->count());
		//echo $dao->getLastSql();
		
	}
	/**
	 * 问题类型
	 * @param $gameId 游戏ID 
	 * $gameId=0即查询所有问题分类
	 */
	private function getQuestionTypes($gameId){
		
		$dao = new QuestionTypesModel();
		return 	$dao->getQuestionTypes($gameId);
	}
	
	/**
	 * 取得问题分类Map
	 */
	private function getQuestionTypeMap(){
		$key = "quesiton_type_maps";
		$map = S($key);
		if(! $map){
			$types = $this->getQuestionTypes(0);
			$map = array();
			if(is_array($types)){
				foreach($types as $vo){
					$map[$vo['Id']]=$vo['title'];
				}
				S($key,$map,60*10);
			}
		}
		return $map;
	}
	
	/**
	 * 问题下的动态表单
	 */
	private function getQuestionForm($qtype){
		$key="question_form_".$qtype;
		$form = S($key);
		if(!$form){
			$dao=M('question_types');
			$filter=array();
			$filter['id']=$qtype;
			$po = $dao->where($filter)->find();
			if($po){
				$form=unserialize($po['form_table']);
				S($key,$form,60*10);
			}
		}
		return $form;
	}
	
	private function getOperatorList($gameid){
		$key="operator_list_".$gameid;
		$servers=S($key);
		if(! $servers){
			$dao = M("operator_list");
			$filter=array();
			$filter['game_type_id']=$gameid;
			$servers = $dao->where($filter)->select();
			if($servers){
				S($key,$servers,60*10);
			}
		}
		return $servers;		
	}
	
	/**
	 * 获取随机队列
	 */
	private function _getQueue($gameTypeId,$roomId){
		$key="Order_Quere_{$gameTypeId}_{$roomId}";
		$cacheCount=S($key);
		if (!$cacheCount){//缓存
			$workOrder=M('work_order');
			$count=$workOrder->query("select count(Id) as count from __TABLE__ where room_id={$roomId} and game_type={$gameTypeId} and status=1");
			$cacheCount=intval($count[0]['count']);
			S($key,$cacheCount,60*10);
		}
		$curHour=date('H',C('CURRENT_TIME'));
		return $cacheCount+rand($this->_queueRand[$curHour][0],$this->_queueRand[$curHour][1]);;
	}


	
	
	
}
?>