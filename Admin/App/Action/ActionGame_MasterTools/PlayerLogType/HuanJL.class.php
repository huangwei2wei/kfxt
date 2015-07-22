<?php
Tools::import('Action_ActionBase');
class ActionGame_MasterTools_PlayerLogType_HuanJL extends Action_ActionBase{
	
	private $_effectiveTime = 604800;	//7天，缓存有效时间，超时自动更新
	
	public function _init(){
		
	}
	
	public function main($UrlAppend=NULL,$get=NULL,$post=NULL){
		if(!$_REQUEST['server_id']){
			return array();
		}
		$effectiveTime = $this->_effectiveTime;
		if($_REQUEST['timeout']){
			$effectiveTime = -1;
		}
		$fileCacheName = $this->getFileCacheName();
// 		echo $fileCacheName;exit;
		$playerLogType = $this->_f($fileCacheName,'',CACHE_DIR,$effectiveTime);
		if(false !== $playerLogType){
			return $playerLogType;
		}
		$playerLogType = $this->_makePlayerLogType($UrlAppend,$get,$post);
		$updateSuccess = 0;
		if($playerLogType){
			$updateSuccess = 1;
			$this->_f($fileCacheName,$playerLogType);
		}
		if($_REQUEST['timeout']){
			if($this->_isAjax()){	//ajax的更新提示
				$this->ajaxReturn(array('status'=>$updateSuccess,'info'=>NULL,'data'=>NULL));
			}elseif($updateSuccess){
				$this->jump('操作成功',1);
			}else{
				$this->jump('操作失败',-1);
			}
		}
		return $playerLogType;
	}
	
	
	private function _makePlayerLogType($UrlAppend=NULL,$get=NULL,$post=NULL){
	 
		$getData = $this->_gameObject->getGetData($get);
		
//		$test = $sendUrl.$UrlAppend.'?'.http_build_query($getData);
//		echo $test;
//		$data = '{"data":{"event":{"3":["{\"typeName\":\"学习/升级武功\",\"objectId\":3,\"eventId\":30001,\"typeDescription\":\"学习/升级武功{0}，武功ID{1}，花费武晶石{2}，银两{3}，金币{4},道具{5}一本，武功达到第{6}级\"}","{\"typeName\":\"离线经验\",\"objectId\":3,\"eventId\":30002,\"typeDescription\":\"使用{1}倍，{2}级易经丹{3}个，获得经验{4}\"}","{\"typeName\":\"打坐经验\",\"objectId\":3,\"eventId\":30003,\"typeDescription\":\"获取在线打坐经验{0}\"}","{\"typeName\":\"洗点\",\"objectId\":3,\"eventId\":30004,\"typeDescription\":\"玩家洗点后的力量{0}，体质{1}，敏捷{2}，智力{3}\"}","{\"typeName\":\"填充续命包\",\"objectId\":3,\"eventId\":30005,\"typeDescription\":\"玩家填充续命包血量为{0}，魔法值为{1}，花费元宝{3}，剩余血包{4}，气包{5}\"}","{\"typeName\":\"领取新手时间礼包\",\"objectId\":3,\"eventId\":30006,\"typeDescription\":\"领取{0}\"}","{\"typeName\":\"嗑药\",\"objectId\":3,\"eventId\":30007,\"typeDescription\":\"玩家一次嗑{0}丹药{1}颗，增加属性到{2}，剩余{3}级丹药使用次数{4}次\"}"],"2":["{\"typeName\":\"提交任务\",\"objectId\":2,\"eventId\":20001,\"typeDescription\":\"完成任务{0}，任务ID={1}，消耗任务道具{2}([名字，数量]...)，获得经验{3}，声望{4}，银两{5},获得道具{6}([名字，数量]...)\"}","{\"typeName\":\"刷新任务\",\"objectId\":2,\"eventId\":20002,\"typeDescription\":\"花费银两{0}，元宝{1}\"}","{\"typeName\":\"提交跑环任务\",\"objectId\":2,\"eventId\":20003,\"typeDescription\":\"完成跑环任务第{0}环{1}，任务ID={2}，消耗任务道具{2}([名字，数量]...)，获得经验{3}，银两{4}，道具{5}([名字，数量]...)，帮派资源{5}，帮派资金{6}，帮派威望值{7}，绑定元宝{8}\"}","{\"typeName\":\"采集任务物品\",\"objectId\":2,\"eventId\":20004,\"typeDescription\":\"做任务{0}，采集{1}道具{2}个\"}"],"1":["{\"typeName\":\"强化\",\"objectId\":1,\"eventId\":10001,\"typeDescription\":\"强化装备：id={0}，等级={1}，名称={2}，装备ID={3}；花费强化材料={4}，数量={5}，银两{6}，结果强化等级={7}\"}","{\"typeName\":\"锻造\",\"objectId\":1,\"eventId\":10002,\"typeDescription\":\"锻造装备：id={0}，等级={1}，名称={2}，装备ID={3}，花费锻造材料={4}，数量={5}，银两{6}，结果锻造等级={7}\"}","{\"typeName\":\"打孔\",\"objectId\":1,\"eventId\":10003,\"typeDescription\":\"打孔装备：id={0}，名称={1}，装备ID={2}，第{3}个孔；花费打孔材料{4}：{5}个\"}","{\"typeName\":\"镶嵌\",\"objectId\":1,\"eventId\":10004,\"typeDescription\":\"镶嵌装备：id={0}，名称={1}，装备ID={2}；花费镶嵌宝石{3}个，宝石ID分别为{4} ，银两{5}\"}","{\"typeName\":\"打磨\",\"objectId\":1,\"eventId\":10005,\"typeDescription\":\"打磨装备：id={0}，名称={0}，装备ID={1}；花费银两{2}，获得银两{3}，宝石碎片{4}：{5}个，宝石{6}：{7}个\"}","{\"typeName\":\"合成\",\"objectId\":1,\"eventId\":10006,\"typeDescription\":\"合成装备:准备{0}ID={1},装备{2}ID={3}，装备{4}ID={5}，装备{6}ID={7}，装备{8}ID={9}，获得装备{1}ID={2}\"}","{\"typeName\":\"拆卸\",\"objectId\":1,\"eventId\":10007,\"typeDescription\":\"拆卸装备：id={0}，名称={1}，装备ID={1}，花费银两{2}，获得宝石{4}一颗\"}","{\"typeName\":\"宝石合成\",\"objectId\":1,\"eventId\":10008,\"typeDescription\":\"合成宝石：花费宝石{0} ，{1}颗；获得宝石{2}一颗\"}","{\"typeName\":\"熔炼\",\"objectId\":1,\"eventId\":10009,\"typeDescription\":\"熔炼装备：{0}，获得绑定金币{1}\"}","{\"typeName\":\"合铸\",\"objectId\":1,\"eventId\":10010,\"typeDescription\":\"合铸主装备{0}，装备ID={1}，id= {2}，副装备{3}，装备ID={4}，id={5}，铸造之后主装备等级{6}，镶嵌宝石{7}，孔数{8}\"}"],"7":["{\"typeName\":\"商城购买\",\"objectId\":7,\"eventId\":70001,\"typeDescription\":\"通过购买方式{0}，购买道具{1}，道具ID={2}，数量{3}个\"}","{\"typeName\":\"声望兑换\",\"objectId\":7,\"eventId\":70002,\"typeDescription\":\"兑换声望道具{0}，花费绑定元宝{1}\"}","{\"typeName\":\"物品丢弃\",\"objectId\":7,\"eventId\":70003,\"typeDescription\":\"丢弃物品{0}，物品ID={1}，数量{2}个\"}","{\"typeName\":\"道具赠送\",\"objectId\":7,\"eventId\":70004,\"typeDescription\":\"玩家{0}，玩家ID={1}赠送道具{2}，道具ID={3}，{4}个，给玩家{5}，玩家ID={6}\"}","{\"typeName\":\"购买天策\",\"objectId\":7,\"eventId\":70005,\"typeDescription\":\"购买天策花费元宝{0}，增加“正确答案”使用次数{1}\"}","{\"typeName\":\"摊位中购买物品\",\"objectId\":7,\"eventId\":90001,\"typeDescription\":\"购买道具{0}{1}个,花费银两{2},金币{3}\"}","{\"typeName\":\"交易\",\"objectId\":7,\"eventId\":90002,\"typeDescription\":\"交易获得银两{0}，金币{1}，道具{2}\"}"],"6":["{\"typeName\":\"创建帮派\",\"objectId\":6,\"eventId\":60001,\"typeDescription\":\"创建帮派{0}，花费银两{1}\"}","{\"typeName\":\"帮派升级\",\"objectId\":6,\"eventId\":60003,\"typeDescription\":\"升级{0}级帮派到{1}级；花费银两{2}，帮派声望值{3}\"}","{\"typeName\":\"帮派科技升级\",\"objectId\":6,\"eventId\":60005,\"typeDescription\":\"升级{0}级{1}到{2}级，消耗资源{3}，银两{4}\"}","{\"typeName\":\"帮派研制\",\"objectId\":6,\"eventId\":60006,\"typeDescription\":\"生产{0}个{1}，消耗资源{2}，银两{3}\"}","{\"typeName\":\"解散帮派\",\"objectId\":6,\"eventId\":60008,\"typeDescription\":\"帮主{0}解散了帮派{1}\"}","{\"typeName\":\"禅让\",\"objectId\":6,\"eventId\":60009,\"typeDescription\":\"帮主{0}禅让给玩家{1}\"}","{\"typeName\":\"开除成员\",\"objectId\":6,\"eventId\":60010,\"typeDescription\":\"帮派{0}开除成员{1}\"}","{\"typeName\":\"退出帮派\",\"objectId\":6,\"eventId\":60011,\"typeDescription\":\"玩家{0}退出帮派{1}\"}","{\"typeName\":\"任命/撤销职位\",\"objectId\":6,\"eventId\":60012,\"typeDescription\":\"玩家{0}任命/撤销{1}职位玩家{2}为{3}职位\"}","{\"typeName\":\"领取摇钱树奖励\",\"objectId\":6,\"eventId\":60014,\"typeDescription\":\"玩家{0}第{1}次领取摇钱树奖励，获得绑定元宝{2}，帮派资金{3}\"}"],"5":["{\"typeName\":\"充值获得\",\"objectId\":5,\"eventId\":50001,\"typeDescription\":\"充值{0}元，获得元宝{1}，充值类型{2}\"}","{\"typeName\":\"绑金获得\",\"objectId\":5,\"eventId\":50002,\"typeDescription\":\"完成新手流程获得绑金{0}\"}","{\"typeName\":\"答题获得银两\",\"objectId\":5,\"eventId\":50003,\"typeDescription\":\"答题获得经验{0}，银两{1}，积分{2}\"}"],"4":["{\"typeName\":\"打怪\",\"objectId\":4,\"eventId\":40001,\"typeDescription\":\"挑战怪物{0}，战斗胜利{3}，获得经验{4}，银两{5}，任务物品{6}([名字...])\"}","{\"typeName\":\"挑战\",\"objectId\":4,\"eventId\":40002,\"typeDescription\":\"挑战玩家{0}，玩家ID={1}，战斗胜利{2}\"}","{\"typeName\":\"刺杀\",\"objectId\":4,\"eventId\":40003,\"typeDescription\":\"刺杀玩家{0}，玩家ID={1}，成功{2}\"}","{\"typeName\":\"进入副本\",\"objectId\":4,\"eventId\":40004,\"typeDescription\":\"玩家当天第{0}次进入副本{1},队友{2}\"}","{\"typeName\":\"打开机关\",\"objectId\":4,\"eventId\":40005,\"typeDescription\":\"玩家打开场景{0}机关，花费{1}道具{2}个\"}","{\"typeName\":\"领取战争前五名奖励\",\"objectId\":4,\"eventId\":40006,\"typeDescription\":\"领取{0}道具{1}个\"}"],"8":["{\"typeName\":\"创建门派\",\"objectId\":8,\"eventId\":80001,\"typeDescription\":\"创建门派{0}，花费银两{1}\"}","{\"typeName\":\"架构派遣\",\"objectId\":8,\"eventId\":80002,\"typeDescription\":\"把徒弟{0}放入{1}职位\"}","{\"typeName\":\"架构移除\",\"objectId\":8,\"eventId\":80003,\"typeDescription\":\"把徒弟{0}从{1}职位移除\"}","{\"typeName\":\"门派升级\",\"objectId\":8,\"eventId\":80004,\"typeDescription\":\"从{0}级升到{1}花费银两{2}，木材{3}，矿石{4}，金属{5}，药材{6}\"}","{\"typeName\":\"资源占领\",\"objectId\":8,\"eventId\":80005,\"typeDescription\":\"派遣徒弟{0}占领玩家{1}资源点{2}，胜利{5}\"}","{\"typeName\":\"门派科技升级\",\"objectId\":8,\"eventId\":80006,\"typeDescription\":\"科技{0}，从等级{1}，升级到{2}，花费木材{3}，金属{4}，矿石{5}，药材{6}\"}","{\"typeName\":\"仓库升级\",\"objectId\":8,\"eventId\":80007,\"typeDescription\":\"仓库从等级{0}，升级到{1}，花费银两{2}，木材{3}，金属{4}，矿石{5}，药材{6}\"}","{\"typeName\":\"采集/炼制执行\",\"objectId\":8,\"eventId\":80008,\"typeDescription\":\"采用{0}，选择徒弟{1}从采集场/炼制塔{2}进行技能等级{3}类型{4}的采集/炼制工作，花费银两{5}，木材{6}，金属{7}，矿石{8}，药材{9}\"}","{\"typeName\":\"采集场租用\",\"objectId\":8,\"eventId\":80009,\"typeDescription\":\"租用{0}号采集场，花费{1}道具一个\"}","{\"typeName\":\"采集场产量提高\",\"objectId\":8,\"eventId\":80010,\"typeDescription\":\"使用{0}道具，提高采集场{1}产量\"}","{\"typeName\":\"采集加速\",\"objectId\":8,\"eventId\":80011,\"typeDescription\":\"采集加速，花费元宝{0}\"}","{\"typeName\":\"采集/炼制收获\",\"objectId\":8,\"eventId\":80012,\"typeDescription\":\"采用{0}，徒弟{1}从采集场/炼制塔{2}进行技能等级{3}类型{4}的采集场/炼制塔收获资源{6}，道具{7}([名字，数量]...)\"}","{\"typeName\":\"收服徒弟\",\"objectId\":8,\"eventId\":80013,\"typeDescription\":\"收服徒弟{0}，徒弟ID={1}，花费{2}道具\"}","{\"typeName\":\"删除徒弟\",\"objectId\":8,\"eventId\":80014,\"typeDescription\":\"删除徒弟{0}，徒弟ID={1}\"}","{\"typeName\":\"徒弟重修\",\"objectId\":8,\"eventId\":80015,\"typeDescription\":\"重修徒弟{0}，徒弟ID={1}，花费元宝{2}\"}","{\"typeName\":\"徒弟特训\",\"objectId\":8,\"eventId\":80016,\"typeDescription\":\"特训徒弟{0}，徒弟ID={1}，花费元宝{2}，银两{3}\"}","{\"typeName\":\"徒弟修行\",\"objectId\":8,\"eventId\":80017,\"typeDescription\":\"徒弟{0}，徒弟ID={1}修行，花费{2}道具，徒弟经验增加{3}\"}","{\"typeName\":\"学习天赋\",\"objectId\":8,\"eventId\":80018,\"typeDescription\":\"徒弟{0}，徒弟ID={1}修行，花费{2}道具，学习了{3}天赋{4}级\"}","{\"typeName\":\"和徒弟进行沟通\",\"objectId\":8,\"eventId\":80019,\"typeDescription\":\"和徒弟{0}，徒弟ID={1}进行沟通，花费银两{2}，元宝{3}，增加友好度{4}\"}","{\"typeName\":\"学习门派武功\",\"objectId\":8,\"eventId\":80020,\"typeDescription\":\"学习门派武功花费木材{0}，金属{1}，矿石{2}，药材{3}，武功{4}达到{5}级\"}"]},"type":{"3":"人物","2":"任务","1":"装备","7":"道具","6":"帮派","5":"元宝","4":"战斗","8":"门徒"}},"status":1,"info":null}';
//		$data = json_decode($data,true);

		$data = $this->getResult($UrlAppend,$getData,$post);
// 		print_r($getData);
// 		print_r($data);exit;
		if($data['status'] != '1'){
			return false;
		}
		$playerLogType = array();
		if($data['data']['type']){
			foreach($data['data']['type'] as $rootTypeId => $rootTypeName){
				$playerLogType[$rootTypeId]['rootTypeName'] = $rootTypeName;
			}
		}
		if($data['data']['event']){
			foreach($data['data']['event'] as $k => $v){
				foreach($v as $subk => $subv){
// 					$event = json_decode($event,true);
					$playerLogType[$k]['subTypeList'][$subk] = array(
						'subTypeName'=>$subv
					);
				}
			}
		}
		return $playerLogType;
	}
//"$playerLog" = Array [8]	
//	3 = Array [2]	
//		rootTypeName = (string:2) 人物	
//		subTypeList = Array [7]	
//			30001 = Array [2]	
//				subTypeName = (string:7) 学习/升级武功	
//				des = (string:57) 学习/升级武功{0}，武功ID{1}，花费武晶石{2}，银两{3}，金币{4},道具{5}一本，武功达到第{6}级	
//			30002 = Array [2]	
//			30003 = Array [2]	
//			30004 = Array [2]	
//			30005 = Array [2]	
//			30006 = Array [2]	
//			30007 = Array [2]	
//	2 = Array [2]	
//	1 = Array [2]	
//	7 = Array [2]	
//	6 = Array [2]	
//	5 = Array [2]	
//	4 = Array [2]	
//	8 = Array [2]	
	
	
	
}