<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Jack YanTC <yanshixin.com>                             |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\widget;

/**
 * CC文件上传小物件
 */
class Bokecc extends WidgetBase
{

    /**
     * 显示CC文件上传视图
     */
    public function index($name = '', $value = '')
    {

        $this->assign('notify_url', $this->logicBokecc->notify_url);
        $this->assign('widget_data', compact('name', 'value'));

        return $this->fetch('admin@widget/bokecc/index');
    }
}
