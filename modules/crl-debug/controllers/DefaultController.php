<?php


namespace app\modules\crl_debug\controllers;


use app\modules\crl_debug\CrlDebug;
use core\base\App;
use core\components\Controller;
use core\helpers\Url;

class DefaultController extends Controller
{
    public $layout = 'default';

    public function actionIndex()
    {
        /**
         * @var CrlDebug $module
         */
        $module = App::$instance->getModule('crl-debug');
        $loggedData = [];
        if ($module->storageType == 'file'){
            $filePath = $module->fileStorageDirectory.DIRECTORY_SEPARATOR.'index.data';
            if (is_file($filePath)) {
                $loggedData = unserialize(file_get_contents($filePath));
            }
        } else {
            if (App::$instance->session->has('crl-debug')){
                $loggedData = App::$instance->session['crl-debug'];
            }
        }
        return $this->render('index', ['data' => $loggedData, 'action' => null, 'id' => null]);
    }

    public function actionEnvironment($id = null)
    {
        if (($data = $this->getLoggedData($id)) === null) {
            return $this->redirect(Url::toAction('index'));
        }
        return $this->render('environment', [
            'fullData' => $data,
            'data' => $data['environment'],
            'action' => 'environment',
            'id' => $id
        ]);
    }

    public function actionRequest($id = null)
    {
        if (($data = $this->getLoggedData($id)) === null) {
            return $this->redirect(Url::toAction('index'));
        }
        if (!empty($data['request']['request_headers'])){
            ksort($data['request']['request_headers']);
        }
        if (!empty($data['request']['response_headers'])){
            ksort($data['request']['response_headers']);
        }
        if (!empty($data['request']['server'])){
            ksort($data['request']['server']);
        }

        return $this->render('request', [
            'fullData' => $data,
            'data' => $data['request'],
            'action' => 'request',
            'id' => $id
        ]);
    }

    public function actionExceptions($id = null){
        if (($data = $this->getLoggedData($id)) === null) {
            return $this->redirect(Url::toAction('index'));
        }
        return $this->render('exception', [
            'fullData' => $data,
            'data' => isset($data['exception']) ? $data['exception'] : null,
            'action' => 'exceptions',
            'id' => $id
        ]);
    }

    public function actionView($id = null){
        if (($data = $this->getLoggedData($id)) === null) {
            return $this->redirect(Url::toAction('index'));
        }
        $time = 0;
        if (isset($data['view'])){
            foreach ($data['view']['view'] as $view){
                $time += $view['time'];
            }
        }
        $time = sprintf('%.1f', $time);
        return $this->render('view', [
            'fullData' => $data,
            'data' => isset($data['view']['view']) ? $data['view']['view'] : null,
            'action' => 'view',
            'renderer' => isset($data['view']) ? $data['view']['renderer'] : null,
            'time' => $time,
            'id' => $id
        ]);
    }


    public function actionDatabase($id = null){
        if (($data = $this->getLoggedData($id)) === null) {
            return $this->redirect(Url::toAction('index'));
        }
        $time = 0;
        foreach ($data['executed_commands'] as $query){
            $time += $query['time'];
        }
        $time = sprintf('%.1f', $time);
        return $this->render('database', [
            'fullData' => $data,
            'data' => $data['executed_commands'],
            'action' => 'database',
            'id' => $id,
            'time' => $time
        ]);
    }

    private function getLoggedData($id = null)
    {
        if ($id !== null) {
            /**
             * @var CrlDebug $module
             */
            $module = App::$instance->getModule('crl-debug');
            if ($module->storageType == 'file'){
                return $this->getLoggedDataFile($module->fileStorageDirectory.DIRECTORY_SEPARATOR.$id.'.data');
            } else {
                return $this->getLoggedDataSession($id);
            }
        }
        return [
            'environment' => CrlDebug::getEnvironmentData(),
            'request' => CrlDebug::getRequestData(),
            'executed_commands' => [
            ]
        ];
    }
    private function getLoggedDataFile($filePath){
        $debugData = null;
        if (is_file($filePath)){
            $debugData = unserialize(file_get_contents($filePath));
        }
        return $debugData;
    }
    private function getLoggedDataSession($id){
        $debugData = null;
        if (App::$instance->session->has('crl-debug')){
            $loggedData = unserialize(App::$instance->session['crl-debug']);
            if (isset($loggedData[$id])){
                $debugData = $loggedData[$id];
            }
        }
        return $debugData;
    }
}