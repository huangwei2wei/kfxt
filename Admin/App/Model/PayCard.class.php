<?php
/**
 * 充值卡号
 * @author php-朱磊
 *
 */
class Model_PayCard extends Model {
	protected $_tableName = 'gold_card';
	
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
			$card.='bto2'.rand(0,99999);
			$addArr [] = array (
//				'amount' => $postArr ['amount'] ,
				'card'=>strtoupper(md5($card)),
				'create_time'=>CURRENT_TIME,
				'type'=>$postArr['type'],
				'batch_num'=>$postArr['batch_num'],
				'start_time'=>$startTime?$startTime:'',
				'end_time'=>$endTime?$endTime:'',
			);
		}
		if ($this->adds($addArr)){
			return array('msg'=>'生成成功','status'=>1,'href'=>1);
		}else {
			return array('msg'=>'生成失败','status'=>-2,'href'=>1);
		}
	}
}