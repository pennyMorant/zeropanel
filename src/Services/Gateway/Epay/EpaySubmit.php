<?php

declare(strict_types=1);

namespace App\Services\Gateway\Epay;

final class EpaySubmit
{
    private $alipay_config;
    private $alipay_gateway_new;

    public function __construct($alipay_config)
    {
        $this->alipay_config = $alipay_config;
        $this->alipay_gateway_new = $this->alipay_config['apiurl'] . 'submit.php?';
    }

    public function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = EpayTool::createLinkstring($para_sort);

        return EpayTool::md5Sign($prestr, $this->alipay_config['key']);
    }

    public function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = EpayTool::paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = EpayTool::argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);

        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper(trim($this->alipay_config['sign_type']));

        return $para_sort;
    }

    public function buildRequestParaToString($para_temp)
    {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp);

        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        return EpayTool::createLinkstringUrlencode($para);
    }

    public function buildRequestForm($para_temp)
    {
        //待请求参数数组
        $para = http_build_query($this->buildRequestPara($para_temp));
        $url = $this->gateway_header . $para;
        return $url;
    }
}