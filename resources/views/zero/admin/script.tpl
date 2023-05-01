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

function getwarning() {
  return new Promise(function (resolve, reject) {
    Swal.fire({
      text: '继续执行下面操作?',
      icon: "warning",
      buttonsStyling: false,
      showCancelButton: true,
      confirmButtonText: "确定",
      cancelButtonText: "取消",
      customClass: {
        confirmButton: "btn btn-primary",
        cancelButton: "btn btn-light"
      }
    }).then(function (result) {
      if (result.isConfirmed) {
        resolve(); // 点击确定时返回一个已解决的 Promise 对象
      } else {
        reject(); // 点击取消时返回一个已拒绝的 Promise 对象
      }
    });
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
        getwarning().then(function() {

        
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
                break;
            case 'payment':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/payment/delete",
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
            case 'order':
                $.ajax({
                    type: "DELETE",
                    url: "/{$config['website_admin_path']}/order/delete",
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
            default:
                getResult('发生错误', '', 'error');

        }
        }).catch(function() {
            Swal.close();
        })
    }
</script>