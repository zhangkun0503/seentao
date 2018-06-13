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

namespace app\admin\controller;

/**
 * 直播控制器
 */
class Live extends AdminBase
{
    
    /**
     * 专题列表
     */
    public function specialList()
    {
        
        $where = $this->logicSpecial->getWhere($this->param);
        
        $this->assign('list', $this->logicSpecial->getSpecialList($where, 'a.*,m.nickname,c.name as category_name', 'a.create_time desc'));
        
        return $this->fetch('special_list');
    }
    
    /**
     * 专题添加
     */
    public function specialAdd()
    {
        
        $this->specialCommon();
        
        return $this->fetch('special_edit');
    }
    
    /**
     * 专题编辑
     */
    public function specialEdit()
    {
        
        $this->specialCommon();
        
        $info = $this->logicSpecial->getSpecialInfo(['a.id' => $this->param['id']], 'a.*,m.nickname,c.name as category_name');
        
        $this->assign('info', $info);
        
        return $this->fetch('special_edit');
    }
    
    /**
     * 专题添加与编辑通用方法
     */
    public function specialCommon()
    {
        
        IS_POST && $this->jump($this->logicSpecial->specialEdit($this->param));
        
        $this->assign('special_category_list', $this->logicSpecial->getSpecialCategoryList([], 'id,name', '', false));
    }
    
    /**
     * 专题分类添加
     */
    public function specialCategoryAdd()
    {
        
        IS_POST && $this->jump($this->logicSpecial->specialCategoryEdit($this->param));
        
        return $this->fetch('special_category_edit');
    }
    
    /**
     * 专题分类编辑
     */
    public function specialCategoryEdit()
    {
        
        IS_POST && $this->jump($this->logicSpecial->specialCategoryEdit($this->param));
        
        $info = $this->logicSpecial->getSpecialCategoryInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('special_category_edit');
    }
    
    /**
     * 专题分类列表
     */
    public function specialCategoryList()
    {
        
        $this->assign('list', $this->logicSpecial->getSpecialCategoryList());
       
        return $this->fetch('special_category_list');
    }
    
    /**
     * 专题分类删除
     */
    public function specialCategoryDel($id = 0)
    {
        
        $this->jump($this->logicSpecial->specialCategoryDel(['id' => $id]));
    }
    
    /**
     * 数据状态设置
     */
    public function setStatus()
    {
        
        $this->jump($this->logicAdminBase->setStatus('Special', $this->param));
    }
}
