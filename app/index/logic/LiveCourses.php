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

namespace app\index\logic;

use GuzzleHttp;

/**
 * 直播课逻辑
 */
class LiveCourses extends IndexBase
{
    public $limit = 20;
    public $live_states = [
        'NOT_START' => '敬请期待',
        'LIVING' => '正在直播',
        'LIVE_END' => '直播结束',
        'LIVE_CANCEL' => '未直播',
        'LIVE_CLOSED' => '已关闭',
    ];

    // 直播课列表
    public function getLiveCoursesList($page = 0)
    {
        $cache_data = cache('cache_live_courses_data_' . $page);

        if (!empty($cache_data)) {
            return $cache_data;
        }

        $query['start'] = ($page*$this->limit);
        $query['limit'] = $this->limit;
        $query['orderby'] = 'CREATE_TIME';
        $query['direction'] = 'DESC';
        $query['liveCourseStates'] = 'NOT_START,LIVING';

        $client = new GuzzleHttp\Client();
        $response = $client->post('http://tapi.seentao.com/live/liveCourses.public.list.get', ['query' => $query]);
        $body = json_decode($response->getBody(), true);

        if ($body['code'] == 200 && !empty($body)) {
            // 根据分页数缓存数据，有效期十分钟
            cache('cache_live_courses_data_' . $page, $body, 600);
        } else {
            return [];
        }

        return $body;
    }

    // 直播间地址
    public function getAudienceLoginUrl($token)
    {
        $cache_data = cache('cache_live_courses_url_' . $token);

        if (!empty($cache_data)) {
            return $cache_data;
        }

        $client = new GuzzleHttp\Client();
        $response = $client->post('http://tapi.seentao.com/live/audienceLoginUrl.byPublicLiveToken.get', ['query' => ['liveToken' => $token]]);
        $body = json_decode($response->getBody(), true);

        if ($body['code'] == 200 && !empty($body['audienceLoginUrl'])) {
            // 根据Token缓存数据，有效期二小时
            cache('cache_live_courses_url_' . $token, $body['audienceLoginUrl'], 7200);
        } else {
            return '';
        }

        return $body['audienceLoginUrl'];
    }

}
