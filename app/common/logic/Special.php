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

namespace app\common\logic;

/**
 * 专题逻辑
 */
class Special extends LogicBase
{
    
    /**
     * 专题分类编辑
     */
    public function specialCategoryEdit($data = [])
    {
        
        $validate_result = $this->validateSpecialCategory->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSpecialCategory->getError()];
        }
        
        $url = url('specialCategoryList');
        
        $result = $this->modelSpecialCategory->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '专题分类' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelSpecialCategory->getError()];
    }
    
    /**
     * 获取专题列表
     */
    public function getSpecialList($where = [], $field = 'a.*', $order = '')
    {
        
        $this->modelSpecial->alias('a');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'a.member_id = m.id'],
                    [SYS_DB_PREFIX . 'special_category c', 'a.category_id = c.id'],
                ];
        
        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];

        return $this->modelSpecial->getList($where, $field, $order, DB_LIST_ROWS, $join);
    }
    
    /**
     * 获取专题列表搜索条件
     */
    public function getWhere($data = [])
    {
        
        $where = [];
        
        !empty($data['search_data']) && $where['a.name|a.describe'] = ['like', '%'.$data['search_data'].'%'];
        
        return $where;
    }
    
    /**
     * 专题信息编辑
     */
    public function specialEdit($data = [])
    {
        
        $validate_result = $this->validateSpecial->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateSpecial->getError()];
        }
        
        $url = url('specialList');
        
        empty($data['id']) && $data['member_id'] = MEMBER_ID;
        
        $data['content'] = html_entity_decode($data['content']);
        
        $result = $this->modelSpecial->setInfo($data);
        
        $handle_text = empty($data['id']) ? '新增' : '编辑';
        
        $result && action_log($handle_text, '专题' . $handle_text . '，name：' . $data['name']);
        
        return $result ? [RESULT_SUCCESS, '专题操作成功', $url] : [RESULT_ERROR, $this->modelSpecial->getError()];
    }

    /**
     * 获取专题信息
     */
    public function getSpecialInfo($where = [], $field = 'a.*')
    {
        
        $this->modelSpecial->alias('a');
        
        $join = [
                    [SYS_DB_PREFIX . 'member m', 'a.member_id = m.id'],
                    [SYS_DB_PREFIX . 'special_category c', 'a.category_id = c.id'],
                ];
        
        $where['a.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        return $this->modelSpecial->getInfo($where, $field, $join);
    }
    
    /**
     * 获取分类信息
     */
    public function getSpecialCategoryInfo($where = [], $field = true)
    {
        
        return $this->modelSpecialCategory->getInfo($where, $field);
    }
    
    /**
     * 获取专题分类列表
     */
    public function getSpecialCategoryList($where = [], $field = true, $order = '', $paginate = 0)
    {
        
        return $this->modelSpecialCategory->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 专题分类删除
     */
    public function specialCategoryDel($where = [])
    {
        
        $result = $this->modelSpecialCategory->deleteInfo($where);
        
        $result && action_log('删除', '专题分类删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '专题分类删除成功'] : [RESULT_ERROR, $this->modelSpecialCategory->getError()];
    }
    
    /**
     * 专题删除
     */
    public function specialDel($where = [])
    {
        
        $result = $this->modelSpecial->deleteInfo($where);
        
        $result && action_log('删除', '专题删除，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '专题删除成功'] : [RESULT_ERROR, $this->modelSpecial->getError()];
    }
}
