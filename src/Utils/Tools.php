<?php

namespace App\Utils;

use App\Models\{
    Link,
    User,
    Node,
    Setting
};
use App\Services\Config;
use GeoIp2\Exception\AddressNotFoundException;
use MaxMind\Db\Reader\InvalidDatabaseException;
use DateTime;

final class Tools
{
    public static function getIPLocation($ip): string
    {
        $geoip = new GeoIP2();
        try {
            $city = $geoip->getCity($ip);
            $country = $geoip->getCountry($ip);
        } catch (AddressNotFoundException|InvalidDatabaseException $e) {
            return '未知';
        }

        if ($city !== null) {
            return $city . ', ' . $country;
        }

        return $country;
    }

    /**
     * 根据流量值自动转换单位输出
     */
    public static function flowAutoShow($value = 0)
    {
        $kb = 1024;
        $mb = 1048576;
        $gb = 1073741824;
        $tb = $gb * 1024;
        $pb = $tb * 1024;
        if (abs($value) > $pb) {
            return round($value / $pb, 2) . 'PB';
        }

        if (abs($value) > $tb) {
            return round($value / $tb, 2) . 'TB';
        }

        if (abs($value) > $gb) {
            return round($value / $gb, 2) . 'GB';
        }

        if (abs($value) > $mb) {
            return round($value / $mb, 2) . 'MB';
        }

        if (abs($value) > $kb) {
            return round($value / $kb, 2) . 'KB';
        }

        return round($value, 2) . 'B';
    }

    /**
     * 根据含单位的流量值转换 B 输出
     */
    public static function flowAutoShowZ($Value)
    {
        $number = substr($Value, 0, strlen($Value) - 2);
        if (!is_numeric($number)) return null;
        $unit = strtoupper(substr($Value, -2));
        $kb = 1024;
        $mb = 1048576;
        $gb = 1073741824;
        $tb = $gb * 1024;
        $pb = $tb * 1024;
        switch ($unit) {
            case 'B':
                $number = round($number, 2);
                break;
            case 'KB':
                $number = round($number * $kb, 2);
                break;
            case 'MB':
                $number = round($number * $mb, 2);
                break;
            case 'GB':
                $number = round($number * $gb, 2);
                break;
            case 'TB':
                $number = round($number * $tb, 2);
                break;
            case 'PB':
                $number = round($number * $pb, 2);
                break;
            default:
                return null;
                break;
        }
        return $number;
    }

    //虽然名字是toMB，但是实际上功能是from MB to B
    public static function toMB($traffic)
    {
        $mb = 1048576;
        return $traffic * $mb;
    }

    //虽然名字是toGB，但是实际上功能是from GB to B
    public static function toGB($traffic)
    {
        $gb = 1048576 * 1024;
        return $traffic * $gb;
    }

    /**
     * @param $traffic
     * @return float
     */
    public static function flowToGB($traffic)
    {
        $gb = 1048576 * 1024;
        return $traffic / $gb;
    }

    /**
     * @param $traffic
     * @return float
     */
    public static function flowToMB($traffic)
    {
        $gb = 1048576;
        return $traffic / $gb;
    }

    //获取随机字符串

