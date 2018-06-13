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
 * 专题逻辑
 */
class Special extends IndexBase
{

    /**
     * 获取专题列表
     */
    public function getSpecialList($where = [], $field = true, $order = '', $paginate = 0)
    {

        return $this->modelSpecial->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取专题分类列表
     */
    public function getSpecialCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {

        return $this->modelSpecialCategory->getList($where, $field, $order, $paginate);
    }

    /**
     * 获取专题信息
     */
    public function getSpecialInfo($where = [], $field = true)
    {

        return $this->modelSpecial->getInfo($where, $field);
    }

}
