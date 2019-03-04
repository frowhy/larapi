<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/11/26
 * Time: 下午2:45
 */


/**
 * 验证码
 */
if (!function_exists('verification_code')) {
    /**
     * @param int $length
     * @param string $type
     *
     * @return string
     */
    function verification_code(int $length = 4, string $type = 'int')
    {
        if ('int' === $type) {
            return sprintf("%0{$length}d", rand(0, pow(10, $length) - 1));
        } else {
            return str_random($length);
        }
    }
}

/**
 * 相对 URL
 */
if (!function_exists('secure')) {
    /**
     * @param null|string $url
     *
     * @return null|string
     */
    function relative_url(?string $url = null)
    {
        return $url === null
            ? $url
            : (false === str_start($url, 'http://') ? (false === str_start($url, 'https://')
                ? $url : str_replace_first('https://', '//', $url)) : str_replace_first('http://', '//', $url));
    }
}

/**
 * 储存 URL
 */
if (!function_exists('storage_url')) {
    function storage_url(?string $url = null)
    {
        return $url === null ? $url : (starts_with($url, 'http') ? $url : \Storage::url($url));
    }
}

/**
 * 两位小数
 */
if (!function_exists('price')) {
    function price(float $price)
    {
        return number_format($price, 2);
    }
}

/**
 * 16 进制转 RGB
 */
if (!function_exists('hex2rgb')) {
    function hex2rgb(string $hexColor)
    {
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) > 3) {
            $rgb = [
                'r' => hexdec(substr($color, 0, 2)),
                'g' => hexdec(substr($color, 2, 2)),
                'b' => hexdec(substr($color, 4, 2)),
            ];
        } else {
            $color = $hexColor;
            $r = substr($color, 0, 1).substr($color, 0, 1);
            $g = substr($color, 1, 1).substr($color, 1, 1);
            $b = substr($color, 2, 1).substr($color, 2, 1);
            $rgb = [
                'r' => hexdec($r),
                'g' => hexdec($g),
                'b' => hexdec($b),
            ];
        }

        return $rgb;
    }
}

/**
 * 灰度等级
 */
if (!function_exists('gray_level')) {
    function gray_level(array $rgb)
    {
        return $rgb['r'] * 0.299 + $rgb['g'] * 0.587 + $rgb['b'] * 0.114;
    }
}

/**
 * 时间范围
 */
if (!function_exists('last_year')) {
    function last_year()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->subYear()->startOfYear();
        $end_at = $carbon->today()->subYear()->endOfYear();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('this_year')) {
    function this_year()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->startOfYear();
        $end_at = $carbon->today()->endOfYear();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('next_year')) {
    function next_year()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->addYear()->startOfYear();
        $end_at = $carbon->today()->addYear()->endOfYear();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('last_month')) {
    function last_month()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->subMonth()->startOfMonth();
        $end_at = $carbon->today()->subMonth()->endOfMonth();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('this_month')) {
    function this_month()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->startOfMonth();
        $end_at = $carbon->today()->endOfMonth();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('next_month')) {
    function next_month()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->addMonth()->startOfMonth();
        $end_at = $carbon->today()->addMonth()->endOfMonth();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('last_week')) {
    function last_week()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->subWeek()->startOfWeek();
        $end_at = $carbon->today()->subWeek()->endOfWeek();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('this_week')) {
    function this_week()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->startOfWeek();
        $end_at = $carbon->today()->endOfWeek();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('next_week')) {
    function next_week()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->addWeek()->startOfWeek();
        $end_at = $carbon->today()->addWeek()->endOfWeek();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('yesterday')) {
    function yesterday()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->yesterday()->startOfDay();
        $end_at = $carbon->yesterday()->startOfDay();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('today')) {
    function today()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->today()->startOfDay();
        $end_at = $carbon->today()->startOfDay();

        return compact('start_at', 'end_at');
    }
}

if (!function_exists('tomorrow')) {
    function tomorrow()
    {
        $carbon = new Illuminate\Support\Carbon();
        $start_at = $carbon->tomorrow()->startOfDay();
        $end_at = $carbon->tomorrow()->startOfDay();

        return compact('start_at', 'end_at');
    }
}

/**
 * 微信浏览器
 */
if (!function_exists('in_wechat')) {
    function in_wechat()
    {
        return str_contains(request(), 'MicroMessenger');
    }
}

/**
 * 微信
 */
if (!function_exists('is_wechat')) {
    function is_wechat()
    {
        return in_wechat() && !is_mini_program();
    }
}

/**
 * 小程序
 */
if (!function_exists('is_mini_program')) {
    function is_mini_program()
    {
        return in_wechat() && request()->get('is_mini_program', false);
    }
}

/**
 * 读取 DATA 数据
 */
if (!function_exists('get_data')) {
    function get_data($data, $index = null, $key = null)
    {
        if ($data instanceof \Illuminate\Support\Collection) {
            $data = $data->toArray();
        }
        if (array_has($data, 'data')) {
            $field = 'data.';
        } else {
            $field = '';
        }

        if (array_has($data, "{$field}0")) {
            if (!is_null($index) && is_int($index)) {
                $key = "{$index}.{$key}";
            } else {
                if (!is_null($index)) {
                    $key = "0.{$index}";
                } else {
                    $key = 0;
                }
            }
        } else {
            if (is_null($index)) {
                $key = null;
            } else {
                $key = $index;
            }
        }
        if ($key === null) {
            $key = '';
        }

        return array_get($data, rtrim("{$field}{$key}", '.'));
    }
}

