<?php
/**
 * 自助FAQ
 * @author 陈成禧
 *
 */
 class FaqAction extends BaseAction{

 	/**
 	 * faq分类数组
 	 * @var array
 	 */
 	private $_faqKind;

 	public function __construct() {
 		parent::__construct();
 		//游戏列表
 		$this->_faqKind=$this->getSysConfig("game_type");
 		if (!isset($_GET['game_type_id']))$_GET['game_type_id']=2;
 		$this->assign('gameTypeId',$_GET['game_type_id']);
 	}

 	/**
 	 * 首页
 	 */
	public function index(){
		$this->assign('faqList',$this->_faqKind);
		$this->display("index");
	}


	/**
	 * FAQ列表
	 */
	public function ls(){
		$kind_id=$_GET['typeid'];
		$map=array();
		if(isset($kind_id))$map['kind_id']=$kind_id;
		$map['game_type_id']=$_GET['game_type_id'];
		$map['lang_id']=C('LANG_ID');	//简体

		//faq缓存
		$faqListKey="faq_list_{$_GET['game_type_id']}_{$kind_id}_{$_GET['p']}";
		$faqList=S($faqListKey);
		if (!$faqList){
			$faq=M('player_faq');
			$faqList=$faq->where($map)->limit(10)->order('ratio desc')->page($_GET['p'])->select();
			S($faqListKey,$faqList,60*60);
		}
		$this->assign('faqList',$faqList);

		//faq缓存记录数,用于分页
		$faqListCountKey="faq_list_count_{$_GET['game_type_id']}_{$kind_id}";
		$count=S($faqListCountKey);
		if ($count==''){
			$faq=M('player_faq');
			$count=$faq->where($map)->count();
		}
		import('ORG.Util.Page');
		$page=new Page($count,10);
		$show=$page->show();
		$this->assign('page',$show);

		//FAQ分类
		$types=$this->_getFaqTypes($_GET['game_type_id']);
		$this->assign("types",$types);
		$this->assign('keywords',$this->_getFaqKeywords($_GET['game_type_id']));
		$this->assign('faqKind',$this->_faqKind);
		$this->display("ls");
	}

	/**
	 * faq意见
	 */
	public function opinion(){
		if ($this->isAjax()){
			$modelPlayerFaqLog=M('player_faq_log');
			$addArr=array(
				'game_type_id'=>$_POST['game_type_id'],
				'lang_id'=>'1',
				'source'=>'1',//来源,1=官网
				'player_faq_id'=>$_POST['player_faq_id'],
				'faq_whether'=>$_POST['faq_info'],
				'faq_opinion'=>$_POST['faq_opinion'],
				'date_create'=>time(),
			);
			if ($_POST['faq_opinion']==5)$addArr['content']=$_POST['content'];
			if ($modelPlayerFaqLog->add($addArr)){
				echo json_encode(array('msg'=>1));
			}else{
				echo json_encode(array('msg'=>0));
			}
		}
	}

	/**
	 * 返回 faq分类
	 * @param int $gameTypeId
	 */
	private function _getFaqTypes($gameTypeId){
		$faqKindKey="player_kind_faq_{$gameTypeId}";//faq分类缓存
		$types=S($faqKindKey);
		if (!$types){
			$faqTypesDao = M("player_kind_faq");
			$types=$faqTypesDao->where("game_type_id={$gameTypeId} and lang_id=".C('LANG_ID'))->order('sort_id asc')->select();
			S($faqKindKey,$types,60*60);
		}
		return $types;
	}

	/**
	 * faq关键字
	 * @param int $gameTypeId 游戏类型
	 * @return array keywords 返回关键字
	 */
	private function _getFaqKeywords($gameTypeId){
		$key='player_faq_keywords';
		$dataList=S($key);
		if (!$dataList){
			$modelPlayerFaqKeywords=M('player_faq_keywords');
			$dataList=$modelPlayerFaqKeywords->findAll();
			if ($dataList)S($key,$dataList,60*60);
		}
		foreach ($dataList as $list){
			if ($list['Id']==$gameTypeId)return explode(',',$list['keywords']);
		}
		return false;
	}

	/**
	 * 显示具体FAQ
	 */
	public function show(){

		$dao=M('player_faq');
		$dao->setInc('ratio',"Id={$_GET['Id']}");//点击率加1
		

		//faq单条记录
		$faqShowKey="player_faq_show_{$_GET['Id']}";
		$faq=S($faqShowKey);
		if (!$faq){
			$modelFaq=M('player_faq');
			$faq=$modelFaq->find($_GET['Id']);
			$faq['question']=str_replace('\\','',$faq['question']);
			$faq['answer']=str_replace('\\','',$faq['answer']);
			S($faqShowKey,$faq,60*60*12);
		}
		$this->assign('faq',$faq);

		$faqStats=M('stats_faq');
		$faqStats->add(array('game_type_id'=>$faq['game_type_id'],'source'=>1,'kind_id'=>$faq['kind_id'],'time'=>C('CURRENT_TIME')));//增加统计

		//faq相关记录
		$faqRelatedKey="player_faq_related_{$_GET['Id']}";
		$relatedFaq=S($faqRelatedKey);
		if (!$relatedFaq){
			$modelFaq=M('player_faq');
			$relatedFaq=$modelFaq->where("kind_id={$faq['kind_id']} and Id<>{$_GET['Id']} and lang_id=".C('LANG_ID'))->limit(5)->select();
			S($faqRelatedKey,$relatedFaq,60*60*12);
		}
		$this->assign('relatedFaq',$relatedFaq);

 		$this->assign('faqOpinion',$this->getSysConfig("faq_opinion"));
 		$this->assign('Id',$_GET['Id']);

		//FAQ分类
		$types=$this->_getFaqTypes($_GET['game_type_id']);
		$this->assign("types",$types);
		$this->assign('keywords',$this->_getFaqKeywords($_GET['game_type_id']));
		$this->assign('faqKind',$this->_faqKind);
		$this->display("show");
	}

	/**
	 * 骗术
	 */
	public function conmanship(){
		$id=$_GET['id'];
		$dao = M("player_faq");
		$map['kind_id']=1;
		$list = $dao->where($map)->select();
		if($list){
			$this->assign("list",$list);
			$nowVo=null;
			foreach ($list as $vo){
				if($vo['Id']==$id){
					$nowVo=$vo;

					break;
				}
			}
			if($nowVo==null){
				$nowVo=$list[0];
			}
			$this->assign("nowVo",$nowVo);
			$this->display("conmanship");
		}else{
			$this->error("暂无数据!");
		}
	}


	/**
	 * 相拟FAQ
	 * Ajax调用
	 */
	public function  likeFaq(){
		$title=$_POST['title'];
		$game_type_id=$_POST['game_type_id'];
		if (!$game_type_id || !$title)$this->ajaxReturn(null,null,0);
		import('@.Util.FaqSearch');
		$faqSearch=new FaqSearch();
		$faqSearch->setFaqStatus(2);	//只显示官网和通用的
		$faqSearch->setLimit(0,5);
		$data=$faqSearch->search($title,$game_type_id);
		if ($data['data']){
			$this->ajaxReturn($data['data']);
		}else {
			$this->ajaxReturn(null,null,0);
		}
		

	}

	/**
	 * 搜索FAQ
	 */
	public function search(){
		$game_id = $_GET['game_type_id'];
		if(! isset($game_id))$game_id=1;
		$keyword = $_GET['keyword'];
		if(isset($keyword)){
			import('@.Util.FaqSearch');
			$faqSearch=new FaqSearch();
			$faqSearch->setFaqStatus(2);	//只显示官网和通用的
			$faqSearch->setLimit($_REQUEST[C('VAR_PAGE')],10);
			$data=$faqSearch->search($keyword,$game_id);
			import('ORG.Util.Page');
			$page=new Page($data['info']['total'],10);
			$this->assign('faqList',$data['data']);
			$this->assign("page",$page->show());
			$this->assign('faqKind',$this->_faqKind);
			$this->assign("keyword",$keyword);

			$this->assign("types",$this->_getFaqTypes($game_id));
			$this->assign('keywords',$this->_getFaqKeywords($_GET['game_type_id']));
			
			$this->display("search");
		}else{
			$this->error("请输入需要搜索的关键字!");
		}
	}

	public function kind(){
		$game_id=$_GET['game_id'];
		$dao = M('player_faq');

		$ls = $dao->query("select Id,question,answer from __TABLE__ limit 2");
		$this->ajaxReturn($ls);
	}

	/**
	 * 银行FAQ列表
	 * 0:银行ID
	 */
	public function bankList(){
		$_GET['game_type_id']=C('FAQ_BANK_ID');
		$this->assign('gameTypeId',$_GET['game_type_id']);
		$kind_id=$_GET['typeid'];
		$map=array();
		if(isset($kind_id))$map['kind_id']=$kind_id;
		$map['game_type_id']=C('FAQ_BANK_ID');
		$map['lang_id']=C('LANG_ID');	//语言,简体

		//faq缓存
		$faqListKey="faq_list_".C('FAQ_BANK_ID')."_{$kind_id}_{$_GET['p']}";
		$faqList=S($faqListKey);
		if (!$faqList){
			$faq=M('player_faq');
			$faqList=$faq->where($map)->limit(10)->order('ratio desc')->page($_GET['p'])->select();
			//S($faqListKey,$faqList,60*60);
		}
		$this->assign('faqList',$faqList);

		//faq缓存记录数,用于分页
		$faqListCountKey="faq_list_count_".C('FAQ_BANK_ID')."_{$kind_id}";
		$count=S($faqListCountKey);
		if ($count==''){
			$faq=M('player_faq');
			$count=$faq->where($map)->count();
		}
		import('ORG.Util.Page');
		$page=new Page($count,10);
		$show=$page->show();
		$this->assign('page',$show);

		//FAQ分类
		$types=$this->_getFaqTypes(C('FAQ_BANK_ID'));
		$this->assign("types",$types);
		$this->assign('keywords',$this->_getFaqKeywords(C('FAQ_BANK_ID')));
		$this->assign('faqKind',$this->_faqKind);
		$this->display("bankLs");
	}

	public function bankShow(){
		$_GET['game_type_id']=C('FAQ_BANK_ID');
		$this->assign('gameTypeId',$_GET['game_type_id']);
		$dao=M('player_faq');
		$dao->setInc('ratio',"Id={$_GET['Id']}");//点击率加1

		//faq单条记录
		$faqShowKey="player_faq_show_{$_GET['Id']}";
		$faq=S($faqShowKey);
		if (!$faq){
			$modelFaq=M('player_faq');
			$faq=$modelFaq->find($_GET['Id']);
			$faq['question']=str_replace('\\','',$faq['question']);
			$faq['answer']=str_replace('\\','',$faq['answer']);
			//S($faqShowKey,$faq,60*60*12);
		}
		$this->assign('faq',$faq);
		
		$faqStats=M('stats_faq');
		$faqStats->add(array('game_type_id'=>$faq['game_type_id'],'source'=>1,'kind_id'=>$faq['kind_id'],'time'=>C('CURRENT_TIME')));//增加统计

		//faq相关记录
		$faqRelatedKey="player_faq_related_{$_GET['Id']}";
		$relatedFaq=S($faqRelatedKey);
		if (!$relatedFaq){
			$modelFaq=M('player_faq');
			$relatedFaq=$modelFaq->where("kind_id={$faq['kind_id']} and Id<>{$_GET['Id']} and lang_id=".C('LANG_ID'))->limit(5)->select();
			//S($faqRelatedKey,$relatedFaq,60*60*12);
		}
		$this->assign('relatedFaq',$relatedFaq);

 		$this->assign('faqOpinion',$this->getSysConfig("faq_opinion"));
 		$this->assign('Id',$_GET['Id']);

		//FAQ分类
		$types=$this->_getFaqTypes($_GET['game_type_id']);
		$this->assign("types",$types);
		$this->assign('keywords',$this->_getFaqKeywords($_GET['game_type_id']));
		$this->assign('faqKind',$this->_faqKind);
		$this->display("bankShow");
	}
 }
