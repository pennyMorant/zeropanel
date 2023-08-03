
                                            <div class="tab-pane fade show active" id="zero_admin_nav_website" role="tabpanel" aria-labelledby="zero_admin_nav_website_tab" tabindex="0">
                                                <div class="card card-bordered mb-5">
                                                    <div class="card-header">
                                                        <div class="card-title fw-bolder">基础配置</div>
                                                        <div class="card-toolbar">
                                                            <button class="btn btn-light-primary btn-sm" onclick="updateAdminConfigSettings('website')">
                                                                <i class="bi bi-save"></i>保存配置
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-5">
                                                            <div class="col-xxl-6">
                                                                <label class="form-label" for="website_url">网站地址</label>
                                                                <input class="form-control mb-5" id="website_url" name="website_url" type="text" placeholder="网站地址" value="{$settings['website_url']}" />
                                                                <label class="form-label" for="website_name">网站名称</label>
                                                                <input class="form-control mb-5" id="website_name" name="website_name" type="text" placeholder="网站名称" value="{$settings['website_name']}" />
                                                                <label class="form-label" for="website_landing_index">LANDING INDEX</label>
                                                                <input class="form-control mb-5" data-bs-toggle="tooltip" title="不懂请保持默认" id="website_landing_index" name="website_landing_index" type="text" placeholder="" value="{$settings['website_landing_index']}" />
                                                                <label class="form-label" for="website_admin_path">自定义管理页面路径</label>
                                                                <input class="form-control mb-5" id="website_admin_path" name="website_admin_path" type="text" placeholder="管理页面路径" value="{$settings['website_admin_path']}" />
                                                            </div>
                                                            <div class="col-xxl-6">
                                                                <label class="form-label" for="website_security_token">安全TOKEN</label>
                                                                <input class="form-control mb-5" data-bs-toggle="tooltip" title="随意填写,尽可能的复杂" id="website_security_token" name="website_security_token" type="text" placeholder="TOKEN" value="{$settings['website_security_token']}" />
                                                                <label class="form-label" for="website_backend_token">后端TOKEN</label>
                                                                <input class="form-control mb-5" data-bs-toggle="tooltip" title="请输入安全的密钥" id="website_backend_token" name="website_backend_token" type="text" placeholder="token" value="{$settings['website_backend_token']}" />
                                                                <label class="form-label" for="website_auth_background_image">登陆页背景图片</label>
                                                                <input class="form-control mb-5" data-bs-toggle="tooltip" title="不更改保持默认" id="website_auth_background_image" name="website_auth_background_image" type="text" placeholder="" value="{$settings['website_auth_background_image']}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-5">
                                                    <div class="col-xxl-6">
                                                        <div class="card card-bordered">
                                                            <div class="card-header">
                                                                <div class="card-title fw-bolder">权限组自定义</div>
                                                                <div class="card-toolbar">
                                                                    <button class="btn btn-light-primary btn-sm" onclick="updateAdminConfigSettings('permission_group')">
                                                                        <i class="bi bi-save"></i>保存配置
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">                                                                  
                                                                <label class="form-label" for="enable_permission_group">开启权限组自定义</label>
                                                                <select class="form-select mb-5" id="enable_permission_group" data-control="select2" data-hide-search="true">
                                                                    <option value="0">关闭</option>
                                                                    <option value="1">开启</option>
                                                                </select>
                                                                <span class="form-label">权限组名称设置</span>
                                                                <div id="permission_group_detail" class="mt-3"></div>                                                                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            