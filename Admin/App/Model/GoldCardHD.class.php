<?php
/**
 * 充值卡号
 * @author php-朱磊
 *
 */
class Model_GoldCardHD extends Model {
	protected $_tableName = 'gold_card';

	/**
	 * Util_ApiFrg
	 * @var Util_ApiFrg
	 */
	private $_utilApiFrg;

	/**
	 * Model_GameSerList
	 * @var Model_GameSerList
	 */
	private $_modelGameSerList;

	private $_payKey = null;
	
	public function __construct(){
//		Tools::import('Cache_OperatorSetup');
//		$this->_payKey=Cache_OperatorSetup::getFrgConf();
	}

	/**
	 * 获取运营商key
	 */
	public function getKey($gameId=0){
		exit('Model_GoldCardHD::getKey停止使用');
		if($this->_payKey !== null){
			return $this->_payKey;
		}
		if($gameId){//兼容其他游戏配置
			$gameClass = $this->_getGlobalData($gameId,'game');
			if($gameClass){
				$tmp = $gameClass->getOptConf();
				if($tmp){
					$this->_payKey = $tmp;
					return $this->_payKey;
				}
			}
		}
		Tools::import('Cache_OperatorSetup');
		$this->_payKey=Cache_OperatorSetup::getFrgConf();
		return $this->_payKey;
	}


	/**
	 * 导入卡号
	 * @param array $postArr
	 */
	public function import($postArr) {
		$addArr = array ();
		if ($postArr['is_time']){
			$startTime=strtotime($postArr['start_time']);
			$endTime=strtotime($postArr['end_time']);
		}
		$postArr['batch_num']=strtoupper(md5($postArr['batch_num']));
		for($i = 0; $i < $postArr ['num']; $i ++) {
			$nameArr = range ( 'a', 'z' );
			$cardArr = array_rand ( $nameArr, 20 );
			$card = '';
			foreach ( $cardArr as $value ) {
				$card .= $nameArr [$value];
			}
			$card.='bto2'.rand(0,99999).CURRENT_TIME;
			$addArr [] = array (
				'gold' => $postArr['gold'] ,
				'card'=>strtoupper(md5($card)),
				'create_time'=>CURRENT_TIME,
				'type'=>$postArr['type']?$postArr['type']:'0',
				'card_type'=>$postArr['card_type'],
				'batch_num'=>$postArr['batch_num'],
				'start_time'=>$startTime?$startTime:0,
				'end_time'=>$endTime?$endTime:0,
				'apply_user_id'=>$postArr['apply_user_id'],
				'operator_id'=>$postArr['operator_id'],
				'game_type'=>$postArr['game_type'],
			);
		}
		if ($addArr && $this->adds($addArr)){
			return array('msg'=>"生成成功. 批号: <b>{$postArr['batch_num']}</b> ",'status'=>1,'href'=>1);
		}else {
			return array('msg'=>'生成失败','status'=>-2,'href'=>1);
		}
	}

	/**
	 * 删除卡号.
	 * @param array $arr
	 */
	public function del($arr){
		if ($arr['card']){
			if (is_array($arr['card'])){
				foreach ($arr['card'] as &$cardValue){
					$cardValue="'{$cardValue}'";
				}
				$this->execute("delete from {$this->tName()} where card in (".implode(',',$arr['card']).") and is_use=0 ");
			}else {
				$this->execute("delete from {$this->tName()} where card = '{$arr['card']}' and is_use=0 ");
			}
		}
		if ($arr['batch_num']){
			if (is_array($arr['batch_num'])){
				foreach ($arr['batch_num'] as &$cardValue){
					$cardValue="'{$cardValue}'";
				}
				$this->execute("delete from {$this->tName()} where batch_num in (".implode(',',$arr['batch_num']).") and is_use=0");
			}else {
				$this->execute("delete from {$this->tName()} where batch_num = '{$arr['batch_num']}' and is_use=0 ");
			}
		}
	}

	/**
	 * 获取批号卡里的详细信息
	 * @param array $batchNum
	 */
	public function getBatchNumData($batchNum){
		$dataList=$this->select("select * from {$this->tName()} where batch_num='{$batchNum}'");
		if ($dataList){
			$cardType=$this->_getGlobalData('frg_gold_card_type');
			$serverList=$this->_getGlobalData('gameser_list');
			foreach ($dataList as &$list){
				Tools::import('Util_FontColor');
				$users=$this->_getGlobalData('user');
				$list['word_is_use']=Util_FontColor::getFrgPayCardStatus($list['is_use']);
				$list['user_ip']=$list['user_ip']?long2ip($list['user_ip']):'';
				$list['word_type']=Util_FontColor::getFrgCardType($list['type']);
				$list['word_card_type']=$cardType[$list['card_type']];
				$list['word_use_server_id']=$serverList[$list['use_server_id']]['full_name'];
				$list['start_time']=$list['start_time']?date('Y-m-d H:i:s',$list['start_time']):'';
				$list['end_time']=$list['end_time']?date('Y-m-d H:i:s',$list['end_time']):'';
				$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
				$list['use_time']=$list['use_time']?date('Y-m-d H:i:s',$list['use_time']):'';
				$list['word_apply_user_id']=$users[$list['apply_user_id']]['nick_name'];
			}
		}
		return $dataList;
	}

