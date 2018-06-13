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

namespace app\admin\logic;

/**
 * BANNER逻辑
 */
class Banner extends AdminBase
{
    
    /**
     * 获取BANNER列表
     */
    public function getBannerList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelBanner->getList($where, $field, $order, $paginate);
    }
    
    /**
     * BANNER信息编辑
     */
    public function bannerEdit($data = [])
    {
        
        $validate_result = $this->validateBanner->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateBanner->getError()];
        }
        
        $url = url('bannerList');
        
        $result = $this->modelBanner->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, 'BANNER' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelBanner->getError()];
    }

    /**
     * 获取BANNER信息
     */
    public function getBannerInfo($where = [], $field = true)
    {
        
        return $this->modelBanner->getInfo($where, $field);
    }
    
    /**
     * BANNER删除
     */
    public function bannerDel($where = [])
    {
        
        $result = $this->modelBanner->deleteInfo($where);
        
        $result && action_log('删除', 'BANNER删除' . '，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '删除成功'] : [RESULT_ERROR, $this->modelBanner->getError()];
    }
}
