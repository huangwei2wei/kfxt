<?php
return array (
  1 => 
  array (
    'Id' => '1',
    'game_type_id' => '1',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'chongzhishijian',
        'description' => '',
        'title' => '充值时间',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'chongzhijine',
        'description' => '',
        'title' => '充值金额',
      ),
      3 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'chongzhileixing',
        'description' => '',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
        'title' => '充值类型',
      ),
      4 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'youxichongzhidingdanhao',
        'description' => '',
        'title' => '游戏订单号',
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'jiaoyidingdanhao',
        'title' => '银行交易订单号',
        'description' => '网银充值时须填写',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'jiaoyiliushuihao',
        'title' => '银行交易流水号',
        'description' => '网银充值时须填写',
      ),
    ),
  ),
  2 => 
  array (
    'Id' => '2',
    'game_type_id' => '1',
    'title' => '道具问题',
    'title_2' => 'Item issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'description' => '',
        'title' => '出现异常时间',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'daojumingcheng',
        'description' => '',
        'title' => '具体道具名称',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'yichangshuliang',
        'description' => '',
        'title' => '道具异常数量',
      ),
      4 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'daojulaiyuan',
        'description' => '',
        'title' => '异常道具来源',
      ),
    ),
  ),
  3 => 
  array (
    'Id' => '3',
    'game_type_id' => '1',
    'title' => '员工问题',
    'title_2' => 'Employee issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'description' => '',
        'title' => '出现异常时间',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'mingziyushuxing',
        'title' => '具体员工名字',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'yuangongshuxing',
        'title' => '员工属性',
      ),
    ),
  ),
  4 => 
  array (
    'Id' => '4',
    'game_type_id' => '1',
    'title' => '活动问题',
    'title_2' => 'Event issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'huodongmingcheng',
        'title' => '具体活动名称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'title' => '出现异常时间',
      ),
    ),
  ),
  5 => 
  array (
    'Id' => '5',
    'game_type_id' => '1',
    'title' => 'G币问题',
    'title_2' => 'G issue ',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'title' => '出现异常时间',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshuliang',
        'title' => 'G币异常数量',
      ),
    ),
  ),
  13 => 
  array (
    'Id' => '13',
    'game_type_id' => '1',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'title' => '出现异常时间',
      ),
    ),
  ),
  14 => 
  array (
    'Id' => '14',
    'game_type_id' => '1',
    'title' => '游戏账号问题',
    'title_2' => 'Account issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'title' => '出现异常时间',
        'description' => '',
      ),
    ),
  ),
  15 => 
  array (
    'Id' => '15',
    'game_type_id' => '1',
    'title' => '建议/投诉举报',
    'title_2' => 'Suggestion/Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'yichangshijian',
        'title' => '出现异常时间',
      ),
    ),
  ),
  29 => 
  array (
    'Id' => '29',
    'game_type_id' => '1',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  6 => 
  array (
    'Id' => '6',
    'game_type_id' => '2',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  7 => 
  array (
    'Id' => '7',
    'game_type_id' => '2',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  8 => 
  array (
    'Id' => '8',
    'game_type_id' => '2',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  9 => 
  array (
    'Id' => '9',
    'game_type_id' => '2',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  10 => 
  array (
    'Id' => '10',
    'game_type_id' => '2',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  11 => 
  array (
    'Id' => '11',
    'game_type_id' => '2',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  12 => 
  array (
    'Id' => '12',
    'game_type_id' => '2',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  16 => 
  array (
    'Id' => '16',
    'game_type_id' => '2',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  17 => 
  array (
    'Id' => '17',
    'game_type_id' => '2',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  20 => 
  array (
    'Id' => '20',
    'game_type_id' => '3',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => false,
  ),
  21 => 
  array (
    'Id' => '21',
    'game_type_id' => '3',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => false,
  ),
  22 => 
  array (
    'Id' => '22',
    'game_type_id' => '3',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => false,
  ),
  23 => 
  array (
    'Id' => '23',
    'game_type_id' => '3',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => false,
  ),
  24 => 
  array (
    'Id' => '24',
    'game_type_id' => '3',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => false,
  ),
  25 => 
  array (
    'Id' => '25',
    'game_type_id' => '3',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => false,
  ),
  26 => 
  array (
    'Id' => '26',
    'game_type_id' => '3',
    'title' => '建议/投诉举报',
    'title_2' => 'Suggestion/Complaint',
    'form_table' => false,
  ),
  27 => 
  array (
    'Id' => '27',
    'game_type_id' => '3',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => false,
  ),
  28 => 
  array (
    'Id' => '28',
    'game_type_id' => '3',
    'title' => '其它问题',
    'title_2' => 'Other',
    'form_table' => false,
  ),
  30 => 
  array (
    'Id' => '30',
    'game_type_id' => '5',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  31 => 
  array (
    'Id' => '31',
    'game_type_id' => '5',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  32 => 
  array (
    'Id' => '32',
    'game_type_id' => '5',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  33 => 
  array (
    'Id' => '33',
    'game_type_id' => '5',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  34 => 
  array (
    'Id' => '34',
    'game_type_id' => '5',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  35 => 
  array (
    'Id' => '35',
    'game_type_id' => '5',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  36 => 
  array (
    'Id' => '36',
    'game_type_id' => '5',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  37 => 
  array (
    'Id' => '37',
    'game_type_id' => '5',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  38 => 
  array (
    'Id' => '38',
    'game_type_id' => '5',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  39 => 
  array (
    'Id' => '39',
    'game_type_id' => '6',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  40 => 
  array (
    'Id' => '40',
    'game_type_id' => '6',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  41 => 
  array (
    'Id' => '41',
    'game_type_id' => '6',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  42 => 
  array (
    'Id' => '42',
    'game_type_id' => '6',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  43 => 
  array (
    'Id' => '43',
    'game_type_id' => '6',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  44 => 
  array (
    'Id' => '44',
    'game_type_id' => '6',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  45 => 
  array (
    'Id' => '45',
    'game_type_id' => '6',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  46 => 
  array (
    'Id' => '46',
    'game_type_id' => '6',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  47 => 
  array (
    'Id' => '47',
    'game_type_id' => '6',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  48 => 
  array (
    'Id' => '48',
    'game_type_id' => '7',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => false,
  ),
  49 => 
  array (
    'Id' => '49',
    'game_type_id' => '7',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => false,
  ),
  50 => 
  array (
    'Id' => '50',
    'game_type_id' => '7',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => false,
  ),
  51 => 
  array (
    'Id' => '51',
    'game_type_id' => '7',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => false,
  ),
  52 => 
  array (
    'Id' => '52',
    'game_type_id' => '7',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => false,
  ),
  53 => 
  array (
    'Id' => '53',
    'game_type_id' => '7',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => false,
  ),
  54 => 
  array (
    'Id' => '54',
    'game_type_id' => '7',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => false,
  ),
  55 => 
  array (
    'Id' => '55',
    'game_type_id' => '7',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => false,
  ),
  56 => 
  array (
    'Id' => '56',
    'game_type_id' => '7',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => false,
  ),
  57 => 
  array (
    'Id' => '57',
    'game_type_id' => '8',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  58 => 
  array (
    'Id' => '58',
    'game_type_id' => '8',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  59 => 
  array (
    'Id' => '59',
    'game_type_id' => '8',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  60 => 
  array (
    'Id' => '60',
    'game_type_id' => '8',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  61 => 
  array (
    'Id' => '61',
    'game_type_id' => '8',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  62 => 
  array (
    'Id' => '62',
    'game_type_id' => '8',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  63 => 
  array (
    'Id' => '63',
    'game_type_id' => '8',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  64 => 
  array (
    'Id' => '64',
    'game_type_id' => '8',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  65 => 
  array (
    'Id' => '65',
    'game_type_id' => '8',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  66 => 
  array (
    'Id' => '66',
    'game_type_id' => '9',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  67 => 
  array (
    'Id' => '67',
    'game_type_id' => '9',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  68 => 
  array (
    'Id' => '68',
    'game_type_id' => '9',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  69 => 
  array (
    'Id' => '69',
    'game_type_id' => '9',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  70 => 
  array (
    'Id' => '70',
    'game_type_id' => '9',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  71 => 
  array (
    'Id' => '71',
    'game_type_id' => '9',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  72 => 
  array (
    'Id' => '72',
    'game_type_id' => '9',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  73 => 
  array (
    'Id' => '73',
    'game_type_id' => '9',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  74 => 
  array (
    'Id' => '74',
    'game_type_id' => '9',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  76 => 
  array (
    'Id' => '76',
    'game_type_id' => '10',
    'title' => '登录问题',
    'title_2' => 'Login issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  77 => 
  array (
    'Id' => '77',
    'game_type_id' => '10',
    'title' => '系统问题',
    'title_2' => 'System issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  78 => 
  array (
    'Id' => '78',
    'game_type_id' => '10',
    'title' => '充值问题',
    'title_2' => 'Recharge issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
      1 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'nickname',
        'title' => '昵称',
      ),
      2 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeamount',
        'title' => '充值金额',
        'description' => '',
      ),
      3 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargetime',
        'title' => '充值时间',
      ),
      4 => 
      array (
        'type' => 'select',
        'required' => true,
        'name' => 'rechargemethod',
        'title' => '充值方式',
        'options' => 
        array (
          0 => '银行卡',
          1 => '支付宝',
          2 => '快钱',
          3 => '财付通',
          4 => '易宝',
          5 => '神州行',
          6 => '手机短信',
          7 => '骏网卡',
          8 => '盛大卡',
          9 => '征途卡',
          10 => '电信',
          11 => '联通',
          12 => '固话充值',
        ),
      ),
      5 => 
      array (
        'type' => 'text',
        'required' => false,
        'name' => 'rechargeband',
        'title' => '充值银行',
        'description' => '如果使用银行卡充值,请填写.',
      ),
      6 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeaccount',
        'title' => '充值卡号/账号',
      ),
      7 => 
      array (
        'type' => 'text',
        'required' => true,
        'name' => 'rechargeordernumber',
        'title' => '充值订单号',
        'description' => '',
      ),
    ),
  ),
  79 => 
  array (
    'Id' => '79',
    'game_type_id' => '10',
    'title' => '游戏问题',
    'title_2' => 'Game issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  80 => 
  array (
    'Id' => '80',
    'game_type_id' => '10',
    'title' => '游戏建议',
    'title_2' => 'Game suggestion',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  81 => 
  array (
    'Id' => '81',
    'game_type_id' => '10',
    'title' => 'BUG问题',
    'title_2' => 'Bug',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  82 => 
  array (
    'Id' => '82',
    'game_type_id' => '10',
    'title' => '投诉举报',
    'title_2' => 'Complaint',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  83 => 
  array (
    'Id' => '83',
    'game_type_id' => '10',
    'title' => '账号和密码问题',
    'title_2' => 'Account & password issue',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => true,
        'name' => 'game_server_id',
      ),
    ),
  ),
  84 => 
  array (
    'Id' => '84',
    'game_type_id' => '10',
    'title' => '其他问题',
    'title_2' => 'Other',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  85 => 
  array (
    'Id' => '85',
    'game_type_id' => '13',
    'title' => '道具卡申请',
    'title_2' => '',
    'form_table' => 
    array (
      0 => 
      array (
        'type' => 'game_server_list',
        'required' => false,
        'name' => 'game_server_id',
      ),
    ),
  ),
  75 => 
  array (
    'Id' => '75',
    'game_type_id' => '99',
    'title' => '防沉迷投诉',
    'title_2' => 'prevent from gaming indulgence',
    'form_table' => false,
  ),
)
?>