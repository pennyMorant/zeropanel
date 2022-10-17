											<div class="card card-flush">
												<div class="card-header">
													<div class="card-title">
														<div class="fw-bolder text-dark fs-h3">
															{$trans->t('user.dashboard.doc')}
														</div>
													</div>
												
													<div class="card-toolbar">
														<ul class="nav">
															<li class="nav-item">
																<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary active fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_tab_pane_1_1">Android</a>
															</li>
															<li class="nav-item">
																<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_tab_pane_2_2">IOS</a>
															</li>
															<li class="nav-item">
																<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4 me-1" data-bs-toggle="tab" href="#kt_tab_pane_3_3">MACOS</a>
															</li>
															<li class="nav-item">
																<a class="nav-link btn btn-sm btn-color-muted btn-active btn-active-light-primary fw-bold px-4" data-bs-toggle="tab" href="#kt_tab_pane_4_4">Windows</a>
															</li>
														</ul>
													</div>
												</div>
												<div class="card-body pt-0">
													<div class="tab-content mt-5">
													<div class="tab-pane fade show active" id="kt_tab_pane_1_1">
														{foreach $zeroconfig['client_android'] as $c_w}
														<div class="d-flex align-items-center flex-wrap mb-8">
															<div class="symbol symbol-40px symbol-light me-5">
																<span class="symbol-label">
																	<img src="{$c_w['img']}" class="h-50 align-self-center" alt="">
																</span>
															</div>
															<div class="d-flex flex-column flex-grow-1 me-2">
																<a  class="fw-bold text-gray-800 fs-h5 mb-1">{$c_w['name']}</a>
															</div>
															<a href="{$c_w['url']}" class="badge badge-light-primary my-lg-0 my-2  fw-bolde fs-5">{$trans->t('user.dashboard.view')}</a>
														</div>
														{/foreach}
													</div>
													<div class="tab-pane fade" id="kt_tab_pane_2_2" role="tabpanel" aria-labelledby="kt_tab_pane_2_2">
														{foreach $zeroconfig['client_ios'] as $c_w}
														<div class="d-flex align-items-center flex-wrap mb-8">
															<div class="symbol symbol-40px symbol-light me-5">
																<span class="symbol-label">
																	<img src="{$c_w['img']}" class="h-50 align-self-center" alt="">
																</span>
															</div>
															<div class="d-flex flex-column flex-grow-1 mr-2">
																<a  class="fw-bold text-gray-800 fs-h5 mb-1">{$c_w['name']}</a>
															</div>
															<a href="{$c_w['url']}" class="badge badge-light-primary my-lg-0 my-2  fw-bolde fs-5">{$trans->t('user.dashboard.view')}</a>
														</div>
														{/foreach}
													</div>
													<div class="tab-pane fade" id="kt_tab_pane_3_3" role="tabpanel" aria-labelledby="kt_tab_pane_3_3">
														{foreach $zeroconfig['client_macos'] as $c_w}
														<div class="d-flex align-items-center flex-wrap mb-8">
															<div class="symbol symbol-40px symbol-light me-5">
																<span class="symbol-label">
																	<img src="{$c_w['img']}" class="h-50 align-self-center" alt="">
																</span>
															</div>
															<div class="d-flex flex-column flex-grow-1 mr-2">
																<a  class="fw-bold text-gray-800 fs-h5 mb-1">{$c_w['name']}</a>
															</div>
															<a href="{$c_w['url']}" class="badge badge-light-primary my-lg-0 my-2  fw-bolde fs-5">{$trans->t('user.dashboard.view')}</a>
														</div>
														{/foreach}
													</div>
													<div class="tab-pane fade" id="kt_tab_pane_4_4" role="tabpanel" aria-labelledby="kt_tab_pane_4_4">
														{foreach $zeroconfig['client_windows'] as $c_w}
														<div class="d-flex align-items-center flex-wrap mb-8">
															<div class="symbol symbol-40px symbol-light me-5">
																<span class="symbol-label">
																	<img src="{$c_w['img']}" class="h-50 align-self-center" alt="">
																</span>
															</div>
															<div class="d-flex flex-column flex-grow-1 mr-2">
																<a  class="fw-bold text-gray-800 fs-h5 mb-1">{$c_w['name']}</a>
															</div>
															<a href="{$c_w['url']}" class="badge badge-light-primary my-lg-0 my-2  fw-bolde fs-5">{$trans->t('user.dashboard.view')}</a>
														</div>
														{/foreach}
													</div>
													</div>
												</div>
											</div>