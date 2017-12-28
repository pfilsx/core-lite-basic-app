<?php


namespace app\modules\crl_debug;


use Core;
use core\base\App;
use core\base\ExceptionManager;
use core\base\Response;
use core\components\Module;
use core\components\View;
use core\db\Command;
use core\helpers\FileHelper;

class CrlDebug extends Module
{

    private $_command_start_time;
    private $_command_end_time;

    private $_render_start_time;
    private $_render_end_time;

    private $_sessionId;

    private $_exception;

    public $storageType = 'session';

    public $fileStorageDirectory = '@app/runtime/crl-debug';


    public function getId()
    {
        return 'crl-debug';
    }

    public function initializeModule()
    {
        if (CRL_DEBUG) {
            Core::setAlias('crl-debug', __DIR__);
            Command::addEventHandler(Command::EVENT_BEFORE_EXECUTE, [$this, 'beforeCommandExecute']);
            Command::addEventHandler(Command::EVENT_AFTER_EXECUTE, [$this, 'afterCommandExecute']);
            Response::addEventHandler(Response::EVENT_AFTER_PREPARE, [$this, 'afterResponsePrepare']);
            View::addEventHandler(View::EVENT_BEFORE_RENDER, [$this, 'beforeViewRender']);
            View::addEventHandler(View::EVENT_AFTER_RENDER, [$this, 'afterViewRender']);
            ExceptionManager::addEventHandler(ExceptionManager::EVENT_BEFORE_RENDER, [$this, 'beforeExceptionRender']);
            App::$instance->router->addRules([
                $this->id => ['route' => $this->id . '/default/index'],
                "{$this->id}/default" => ['route' => $this->id.'/default/index'],
                "{$this->id}/<action>" => ['route' => $this->id . '/default/<action>'],
                "{$this->id}/<controller>/<action>/<param>" => ['route' => $this->id . '/default/<action>?id=<param>'],
            ]);
            if ($this->storageType == 'file'){
                $this->fileStorageDirectory = FileHelper::normalizePath(Core::getAlias($this->fileStorageDirectory));
                FileHelper::createDirectory($this->fileStorageDirectory, 0777);
            } else {
                if (!App::$instance->session->has($this->id)) {
                    App::$instance->session[$this->id] = [];
                }
            }
        }
    }

    public function beforeCommandExecute($args)
    {
        $this->_command_start_time = microtime(true);
    }

    public function afterCommandExecute($args)
    {
        /**
         * @var $command Command
         */
        $command = $args['callerObj'];
        $this->_command_end_time = microtime(true);

        if (!App::$instance->session->has('crl-debug-runtime')){
            App::$instance->session['crl-debug-runtime'] = [];
        }
        $data = App::$instance->session['crl-debug-runtime'];
        if (!isset($data['commands'])){
            $data['commands'] = [];
        }
        $data['commands'][] = [
            'sql' => $command->getRawSql(),
            'time' => ($this->_command_end_time - $this->_command_start_time) * 1000
        ];
        App::$instance->session['crl-debug-runtime'] = $data;
    }

    public function afterResponsePrepare($args){
        /**
         * @var Response $response
         */
        $response = $args['callerObj'];
        if (($module = App::$instance->router->getModuleClass()) !== null && $module::className() == static::className()) {
            return;
        }
        $this->prepareDataLog($response);
        if ($response->format == Response::FORMAT_HTML && $response->content != null){
            $content = View::renderPartial('@crl-debug/views/debugpanel.php', ['debug' => $this]);
            $response->content = strtr($response->content, ['</body>' => $content . '</body>']);
        }
    }

    public function beforeExceptionRender($args){
        $this->_exception = $args['exception'];
    }

    public function beforeViewRender($args){
        $this->_render_start_time = microtime(true);
    }

    public function afterViewRender($args){
        $this->_render_end_time = microtime(true);
        if (!App::$instance->session->has('crl-debug-runtime')){
            App::$instance->session['crl-debug-runtime'] = [];
        }

        $data = App::$instance->session['crl-debug-runtime'];
        if (!isset($data['views'])){
            $data['views'] = [];
        }
        $data['views']['renderer'] = $args['renderer'];
        if (!isset($data['views']['view'])){
            $data['views']['view'] = [];
        }
        $data['views']['view'][] = [
            'file' => $args['file'],
            'params' => $args['params'],
            'time' => ($this->_render_end_time - $this->_render_start_time) * 1000
        ];
        App::$instance->session['crl-debug-runtime'] = $data;
    }


    public function getSessionId()
    {
        if ($this->_sessionId == null) {
            $this->_sessionId = uniqid();
        }
        return $this->_sessionId;
    }

    private function logData($key, $data)
    {
        if ($this->storageType == 'file'){
            $this->logFileData($key, $data);
        } else {
            $this->logSessionData($key, $data);
        }
    }

