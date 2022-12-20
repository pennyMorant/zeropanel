<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
    <meta name="theme-color" content="#4285f4">
    <title>{$config['appName']}</title>
    <!-- css -->
    <link href="/theme/material/css/base.min.css" rel="stylesheet">
    <link href="/theme/material/css/project.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/material-design-lite@1.3.0/dist/material.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/DataTables/DataTables@1.10.19/media/css/dataTables.material.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.css" rel="stylesheet" type="text/css">
    <!-- js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsoneditor/dist/jsoneditor.min.js"></script>
    <!-- favicon -->
    <!-- ... -->
    <style>
        body {
            position: relative;
        }

        
        
        .table-responsive {
            background: white;
        }

        .dropdown-menu.dropdown-menu-right a {
            color: #212121;
        }

        a[href='#ui_menu'] {
            color: #212121;
        }
        
        #custom_config {
            height: 500px;
        }
        
		.label {
		  padding: 0;
		  margin: 0;
		  display: -webkit-inline-box;
		  display: -ms-inline-flexbox;
		  display: inline-flex;
		  -webkit-box-pack: center;
		  -ms-flex-pack: center;
		  justify-content: center;
		  -webkit-box-align: center;
		  -ms-flex-align: center;
		  align-items: center;
		  height: 20px;
		  width: 20px;
		  border-radius: 50%;
		  font-size: 0.8rem;
		  background-color: #ECF0F3;
		  color: #464E5F;
		  font-weight: 400;
		  height: 20px;
		  width: 20px;
		  font-size: 0.8rem; }
		  .label.label-primary {
			color: #FFFFFF;
			background-color: #3B5998; }
		  .label.label-outline-primary {
			background-color: transparent;
			color: #3B5998;
			border: 1px solid #3B5998; }
			.label.label-outline-primary.label-outline-2x {
			  border: 2px solid #3B5998; }
		  .label.label-light-primary {
			color: #3B5998;
			background-color: #E1E9FF; }
		  .label.label-secondary {
			color: #464E5F;
			background-color: #E5EAEE; }
		  .label.label-outline-secondary {
			background-color: transparent;
			color: #464E5F;
			border: 1px solid #E5EAEE; }
			.label.label-outline-secondary.label-outline-2x {
			  border: 2px solid #E5EAEE; }
		  .label.label-light-secondary {
			color: #E5EAEE;
			background-color: #ECF0F3; }
		  .label.label-success {
			color: #ffffff;
			background-color: #1BC5BD; }
		  .label.label-outline-success {
			background-color: transparent;
			color: #1BC5BD;
			border: 1px solid #1BC5BD; }
			.label.label-outline-success.label-outline-2x {
			  border: 2px solid #1BC5BD; }
		  .label.label-light-success {
			color: #1BC5BD;
			background-color: #C9F7F5; }
		  .label.label-info {
			color: #ffffff;
			background-color: #8950FC; }
		  .label.label-outline-info {
			background-color: transparent;
			color: #8950FC;
			border: 1px solid #8950FC; }
			.label.label-outline-info.label-outline-2x {
			  border: 2px solid #8950FC; }
		  .label.label-light-info {
			color: #8950FC;
			background-color: #EEE5FF; }
		  .label.label-warning {
			color: #ffffff;
			background-color: #FFA800; }
		  .label.label-outline-warning {
			background-color: transparent;
			color: #FFA800;
			border: 1px solid #FFA800; }
			.label.label-outline-warning.label-outline-2x {
			  border: 2px solid #FFA800; }
		  .label.label-light-warning {
			color: #FFA800;
			background-color: #FFF4DE; }
		  .label.label-danger {
			color: #ffffff;
			background-color: #F64E60; }
		  .label.label-outline-danger {
			background-color: transparent;
			color: #F64E60;
			border: 1px solid #F64E60; }
			.label.label-outline-danger.label-outline-2x {
			  border: 2px solid #F64E60; }
		  .label.label-light-danger {
			color: #F64E60;
			background-color: #FFE2E5; }
		  .label.label-light {
			color: #80808F;
			background-color: #F3F6F9; }
		  .label.label-outline-light {
			background-color: transparent;
			color: #464E5F;
			border: 1px solid #F3F6F9; }
			.label.label-outline-light.label-outline-2x {
			  border: 2px solid #F3F6F9; }
		  .label.label-light-light {
			color: #F3F6F9;
			background-color: #F3F6F9; }
		  .label.label-dark {
			color: #ffffff;
			background-color: #212121; }
		  .label.label-outline-dark {
			background-color: transparent;
			color: #212121;
			border: 1px solid #212121; }
			.label.label-outline-dark.label-outline-2x {
			  border: 2px solid #212121; }
		  .label.label-light-dark {
			color: #212121;
			background-color: #D6D6E0; }
		  .label.label-white {
			color: #464E5F;
			background-color: #ffffff; }
		  .label.label-outline-white {
			background-color: transparent;
			color: #ffffff;
			border: 1px solid #ffffff; }
			.label.label-outline-white.label-outline-2x {
			  border: 2px solid #ffffff; }
		  .label.label-light-white {
			color: #ffffff;
			background-color: #ffffff; }
		  .label.label-dark-75 {
			color: #ffffff;
			background-color: #464E5F; }
		  .label.label-outline-dark-75 {
			background-color: transparent;
			color: #464E5F;
			border: 1px solid #464E5F; }
			.label.label-outline-dark-75.label-outline-2x {
			  border: 2px solid #464E5F; }
		  .label.label-light-dark-75 {
			color: #464E5F;
			background-color: #E5EAEE; }
		  .label.label-inline {
			width: auto;
			padding: 0.15rem 0.5rem;
			border-radius: 0.42rem; }
			.label.label-inline.label-md {
			  padding: 0.8rem 0.6rem; }
			.label.label-inline.label-lg {
			  padding: 0.9rem 0.75rem; }
			.label.label-inline.label-xl {
			  padding: 1rem 0.85rem; }
		  .label.label-pill {
			border-radius: 2rem; }
		  .label.label-rounded {
			border-radius: 0.42rem; }
		  .label.label-square {
			border-radius: 0; }
		  .label.label-dot {
			display: inline-block;
			font-size: 0 !important;
			vertical-align: middle;
			text-align: center; }
		  .label.label-inline {
			width: auto; }
		  .label.label-dot {
			line-height: 6px;
			min-height: 6px;
			min-width: 6px;
			height: 6px;
			width: 6px; }
		  .label.label-sm {
			height: 16px;
			width: 16px;
			font-size: 0.75rem; }
			.label.label-sm.label-inline {
			  width: auto; }
			.label.label-sm.label-dot {
			  line-height: 4px;
			  min-height: 4px;
			  min-width: 4px;
			  height: 4px;
			  width: 4px; }
		  .label.label-lg {
			height: 24px;
			width: 24px;
			font-size: 0.9rem; }
			.label.label-lg.label-inline {
			  width: auto; }
			.label.label-lg.label-dot {
			  line-height: 8px;
			  min-height: 8px;
			  min-width: 8px;
			  height: 8px;
			  width: 8px; }
		  .label.label-xl {
			height: 28px;
			width: 28px;
			font-size: 1rem; }
			.label.label-xl.label-inline {
			  width: auto; }
			.label.label-xl.label-dot {
			  line-height: 10px;
			  min-height: 10px;
			  min-width: 10px;
			  height: 10px;
			  width: 10px; }
    </style>
</head>

<body class="page-brand">
<header class="header header-red header-transparent header-waterfall ui-header">
    <ul class="nav nav-list pull-left">
        <div>
            <a data-toggle="menu" href="#ui_menu">
                <span class="icon icon-lg">menu</span>
            </a>
        </div>
    </ul>

    <ul class="nav nav-list pull-right">
        <div class="dropdown margin-right">
            <a class="dropdown-toggle padding-left-no padding-right-no" data-toggle="dropdown">
                {if $user->isLogin}
                <span class="access-hide">{$user->name}</span>
                <span class="avatar avatar-sm"><img src="{$user->gravatar}"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a class="waves-attach" href="/user/"><span class="icon icon-lg margin-right">account_box</span>用户中心</a>
                </li>
                <li>
                    <a class="waves-attach" href="/user/logout"><span
                                class="icon icon-lg margin-right">exit_to_app</span>登出</a>
                </li>
            </ul>
            {else}
            <span class="access-hide">未登录</span>
            <span class="avatar avatar-sm"><img src="/theme/material/images/users/avatar-001.jpg"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>
                    <a class="waves-attach" href="/auth/login"><span
                                class="icon icon-lg margin-right">account_box</span>登录</a>
                </li>
                <li>
                    <a class="waves-attach" href="/auth/register"><span
                                class="icon icon-lg margin-right">pregnant_woman</span>注册</a>
                </li>
            </ul>
            {/if}

        </div>
    </ul>
</header>
<nav aria-hidden="true" class="menu menu-left nav-drawer nav-drawer-md" id="ui_menu" tabindex="-1">
    <div class="menu-scroll">
        <div class="menu-content">
            <a class="menu-logo" href="/"><i class="icon icon-lg">person_pin</i>&nbsp;管理面板</a>
            <ul class="nav">
                <li>
                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_me">我的</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_me">
                        <li><a href="/admin"><i class="icon icon-lg">business_center</i>&nbsp;系统概览</a></li>
                        <li><a href="/admin/announcement"><i class="icon icon-lg">announcement</i>&nbsp;公告管理</a></li>
                        <li><a href="/admin/ticket"><i class="icon icon-lg">question_answer</i>&nbsp;工单管理</a></li>
                    </ul>

                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_node">节点</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_node">
                        <li><a href="/admin/node"><i class="icon icon-lg">router</i>&nbsp;节点列表</a></li>
                        <li><a href="/admin/log/traffic"><i class="icon icon-lg">traffic</i>&nbsp;流量记录</a></li>
                    </ul>

                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_user">用户</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_user">
                        <li><a href="/admin/user"><i class="icon icon-lg">supervisor_account</i>&nbsp;用户列表</a></li>
                        <li><a href="/admin/invite"><i class="icon icon-lg">loyalty</i>&nbsp;邀请与返利</a></li>
                        <li><a href="/admin/subscribe"><i class="icon icon-lg">dialer_sip</i>&nbsp;订阅记录</a></li>
                        <li><a href="/admin/login"><i class="icon icon-lg">text_fields</i>&nbsp;登录记录</a></li>
                        <li><a href="/admin/alive"><i class="icon icon-lg">important_devices</i>&nbsp;在线IP</a></li>
                    </ul>

                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_agent">代理</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_agent">
                        <li><a href="/admin/agent/take_log"><i class="icon icon-lg">library_books</i>&nbsp;提现处理</a></li>
                    </ul>

                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_config">配置</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_config">
                        <li><a href="/admin/setting"><i class="icon icon-lg">settings</i>&nbsp;设置中心</a></li>
                    </ul>
                    <a class="waves-attach" data-toggle="collapse" href="#ui_menu_trade">交易</a>
                    <ul class="menu-collapse collapse in" id="ui_menu_trade">
                        <li><a href="/admin/order"><i class="icon icon-lg">list</i>&nbsp;订单记录</a></li>
                        <li><a href="/admin/shop"><i class="icon icon-lg">shop</i>&nbsp;商品</a></li>
                        <li><a href="/admin/coupon"><i class="icon icon-lg">card_giftcard</i>&nbsp;优惠码</a></li>
                    </ul>
                <li><a href="/user"><i class="icon icon-lg">person</i>&nbsp;用户中心</a></li>
            </ul>
        </div>
    </div>
</nav>
