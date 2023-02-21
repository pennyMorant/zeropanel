table_1 = $('#table_1').DataTable({
ajax: {
url: '{$table_config['ajax_url']}',
type: "POST"
},
searchDelay: 500,
processing: true,
serverSide: true,
scrollCollapse: true,
scrollX: true,
order: [[ 0, 'desc' ]],
stateSave: true,
columnDefs: [
    { width: '5%', targets: 0 },
    { className: 'text-end', targets: -1 }
],
columns: [
{foreach $table_config['total_column'] as $key => $value}
    { "data": "{$key}" },
{/foreach}
],
fixedColumns: true,
{include file='table/lang_chinese.tpl'}
})
