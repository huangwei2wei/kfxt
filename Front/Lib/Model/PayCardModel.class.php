<?php
class PayCardModel extends Model {
	protected function _initialize(){
		parent::_initialize();
	}
	
	protected $tableName = 'gold_card'; 
	
	public function pay($postArr){
		if (!$postArr['server_id'])return array('status'=>-2,'msg'=>'请选择服务器');
		$card=$this->where("card='{$postArr['card']}' and is_use=0")->limit(1)->select();
		if ($card){//如果存在就表示输入正确,而且没有被使用过
			$card=$card[0];
			$currentTime=C('CURRENT_TIME');
			if ($card['start_time'] && $card['end_time']){//表示有时间限制.
				if ($card['start_time']<$currentTime)return array('status'=>-1,'msg'=>'此卡号还未到使用时间');	//如果开始时间小于当前时间
				if ($currentTime>$card['end_time'])return array('status'=>-1,'msg'=>'此卡号以过期');	
			}
		
			load('extend');
			import('@.Util.FRGInterface');
			import('@.Util.ServerSelect');
			$serverSelect=new ServerSelect();
			$server=$serverSelect->getServerApiUrl($postArr['server_id']);
			$frgApi=new FRGInterface();
			$url=$server['server_url'].'php/interface.php';
			$frgApi->set_sendUrl($url);
			$getArr=array(
				'm'=>'User',
				'c'=>'Deposit',
				'a'=>'Pay',
				'addcoin'=>$card['type'],
				'Uname'=>$postArr['user_name'],
				'Money'=>$card['gold'],
				'Transactionid'=>$card['batch_num'],
				'Depay'=>0,
				'gDepay'=>0,
				'isGoldCard'=>$card['card_type'],
				'GoldCard'=>$card['card'],
				'GameId'=>C('FRG_GAME_ID'),
				'ServiceId'=>$server['marking'],
				'syskey'=>C('SYS_KEY'),
			);
			$sign="Depay={$getArr['Depay']}&gDepay={$getArr['gDepay']}&addcoin={$getArr['addcoin']}&Uname={$getArr['Uname']}&Money={$getArr['Money']}&GameId={$getArr['GameId']}&ServiceId={$getArr['ServiceId']}&Transactionid={$getArr['Transactionid']}&Key={$getArr['syskey']}";
			$getArr['Sign']=md5($sign);
			$frgApi->setGet($getArr);
			$data=$frgApi->callInterface();
			if ($data['data']==1){//成功
				$updateArr=array(
					'is_use'=>1,
					'user_name'=>$postArr['user_name'],
					'user_ip'=>ip2long(get_client_ip()),
					'use_time'=>C('CURRENT_TIME'),
					'Id'=>$card['Id'],
					'use_server_id'=>$postArr['server_id'],
				);
				$this->save($updateArr);
				return array('status'=>1,'msg'=>'领取成功');
			}else {
				return array('status'=>-2,'msg'=>'领取失败');
			}
		}else {
			return array('status'=>'-2','msg'=>'卡号无效,或者已使用');
		}
	}
}

































