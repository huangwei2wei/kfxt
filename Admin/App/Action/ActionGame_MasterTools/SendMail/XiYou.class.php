<?php
Tools::import('Action_ActionBase');

class ActionGame_MasterTools_SendMail_XiYou extends Action_ActionBase
{

    private $_userType = array();

    public function _init()
    {
        $this->_userType = Tools::gameConfig('userType', $this->_gameObject->_gameId);
        $this->_assign['userType'] = $this->_userType;
    }

    public function getPostData($post = null)
    {
        $goods = '';
        if ($_POST['itemNum']) {
            foreach ($_POST['itemNum'] as $k => $v) {
                $goods .= $k . '_' . $v . '&';
            }
        }
        if (strlen($goods) > 0) {
            $goods = substr($goods, 0, strlen($goods) - 1);
        }
        $postData = array(
            'userType' => trim($_POST['userType']),
            'user' => str_replace('，', ',', trim($_POST['user'])),
            'title' => trim($_POST['title']),
            'content' => trim($_POST['content']),
            'goods' => $goods
        );
        if ($post && is_array($post)) {
            $postData = array_merge($postData, $post);
        }
        return $postData;
    }

    public function main($UrlAppend = NULL, $get = NULL, $post = NULL)
    {
        if (! $_REQUEST['server_id']) {
            return $this->_assign;
        }
        if ($this->_isAjax()) {
            $postData = $this->getPostData($post);
            $postData = $this->_gameObject->getPostData($postData);
            $data = $this->getResult($UrlAppend, $get, $postData);
            if ($data['status'] == 1) {
                $this->ajaxReturn(array(
                    'status' => 1,
                    'info' => '发送成功！',
                    'data' => null
                ));
            } else {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'info' => '发送失败！',
                    'data' => null
                ));
            }
        }
        $playerIds = '';
        if ($_POST['playerIds']) {
            $playerIds = implode(',', $_POST['playerIds']);
        }
        $this->_assign['playerIds'] = $playerIds;
        $this->_assign['items'] = $this->partner('Item');
        $this->_assign['tplServerSelect'] = "BaseZp/MultiServerSelect.html";
        return $this->_assign;
    }

    private function _urlNotice()
    {
        $query = array(
            'zp' => PACKAGE,
            '__game_id' => $this->_gameObject->_gameId,
            'server_id' => $_REQUEST['server_id']
        );
        return Tools::url(CONTROL, 'Notice', $query);
    }
}