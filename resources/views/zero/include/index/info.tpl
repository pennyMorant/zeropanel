											<div class="card card-flush h-md-100">
												<div class="card-header border-0">
													<div class="card-title text-dark fs-3 fw-bolder">														
														{$trans->t('user.dashboard.sub')}
													</div>
												</div>     
												<div class="card-body pt-0">
													<div class="d-flex align-items-center mb-9 bg-light-warning rounded p-5">
														<span class="svg-icon svg-icon-warning svg-icon-1 me-5">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
																<path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
																<path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
																<path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
															</svg>
														</span>
														<div class="d-flex flex-column flex-grow-1 mr-2">
															<a class="fs-lg fw-bolder text-gray-800 mb-1">
																<strong>
																	{if $user->class_expire != "1989-06-04 00:05:00" && $user->class >= 1}
																		<span class="counter">{$trans->t('user.account.time')}:&nbsp;{$class_left_days}&nbsp;{$trans->t('general.day')}</span>
																	{else if $user->class_expire != "1989-06-04 00:05:00" && $user->class <= 0}
																		<span class="counter">{$trans->t('user.account.time')}:&nbsp;{$trans->t('user.status.no_product')}</span>
																	{else}<span class="counter">长期有效用户</span>
																	{/if}
																</strong>
															</a>					   
															<span class="text-muted fw-semibold d-block">
																{if $user->class_expire != "1989-06-04 00:05:00" && $user->class >= 1}
																	{$trans->t('user.account.expired_date')}:&nbsp;{substr($user->class_expire, 0, 10)}
																{/if}
															</span>
														</div>
													</div>
													<div class="d-flex align-items-center bg-light-success rounded p-5 mb-9">
														<span class="svg-icon svg-icon-success svg-icon-1 me-5">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-soundwave" viewBox="0 0 16 16">
																<path fill-rule="evenodd" d="M8.5 2a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-1 0v-11a.5.5 0 0 1 .5-.5zm-2 2a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm4 0a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zm-6 1.5A.5.5 0 0 1 5 6v4a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm8 0a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm-10 1A.5.5 0 0 1 3 7v2a.5.5 0 0 1-1 0V7a.5.5 0 0 1 .5-.5zm12 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0V7a.5.5 0 0 1 .5-.5z"/>
															</svg>
														</span>
														<div class="d-flex flex-column flex-grow-1 mr-2">
															<a class="fw-bolder text-gray-800 fs-lg mb-1">
																<strong>{$trans->t('user.account.traffic')}:&nbsp;{$user->usedTraffic()}</strong> / <strong id="traffic">{$user->enableTraffic()}</strong>
															</a>
															<span class="text-muted fw-semibold d-block">
																{$trans->t('user.account.reset_date')}:&nbsp;{$user->productTrafficResetDate()}
															</span>
														</div>
													</div>
													<div class="d-flex align-items-center bg-light-danger rounded p-5 mb-9">
														<span class="svg-icon svg-icon-danger svg-icon-1 me-5">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-phone" viewBox="0 0 16 16">
																<path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h6zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H5z"/>
																<path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
															</svg>
														</span>
														<div class="d-flex flex-column flex-grow-1 mr-2">
															<a class="fw-bolder text-gray-800 fs-lg mb-1">
																<strong>
																	{$trans->t('user.account.online_ip')}:&nbsp;{$user->online_ip_count()} / {if $user->node_connector === 0 }{$trans->t('user.status.unlimited')}{else}{$user->node_connector}{/if}
																</strong>
															</a>
															<span class="text-muted fw-semibold d-block">
																{$trans->t('user.account.last_used')}:&nbsp;{$user->lastUseTime()}
															</span>
														</div>
													</div>
													<div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
														<span class="svg-icon svg-icon-info svg-icon-1 me-5">
															<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
																<path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
															</svg>
														</span>
														<div class="d-flex flex-column flex-grow-1 mr-2">
															<a class="fw-bolder text-gray-800 fs-lg mb-1">
																<strong>
																	{$trans->t('user.account.credit')}:&nbsp;{$user->money} $
																</strong>
															</a>
															<span class="text-muted fw-semibold d-block">
																{$trans->t('user.account.commission')}:&nbsp;{$user->commission} $
															</span>
														</div>
													</div>
												</div>
											</div>