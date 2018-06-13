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

/**
 * BANNER逻辑
 */
class Banner extends IndexBase
{
    
    /**
     * 获取BANNER列表
     */
    public function getBannerList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelBanner->getList($where, $field, $order, $paginate);
    }

}