    private function logFileData($key, $data){
        $filePath = $this->fileStorageDirectory.DIRECTORY_SEPARATOR.$key.'.data';
        $indexFilePath = $this->fileStorageDirectory.DIRECTORY_SEPARATOR.'index.data';

        file_put_contents($filePath, serialize($data));

        $time = $data['request']['time'];
        $method = $data['request']['method'];
        $url = $data['request']['url'];
        $statusCode = $data['request']['statusCode'];

        $indexData = [];
        if (is_file($indexFilePath)){
            $indexData = unserialize(file_get_contents($indexFilePath));
        }
        $indexData[] = [
            'id' => $key,
            'time' => $time,
            'method' => $method,
            'url' => $url,
            'statusCode' => $statusCode
        ];
        file_put_contents($indexFilePath, serialize($indexData));
    }

    private function logSessionData($key, $data){
        $debugData = unserialize(App::$instance->session[$this->id]);
        if (!isset($debugData[$key])) {
            if (count($debugData) >= 10) {
                array_shift($debugData);
            }
        }
        $debugData[$key] = $data;
        App::$instance->session[$this->id] = serialize($debugData);
    }

    private function getLoggedData($key, $default = null)
    {
        if ($this->storageType == 'file'){
            $filePath = $this->fileStorageDirectory.DIRECTORY_SEPARATOR.$this->getSessionId().'.data';
            if (is_file($filePath)){
                $data = unserialize(file_get_contents($filePath));
                if (isset($data[$key])) {
                    return $data[$key];
                }
            }
            return $default;
        } else {
            $debugData = unserialize(App::$instance->session[$this->id]);
            return isset($debugData[$this->getSessionId()][$key]) ? $debugData[$this->getSessionId()][$key] : $default;
        }
    }

    private function prepareDataLog($response)
    {
        $envData = static::getEnvironmentData();
        $requestData = static::getRequestData($response);
        $dbData = static::getDbData();
        $viewData = static::getViewsData();

        $this->logData($this->getSessionId(), [
            'environment' => $envData,
            'request' => $requestData,
            'executed_commands' => $dbData,
            'exception' => $this->_exception,
            'view' => $viewData
        ]);
    }

    /**
     * @param Response $response
     * @param bool $withText
     * @return string
     */
    private static function getResponseStatusCode($response, $withText = true)
    {
        $statusCode = $response->getStatusCode();
        if ($withText) {
            switch ($statusCode) {
                case 100:
                    $text = 'Continue';
                    break;
                case 101:
                    $text = 'Switching Protocols';
                    break;
                case 200:
                    $text = 'OK';
                    break;
                case 201:
                    $text = 'Created';
                    break;
                case 202:
                    $text = 'Accepted';
                    break;
                case 203:
                    $text = 'Non-Authoritative Information';
                    break;
                case 204:
                    $text = 'No Content';
                    break;
                case 205:
                    $text = 'Reset Content';
                    break;
                case 206:
                    $text = 'Partial Content';
                    break;
                case 300:
                    $text = 'Multiple Choices';
                    break;
                case 301:
                    $text = 'Moved Permanently';
                    break;
                case 302:
                    $text = 'Moved Temporarily';
                    break;
                case 303:
                    $text = 'See Other';
                    break;
                case 304:
                    $text = 'Not Modified';
                    break;
                case 305:
                    $text = 'Use Proxy';
                    break;
                case 400:
                    $text = 'Bad Request';
                    break;
                case 401:
                    $text = 'Unauthorized';
                    break;
                case 402:
                    $text = 'Payment Required';
                    break;
                case 403:
                    $text = 'Forbidden';
                    break;
                case 404:
                    $text = 'Not Found';
                    break;
                case 405:
                    $text = 'Method Not Allowed';
                    break;
                case 406:
                    $text = 'Not Acceptable';
                    break;
                case 407:
                    $text = 'Proxy Authentication Required';
                    break;
                case 408:
                    $text = 'Request Time-out';
                    break;
                case 409:
                    $text = 'Conflict';
                    break;
                case 410:
                    $text = 'Gone';
                    break;
                case 411:
                    $text = 'Length Required';
                    break;
                case 412:
                    $text = 'Precondition Failed';
                    break;
                case 413:
                    $text = 'Request Entity Too Large';
                    break;
                case 414:
                    $text = 'Request-URI Too Large';
                    break;
                case 415:
                    $text = 'Unsupported Media Type';
                    break;
                case 500:
                    $text = 'Internal Server Error';
                    break;
                case 501:
                    $text = 'Not Implemented';
                    break;
                case 502:
                    $text = 'Bad Gateway';
                    break;
                case 503:
                    $text = 'Service Unavailable';
                    break;
                case 504:
                    $text = 'Gateway Time-out';
                    break;
                case 505:
                    $text = 'HTTP Version not supported';
                    break;
                default:
                    $text = 'unknown';
                    break;
            }
            return "$statusCode $text";
        }
        return $statusCode;
    }

