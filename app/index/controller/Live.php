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

namespace app\index\controller;

/**
 * 前端直播控制器
 */
class Live extends IndexBase
{

    // 首页
    public function index()
    {
        $recommend = [];
        $recommend['status'] = 1;
        $recommend['is_recommend'] = 1;
        $recommend['cc_video_id'] = ['neq', ''];

        // 专题列表
        $this->assign('special_list', $this->logicSpecial->getSpecialList(['status' => 1], 'id,name,describe,cover_id,create_time', 'create_time desc'));

        // 推荐专题
        $this->assign('recommend_list', $this->logicSpecial->getSpecialList($recommend, 'id,name,describe,cover_id,create_time', 'create_time desc', 6));

        // 专题分类
        $this->assign('category_list', $this->logicSpecial->getSpecialCategoryList([], true, 'create_time asc', false));

        // banner列表
        $this->assign('banner_list', $this->logicBanner->getBannerList(['status' => 1], 'name,img_id,url,describe', 'sort desc', 5));

        // 直播状态
        $this->assign('live_states', $this->logicLiveCourses->live_states);

        // 直播课
        $this->assign('live_courses', $this->logicLiveCourses->getLiveCoursesList());
//        print_r($this->logicLiveCourses->getLiveCoursesList());

        return $this->fetch('index');
    }

    // 详情
    public function detail($id = 0)
    {
        // 专题ID为空，重定向到云直播首页
        empty((int)$id) && $this->redirect('index/live/index', 302);

        // 专题信息
        $data = $this->logicSpecial->getSpecialInfo(['id' => $id]);

        // 未查询到专题，重定向到云直播首页
        empty($data) && $this->redirect('index/live/index', 302);

        !empty($data['cc_video_id']) && $data['play_code'] = $this->logicBokecc->playcode($data['cc_video_id'], 375, 240);

        !empty($data['cc_video_id']) && $data['play_count'] = $this->logicBokecc->playcount($data['cc_video_id'], date('Y-m-d', strtotime($data['create_time'])));

        $data['content'] = html_entity_decode($data['content']);

        $this->assign('special_info', $data);

        // 相关视频
        $this->assign('relation_list', $this->logicSpecial->getSpecialList(['category_id' => $data['category_id']], 'id,name,describe,cover_id,create_time', 'rand(), create_time desc', 6));

        return $this->fetch('detail');
    }

    // 获取专题
    public function get_special_list($cid = 0)
    {
        $where['status'] = 1;
        !empty((int)$cid) && $where['category_id'] = $cid;

        $data = $this->logicSpecial->getSpecialList($where, 'id,name,describe,cover_id,create_time', 'create_time desc')->toArray();

        foreach ($data['data'] as &$item) {
            $item['cover_id'] = get_picture_url($item['cover_id']);
            $item['url'] = url('index/live/detail', ['id' => $item['id']]);
        }

        return json(['code' => 0, 'msg' => '查询成功', 'data' => $data]);
    }

    // 获取直播课
    public function get_live_courses($page = 1)
    {
        // page为空
        if (empty($page)) {
            return json(['code' => -1, 'msg' => 'page 不能为空', 'data' => null]);
        }

        $data = $this->logicLiveCourses->getLiveCoursesList($page);

        return json(['code' => 0, 'msg' => '查询成功', 'data' => $data]);
    }

    // 直播地址
    public function get_live_url($token = 0)
    {
        // liveToken为空
        if (empty($token)) {
            return json(['code' => -1, 'msg' => 'liveToken 不能为空', 'data' => null]);
        }

        $data['live_url'] = $this->logicLiveCourses->getAudienceLoginUrl($token);

        return json(['code' => 0, 'msg' => '查询成功', 'data' => $data]);
    }
}
