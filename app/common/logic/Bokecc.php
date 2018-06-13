<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\Common\logic;

use GuzzleHttp;

class Bokecc extends LogicBase
{
    // 上传url
    public $cc_server_url;

    // 上传回调url
    public $notify_url;

    // userId
    public $cc_user_id;

    // apikey
    public $cc_apikey;

    // 视频分类id
    public $cc_categoryid;

    // 播放器id
    public $cc_playerid;

    public function initialize()
    {
        $cc_config = config('app_debug') ? config('bokecc.test') : config('bokecc.pro');

        $this->cc_server_url = $cc_config['cc_server_url'];
        $this->notify_url = $cc_config['notify_url'];
        $this->cc_user_id = $cc_config['cc_user_id'];
        $this->cc_apikey = $cc_config['cc_apikey'];
        $this->cc_categoryid = $cc_config['cc_categoryid'];
        $this->cc_playerid = $cc_config['cc_playerid'];
    }

    // HTTP通信加密算法
    public function gen_thqs($data)
    {
        ksort($data);

        $data ['time'] = time();
        $data ['salt'] = $this->cc_apikey;
        $data ['hash'] = strtoupper(md5(http_build_query($data)));
        unset($data ['salt']);

        return http_build_query($data);
    }

    // 获取播放器代码
    public function playcode($videoid, $width = 800, $height = 450, $auto_play = false)
    {
        $cache_data = cache('cache_play_code_' . $videoid);

        if (!empty($cache_data)) {
            return $cache_data;
        }

        $data ['format'] = 'json';
        $data ['videoid'] = $videoid;
        $data ['player_width'] = $width;
        $data ['player_height'] = $height;
        $data ['auto_play'] = $auto_play;
        $data ['userid'] = $this->cc_user_id;
        $data ['playerid'] = $this->cc_playerid;

        $thqs = $this->gen_thqs($data);
        $client = new GuzzleHttp\Client(['base_uri' => $this->cc_server_url]);
        $response = $client->request('GET', "api/video/playcode?$thqs", ['verify' => false]);
        $body = json_decode($response->getBody(), true);
        $playcode = $body['video']['playcode'];

        // 根据videoid缓存数据，有效期半小时
        !empty($playcode) && cache('cache_play_code_' . $videoid, $playcode, 1800);

        return $playcode;
    }

    // 播放量统计获取
    public function playcount($videoid, $start_date)
    {
        $cache_data = cache('cache_play_count_' . $videoid);

        if (!empty($cache_data)) {
            return $cache_data;
        }

        $data ['videoid'] = $videoid;
        $data ['start_date'] = $start_date;
        $data ['end_date'] = date('Y-m-d');
        $data ['userid'] = $this->cc_user_id;

        $thqs = $this->gen_thqs($data);
        $client = new GuzzleHttp\Client(['base_uri' => $this->cc_server_url]);
        $response = $client->request('GET', "api/stats/playcount/video/daily?$thqs", ['verify' => false]);
        $body = json_decode($response->getBody(), true);

        $play_count = 0;
        if (isset($body['play_counts']['play_count'])) {
            $play_counts = $body['play_counts']['play_count'];
            $pc = array_column($play_counts, 'pc');
            $mobile = array_column($play_counts, 'mobile');

            $play_count = array_sum($pc) + array_sum($mobile);
        }

        // 根据videoid缓存数据，有效期五分钟
        cache('cache_play_count_' . $videoid, $play_count, 300);

        return $play_count;
    }

}
