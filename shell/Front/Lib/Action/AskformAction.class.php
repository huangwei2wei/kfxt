<?php

/**
 * 问卷调查
 * @author 陈成禧
 *
 */
class AskformAction extends BaseAction{
	
	/**
	 * 问题首页
	 */
	public function index(){
		return ;
		//问卷列表
		$dao = M("askform");
		$now = time();
		$map['status']=1;
		$map['start_time']=array("lt",$now);
		$map['end_time']=array("gt",$now);
		$count = $dao->where($map)->count();
		//echo $dao->getLastSql();
		import('ORG.Util.Page');
		$page = New Page($count, 10);
		$list = $dao->where($map)->order('id desc')->limit($page->firstRow.','.$page->listRows)->select(); 
		$this->assign("list",$list);
		$this->assign("show",$page->show());
		$this->assign("p",$_GET['p']);
		$this->display();
	}
	
	/**
	 * 问卷明细调查页
	 */
	public function detail(){
		//检测登录
		$id=$_GET['id'];
		$askformDao = M("askform");
		$askVo = $askformDao->find($id);
		if($askVo){
			$optionDao = M("askform_option");
			$list = $optionDao->where("askform_id={$id}")->select();
			if($list && is_array($list)){
				$len=count($list);
				//反序列问卷选项
				for($i=0;$i<$len;$i++){
					$list[$i]['content']=unserialize($list[$i]['content']);
				}
			}
			$this->assign("list",$list);
			$this->assign("askVo",$askVo);
		}
		$this->display();
	}
	
	/**
	 * 保存问卷调查结果
	 */
	public function save(){
		include COMMON_PATH . 'extend.php';
		$id = $_POST['id'];
		$logDao = M("askform_log");
		$clientIp=get_client_ip();
		//检测是否参与过此调查
		$map['ip']=ip2long($clientIp);
		$map['askform_id']=$id;
		$logvo=$logDao->where($map)->find();
		if($logvo){
			$this->assign("jumpUrl",U("Askform/index"));
			$this->error("您已参与过此调查！");
		}else{
			$askformDao = M("askform");
			$askform=$askformDao->find($id);
			if($askform){
				$optionDao = M("askform_option");
				$list = $optionDao->where("askform_id={$id}")->select();
				//echo $optionDao->getLastSql();
				if($list){
					//echo $list;
					$len=count($list);
					//反序列问卷选项
					for($i=0;$i<$len;$i++){
						$content=unserialize($list[$i]['content']);
						$contentLen=count($content);
						//问卷结果
						$result=array();
						if($list[$i]['result']){
							$result=unserialize($list[$i]['result']);
						}
						//将选择数加一
						if($list[$i]['types']==1){
							//单择
							$value=$_POST['radio_'.$list[$i]['Id']];
							if(isset($value)){
								$result[$value]=$result[$value]+1;
							}
						}else{
							//多择
							$valueArr=$_POST['checkbox_'.$list[$i]['Id']];
							if(isset($valueArr) && is_array($valueArr)){
								foreach ($valueArr as $value){
									$result[$value]=$result[$value]+1;
								}
							}
						}
						$list[$i]['result']=serialize($result);
						$optionDao->save($list[$i]);
						
					}
				}
				//参与调查人数
				$askform['attend_count']=$askform['attend_count']+1;
				$askformDao->save($askform);
				
				//记录参与历史
				$log['askform_id']=$id;
				$log['user_id']=$this->uwanUserId;
				$log['attend_time']=time();
				$log['moblie']=$_POST['moblie'];
				$log['qq']=$_POST['qq'];
				$log['option']=serialize($_POST);
				$log['ip']=ip2long($clientIp);
				$logDao->add($log);
				
				$this->assign("askform",$askform);
			}
			$this->display();
		}
		
	}
}