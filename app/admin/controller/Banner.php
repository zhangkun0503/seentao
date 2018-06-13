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
 * BANNER控制器
 */
class Banner extends AdminBase
{
    
    /**
     * BANNER列表
     */
    public function bannerList()
    {
        
        $this->assign('list', $this->logicBanner->getBannerList());
        
        return $this->fetch('banner_list');
    }
    
    /**
     * BANNER添加
     */
    public function bannerAdd()
    {
        
        IS_POST && $this->jump($this->logicBanner->bannerEdit($this->param));
        
        return $this->fetch('banner_edit');
    }
    
    /**
     * BANNER编辑
     */
    public function bannerEdit()
    {
        
        IS_POST && $this->jump($this->logicBanner->bannerEdit($this->param));
        
        $info = $this->logicBanner->getBannerInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('banner_edit');
    }
    
    /**
     * BANNER删除
     */
    public function bannerDel($id = 0)
    {
        
        $this->jump($this->logicBanner->bannerDel(['id' => $id]));
    }
}
