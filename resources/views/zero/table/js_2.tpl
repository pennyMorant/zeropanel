table_1 = $('#table_1').DataTable({
ajax: {
url: '{$table_config['ajax_url']}',
type: "POST"
},
processing: true,
serverSide: true,
scrollX: true,
order: [[ 0, 'desc' ]],
stateSave: true,
columnDefs: [
{
}
],
columns: [
{foreach $table_config['total_column'] as $key => $value}
    { "data": "{$key}" },
{/foreach}
],
{include file='table/lang_chinese.tpl'}
})


var has_init = JSON.parse(localStorage.getItem(window.location.href + '-hasinit'));