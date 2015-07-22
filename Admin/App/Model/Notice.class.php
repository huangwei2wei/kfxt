<?php
/**
 * 公告管理
 * @author PHP-华龙
 */
class Model_Notice extends Model {
	protected $_tableName = 'notice';
	/**
	 * 
	 * 抓取公告
	 * @param unknown_type $data
	 * $data = array(
	 * 		'game_type'	=	'',
	 * 		'server_id'	=	'',
	 * 		'post'		=	'',
	 * );
	 */
	public function crawlNotice($data=array()){
		$game		=	$this->_getGlobalData('Game_'.$data['game_type'],'object');
		$datalist	=	$game->TransformNoticeData($data);
		$gamelist	=	$this->_getGlobalData('gameser_list');
		$serverIds	=	$gamelist[$data['server_id']];
		$this->delByServerId($data['server_id'],$data['game_type']);
		$addArrs	=	array();
		if($datalist){
			foreach($datalist as $_msg){
				$addArr=array();
				if ($serverIds['operator_id'])$addArr['operator_id']=$serverIds['operator_id'];
				$addArr['server_id']		=	$data['server_id'];
				$addArr['content']			=	$_msg['content'];
				$addArr['title']			=	$_msg['title'];
				$addArr['start_time']		=	$_msg['start_time'];
				$addArr['end_time']			=	$_msg['end_time']?"":0;
				$addArr['interval_time']	=	$_msg['interval_time'];
				$addArr['url']				=	$_msg['url'];
				$addArr['create_time']		=	$_msg['create_time']?$_msg['create_time']:'0';
				$addArr['last_send_time']	=	$_msg['last_send_time'];
				$addArr['main_id']			=	$_msg['main_id'];
				$addArr['game_type']		=	$data['game_type'];
				unset($_msg['content'],$_msg['title'],$_msg['start_time'],$_msg['end_time'],$_msg['interval_time'],$_msg['url'],$_msg['create_time'],$_msg['last_send_time'],$_msg['main_id'],$_msg['game_type']);
				if($_msg){
					$addArr['other']	=	serialize($_msg);
				}
				$this->add($addArr,null,true);
			}
			if(true){
				$this->ajaxReturn(array('status'=>1,'msg'=>'抓取成功，'));
			}else{
				$this->ajaxReturn(array('status'=>0,'msg'=>'抓取失败，'));
			}	
			$this->ajaxReturn(array('status'=>1,'msg'=>'抓取成功，'));
		}else{
			$this->ajaxReturn(array('status'=>0,'msg'=>'连接失败，'));
		}
	}
	
	public function delByServerId($serverId,$game_type){
		$this->execute("delete from {$this->tName()} where server_id={$serverId} and game_type={$game_type}");
	}
	/**
	 * 
	 *	获取公告列表
	 * @param unknown_type $data
	 */
	public function getNoticelist($data){
		$this->_utilRbac=$this->_getGlobalData('Util_Rbac','object');
		$userClass=$this->_utilRbac->getUserClass();
		$operator	=	$this->_utilRbac->getOperatorActList($data['game_type']);
		$operator	=	array_keys($operator);
		if(count($operator)<1){
			return false;
		}else{
			$operator	=	implode(',',$operator);
		}
		$operator = rtrim($operator,',');
		$gamelist	=	$this->_getGlobalData('gameser_list');
		$this->_loadCore ( 'Help_SqlSearch' );
		$helpSqlSearch 		= new Help_SqlSearch ();
		$helpSqlSearch->set_tableName ($this->tName());
		$helpSqlSearch->set_conditions ('game_type="'.$data['game_type'].'"');
		if($operator){
			$helpSqlSearch->set_conditions ('operator_id in ('.$operator.')');
		}
		if($data['start_time']){
			$helpSqlSearch->set_conditions("start_time>".strtotime($data['start_time']));
		}
		if($data['end_time']){
			$helpSqlSearch->set_conditions("end_time<".strtotime($data['end_time']));
		}
		if($data['content']){
			$helpSqlSearch->set_conditions("content like '%{$data['content']}%'");
		}
		$helpSqlSearch->set_orderBy('id');
		$helpSqlSearch->setPageLimit ($data['page']);
		$conditions 		= 	$helpSqlSearch->get_conditions();
		$sql 				= 	$helpSqlSearch->createSql ();
		$datalist			=	$this->select($sql);
		if ($datalist) {
			$this->_loadCore ( 'Help_Page' );
			$helpPage 	= new Help_Page ( array ('total' => $this->findCount ( $conditions ), 'perpage' => '20' ) );
			$pageshow	=	$helpPage->show();
			$thisdatalist	=	array();
			foreach($datalist as $_msg){
				$_msg['url_edit']=Tools::url(CONTROL,'Announcement',array('zp'=>$data['zp'],'doaction'=>'edit','server_id'=>$_msg['server_id'],'id'=>$_msg['main_id'],'__game_id'=>$_msg['game_type']));
				$_msg['title']			=	strip_tags($_msg['title']);
				$_msg['content']		=	strip_tags($_msg['content']);
				$_msg['server_main_id']	=	$_msg['server_id'];
				$_msg['server_id']	=	$gamelist[$_msg['server_id']]['full_name'];
//				$_msg['start_time']	=	date('Y-m-d H:i:s',$_msg['start_time']);
//				$_msg['end_time']	=	date('Y-m-d H:i:s',$_msg['end_time']);
				if($_msg['end_time']==='0'){
					$_msg['end_time']	=	'暂无记录';
				}else{
					$_msg['last_send_time']	=	date('Y-m-d H:i:s',$_msg['last_send_time']);
				}
				if($_msg['start_time']==='0'){
					$_msg['start_time']	=	'暂无记录';
				}else{
					$_msg['last_send_time']	=	date('Y-m-d H:i:s',$_msg['last_send_time']);
				}
				if($_msg['last_send_time']==='0'){
					$_msg['last_send_time']	=	'暂无记录';
				}else{
					$_msg['last_send_time']	=	date('Y-m-d H:i:s',$_msg['last_send_time']);
				}
				if($_msg['create_time']==='0'){
					$_msg['create_time']	=	'暂无记录';
				}else{
					$_msg['create_time']	=	date('Y-m-d H:i:s',$_msg['create_time']);
				}
				//http://hl.uwan.com/admin.php?c=DaTangOperator&a=Announcement&zp=DaTang&doaction=edit&id=1&server_id=948
				
				$thisdatalist[]	=	$_msg;
			}
		}
		
		
		$returndata		=	array(
			'dataList'		=>	$thisdatalist,
			'pageBox'		=>	$pageshow,
		);
		return $returndata;
	}
	
	public function deleteNotice($id,$game){
		$notice	=	$this->findById($id);
		$game	=	$this->_getGlobalData('Game_'.$game,'object');
		$data	=	array(
			'game_type'	=>	$game,
			'ids'		=>	$notice['main_id'],
			'server_id'	=>	$notice['server_id'],
		);
		if($game->delNotice($data)){
			$this->delById($id);
			return true;
		}else{
			return false;
		}
	}
	
	public function ajaxReturn($result=array()){
//		if($result['status'] == 1){
//			$this->notify();	//操作成功情况下,通知监听器
//		}
		exit(json_encode($result));
	}
}

?>