	/**
	 * 充值卡号
	 * @param array $postArr
	 */
	public function pay($postArr,$gameId = 2){	//$gameId = 2兼容富人国
		if (!$postArr['server_id'])return array('status'=>-1,'msg'=>'请选择服务器','href'=>1);
		if (!$postArr['card'])return array('status'=>-1,'msg'=>'请输入卡号','href'=>1);
		if (!$postArr['use_name'])return array('status'=>-1,'msg'=>'请输入用户名','href'=>1);
		$cardDetail=$this->select("select * from {$this->tName()} where game_type={$gameId} and card='{$postArr['card']}'",1);
		if (!$cardDetail)return array('status'=>-1,'msg'=>"卡号: <b>{$postArr['card']}</b> 不存在",'href'=>1);
		if ($cardDetail['is_use']==1)return array('status'=>-1,'msg'=>"卡号: <b>{$postArr['card']}</b> 已经使用过了",'href'=>1);
		if ($cardDetail['start_time'] && $cardDetail['end_time']){//表示有时间限制.
			if ($cardDetail['start_time']<CURRENT_TIME)return array('status'=>-1,'msg'=>'此卡号还未到使用时间','href'=>1);	//如果开始时间小于当前时间
			if (CURRENT_TIME>$cardDetail['end_time'])return array('status'=>-1,'msg'=>'此卡号以过期','href'=>1);
		}
		$this->_modelGameSerList=$this->_getGlobalData('Model_GameSerList','object');
		$serverList=$this->_modelGameSerList->findByGameIdOperatorId($gameId,$cardDetail['operator_id']);
		if (!array_key_exists($postArr['server_id'],$serverList))return array('status'=>-1,'msg'=>'您的卡号无法充值所选择的服务器','href'=>1);
		

		//使用的配置改为从表中取
		$gameObject = $this->_getGlobalData($gameId,'game');
		$gameOperatorExt = $gameObject->getOptConf($cardDetail['operator_id']);
		$syskey = isset($gameOperatorExt['syskey'])?$gameOperatorExt['syskey']:'';

		$this->_utilApiFrg=$this->_getGlobalData('Util_ApiFrg','object');
		$getArr=array(
			'm'=>'User',
			'c'=>'Deposit',
			'a'=>'PayForGold',
			'addcoin'=>$cardDetail['type'],
			'Uname'=>$postArr['use_name'],
			'Money'=>$cardDetail['gold'],
			'Transactionid'=>$cardDetail['card'],
			'Depay'=>0,
			'gDepay'=>0,
			'isGoldCard'=>$cardDetail['card_type'],
			'GoldCard'=>$cardDetail['card'],
			'GameId'=>isset($gameOperatorExt['GameId'])?$gameOperatorExt['GameId']:'',
			//'ServiceId'=>(strpos($serverList[$postArr['server_id']]['marking'],'|'))?end(explode('|',$serverList[$postArr['server_id']]['marking'])):intval(preg_replace('/[a-zA-Z]*(\d+).*/', '\\1', $serverList[$postArr['server_id']]['marking'] ) ),
//			'syskey'=>$this->_payKey[$cardDetail['operator_id']]['key'],
		);
		if($gameOperatorExt["co_action"]=="qq"){
			$getArr["ServiceId"]	=	$serverList[$postArr['server_id']]["ordinal"];
		}else{
			$getArr["ServiceId"]	=	(strpos($serverList[$postArr['server_id']]['marking'],'|'))?end(explode('|',$serverList[$postArr['server_id']]['marking'])):intval(preg_replace('/[a-zA-Z]*(\d+).*/', '\\1', $serverList[$postArr['server_id']]['marking'] ) );
			$getArr["ServiceId"]	=	"S".$getArr["ServiceId"];
		}
		if(strpos($serverList[$postArr['server_id']]['marking'],'|') !== false){
			(strpos($serverList[$postArr['server_id']]['marking'],'|'))?end(explode('|',$serverList[$postArr['server_id']]['marking'])):$serverList[$postArr['server_id']]['marking'];
		}
		$sign="Depay={$getArr['Depay']}&gDepay={$getArr['gDepay']}&addcoin={$getArr['addcoin']}&Uname={$getArr['Uname']}&Money={$getArr['Money']}&GameId={$getArr['GameId']}&ServiceId={$getArr['ServiceId']}&Transactionid={$getArr['Transactionid']}&Key={$syskey}";
		$getArr['Sign']=md5($sign);
		
		$url=$serverList[$postArr['server_id']]['server_url'].'php/interface.php';
		$this->_utilApiFrg->addHttp($url,$getArr);
		$this->_utilApiFrg->send();
		$data=$this->_utilApiFrg->getResult();
		if ($data['data']==1){//成功
			$updateArr=array(
				'is_use'=>1,
				'user_name'=>$postArr['use_name'],
				'user_ip'=>ip2long(Tools::getClientIP()),
				'use_time'=>CURRENT_TIME,
				'use_server_id'=>$postArr['server_id'],
			);
			$this->update($updateArr,"Id={$cardDetail['Id']}");
			return array('status'=>1,'msg'=>'领取成功','href'=>1);
		}else {
			return array('status'=>-2,'msg'=>'领取失败。'.$data['message'],'href'=>1);
		}

	}
}