<script src="/theme/zero/assets/plugins/global/plugins.bundle.js"></script>
<script src="/theme/zero/assets/js/scripts.bundle.js"></script>
<script src="/theme/zero/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
    // get result 
function getResult(titles, texts, icons) {
    Swal.fire({
        title: titles,
        text: texts,
        icon: icons,
        buttonsStyling: false,
        confirmButtonText: "OK",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
}
$(document).ready(function (){
    // 获取当前 URL 路径
    var path = window.location.pathname;

    // 使用 split() 切割路径字符串
    var parts = path.split('/');

    // 访问最后一个元素
    var target2 = parts[2];
    var target1 = parts[1];
    $("a.menu-link[href='/"+target1+"/"+target2+"']").addClass('active');
});
</script>
<script>
    function zeroAdminDelete(type, id){
        switch (type) {
            case 'product':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/product/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
                break;
            case 'node':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/node/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
                break;
            case 'user':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/user/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
                break;
            case 'ban_rule':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/ban/rule/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
                break;
            case 'news':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/news/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
                break;
            case 'ticket':
            $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/ticket/delete",
                    dataType: "json",
                    data: {
                        id
                    },
                    success: function(data){
                        if (data.ret === 1){
                            getResult(data.msg, '', 'success');
                            table_1.ajax.reload();
                        }else{
                            getResult('发生错误', '', 'error');
                        }
                    }
                });
        }
    }
</script>