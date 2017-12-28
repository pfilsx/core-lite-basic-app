<?php

namespace app\controllers;


use core\components\Controller;
use core\db\QueryBuilder;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        QueryBuilder::create()->select('*')->from('sl_products')->execute();
        return $this->render('index');
    }
}