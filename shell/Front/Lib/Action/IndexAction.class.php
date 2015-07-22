<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends BaseAction {
	public function index() {
		
		$key='player_faq_top_bto';//大亨
		$listBto=S($key);
		if (!$listBto){
			$dao = M ( 'player_faq' );
			$listBto = $dao->order ( 'ratio desc' )->where('game_type_id=1 and lang_id=1')->limit ( 7 )->select ();
			S($key,$listBto,60*60);
		}

		$key='player_faq_top_bto2';//富人国
		$listBto2=S($key);
		if (!$listBto2){
			$dao = M ( 'player_faq' );
			$listBto2 = $dao->order ( 'ratio desc' )->where('game_type_id=2 and lang_id = 1')->limit ( 7 )->select ();
			S($key,$listBto2,60*60);
		}
		$this->assign("listBto",$listBto);
		$this->assign("listBto2",$listBto2);
		//游戏列表
		$this->assign("games",$this->getSysConfig("game_type"));
		
		$this->display ( "index" );
	}
	
	public function error(){
		$this->display('exception');
	}
	
}
?>