<?php

/**
 * 客服公告
 * @author 陈成禧
 *
 */
class NoticeAction extends BaseAction{

	/**
	 * 客服公告首页
	 */
	public function index(){
		$dao = M("player_faq");
		$map['kind_id']=2;
		import('ORG.Util.Page');
		$count=$dao->where($map)->count();
		if($count>0){
			$page=new Page($count,12);
			$show=$page->show();
			$list = $dao->where($map)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();

			$this->assign("list",$list);
			$this->assign("show",$show);

		}
		$this->display("index");
	}

	public function show(){
		$id=$_GET['id'];
		$dao = M("player_faq");
		$vo=$dao->find($id);
		if($vo){
			$this->assign("vo",$vo);
			$this->display();
		}else{
			$this->error("记录不存在!");
		}
	}

	/**
	 * 客服介绍
	 */
	public function intro(){
		$this->display("intro");
	}

	/**
	 * 服务渠道
	 */
	public function channel(){
		$this->display("channel");
	}

	/**
	 * 骗术
	 */
	public function trick(){
		$this->display("trick");
	}
	/**
	 * 防沉迷系统简介
	 */
	public function addicted(){
		$this->display("addicted");
	}
	
	/**
	 * 实名注册与防沉迷
	 */
	public function realname(){
		$this->display("realname");
	}

	/**
	 * 防沉迷常见问题
	 */
	public function addictedqs(){
		$this->display("addictedqs");
	}
	/**
	 * 客服守则
	 */
	public function shouze(){
		$this->display("shouze");
	}
	/**
	 * 客服公告
	 */
	public function notice(){
		$this->display('notice');
	}

    public function pay() {
        $this->display('pay');
    }
    
    
}
?>