/**
 * 清空缓存
 */
if (!function_exists('clear_cache')) {
    function clear_cache()
    {
        if (config('cache.opcache_enabled')) {
            $opcache = app('Appstract\Opcache\OpcacheFacade');
            if (false !== $opcache::getStatus()) {
                $opcache::clear();
            }
        }
        \Cache::tags('website')->flush();
    }
}

/**
 * 判定缓存
 */
if (!function_exists('has_cache')) {
    function has_cache(string $uri)
    {
        return \Cache::tags('website')->has($uri);
    }
}

/**
 * 读取缓存
 */
if (!function_exists('get_cache')) {
    function get_cache(string $uri)
    {
        return \Cache::tags('website')->get($uri);
    }
}

/**
 * 写缓存
 */
if (!function_exists('set_cache')) {
    function set_cache(string $uri, string $response)
    {
        \Cache::tags('website')->put($uri, $response, config('cache.timeout'));
    }
}

/**
 * 随机值
 */
if (!function_exists('random')) {
    function random(int $length = 4, string $type = 'digital')
    {
        if ('digital' === $type) {
            return randomDigital($length);
        } elseif ('alphabet' === $type) {
            return randomAlphabet($length);
        } else {
            return str_random($length);
        }
    }
}

if (!function_exists('randomDigital')) {
    function randomDigital(int $length = 4)
    {
        return sprintf("%0{$length}d", rand(0, pow(10, $length) - 1));
    }
}

if (!function_exists('randomAlphabet')) {
    function randomAlphabet(int $length = 4)
    {
        $str = '';
        $map = [
            ['65', '90'],
            ['97', '122'],
        ];
        for ($i = 0; $i < $length; $i++) {
            $param = array_random($map);
            $str .= chr(call_user_func_array('rand', $param));
        }
        return $str;
    }
}

if (!function_exists('randomAlphabetUpper')) {
    function randomAlphabetUpper(int $length = 4)
    {
        return strtoupper(randomAlphabet($length));
    }
}

if (!function_exists('randomAlphabetLower')) {
    function randomAlphabetLower(int $length = 4)
    {
        return strtolower(randomAlphabet($length));
    }
}

if (!function_exists('randomDate')) {
    function randomDate()
    {
        return mt_rand(2000, date('Y')).sprintf("%02d", mt_rand(1, 12)).sprintf("%02d", mt_rand(1, 28));
    }
}

/**
 * 轮询调度
 *
 * @param $items
 * @param $result
 */
if (!function_exists('round_robin')) {
    function round_robin(&$items, &$result)
    {
        $total = 0;
        $best = null;

        foreach ($items as $key => $item) {
            $current = &$items[$key];
            $weight = $current['weight'];

            $current['current_weight'] += $weight;
            $total += $weight;

            if (($best == null) || ($items[$best]['current_weight'] <
                                    $current['current_weight'])) {
                $best = $key;
            }
        }

        $items[$best]['current_weight'] -= $total;
        $items[$best]['count']++;

        $result[] = $best;
    }
}

/**
 * 13 位时间戳
 *
 * @return float
 */
if (!function_exists('getMillisecond')) {
    function getMillisecond()
    {
        list($t1, $t2) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
    }
}

// ASCII
if (!function_exists('asciiEncode')) {
    function asciiEncode($string)
    {
        $length = strlen($string);
        $a = 0;
        $ascii = null;
        while ($a < $length) {
            $ud = 0;
            if (ord($string{$a}) >= 0 && ord($string{$a}) <= 127) {
                $ud = ord($string{$a});
                $a += 1;
            } elseif (ord($string{$a}) >= 192 && ord($string{$a}) <= 223) {
                $ud = (ord($string{$a}) - 192) * 64 + (ord($string{$a + 1}) - 128);
                $a += 2;
            } elseif (ord($string{$a}) >= 224 && ord($string{$a}) <= 239) {
                $ud =
                    (ord($string{$a}) - 224) * 4096 + (ord($string{$a + 1}) - 128) * 64 + (ord($string{$a + 2}) - 128);
                $a += 3;
            } elseif (ord($string{$a}) >= 240 && ord($string{$a}) <= 247) {
                $ud =
                    (ord($string{$a}) - 240) * 262144 +
                    (ord($string{$a + 1}) - 128) * 4096 +
                    (ord($string{$a + 2}) - 128) * 64 +
                    (ord($string{$a + 3}) - 128);
                $a += 4;
            } elseif (ord($string{$a}) >= 248 && ord($string{$a}) <= 251) {
                $ud =
                    (ord($string{$a}) - 248) * 16777216 +
                    (ord($string{$a + 1}) - 128) * 262144 +
                    (ord($string{$a + 2}) - 128) * 4096 +
                    (ord($string{$a + 3}) - 128) * 64 +
                    (ord($string{$a + 4}) - 128);
                $a += 5;
            } elseif (ord($string{$a}) >= 252 && ord($string{$a}) <= 253) {
                $ud =
                    (ord($string{$a}) - 252) * 1073741824 +
                    (ord($string{$a + 1}) - 128) * 16777216 +
                    (ord($string{$a + 2}) - 128) * 262144 +
                    (ord($string{$a + 3}) - 128) * 4096 +
                    (ord($string{$a + 4}) - 128) * 64 +
                    (ord($string{$a + 5}) - 128);
                $a += 6;
            } elseif (ord($string{$a}) >= 254 && ord($string{$a}) <= 255) {
                $ud = false;
            }
            $ascii .= "&#$ud;";
        }
        return $ascii;
    }
}
