
<table class="table align-middle table-row-dashed fs-6 gy-5" id="table_1">
    <thead>
        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">                                                       
            {foreach $table_config['total_column'] as $key => $value}
                <th class="{$key}">{$value}</th>
            {/foreach}
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold"></tbody>
</table>
