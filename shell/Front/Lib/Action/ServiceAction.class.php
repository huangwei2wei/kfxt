<?php 
/**
 * 对外服务接口
 * @author 陈成禧
 *
 */
class ServiceAction extends Action{
	private $gmae_id;
	private static $_key="cndw_kefu";
	function _initialize(){
		if($this->check()==false){
			$this->ajaxReturn(null,"ERROR",0);
		}
		//parent::_initialize();
		$this->gmae_id=$_GET['game_id'];
	}
	
	/**
	 * 验证是否合法请求数据
	 */
	private function check(){
		echo md5(self::$_key.$_POST['_verifycode']);
		$sign=$_POST['_sign'];
		$verifycode=$_POST['_verifycode'];
		if(isset($sign) && isset($verifycode)){
			if(MD5(self::$_key.$verifycode)==$sign){
				return true;
			}else{
				false;
			}
		}else{
			return false;
		}
	}
	
	

	/**
	 * FAQ列表
	 */
	public function faq_list(){
		$kind_id=$_GET['kind_id'];
		if(!isset($kind_id)){
			$kind_id=0;
		}
		$ps=$_GET['ps'];
		if(!isset($ps)){
			$ps=10;
		}
		$dao = new PlayerFaqModel();
		$count=$dao->getCount($this->gmae_id, $kind_id);
		$result=array();
		$result['count']=$count;
		if($count>0){
			import("ORG.Util.Page");
			$page = new Page($count,$ps);
			$list=$dao->getList($this->gmae_id, $kind_id, $page->firstRow, $page->listRows);
			$result['list']=$list;
		}
		$this->ajaxReturn($result);
		
	}
	
	/**
	 * FAQ分类
	 */
	public function faq_type(){
		if($this->gmae_id){
			$dao = new PlayerKindFaqModel();
			$list=$dao->getKinds($this->gmae_id);
			$this->ajaxReturn($list);
		}else{
			$this->ajaxReturn(NULL,"NO_FIND",0);
		}
	}
	
	/**
	 * FAQ明细
	 */
	public function faq_detail(){
		$id=$_GET['id'];
		if(isset($id)){
			$dao = new PlayerFaqModel();
			$vo=$dao->getById($id);
			if($vo){
				$this->ajaxReturn($vo);
			}else{
				$this->ajaxReturn(null,"NO_FIND",0);
			}
		}else{
			//参数错误
			$this->ajaxReturn(null,"PARAM_ERROR",0);
		}
	}
	
	/**
	 * FAQ评价
	 */
	public function faq_evaluate(){
		$id=$_POST['id'];
		if(isset($id)){
			$faq_whether=$_POST['faq_whether'];
			$faq_opinion=$_POST['faq_opinion'];
			$content=$_POST['content'];
			if(isset($faq_whether)){
				$vo['player_faq_id']=$id;
				$vo['faq_whetcher']=$faq_whether;
				if($faq_whether==0){
					//没用
					if(isset($faq_opinion)){
						$vo['faq_opinion']=$faq_opinion;
					}
					if(isset($content)){
						$vo['content']=$content;
					}
				}
				$dao = M("player_faq_log");
				$dao->add($vo);
			}
		}
		$this->ajaxReturn(null,null,1);
	}
	
	
	/**
	 * 问题类型
	 */
	public function question_types(){
		
		if($this->gmae_id){
			$dao = new QuestionTypesModel();
			$list=$dao->getQuestionTypes($this->gmae_id);
			$this->ajaxReturn($list);
		}else{
			$this->ajaxReturn(NULL,"NO_FIND",0);
		}
	}
	/**
	 * 保存游戏中提交的问题
	 */
	public function question_save(){
		//处理表单内容
		$vo=array();
		//游戏类型
		$vo['game_type']=$this->gmae_id;
		//问题类型
//		$vo['question_type']=$_POST['question_type'];
		//用户帐号
		$vo['user_account']=$_POST['user_account'];
		//用户ID
		$vo['user_id']=$_POST['user_id'];
		//用户昵称
		$vo['user_nickname']=$_POST['user_nickname'];
		//服务器ID
		$gameServerId=$_POST['game_server_id'];
		//游戏服务器ID
		if(isset($gameServerId)){
			$vo['game_server_id']=$gameServerId;
		}
		$vo['create_time']=time();
		//标题
		$vo['title']=$_POST['title'];
		
		//充值总额
		$vo['money_total']=$_POST['money_total'];
		//近月充值总额
		$vo['money_month']=$_POST['money_month'];
		//Load('extend');
		//$vo['create_ip']=get_client_ip();
		//来源2=游戏端
		$vo['source']=2;
		//运营商ID
		$vo['operator_id']=$_POST['operator_id'];
		//提问数
		$vo['question_num']=1;
		
		
		$dao = M("work_order");
		$id=$dao->add($vo);
		if($id){
			//问题内容
			$qa=array();
			//生成的工单ID
			$qa['work_order_id']=$id;
			$qa['qa']=0;
			//提问内容
			$qa['content']=$_POST['content'];
			$qaDao = M("work_order_qa");
			$qaDao->add($qa);
			//问题明细
			$detail=array();
			$detail['work_order_id']=$id;
			//用户信息
			$detailContent=array(); 
			$detailContent['user_data']=array();
			//表单明细
			$detailContent['form_detail']=array();
			//表得动态表单项
			if(isset($vo['question_type']) && $vo['question_type']!=0){
				$form=$this->getQuestionForm($vo['question_type']);
				if($form && is_array($form)){
					$formVo=array();
					foreach ($form as $item){
						$itemValue=$_POST[$item['name']];
						if(isset($itemValue)){
							$formVo[$item['name']]=$itemValue;
						}
					}
					$detailContent['form_detail']=$formVo;
				}
			}
			$detail['content']=serialize($detailContent);
			$detailDao = M('work_order_detail');
			$detailDao->add($detail);
			
			$this->ajaxReturn($id);
			
		}else{			
			//保存失败
			$this->ajaxReturn(null,$dao->getDbError(),0);
		}
	}
	
	/**
	 * 问题列表
	 */
	public function question_list(){
		
	}
	
	/**
	 * 问题明细
	 */
	public function question_detail(){
		
	}
	
	/**
	 * 回复问题
	 */
	public function reply(){
		$id=$_POST['id'];
		$content=$_POST['content'];
		$vo['work_order_id']=$id;
		$vo['qa']=0;
		$vo['content']=$content;
		$dao = M("work_order_qa");
		$lastid=$dao->add($vo);
		if($lastid){
			//增加回复数
			$orderDao = M("work_order");
			$order = $orderDao->find($id);
			if($order){
				//更新状态为待处理
				$order['status']=1;
				$order['question_num']=$order['question_num']+1;
				$orderDao->save($order);
			}
			$this->ajaxReturn(null,null,1);
		}else{
			$this->ajaxReturn(null,null,0);
		}
	}
	
	/**
	 * 问题评价
	 */
	public function question_evaluate(){
		//工单ID
		$id=$_POST['id'];
		//评价值
		$ev=$_POST['ev'];
		//评差时的原因
		$des=$_POST['des'];
		$dao=M("work_order");
		$vo=$dao->find($id);
		if($vo){
			$vo['evaluation_status']=$ev;
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
}
?>