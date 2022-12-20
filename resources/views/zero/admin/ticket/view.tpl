{include file='admin/main.tpl'}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/editor.md@1.5.0/css/editormd.min.css"/>

<main class="content">
    <div class="content-header ui-content-header">
        <div class="container">
            <h1 class="content-heading">查看工单</h1>
        </div>
    </div>
    <div class="container">
        <div class="col-lg-12 col-sm-12">
            <section class="content-inner margin-top-no">

                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">
                            <div class="form-group form-group-label">
                                <label class="floating-label" for="content">内容</label>
                                <div id="editormd">
                                    <textarea style="display:none;" id="content"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-main">
                        <div class="card-inner">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-10">
                                        <button id="submit" type="submit" class="btn btn-brand waves-attach waves-light">添加</button>
                                        <button id="close" type="submit" class="btn btn-brand-accent waves-attach waves-light">添加并关闭</button>
                                        <button id="close_directly" type="submit" class="btn btn-brand-accent waves-attach waves-light">直接关闭</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {$render}
                {foreach $ticketset as $ticket}
                    <div class="card">
                        <aside class="card-side pull-left" style="padding: 16px; text-align: center">
                            <img style="border-radius: 100%; width: 100%" src="{$ticket->user()->gravatar}">
                            <br>
                            {$ticket->user()->name}
                        </aside>
                        <div class="card-main">
                            <div class="card-inner">
                                {$ticket->content}
                            </div>
                            <div class="card-action" style="padding: 12px"> {$ticket->datetime()}</div>
                        </div>
                    </div>
                {/foreach}
                {$render}
                {include file='dialog.tpl'}
        </div>
    </div>
</main>

{include file='admin/footer.tpl'}

<script src="https://cdn.jsdelivr.net/npm/editor.md@1.5.0/editormd.min.js"></script>
<script>
    window.addEventListener('load', () => {
        function submit() {
            $("#result").modal();
            $$.getElementById('msg').innerHTML = `正在提交。`;
            $.ajax({
                type: "PUT",
                url: "/admin/ticket/{$id}",
                dataType: "json",
                data: {
                    content: editor.getHTML(),
                    status
                },
                success: data => {
                    if (data.ret) {
                        $("#result").modal();
                        $$.getElementById('msg').innerHTML = data.msg;
                        window.setTimeout("location.href=top.document.referrer", {$config['jump_delay']});
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
        $$.getElementById('submit').addEventListener('click', () => {
            status = 1;
            submit();
        });
        $$.getElementById('close').addEventListener('click', () => {
            status = 0;
            submit();
        });
        $$.getElementById('close_directly').addEventListener('click', () => {
            status = 0;
            $("#result").modal();
            $$.getElementById('msg').innerHTML = `正在提交。`;
            $.ajax({
                type: "PUT",
                url: "/admin/ticket/{$id}",
                dataType: "json",
                data: {
                    content: '这条工单已被关闭',
                    status
                },
                success: data => {
                    if (data.ret) {
                        $("#result").modal();
                        $$.getElementById('msg').innerHTML = data.msg;
                        window.setTimeout("location.href=top.document.referrer", {$config['jump_delay']});
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
        });
        
    });
    (() => {
        editor = editormd("editormd", {
            path: "https://cdn.jsdelivr.net/npm/editor.md@1.5.0/lib/", // Autoload modules mode, codemirror, marked... dependents libs path
            height: 450,
            saveHTMLToTextarea: true,
            emoji: true
        });
        /*
        // or
        var editor = editormd({
            id   : "editormd",
            path : "../lib/"
        });
        */
    })();
</script>