<?php
if (!defined('EMLOG_ROOT')) {exit('error!');}

function theme_emtalk_config() {
    return [
        'logo_text' => [
            'type' => 'text',
            'name' => '站点名称',
            'value' => '微话'
        ],
        'footer_info' => [
            'type' => 'text',
            'name' => '底部信息',
            'value' => 'Powered by Emlog Pro'
        ],
        'icp' => [
            'type' => 'text',
            'name' => 'ICP备案号',
            'value' => ''
        ],
        'stat_code' => [
            'type' => 'textarea',
            'name' => '统计代码',
            'value' => ''
        ]
    ];
}
?>
