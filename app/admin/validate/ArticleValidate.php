<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\validate;

use think\Validate;

class ArticleValidate extends Validate
{
    protected $rule = [
        'title' => 'require',
        'thumb'  => 'require',
        'c_id'  => 'require',
        'content'  => 'require',
    ];

    protected $message = [
        'title.require' => '名称不能为空',
        'thumb.require'  => '图片不能为空',
        'c_id.require'  => '分类不能为空',
        'content.require'  => '文章内容不能为空',
    ];

}