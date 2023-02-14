{include file='admin/main.tpl'}

<main class="content">
    <div class="content-header ui-content-header">
        <div class="container">
            <h1 class="content-heading">编辑商品</h1>
        </div>
    </div>
    <div class="container">
        <div class="col-lg-12 col-sm-12">
            <section class="content-inner margin-top-no">

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="name">名称</label>
                                <input class="form-control maxwidth-edit" id="name" type="text" value="{$product->name}">
                            </div>


                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">月付</label>
                                <input class="form-control maxwidth-edit" value="{$product->month_price}" id="month_price" type="text">
                            </div>
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">季付</label>
                                <input class="form-control maxwidth-edit" value="{$product->quarter_price}" id="quarter_price" type="text">
                            </div>
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">半年</label>
                                <input class="form-control maxwidth-edit" value="{$product->half_year_price}" id="half_year_price" type="text">
                            </div>
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">一年</label>
                                <input class="form-control maxwidth-edit" value="{$product->year_price}" id="year_price" type="text">
                            </div>
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">两年</label>
                                <input class="form-control maxwidth-edit" value="{$product->two_year_price}" id="two_year_price" type="text">
                            </div>
                            
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">产品类型</label>
                                <select class="form-control maxwidth-edit" id="type">
                                    <option value="1" {if $product->type == 1}selected{/if}>周期产品</option>
                                    <option value="2" {if $product->type == 2}selected{/if}>流量产品</option>
                                    <option value="3" {if $product->type == 3}selected{/if}>其他产品</option>
                                </select>
                            </div>
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="price">排序</label>
                                <input class="form-control maxwidth-edit" id="sort" value="{$product->sort}" type="text">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">

                            <div class="form-group form-group-label">
                                <label class="floating-label" for="traffic">流量（GB）</label>
                                <input class="form-control maxwidth-edit" id="traffic" type="text"
                                       value="{$product->bandwidth()}">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">

                            <div class="form-group form-group-label">
                                <label class="floating-label" for="stock">限制购买总数量{if $product->stock !== -1} (该套餐当前已销售{$product->sales} 份){/if}</label>
                                <input class="form-control maxwidth-edit" id="stock" type="text" value="{$product->stock}">
                                <p class="form-control-guide"><i class="material-icons">info</i>按所有用户中, 生效套餐为该套餐的累计, 超过该数量其他用户则不能购买, -1 为不限制</p>
                                <p class="form-control-guide"><i class="material-icons">info</i>如果其他用户套餐到期后没有再购买该套餐, 则自动有1个名额可以购买</p>
                            </div>

                        </div>
                    </div>
                </div>

                
                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">
                        
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="node_group">用户群组</label>
                                <input class="form-control maxwidth-edit" id="node_group" type="text" value="{$product->node_group()}">
                                <p class="form-control-guide"> <i class="material-icons">info</i> 购买该套餐将用户修改成此分组下,   -1 不分配, 保持用户默认</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">

                            <div class="form-group form-group-label">
                                <label class="floating-label" for="class">等级</label>
                                <input class="form-control maxwidth-edit" id="class" type="text"
                                       value="{$product->user_class()}">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="reset">流量重置周期</label>
                                <select class="form-control maxwidth-edit" id="reset">
                                    <option value="0" {if $product->reset_traffic_cycle === 0}selected{/if}>一次性</option>
                                    <option value="1" {if $product->reset_traffic_cycle === 1}selected{/if}>订单日重置</option>
                                    <option value="2" {if $product->reset_traffic_cycle === 2}selected{/if}>每月1日重置</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="speed_limit">端口限速</label>
                                <input class="form-control maxwidth-edit" id="speed_limit" type="number"
                                       value="{$product->speedlimit()}">
                            </div>


                            <div class="form-group form-group-label">
                                <label class="floating-label" for="ip_limit">IP限制</label>
                                <input class="form-control maxwidth-edit" id="ip_limit" type="number"
                                       value="{$product->connector()}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-10 col-md-push-1">
                                        <button id="submit" type="submit"
                                                class="btn btn-block btn-brand waves-attach waves-light">保存
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {include file='dialog.tpl'}


        </div>


    </div>
</main>

{include file='admin/footer.tpl'}


<script>
    window.addEventListener('load', () => {
        function submit() {           
            let data = {
                name: $$getValue('name'),
                month_price: $$getValue('month_price'),
                quarter_price: $$getValue('quarter_price'),
                half_year_price: $$getValue('half_year_price'),
                year_price: $$getValue('year_price'),
                two_year_price: $$getValue('two_year_price'),
                type: $$getValue('type'),
                sort: $$getValue('sort'),
                traffic: $$getValue('traffic'),
                speed_limit: $$getValue('speed_limit'),
                ip_limit: $$getValue('ip_limit'),
                class: $$getValue('class'),
                reset: $$getValue('reset'),
                node_group: $$getValue('node_group'),
                stock: $$getValue('stock'),
            }
            $.ajax({
                type: "PUT",
                url: "/admin/shop/{$product->id}",
                dataType: "json",
                data,
                success: data => {
                    if (data.ret) {
                        $("#result").modal();
                        $$.getElementById('msg').innerHTML = data.msg;
                        window.setTimeout("location.href='/admin/shop'", {$config['jump_delay']});
                    } else {
                        $("#result").modal();
                        $$.getElementById('msg').innerHTML = data.msg;
                    }
                },
                error: jqXHR => {
                    $("#result").modal();
                    $$.getElementById('msg').innerHTML = `发生错误：${
                            jqXHR.status
                            }`;
                }
            });
        }
        $("html").keydown(event => {
            if (event.keyCode === 13) {
                login();
            }
        });
        $$.getElementById('submit').addEventListener('click', submit);
    })
</script>