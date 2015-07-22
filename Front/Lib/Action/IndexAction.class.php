<?php
/**
 * 主页模块
 */
class IndexAction extends BaseAction {
	
	public function _initialize(){
		parent::_initialize();
		$this->mainTpl = 'common:main';	//指定使用主模板
		if (is_callable(array(&$this,'left1'))){
			$this->left1();
		}
	}
	
	public function index() {
		$topFaqlist = D('PlayerFaq')->getTopFAQ();
		$this->assign("topFaqlist",$topFaqlist);
		$gameLists = D('QuestionTypes')->getGameList();
		$this->assign('games',$gameLists);
		$this->display ('index');
	}
	
	//已废弃
	public function questionlist(){
		$dao = M ( 'player_faq' );
		$listBto = $dao->order ( 'ratio desc' )
		->where('game_type_id='.$_GET["gametype"].' and lang_id=1')
		->limit ( 14 )->select ();
		$game	=	$this->getSysConfig("game_type");
		foreach($listBto as $value){
			echo '<li>sadfsa<span>'.$game[$value["game_type_id"]]["name"].'</span>
<a href="/index.php?s=/Faq/show/game_type_id/'.$value['game_type_id'].'/Id/'.$value['Id'].'" title="'.$value['question'].'">'.$value['question'].'</a>
</li>';
		}
	}
	
	public function error(){
		$this->display('exception');
	}
	
}
?>