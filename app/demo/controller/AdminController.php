<?php
namespace app\demo\controller;

use cmf\controller\AdminBaseController;

class AdminController extends AdminBaseController
{
    public function index1()
    {
        return $this->fetch();
    }
}