    public static function genRandomNum($length = 8)
    {
        // 来自Miku的 6位随机数 注册验证码 生成方案
        $chars = '0123456789';
        $char = '';
        for ($i = 0; $i < $length; $i++) {
            $char .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $char;
    }

    public static function genRandomChar($length = 16)
    {
        return bin2hex(openssl_random_pseudo_bytes($length / 2));
    }

    // Unix time to Date Time
    public static function toDateTime($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    public static function secondsToTime($seconds)
    {
        $dtF = new DateTime('@0');
        $dtT = new DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a 天, %h 小时, %i 分 + %s 秒');
    }

    public static function base64_url_encode($input)
    {
        return strtr(base64_encode($input), array('+' => '-', '/' => '_', '=' => ''));
    }

    public static function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public static function getDir($dir)
    {
        $dirArray[] = null;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if ($file != '.' && $file != '..' && !strpos($file, '.')) {
                    $dirArray[$i] = $file;
                    $i++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    public static function isSpecialChars($input)
    {
        return ! preg_match('/[^A-Za-z0-9\-_\.]/', $input);
    }

    /**
     * Filter key in `App\Models\Model` object
     *
     * @param \App\Models\Model $object
     * @param array $filter_array
     *
     * @return \App\Models\Model
     */
    public static function keyFilter($object, $filter_array)
    {
        foreach ($object->toArray() as $key => $value) {
            if (!in_array($key, $filter_array)) {
                unset($object->$key);
            }
        }
        return $object;
    }

    public static function getRealIp($rawIp)
    {
        return str_replace('::ffff:', '', $rawIp);
    }

    public static function isEmail($input)
    {
        if (filter_var($input, FILTER_VALIDATE_EMAIL) === false) {
            return false;
        }
        return true;
    }

    public static function isIPv4($input)
    {
        if (filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return false;
        }
        return true;
    }

    public static function isIPv6($input)
    {
        if (filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
            return false;
        }
        return true;
    }

    public static function isInt($input)
    {
        if (filter_var($input, FILTER_VALIDATE_INT) === false) {
            return false;
        }
        return true;
    }


    // 请将冷门的国家或地区放置在上方，热门的中继起源放置在下方
    // 以便于兼容如：【上海 -> 美国】等节点名称
    private static $emoji = [
        "🇦🇷" => [
            "阿根廷",
            "AR"
        ],
        "🇦🇹" => [
            "奥地利",
            "维也纳",
            "AT"
        ],
        "🇦🇺" => [
            "澳大利亚",
            "悉尼",
            "AU"
        ],
        "🇧🇷" => [
            "巴西",
            "圣保罗",
            "BR"
        ],
        "🇨🇦" => [
            "加拿大",
            "蒙特利尔",
            "温哥华",
            "CA"
        ],
        "🇨🇭" => [
            "瑞士",
            "苏黎世",
            "CH"
        ],
        "🇩🇪" => [
            "德国",
            "法兰克福",
            "DE"
        ],
        "🇫🇮" => [
            "芬兰",
            "赫尔辛基",
            "FI"
        ],
        "🇫🇷" => [
            "法国",
            "巴黎"
        ],
        "🇬🇧" => [
            "英国",
            "伦敦",
            "GB"
        ],
        "🇮🇩" => [
            "印尼",
            "印度尼西亚",
            "雅加达",
            "ID"
        ],
        "🇮🇪" => [
            "爱尔兰",
            "都柏林",
            "IE"
        ],
        "🇮🇳" => [
            "印度",
            "孟买",
            "IN"
        ],
        "🇮🇹" => [
            "意大利",
            "米兰",
            "IT"
        ],
        "🇰🇵" => [
            "朝鲜",
            "KP"
        ],
        "🇲🇾" => [
            "马来西亚",
            "MY"
        ],
        "🇳🇱" => [
            "荷兰",
            "阿姆斯特丹",
            "NL"
        ],
        "🇵🇭" => [
            "菲律宾",
            "PH"
        ],
        "🇷🇴" => [
            "罗马尼亚",
            "RO"
        ],
        "🇷🇺" => [
            "俄罗斯",
            "伯力",
            "莫斯科",
            "圣彼得堡",
            "西伯利亚",
            "新西伯利亚",
            "RU"
        ],
        "🇸🇬" => [
            "新加坡",
            "SG"
        ],
        "🇹🇭" => [
            "泰国",
            "曼谷",
            "TH"
        ],
        "🇹🇷" => [
            "土耳其",
            "伊斯坦布尔",
            "TR"
        ],
        "🇺🇲" => [
            "美国",
            "波特兰",
            "俄勒冈",
            "凤凰城",
            "费利蒙",
            "硅谷",
            "拉斯维加斯",
            "洛杉矶",
            "圣克拉拉",
            "西雅图",
            "芝加哥",
            "沪美",
            "US"
        ],
        "🇲🇽" => [
            "MX"
        ],
        "🇻🇳" => [
            "越南",
            "VN"
        ],
        "🇿🇦" => [
            "南非",
            "ZA"
        ],
        "🇰🇷" => [
            "韩国",
            "首尔",
            "KR"
        ],
        "🇲🇴" => [
            "澳门",
            "MO"
        ],
        "🇯🇵" => [
            "日本",
            "东京",
            "大阪",
            "埼玉",
            "沪日",
            "JP"
        ],
        "🇹🇼" => [
            "台湾",
            "台北",
            "台中",
            "TW"
        ],
        "🇭🇰" => [
            "香港",
            "深港",
            "HK"
        ],
        "🇨🇳" => [
            "中国",
            "江苏",
            "北京",
            "上海",
            "深圳",
            "杭州",
            "徐州",
            "宁波",
            "镇江"
        ]
    ];

    public static function addEmoji($Name)
    {
        $done = [
            'index' => -1,
            'emoji' => ''
        ];
        foreach (self::$emoji as $key => $value) {
            foreach ($value as $item) {
                $index = strpos($Name, $item);
                if ($index !== false) {
                    $done['index'] = $index;
                    $done['emoji'] = $key;
                    continue 2;
                }
            }
        }
        return ($done['index'] == -1
            ? $Name
            : ($done['emoji'] . ' ' . $Name));
    }

    /**
     * Add files and sub-directories in a folder to zip file.
     *
     * @param string $folder
     * @param ZipArchive $zipFile
     * @param int $exclusiveLength Number of text to be exclusived from the file path.
     */
    public static function folderToZip($folder, &$zipFile, $exclusiveLength)
    {
        $handle = opendir($folder);
        while (false !== $f = readdir($handle)) {
            if ($f != '.' && $f != '..') {
                $filePath = "$folder/$f";
                // Remove prefix from file path before add to zip.
                $localPath = substr($filePath, $exclusiveLength);
                if (is_file($filePath)) {
                    $zipFile->addFile($filePath, $localPath);
                } else if (is_dir($filePath)) {
                    // Add sub-directory.
                    $zipFile->addEmptyDir($localPath);
                    self::folderToZip($filePath, $zipFile, $exclusiveLength);
                }
            }
        }
        closedir($handle);
    }

    /**
     * 清空文件夹
     *
     * @param string $dirName
     */
    public static function delDirAndFile($dirPath)
    {
        if ($handle = opendir($dirPath)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dirPath . '/' . $item)) {
                        self::delDirAndFile($dirPath . '/' . $item);
                    } else {
                        unlink($dirPath . '/' . $item);
                    }
                }
            }
            closedir($handle);
        }
    }

