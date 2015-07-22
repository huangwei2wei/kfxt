<?php
/**
 * 玩家FAQ
 * @author 陈成禧
 *
 */
class PlayerFaqModel extends Model{
	private $timeOut;
	protected function _initialize(){
		parent::_initialize();
		$this->timeOut=3600*12;
	}
	
	/**
	 * 搜索FAQ
	 * @param string $keyword 关键词
	 * @param int $game_id 游戏ID
	 * @return array $result['total_rows']=总记录数<br/>
	 * $result['list']=记录集
	 */
	public function search($keyword,$game_id,$size){
		$filter['game_type_id']=$game_id;
		$filter['question']=array('like',$keyword."%");
		$count=$this->where($filter)->count();
		import('ORG.Util.Page');
		$Page  = new Page($count,$size);		
		$list=$this->where($filter)->limit($Page->firstRow.','.$Page->listRows)->select();
		$result['page']=$Page;
		$result['list']=$list;
		
	}
	
	/**
	 * 取某游戏某分类下FAQ总数
	 * @param int $game_id 游戏ID
	 * @param int $kind_id FAQ分类ID 0=所有
	 * @return int
	 */
	public function getCount($game_id,$kind_id){
		$key="faq_count_".$game_id."_".$kind_id;
		$count=S($key);
		if($count==false){
			$filter=array();
			
			if($kind_id!=0){
				$filter['kind_id']=$kind_id;
			}else{
				$filter['game_type_id']=$game_id;
			}
			$count = $this->where($filter)->count();
			unset($filter);
			if($count){
				S($key,$count,$this->timeOut);
			}
		}
		return $count;
	}
	
	/**
	 * 	FAQ列表
	 * @param int $game_id 游戏ID
	 * @param int $kind_id FAQ分类ID
	 * @param int $startIndex 开始记录
	 * @param int $pageSize 分类大小
	 */
	public function getList($game_id,$kind_id,$start_index,$page_size){
		$key="faq_list_".$game_id."_".$kind_id."_".$start_index."_".$page_size;
		$list=S($key);
		if($list==false){
			$filter=array();
			if($kind_id!=0){
				$filter['kind_id']=$kind_id;
			}else{
				$filter['game_type_id']=$game_id;
			}
			$list = $this->where($filter)->order("id desc")->limit($start_index.",".$page_size)->select();
			//echo $this->getLastSql();
			if($list){
				S($key,$list,$this->timeOut);
			}
		}
		return $list;
		
	}
	
	/**
	 * 具体FAQ
	 * @param int $id FAQ ID
	 */
	public function getById($id){
		$key="faq_detal_".$id;
		$vo = S($key);
		if($vo==false){
			$vo=$this->find($id);
			if($vo){
				S($key,$vo,$this->timeOut);
			}
		}
		return $vo;
	}
	
	/**
	 * 热点问题（最高点击率FAQ）
	 * @param int $gameId
	 */
	public function getTopFAQ($gameId=0){
		$gameId = intval($gameId);
		if($gameId>0){
			$key='player_faq_top_'.$gameId;	//缓存key
		}else{
			$key='player_faq_top_all';	//缓存key
		}
		$topFaqlist = S($key);
		if($topFaqlist !== null){
			return $topFaqlist;
		}
		$topFaqlist = array();
		$gameTypes=D('Sysconfig')->getSysConfig('game_type');
		$map['lang_id'] = 1;
		$map['status'] = array(array('egt',0),array('elt',1));
		if($gameId>0){
			$map['game_type_id'] = $gameId;
		}else{
			$gameIds = array_keys($gameTypes);
			$map['game_type_id'] = $gameIds?array('in',$gameIds):2;
		}
		$data = $this->where($map)->order('ratio desc')->limit(20)->select ();
		foreach($data as $value){
			$topFaqlist[] = array(
				'game_name'=>$gameTypes[$value['game_type_id']]['name'],
				'game_type_id'=>$value['game_type_id'],
				'Id'=>$value['Id'],
				'question'=>$value['question'],
			);
		}
		S($key,$topFaqlist,60*60*6);
		return $topFaqlist;
	}
	
	public function getGameList(){
		$gameTypes=D('Sysconfig')->getSysConfig('game_type');
		$returnData = array();
		if(is_array($gameTypes)){
			foreach($gameTypes as &$value){
				$value['faq_url'] = U('Faq/ls',array('game_type_id'=>$value['Id']));
			}
		}
		return $gameTypes;
	}
}