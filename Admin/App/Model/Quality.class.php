<?php
class Model_Quality extends Model {
	protected $_tableName='quality';
	
	/**
	 * 根据qa_id来查找质检详细
	 * @param int $id
	 */
	public function findByQaId($id){
		return $this->select("select * from {$this->tName()} where qa_id={$id}",1);
	}
	
	/**
	 * 获取分数列表
	 */
	public function getScorce(){
		return array(-3=>-3,-2=>-2,-1=>-1,0=>0,1=>1,2=>2,3=>3);
	}
	
	public function getCountSelect($select){
		$type	=	array(
				'count'	=>	0,//总数
				'1'		=>	0,//'对',
				'2'		=>	0,//'加分',
				'-1'	=>	0,//'错字',
				'-2'	=>	0,//'内容不完整',
				'-3'	=>	0,//'内容错误',
				'-4'	=>	0,//'建议优化',
				'-5'	=>	0,//'超时',
				'-6'	=>	0,//'其它',
		);
		$str	=	'select*from '.$this->tName().' where quality_user_id in('.implode(',',$select['userid']).')';
		if($select['start_date']){
			$str	.=	' and quality_time>'.$select['start_date'];
		}
		if($select['end_date']){
			$str	.=	' and quality_time<'.$select['end_date'];
		}
		foreach($this->select($str) as $_msg){
			if($_msg['option_id']!==''){
				$type[$_msg['option_id']]++;
				$type['count']++;
			}
		}
		return $type;
	}
	
	
}