    /**
     * 重置自增列 ID
     *
     * @param DatatablesHelper  $db
     * @param string $table
     */
    public static function reset_auto_increment($db, $table)
    {
        $maxid = $db->query("SELECT `auto_increment` AS `maxid` FROM `information_schema`.`tables` WHERE `table_schema` = '" . $_ENV['db_database'] . "' AND `table_name` = '". $table ."'")[0]['maxid'];
        if ($maxid >= 2000000000) {
            $db->query('ALTER TABLE `' . $table . '` auto_increment = 1');
        }
    }
    
    /**
     * Eloquent 分页链接渲染
     *
     * @param mixed $data
     */
    public static function paginate_render($data): string
    {
        $totalPage   = $data->lastPage();
        $currentPage = $data->currentPage();
        $html = '<ul class="pagination">';
        for ($i = 1; $i <= $totalPage; $i++) {
            $active = '<li class="active"><span>' . $i . '</span></li>';
            $page   = '<li><a href="' . $data->url($i) . '">' . $i . '</a></li>';
            if ($i == 1) {
                // 当前为第一页
                if ($currentPage == $i) {
                    $html .= '<li class="disabled"><span>«</span></li>';
                    $html .= $active;
                    if ($i == $totalPage) {
                        $html .= '<li class="disabled"><span>»</span></li>';
                        continue;
                    }
                } else {
                    $html .= '<li><a href="' . $data->url($currentPage - 1) . '" rel="prev">«</a></li>';
                    if ($currentPage > 4) {
                        $html .= '<li><a href="javascript:void(0)">...</a></li>';
                    } else {
                        $html .= $page;
                    }
                }
            }
            if ($i == $totalPage) {
                // 当前为最后一页
                if ($currentPage == $i) {
                    $html .= $active;
                    $html .= '<li class="disabled"><span>»</span></li>';
                } else {
                    if ($totalPage - $currentPage > 3) {
                        $html .= '<li><a href="javascript:void(0)">...</a></li>';
                    } else {
                        $html .= $page;
                    }
                    $html .= '<li><a href="' . $data->url($currentPage + 1) . '" rel="next">»</a></li>';
                }
            }
            if ($i > 1 && $i < $totalPage) {
                // 其他页
                if ($currentPage == $i) {
                    $html .= $active;
                } else {
                    if ($totalPage > 10) {
                        if (
                            ($currentPage > 4 && $i < $currentPage && $i > $currentPage - 3)
                            ||
                            ($totalPage - $currentPage > 4 && $i > $currentPage && $i < $currentPage + 4)
                            ||
                            ($currentPage <= 4 && $i <= 4)
                            ||
                            ($totalPage - $currentPage <= 4 && $i > $currentPage)
                        ) {
                            $html .= $page;
                        }
                        continue;
                    }
                    $html .= $page;
                }
            }
        }
        $html .= '</ul>';
        return $html;
    }

    public static function etag($data) {
        $etag = sha1(json_encode($data));
        return $etag;
    }
}
