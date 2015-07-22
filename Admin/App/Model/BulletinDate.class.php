<?php
class Model_BulletinDate extends Model {
	protected $_tableName='bulletin_date';

	/**
	 * 增加新的公告到今天的日期里面
	 * @param int $id 公告的Id
	 */
	public function addBulletin($id){
		$date=date('Ymd',CURRENT_TIME);
		$dataList=$this->select("select * from {$this->tName()} where date={$date}",1);
		if ($dataList){//表示有记录
			$dataList['child_ids']=unserialize($dataList['child_ids']);
			if (array_search($id,$dataList['child_ids'])===false)array_push($dataList['child_ids'],$id);//如果未找到这个id,就加入这个id
			$dataList['child_ids']=serialize($dataList['child_ids']);
			return $this->update(array('child_ids'=>$dataList['child_ids']),"date={$date}");
		}else {//新建记录
			$addArr=array();
			$addArr['date']=$date;
			$addArr['child_ids']=array($id);
			$addArr['child_ids']=serialize($addArr['child_ids']);
			return $this->add($addArr);
		}
	}
}