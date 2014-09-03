<?php

class ControllerGenerator extends CodeGenerator {

    //Helper UI
    public static function listAllFile() {
        $dir = Yii::getPathOfAlias('application.modules');
        $modules = glob($dir . DIRECTORY_SEPARATOR . "*");
        $files = array();
        foreach ($modules as $m) {
            $module = ucfirst(str_replace($dir . DIRECTORY_SEPARATOR, '', $m));
            $items = ControllerGenerator::listFile($module);
            $files[] = array(
                'module' => $module,
                'items' => $items
            );
        }
        return $files;
    }

    public static function listFile($module) {     
        Yii::import('application.modules.' . lcfirst($module) . '.controllers.*');
        $dir = Yii::getPathOfAlias("application.modules.{$module}.controllers");  
        $items = glob($dir . DIRECTORY_SEPARATOR . "*");

        foreach ($items as $k => $m) {
            $exist = 'yes';
            $m = str_replace($dir . DIRECTORY_SEPARATOR, "", $m);
            $m = str_replace('.php', "", $m);
            if (!class_exists($m)) {
                $exist = 'no';
            }
            
            $items[$k] = array(
                'name' => $m,
                'module' => $module,
                'class' => 'application.modules.' . lcfirst($module) . '.controllers.' . $m,
                'class_path' => 'application.modules.' . lcfirst($module) . '.controllers.',
                'exist' => $exist,
            );
        }
        return $items;
    }

    public function listMethod($class, $class_name) {
        $declaredClasses = get_declared_classes();

        if (!in_array($class_name, $declaredClasses))
            Yii::import($class, true);
        $reflection = new ReflectionClass($class_name);
        $methods = $reflection->getMethods();
        $action = array();
        foreach ($methods as $m) {
            if ($m->class == $class_name && !$reflection->getMethod($m->name)->isProtected() && !$reflection->getMethod($m->name)->isStatic() && self::isAction($m->name) == true) {
                $rawParams = $reflection->getMethod($m->name)->getParameters();
                $params = array();
                if (!empty($rawParams)) {
                    foreach ($rawParams as $p) {
                        if ($p->isOptional()) {
                            if (is_null($p->getDefaultValue())) {
                                $params[] = '$' . $p->getName() . ' = null';
                            } else {
                                $params[] = '$' . $p->getName() . ' = ' . $p->getDefaultValue();
                            }
                        } else {
                            $params[] = '$' . $p->getName();
                        }
                    }
                }

                if (!empty($params))
                    $strParams = implode(',', $params);
                else
                    $strParams = null;
                $action[] = array(
                    'name' => $m->name,
                    'param' => $strParams
                );
            }
        }
        return $action;
    }

    public static function isAction($method) {
        if (substr($method, 0, 6) == 'action')
            return true;
        else
            return false;
    }

    public static function checkUrl($class, $param, $method) {
        $module = explode('.modules.', $class);
        $module = explode('.controllers.', $module[1]);
        $moduleName = $module[0];
        $controllerName = $module[1];
        $controllerName = lcfirst(substr($controllerName, 0, -10));
        $url = null;
        if (empty($param)) {
            $method = lcfirst(substr($method, 6));
            $url = $moduleName . '/' . $controllerName . '/' . $method;
        }
        return $url;
    }

    public static function controllerPath($class) {
        $classPath = Yii::getPathOfAlias($class);
        $basePath = Yii::getPathOfAlias('application');
        $classPath = str_replace($basePath, '' , $classPath);
        $classPath = $classPath .'.php';
        return $classPath;
    }

    public static function controllerName($class) {
        $className = explode('.controllers.', $class);
        $className = $className[1];
        return $className;
    }

    public static function moduleControllerName($class) {
        $module = explode('.modules.', $class);
        $module = explode('.controllers.', $module[1]);
        $moduleName = $module[0];
        $controllerName = self::controllerName($class);
        return array(
            'module' => $moduleName,
            'controller' => $controllerName
        );
    }

    //CodeGenerator
    protected $baseClass = "Controller";
    protected $basePath = "application.modules.{module}.Controllers";

    public function addActionIndex($actionName, $modelClass = null, $params) {
        $body = '
        $this->renderForm("' . $modelClass . '");';
        $this->updateFunction($actionName, $body, array('params' => $params));
    }

    public function addActionCreate($actionName, $modelClass = null, $params) {
        $body = '
        $model = new ' . $modelClass . ';
                
        if (isset($_POST["' . $modelClass . '"])) {
            $model->attributes = $_POST["' . $modelClass . '"];
            if ($model->save()) {
                $this->redirect(array("index"));
            }
        }
        $this->renderForm("' . $modelClass . '",$model);';
        $this->updateFunction($actionName, $body, array('params' => $params));
    }

    public function addActionUpdate($actionName, $modelClass = null, $params) {
        $body = '
        $model = $this->loadModel($id , "' . $modelClass . '");
                
        if (isset($_POST["' . $modelClass . '"])) {
            $model->attributes = $_POST["' . $modelClass . '"];
            if ($model->save()) {
                $this->redirect(array("index"));
            }
        }
        $this->renderForm("' . $modelClass . '",$model);';
        $this->updateFunction($actionName, $body, array('params' => $params));
    }

    public function addActionDelete($actionName, $modelClass = null, $params) {
        $body = '
        $this->loadModel($id , "' . $modelClass . '")->delete();';
        $this->updateFunction($actionName, $body, array('params' => $params));
    }

    public static function getTemplate() {
        return array(
            'default' => 'Default Action',
            '---' => '---',
            'index' => 'actionIndex',
            'create' => 'actionCreate',
            'update' => 'actionUpdate',
            'delete' => 'actionDelete',
        );
    }

    public function __construct($module, $class) {
        $this->basePath = str_replace('{module}', $module, $this->basePath);
        $this->load($class);
    }

}