<?php
/**
 * 状态颜色加载
 * @author php-朱磊
 */
class Util_FontColor extends Control {

	private static $_gameTypeColor=array(
		'1'=>'#0066CC',	//商业大亨
		'2'=>'#6666FF',	//富人国
		'3'=>'#990099',	//三分天下
		'5'=>'#CC3333',	//寻侠
		'7'=>'#FF0000'//双龙记
	);

	private static $_workOrderSource=array(
		'1'=>'#0000FF',	//官网提问
		'2'=>'#993333',	//游戏内提问
        '3'=>'#009933', //手动提单
		'4'=>'#666666',	//其他
	);

	private static $_workOrderStatus=array(
		'1'=>'#FF0000',	//待处理
		'2'=>'#006699',	//已经回复
		'3'=>'#00CC00',	//已经处理
		'4'=>'#666666'	//已经被玩家删除
	);

	private static $_verifyLevel=array(
		'1'=>'#000000',	//一般
		'2'=>'#d50dd3', //优先
		'3'=>'#FF0000', //紧急
	);

	private static $_playerEvaluation=array(
		'0'=>'#666666',	//未评价
		'1'=>'#00CC00',	//好评
		'2'=>'#000000',	//中评
		'3'=>'#d50dd3',	//差评
	);

	private static $_qualityOptions=array(
		'1'=>'#148f94',	//对
		'2'=>'#1a8629',	//推荐
		'3'=>'#9d6c16',	//错字
		'4'=>'#ae990a',	//内容不完整
		'5'=>'#ae990a',	//内容不沫晰
		'6'=>'#ae0aa4',	//内容错误
		'7'=>'#470aae',	//建议优化
		'8'=>'#000000',	//其它
	);

	private static $_qualityStatus=array(
		'1'=>'#666666',	//未申诉
		'2'=>'#148f94',	//已经申诉
		'3'=>'#0729ba',	//同意申诉
		'4'=>'#FF0000',	//拒绝质检
		'5'=>'#908e00',	//同意质检
	);

	private static $_verifySource=array(
		'1'=>'#0000FF',	//游戏
		'2'=>'#148f94',	//论坛
		'3'=>'#0729ba',	//电话
		'4'=>'#908e00',	//网站
		'5'=>'#666666',	//其他
	);

	private static $_online=array(
		'0'=>'#666666',	//离线
		'1'=>'#00CC00',	//在线
	);

	private static $_mailRead=array(
		'0'=>'#FF0000',	//未读
		'1'=>'#00CC00',	//已读
	);

	private static $_frgLibaoCardStatus=array(//富 人国礼包卡使用状态
		'0'=>'#00CC00',	//未使用
		'1'=>'#FF0000',	//已使用
	);

	private static $_mailType=array(
		'0'=>'#000000',	//其它
		'1'=>'#0000FF',	//游戏
		'2'=>'#148f94',	//论坛
		'3'=>'#0729ba',	//电话
	);

	/**
	 * 富人国游戏数值修改类型
	 * @var array
	 */
	private static $_frgAuditType=array(
		'1'=>'#470aae',	//奖励发放
		'2'=>'#1a8629',	//玩家数值修改
		'3'=>'#9d6c16',	//玩家工厂数值修改
		'4'=>'#ae990a',	//玩家员工数值修改
		'5'=>'#148f94',	//玩家座驾数值修改
		'6'=>'#ae0aa4',	//商会数值修改
	);

	/**
	 * 富人国发送状态修改
	 * @var array
	 */
	private static $_frgAudit=array(
		'0'=>'#666666',	//未发送
		'1'=>'#00CC00',	//已发送
		'2'=>'#FF0000',	//拒绝
	);

	/**
	 * 客服投票是否即时开放结果
	 * @var array
	 */
	private static $_isOpen=array(
		'0'=>'#ff0000',
		'1'=>'#00CC00',
	);

	/**
	 * 客服日常工具用户投票状态
	 * @var array
	 */
	private static $_voteUserStatus=array(
		'0'=>'#666666',
		'1'=>'#FF0000',
		'2'=>'#00CC00',
	);


	private static $_htmlFont = '$str="<font color=\"$color\">$string</font>";';

	public static function getGameTypeColor($gameTypeId, $string) {
		$color=self::$_gameTypeColor[$gameTypeId];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getWorkOrderSource($sourceId,$string){
		$color=self::$_workOrderSource[$sourceId];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getWorkOrderStatus($status,$string){
		$color=self::$_workOrderStatus[$status];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getVerifyLevel($level,$string){
		$color=self::$_verifyLevel[$level];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getPlayerEvaluation($ev,$string){
		$color=self::$_playerEvaluation[$ev];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getQualityOptions($option,$string){
		$color=self::$_qualityOptions[$option];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getQualityStatus($status,$string){
		$color=self::$_qualityStatus[$status];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getVerifySource($source,$string){
		$color=self::$_verifySource[$source];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getOnline($status){
		$string=$status?'[在线]':'[离线]';
		$color=self::$_online[$status];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getMailRead($isRead){
		$string=$isRead?'[已读]':'[未读]';
		$color=self::$_mailRead[$isRead];
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getMailType($type,$string){
		$color=self::$_mailType[$type];
		eval(self::$_htmlFont);
		return $str;
	}

	/**
	 * 富人国申请类型
	 */
	public static function getFRGauditType($type,$string){
		$color=self::$_frgAuditType[$type];
		eval(self::$_htmlFont);
		return $str;
	}

	public static $frgSendString=array(
		'0'=>'未审核',
		'1'=>'已审核',
		'2'=>'拒绝',
	);

	/**
	 * 富人国审核列表发送状态
	 */
	public static function getFRGsendType($isSend){
		switch ($isSend){
			case '0' :{
				$string=self::$frgSendString[0];
				$color=self::$_frgAudit[0];
				break;
			}
			case '1' :{
				$string=self::$frgSendString[1];
				$color=self::$_frgAudit[1];
				break;
			}
			case '2' :{
				$string=self::$frgSendString[2];
				$color=self::$_frgAudit[2];
				break;
			}
		}
		eval(self::$_htmlFont);
		return $str;
	}

	public static function getVoteOpen($isOpen){
		$string=$isOpen?'是':'否';
		$color=self::$_isOpen[$isOpen];
		eval(self::$_htmlFont);
		return $str;
	}


	/**
	 * 客服日常工具用户有无权限投票文字
	 * @param $status
	 */
	public static function getVoteUserStatus($status){
		switch ($status){
			case '0' :{
				$string='无权限';
				$color=self::$_voteUserStatus[0];
				break;
			}
			case '1' :{
				$string='未投票';
				$color=self::$_frgAudit[1];
				break;
			}
			case '2' :{
				$string='已投票';
				$color=self::$_frgAudit[2];
				break;
			}
		}
		eval(self::$_htmlFont);
		return $str;
	}

	/**
	 * 富 人国礼包卡使用状态
	 * @param int $status
	 */
	public static function getFrgLibaoCardStatus($status){
		$string=$status?'已使用':'未使用';
		$color=self::$_frgLibaoCardStatus[$status];
		eval(self::$_htmlFont);
		return $str;
	}

	/**
	 * 富 人国充值卡号使用状态
	 * @param int $status
	 */
	public static function getFrgPayCardStatus($status){
		return self::getFrgLibaoCardStatus($status);
	}

	public static function getFrgCardType($type){
		$string=$type?'套餐':'金币';
		$color=$type?'#148f94':'#0729ba';
		eval(self::$_htmlFont);
		return $str;
	}


}