<!DOCTYPE html>
<html lang="en">
    <head>
	<title>{$config["appName"]} Order</title>
        <meta charset="UTF-8" />
        <meta name="renderer" content="webkit" />
        <meta name="description" content="Updates and statistics" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="format-detection" content="telephone=no,email=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="theme-color" content="#3B5598" />
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
        <meta http-equiv="expires" content="0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
        <link href="/theme/zero/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/theme/zero/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="/favicon.png" rel="shortcut icon">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon">
    </head>
    {include file ='include/index/menu.tpl'}
                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid mt-10">
                            <div id="kt_app_content" class="app-content flex-column-fluid">
                                <div id="kt_app_content_container" class="app-container container-xxl">
                                    <div class="d-flex flex-column flex-lg-row">
                                        <div class="flex-column flex-g-row-auto w-lg-250px w-xl-350px mb-10">
                                            <div class="card mb-5 mb-xl-8">
                                                <div class="card-body">
                                                    <div class="d-flex flex-center flex-column py-5">
                                                        <div class="symbol symbol-100px symbol-circle mb-7">
                                                            <img src="{$user->gravatar}" alt="image">
                                                        </div>
                                                        <a class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{$user->email}</a>
                                                        <div class="mb-9">
                                                            <div class="badge badge-lg badge-light-primary d-inline">LV-{$user->class}</div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-stack fs-4 py-3">
                                                        <div class="fw-bold rotate collapsible active" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="true" aria-controls="kt_user_view_details">{$trans->t('details')}
                                                            <span class="ms-2 rotate-180">
                                                                <span class="svg-icon svg-icon-3">
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"></path>
                                                                    </svg>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="separator"></div>
                                                    <div id="kt_user_view_details" class="collapse show" style>
                                                        <div class="pb-5 fs-6">
                                                            <div class="fw-bold mt-5">{$trans->t('users')} ID</div>
                                                            <div class="text-gray-600">ID-{$user->id}</div>
                                                            <div class="fw-bold mt-5">{$trans->t('email')}</div>
                                                            <div class="text-gray-600">
                                                                <a class="text-gray-600 text-hover-primary">{$user->email}</a>
                                                            </div>
                                                            <div class="fw-bold mt-5">{$trans->t('last signin date')}</div>
                                                            <div class="text-gray-600">{if $user->last_signin_time == null}{$trans->t('no record')}{else}{$user->last_signin_time}{/if}</div>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            </div>
                                            <div class="card mb-5 mb-xl-8">
                                                <div class="card-header border-0">
                                                    <div class="card-title">
                                                        <h3 class="fw-bold m-0">{$trans->t('notification')}</h3>
                                                    </div>
                                                </div>
                                                <div class="card-body pt-2">
                                                    <div class="py-2">
                                                        <div class="d-flex flex-stack">
                                                            <div class="d-flex">
                                                                <i class="bi bi-envelope text-primary fs-3hx me-6"></i>
                                                                <div class="d-flex flex-column">
                                                                    <a class="fs-5 text-dark text-hover-primary fw-bold">{$trans->t('email')}</a>
                                                                    <div class="fs-6 fw-semibold text-muted">{$trans->t('email notification')}</div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" {if $user->notify_type == 'email'}checked="checked"{/if} id="notify_email" type="checkbox" onclick="KTUsersEnableNotify('email')" >
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="separator separator-dashed my-5"></div>
                                                        <div class="d-flex flex-stack">
                                                            <div class="d-flex">
                                                                <i class="bi bi-telegram text-primary fs-3hx me-6"></i>
                                                                <div class="d-flex flex-column">
                                                                    <a class="fs-5 text-dark text-hover-primary fw-bold">Telegram</a>
                                                                    <div class="fs-6 fw-semibold text-muted">{$trans->t('telegram notification')}</div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end">
                                                                <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                                                    <input class="form-check-input" {if $user->notify_type == 'telegram'}checked="checked"{/if} id="notify_telegram" type="checkbox" {if $user->telegram_id == null || $config['enable_telegram_bot'] == false} disabled {/if}  onclick="KTUsersEnableNotify('telegram')" />
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>                                                   
                                                </div>
												<!--
                                                <div class="card-footer border-0 d-flex justify-content-center pt-0">
                                                    <button class="btn btn-sm btn-light-primary">Save changes</button>
                                                </div>
                                                -->
                                            </div>
                                        </div>
                                        <div class="flex-lg-row-fluid ms-lg-15">
                                            <div class="row">
                                                <div class="card card-flush mb-5">
                                                    <div class="card-header pt-5">
                                                        <div class="card-title d-flex flex-column">
                                                            <span class="fs-2hx fw-bold text-dark me-2 lh-1 ls-n2" id="wallet_total">{$user->commission + $user->money}$</span>
                                                            <span class="text-gray-400 pt-1 fw-semibold fs-6">{$trans->t('wallet')}</span>
                                                        </div>
                                                        <div class="card-toolbar">
                                                            <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                                <span class="svg0icon svg-icon-2">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="5" y="5" width="5" height="5" rx="1" fill="currentColor"></rect>
                                                                            <rect x="14" y="5" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                                                            <rect x="5" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                                                            <rect x="14" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </button>
                                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                                                <div class="menu-item px-3">
                                                                    <div class="menu-content fs-6 text-dark fw-bold px-3 py-4">{$trans->t('action')}</div>
                                                                </div>
                                                                <div class="separator mb-3 opacity-75"></div>
                                                                <div class="menu-item px-3">
                                                                    <a data-bs-toggle="modal" type="button" data-bs-target="#zero_user_add_credit_modal" class="menu-link px-3">{$trans->t('add credit')}</a>
                                                                </div>
                                                                <div class="menu-item px-3">
                                                                    <a data-bs-toggle="modal" type="button" data-bs-target="#zero_user_withdraw_modal" class="menu-link px-3">{$trans->t('commission withdrawal')}</a>
                                                                </div>
																<div class="menu-item px-3 mb-3">
                                                                    <a data-bs-toggle="modal" type="button" data-bs-target="#zero_user_withdraw_method_modal" class="menu-link px-3">{$trans->t('withdraw account')}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body d-flex flex-column pt-6">
                                                        <div class="row align-items-center mx-0 w-100 flex-column flex-sm-row">
                                                            <div class="col-5">
																<div class="d-flex flex-column align-items-center me-7 me-xxl-10">
																	<div id="zero_user_wallet_chart" class="max-auto" style="min-height: 150px;"></div>
																</div>
                                                            </div>
                                                            <div class="col-7">
																<div class="d-flex flex-column content-justify-center flex-grow-1">
																	<div class="d-flex fs-6 fw-semibold align-items-center">
																		<div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
																		<div class="fs-6 fw-bold text-gray-400 flex-shrink-0">{$trans->t('credit')}</div>
																		<div class="separator separator-dashed min-w-10px flex-grow-1 mx-2"></div>
																		<div class="ms-auto fw-bolder text-gray-700 text-end" id="user_balance">{trim($user->money)}</div>
																	</div>
																	<div class="d-flex fs-6 fw-semibold align-items-center my-1">
																		<div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
																		<div class="fs-6 fw-bold text-gray-400 flex-shrink-0">{$trans->t('commission')}</div>
																		<div class="separator separator-dashed min-w-10px flex-grow-1 mx-2"></div>
																		<div class="ms-auto fw-bolder text-gray-700 text-end" id="user_commission">{trim($user->commission)}</div>
																	</div>
																</div>
                                                            </div>
                                                        </div>
                                                    </div>
												</div>
                                                <div class="card pt-4 mb-6 mb-xl-9">
                                                    <div class="card-header border-0">
                                                        <div class="card-title">
                                                            <h2>{$trans->t('profile')}</h2>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pt-0 pb-5">
                                                        <div class="table-responsive">
                                                            <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                                                <tbody class="fs-6 fw-semibold text-gray-600">
                                                                    <tr>
                                                                        <td>{$trans->t('email')}</td>
                                                                        <td>{$user->email}</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#zero_modal_user_update_email">
                                                                                
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                                                                    </svg>
                                                                                </span>
                                                                                
																			</button>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>{$trans->t('passwd')}</td>
                                                                        <td>******</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="modal" data-bs-target="#zero_modal_user_update_password">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </td>
																	</tr>
                                                                    <tr>
                                                                        <td>Shadwosocks {$trans->t('passwd')}</td>
                                                                        <td>{$user->passwd}</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="tooltip" data-bs-title="click reset" onclick="KTUsersResetPasswd()">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                                                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                                                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </td>
																	</tr>
                                                                    <tr>
                                                                        <td>VMESS/TROJAN UUID</td>
                                                                        <td>{$user->uuid}</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="tooltip" data-bs-title="click reset" onclick="KTUsersResetUUID()">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                                                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                                                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </td>
																	</tr>
                                                                    <tr>
                                                                        <td>{$trans->t('subscription token')}</td>
                                                                        <td>{$sub_token}</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="tooltip" data-bs-title="click reset" onclick="KTUsersResetSubLink()">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
                                                                                        <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                                                                        <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </td>
																	</tr>
                                                                    <tr>
                                                                        <td>Telegram</td>
                                                                        <td>{if $user->telegram_id == null}{$trans->t('unbound')} Telegram{else}{$user->telegram_id}{/if}</td>
                                                                        <td class="text-end">
                                                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-bs-toggle="tooltip" data-bs-title="绑定或者解绑">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-diamond" viewBox="0 0 16 16">
                                                                                        <path d="M7.987 16a1.526 1.526 0 0 1-1.07-.448L.45 9.082a1.531 1.531 0 0 1 0-2.165L6.917.45a1.531 1.531 0 0 1 2.166 0l6.469 6.468A1.526 1.526 0 0 1 16 8.013a1.526 1.526 0 0 1-.448 1.07l-6.47 6.469A1.526 1.526 0 0 1 7.988 16zM7.639 1.17 4.766 4.044 8 7.278l3.234-3.234L8.361 1.17a.51.51 0 0 0-.722 0zM8.722 8l3.234 3.234 2.873-2.873c.2-.2.2-.523 0-.722l-2.873-2.873L8.722 8zM8 8.722l-3.234 3.234 2.873 2.873c.2.2.523.2.722 0l2.873-2.873L8 8.722zM7.278 8 4.044 4.766 1.17 7.639a.511.511 0 0 0 0 .722l2.874 2.873L7.278 8z"/>
                                                                                    </svg>
                                                                                </span>
                                                                            </button>
                                                                        </td>
																	</tr>
                                                                    <tr>
                                                                        <td>{$trans->t('signup date')}</td>
                                                                        <td>{$user->signup_date}</td>
                                                                        
																	</tr>
                                                                    
                                                                    <tr>
                                                                        <td>{$trans->t('membership expiration date')}</td>
                                                                        <td>{if $user->class <= 0}No Product{else}{$user->class_expire}{/if}</td>
                                                                        
																	</tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="app_footer py-4 d-flex flex-lg-column" id="kt_app_footer">
                            <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                                <div class="text-dark-75 order-2 order-md-1">
                                    &copy;<script>document.write(new Date().getFullYear());</script>,&nbsp;<a>{$config["appName"]},&nbsp;Inc.&nbsp;All rights reserved.</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<div class="modal fade" id="zero_modal_user_update_email" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="fw-bold">{$trans->t('change email')}</h2>
			</div>
			<div class="modal-body scrolly-y mx-5 mx-xl-15 my-7">
				<form class="form" action="#" id="zero_modal_user_update_email_form">
					<div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
					    <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
								<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
								<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
							</svg>
						</span>
						<div class="d-felx flex-stack flex-grow-1">
						    <div class="fw-semibold">
							    <div class="fs-6 text-gray-700">邮箱是本网站唯一凭证，请妥善保管帐号。请勿设置虚假邮箱帐号</div>
							</div>
						</div>
					</div>
				    <div class="fv-row mb-7">
					    <label class="fs-6 fw-semibold form-label mb-2">
							<span class="required">{$trans->t('email')}</span>
						</label>
						<input class="form-control form-control-solid" placeholder="" name="profile_email" value="" id="profile_email" />
					</div>
				    <div class="text-center pt-15">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">{$trans->t('submit')}</span>
							<span class="indicator-progress">{$trans->t('please wait')}
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="zero_modal_user_update_password" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-650px">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="fw-bold">{$trans->t('change passwd')}</h2>
			</div>
			<div class="modal-body scrolly-y mx-5 mx-xl-15 my-7">
				<form id="zero_modal_user_update_password_form" class="form" action="#">
					<div class="fv-row mb-10">
						<label class="required form-label fs-6 mb-2">Current Password</label>
						<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="current_password" name="current_password" autocomplete="off" />
					</div>
					<div class="mb-10 fv-row" data-kt-password-meter="true">
						<div class="mb-1">
							<label class="form-label fw-semibold fs-6 mb-2">New Password</label>
							<div class="position-relative mb-3">
								<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="new_password" name="new_password" autocomplete="off" />
								<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
									<i class="bi bi-eye-slash fs-2"></i>
									<i class="bi bi-eye fs-2 d-none"></i>
								</span>
							</div>
							<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
								<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
							</div>
						</div>
						<div class="text-muted">Use 8 or more characters with a mix of letters, numbers & symbols.</div>
					</div>
					<div class="fv-row mb-10">
						<label class="form-label fw-semibold fs-6 mb-2">Confirm New Password</label>
						<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" id="confirm_password" name="confirm_password" autocomplete="off" />
					</div>
					<div class="text-center pt-15">
						<button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">{$trans->t('discard')}</button>
						<button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
							<span class="indicator-label">{$trans->t('submit')}</span>
							<span class="indicator-progress">{$trans->t('please wait')}
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="zero_user_withdraw_method_modal" tabindex="-1" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{$trans->t('withdraw account')}</h4>
            </div>
            <div class="modal-body">
                <!--<p class="text-warning">{$trans->t('user.billing.withdraw_alert')}</p>-->
                <form>
					<div class="mb-3">
						<label class="col-form-label fw-bold" for="recipient-name">{$trans->t('type')}:</label>
						<select class="form-select" id="withdraw_account_type" data-style="btn-primary">
							{foreach json_decode($config['withdraw_method'], true) as $acctype}
							<option value="{$acctype}" {if isset($user->withdraw_account) && $acctype == $user->withdraw_account_type}selected="selected"{/if}>{$acctype}</option>
							{/foreach}
						</select>
                </form>
                <form>
					<div class="mb-3">
						<label class="col-form-label fw-bold" for="recipient-name">{$trans->t('account')}:</label>						
						{if isset($user->withdraw_account) && $user->withdraw_account_type}
						<input type="text" class="form-control" value="{$user->withdraw_account}" id="withdraw_account_value"/>
						{else}
						<input type="text" class="form-control" value="" id="withdraw_account_value"/>
						{/if}
					</div>
                </form>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                <button type="button" class="btn btn-primary" onclick="updateWithdrawAccount()">{$trans->t('submit')}</button>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="zero_user_withdraw_modal" tabindex="-1" data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{$trans->t('commission withdrawal')}</h4>
            </div>
            <div class="modal-body">
				<label class="col-form-label fw-bold" for="recipient-name">{$trans->t('amount')}:</label>
                <form class="mb-3">
                    <input type="number" class="form-control" placeholder="{$trans->t('amount')}" id="commission_amount"/>
                </form>
				<label class="col-form-label fw-bold" for="recipient-name">{$trans->t('withdrawal method')}:</label>
                <ul class="nav nav-pills row nav-primary mb-3" role="tablist">
                    <li class="nav-item d-flex col flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0" id="taketype">
                        <a class="btn btn-outline btn-outline-dashed btn-active-light-primary text-start d-flex flex-grow-1 flex-column align-items-center active" data-bs-toggle="pill" data-type="1">
                            <span class="py-2 w-auto">
								<i class="bi bi-wallet-fill text-primary fs-2hx"></i>
                            </span>
                            <span class="nav-text fs-6 py-2 text-center">{$trans->t('withdraw to credit')}<br/>
                            <small>{$trans->t('no less than')}: 0</small></span>
                        </a>
                    </li>
                    {if $config['enable_withdraw'] == true}
                    <li class="nav-item d-flex col flex-grow-1 flex-shrink-0 mr-3 mb-3 mb-lg-0" id="taketype">
                        <a class="btn btn-outline btn-outline-dashed btn-active-light-primary text-start d-flex flex-grow-1 flex-column align-items-center" data-bs-toggle="pill" data-type="2">
                            <span class="nav-icon py-2 w-auto">
								<i class="bi bi-currency-exchange fs-2hx text-primary"></i>
                            </span>
                            <span class="nav-text fs-6 py-2 text-center">{$trans->t('user.billing.withdraw_to_usdt')}<br />
                            <small>{$trans->t('user.billing.less_than')}: {$config['withdraw_less_amount']}</small></span>
                        </a>
                    </li>
                    {/if}
                </ul>
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                <button type="button" class="btn btn-primary" type="button" onclick="withdrawCommission()">{$trans->t('submit')}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="zero_user_add_credit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{$trans->t('add credit')}</h4>
            </div>
            <div class="modal-body">
				<label class="col-form-label fw-bold" for="recipient-name">{$trans->t('amount')}:</label>		
				<div class="form-group">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="{$trans->t('amount')}" id="add_credit_amount">
					</div>
				</div>
                
            </div>
            <div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">{$trans->t('discard')}</button>
                <button type="submit" class="btn btn-primary" data-kt-users-action="submit" onclick="KTUsersCreateOrder(2, '', '')">
					<span class="indicator-label">{$trans->t('submit')}</span>			
					<span class="indicator-progress">{$trans->t('please wait')}
					<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
				</button>
            </div>
        </div>
    </div>
</div>
{include file='include/global/scripts.tpl'}
{include file='include/index/news.tpl'}
    </body>
</html> 