    public static function getEnvironmentData()
    {
        $bytes = memory_get_peak_usage(true);
        return [
            'memory_peak' => ($bytes / (1024 * 1024)) . 'M',
            'php_version' => phpversion(),
            'core_version' => Core::version(),
            'memory_limit' => ini_get('memory_limit'),
            'environment' => CRL_ENV,
            'architecture' => (2147483647 == PHP_INT_MAX ? 32 : 64),
            'timezone' => date_default_timezone_get(),
            'charset' => App::$instance->charset,
            'debug' => CRL_DEBUG,
            'xdebug' => extension_loaded('xdebug'),
            'config' => App::$instance->config
        ];
    }

    public static function getDbData(){
        $data = [];
        if (App::$instance->session->has('crl-debug-runtime')){
            $debugData = App::$instance->session['crl-debug-runtime'];
            if (isset($debugData['commands'])) {
                $data = $debugData['commands'];
                unset($debugData['commands']);
                App::$instance->session['crl-debug-runtime'] = $debugData;
            }
        }
        return $data;
    }

    public static function getViewsData(){
        $data = [];
        if (App::$instance->session->has('crl-debug-runtime')){
            $debugData = App::$instance->session['crl-debug-runtime'];
            if (isset($debugData['views'])) {
                $data = $debugData['views'];
                unset($debugData['views']);
                App::$instance->session['crl-debug-runtime'] = $debugData;
            }
        }
        return $data;
    }

    public static function getRequestData($response = null)
    {
        $controller = App::$instance->router->controllerClass;
        $request = App::$instance->request;
        $router = App::$instance->router;
        $response = $response != null ? $response : App::$instance->response;
        return [
            'statusCode' =>  static::getResponseStatusCode($response, false),
            'status' =>  static::getResponseStatusCode($response),
            'route' =>  ($router->module != null ? $router->module . '/' : '') . $router->controller . '/' . $router->action,
            'module' =>  ($module = $router->getModuleClass()) !== null ? $module : null,
            'controller' =>  $controller != null ? $controller::className() : null,
            'action' =>  App::$instance->router->getActionMethod(),
            'url' =>  $request->getAbsoluteUrl(),
            'time' =>  time(),
            'method' =>  $request->getMethod(),
            'get' => $request->get,
            'post' => $request->post,
            'request_headers' => $request->headers->toArray(),
            'response_headers' => $response->headers->toArray(),
            'server' => $_SERVER,
            'request_cookies' => $request->getCookies()->toArray(),
            'response_cookies' => $response->getCookies()->toArray(),
        ];
    }

    //region getters for panel

    public function getPHPVersion()
    {
        return $this->getLoggedData('environment')['php_version'];
    }

    public function getCoreVersion()
    {
        return $this->getLoggedData('environment')['core_version'];
    }
    public function getMemoryPeak()
    {
        return $this->getLoggedData('environment')['memory_peak'];
    }
    public function getMemoryLimit()
    {
        return $this->getLoggedData('environment')['memory_limit'];
    }
    public function getRequestRoute()
    {
        return $this->getLoggedData('request')['route'];
    }
    public function getActiveModule()
    {
        /**
         * @var Module $module
         */
        if (($module = $this->getLoggedData('request')['module']) !== null) {
            return '<div class="crl-debug-detail-row"><b>Module</b> <span>' . $module::className() . '</span></div>';
        }
        return null;
    }
    public function getActiveController()
    {
        return $this->getLoggedData('request')['controller'];
    }
    public function getActiveAction()
    {
        return $this->getLoggedData('request')['action'];
    }
    public function getStatus()
    {
        return $this->getLoggedData('request')['status'];
    }
    public function renderStatus()
    {
        $statusCode = $this->getLoggedData('request')['statusCode'];
        $className = 'crl-debug-block-label';
        if ($statusCode == 200) {
            $className .= ' crl-debug-label-success';
        } elseif ($statusCode >= 400) {
            $className .= ' crl-debug-label-danger';
        }
        return '<span class="' . $className . '">' . $statusCode . '</span>';
    }
    public function getDbLog()
    {
        if ($this->getDbQueriesCount() > 0) {
            $content = View::renderPartial('@crl-debug/views/dblog.php', ['debug' => $this]);
            return $content;
        }
        return null;
    }
    public function getDbQueriesCount()
    {
        return count($this->getLoggedData('executed_commands', []));
    }
    public function getDbQueriesTotalTime()
    {
        $time = 0;
        foreach ($this->getLoggedData('executed_commands', []) as $command) {
            $time += $command['time'];
        }
        return sprintf('%.1f ms', $time);
    }
    //endregion
}