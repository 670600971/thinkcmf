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
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use app\admin\model\ArticleModel;
use app\admin\model\LinkModel;
use app\admin\model\NavMenuModel;
class ArticleController extends AdminBaseController
{
    protected $targets = ["_blank" => "新标签页打开", "_self" => "本窗口打开"];

    /**
     * 友情链接管理
     * @adminMenu(
     *     'name'   => '友情链接',
     *     'parent' => 'admin/Setting/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 50,
     *     'icon'   => '',
     *     'remark' => '友情链接管理',
     *     'param'  => ''
     * )
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $content = hook_one('admin_link_index_view');
        // dump($content);exit;
        if (!empty($content)) {
            return $content;
        }

        $ArticleModel = new ArticleModel();
        $list     = $ArticleModel->select();

        //判断显示与否
        $data = [
            ['label-default','否'],
            ['label-success','是']
        ];
        $this->assign('data', $data);
        $this->assign('list', $list);
        // dump($list);exit;
        return $this->fetch();
    }



    /**
     * 添加友情链接
     * @adminMenu(
     *     'name'   => '添加友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加友情链接',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $NavMenuModel = new NavMenuModel();
        $parent_category = $NavMenuModel->navMenusTreeArray();

        $this->assign('parent_category', $parent_category);
        $this->assign('targets', $this->targets);
        return $this->fetch();
    }



    /**
     * 添加友情链接提交保存
     * @adminMenu(
     *     'name'   => '添加友情链接提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加友情链接提交保存',
     *     'param'  => ''
     * )
     */
    public function addPost()
    {
        $NavMenuModel = new NavMenuModel();
        
        $data['title']      = $this->request->param('name');
        $data['author']     = $this->request->param('author');
        $data['thumb']      = $this->request->param('image');
        $data['content']    = $this->request->param('content');
        $data['c_id']       = $this->request->param('category');
        $data['ch_id']      = $this->request->param('ch_id');
        $data['is_rec']     = $this->request->param('is_rec');
        $data['is_hot']     = $this->request->param('is_hot');
        $data['is_show']    = $this->request->param('is_show');
        $data['posttime']   = time();
        $combine_name       = $NavMenuModel->navCombine($data['c_id'],$data['ch_id']);
        $data['c_description']   = $combine_name;

        $ArticleModel = new ArticleModel();
        $result    = $this->validate($data, 'Article');
        if ($result !== true) {
            $this->error($result);
        }

        $ArticleModel->save($data);

        $this->success("添加成功！", url("Article/index"));
    }

    /**
     * 编辑友情链接
     * @adminMenu(
     *     'name'   => '编辑友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑友情链接',
     *     'param'  => ''
     * )
     * @return mixed
     * @throws \think\Exception\DbException
     */
    public function edit()
    {
        $id        = $this->request->param('id', 0, 'intval');
        $NavMenuModel = new NavMenuModel();
        $parent_category = $NavMenuModel->navMenusTreeArray();
        $ArticleModel = new ArticleModel();
        $data = $ArticleModel->where('id',$id)->find();

        $this->assign('data', $data);
        $this->assign('parent_category', $parent_category);
        $this->assign('targets', $this->targets);
        return $this->fetch();
    }

    /**
     * 编辑友情链接提交保存
     * @adminMenu(
     *     'name'   => '编辑友情链接提交保存',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑友情链接提交保存',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {
        $data   = $this->request->param();
        $result = $this->validate($data, 'Link');
        if ($result !== true) {
            $this->error($result);
        }
        $linkModel = LinkModel::find($data['id']);
        $linkModel->save($data);

        $this->success("保存成功！", url("Link/index"));
    }

    /**
     * 删除友情链接
     * @adminMenu(
     *     'name'   => '删除友情链接',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '删除友情链接',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $id = $this->request->param('id', 0, 'intval');
        ArticleModel::destroy($id);
        $this->success("删除成功！");
    }

    /**
     * 友情链接排序
     * @adminMenu(
     *     'name'   => '友情链接排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '友情链接排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        $linkModel = new  LinkModel();
        parent::listOrders($linkModel);
        $this->success("排序更新成功！");
    }

    /**
     * 友情链接显示隐藏
     * @adminMenu(
     *     'name'   => '友情链接显示隐藏',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '友情链接显示隐藏',
     *     'param'  => ''
     * )
     */
    public function toggle()
    {
        $data      = $this->request->param();
        $linkModel = new LinkModel();

        if (isset($data['ids']) && !empty($data["display"])) {
            $ids = $this->request->param('ids/a');
            $linkModel->where('id', 'in', $ids)->update(['status' => 1]);
            $this->success("更新成功！");
        }

        if (isset($data['ids']) && !empty($data["hide"])) {
            $ids = $this->request->param('ids/a');
            $linkModel->where('id', 'in', $ids)->update(['status' => 0]);
            $this->success("更新成功！");
        }


    }

}