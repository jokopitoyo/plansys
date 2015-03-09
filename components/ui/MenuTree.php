<?php

/**
 * Class MenuTree
 * @author rizky
 */
class MenuTree extends CComponent {

    public static function listAllFile() {
        $files = [];

        ## dev
        if (Setting::get('app.mode') == "plansys") {
            $dir = Yii::getPathOfAlias('application.modules');
            $modules = glob($dir . DIRECTORY_SEPARATOR . "*");
            foreach ($modules as $m) {
                $module = ucfirst(str_replace($dir . DIRECTORY_SEPARATOR, '', $m));
                $items = MenuTree::listFile($module);
                if (count($items) > 0) {
                    $files[] = [
                        'module' => $module,
                        'items'  => $items
                    ];
                }
            }
        }

        ## app
        $dir = Yii::getPathOfAlias('app.modules');
        $modules = glob($dir . DIRECTORY_SEPARATOR . "*");
        foreach ($modules as $m) {
            $module = ucfirst(str_replace($dir . DIRECTORY_SEPARATOR, '', $m));
            $items = MenuTree::listFile($module);
            if (count($items) > 0) {
                $files[] = [
                    'module' => $module,
                    'items'  => $items
                ];
            }
        }
        return $files;
    }

    CONST OPTIONS_COMMENT_START = "## AUTOGENERATED OPTIONS - DO NOT EDIT";
    CONST OPTIONS_COMMENT_END = "## END OF AUTOGENERATED OPTIONS";

    public static function isModeLocked($alias) {
        $path = Asset::resolveAlias($alias . ".php");
        if (isset($path)) {
            $file = file($path);

            $foundPHP = false;
            $startPHP = 0;
            $startLine = false;
            $lineLength = false;
            foreach ($file as $k => $f) {
                $tf = trim($f);
                if (!$foundPHP) {
                    if (substr($tf, 0, 5) == "<?php") {
                        $foundPHP = true;
                        $startPHP = $k + 1;
                    }
                } else if ($tf != "") {
                    if (!$startLine) {
                        if (substr($tf, 0, 10) == substr(MenuTree::OPTIONS_COMMENT_START, 0, 10)) {
                            $startLine = $k + 1;
                        }
                    } else if ($startLine && !$lineLength) {
                        if (substr($tf, 0, 4) == substr(MenuTree::OPTIONS_COMMENT_END, 0, 4)) {
                            $lineLength = $k - $startLine;
                        }
                    } else if ($lineLength) {
                        if (!preg_match("/return\s+array\s*\(/", $tf)) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
            die();
            return false;
        }
    }

    public static function setOptions($alias, $options) {
        $path = Asset::resolveAlias($alias . ".php");
        if (isset($path)) {
            $file = file($path);

            $foundPHP = false;
            $startPHP = 0;
            $startLine = false;
            $lineLength = false;
            foreach ($file as $k => $f) {
                $tf = trim($f);
                if (!$foundPHP) {
                    if (substr($tf, 0, 5) == "<?php") {
                        $foundPHP = true;
                        $startPHP = $k + 1;
                    }
                } else if ($tf != "") {
                    if (!$startLine) {
                        if ($tf == MenuTree::OPTIONS_COMMENT_START) {
                            $startLine = $k + 1;
                        }
                    } else if ($startLine && !$lineLength) {
                        if ($tf == MenuTree::OPTIONS_COMMENT_END) {
                            $lineLength = $k - $startLine;
                        }
                    }
                }
            }

            $optionsCode = explode("\n", '$options = ' . FormBuilder::formatCode($options, ""));
            $optionsCode[count($optionsCode) - 1] = $optionsCode[count($optionsCode) - 1] . ";";

            if (!$startLine) {
                array_unshift($optionsCode, MenuTree::OPTIONS_COMMENT_START);
                array_push($optionsCode, MenuTree::OPTIONS_COMMENT_END);
                $lineLength = 0;
                $startLine = $startPHP;
            }

            foreach ($optionsCode as $k => $o) {
                $optionsCode[$k] = $optionsCode[$k] . "\n";
            }

            array_splice($file, $startLine, $lineLength, $optionsCode);
            return implode("", $file);
        }
    }

    public static function writeOptions($alias, $options) {
        $path = Asset::resolveAlias($alias . ".php");
        file_put_contents($path, MenuTree::setOptions($alias, $options));
    }

    public static function getOptions($alias) {
        $path = Asset::resolveAlias($alias . ".php");

        ob_start();
        include($path);
        ob_get_clean();

        if (!isset($options)) {
            $options = [
                'mode' => 'normal'
            ];
        }

        return $options;
    }

    public static function listFile($module) {
        $path = "application.modules." . lcfirst($module) . ".menus";
        $dir = Yii::getPathOfAlias($path);

        if (!is_dir($dir)) {
            $path = "app.modules." . lcfirst($module) . ".menus";
            $dir = Yii::getPathOfAlias($path);
        }

        $items = glob($dir . DIRECTORY_SEPARATOR . "*");
        foreach ($items as $k => $m) {
            $m = str_replace($dir . DIRECTORY_SEPARATOR, "", $m);
            $ext = explode(".", $m);
            $ext = $ext[count($ext) - 1];

            if ($ext == "php") {
                $m = str_replace('.php', "", $m);

                $items[$k] = [
                    'name'       => $m,
                    'module'     => $module,
                    'class'      => $path . '.' . $m,
                    'class_path' => $path
                ];
            } else {
                unset($items[$k]);
            }
        }
        return $items;
    }

    public static function listDropdown($module, $includeEmpty = true, $withClass = true) {
        $raw = MenuTree::listFile($module);
        $list = [];
        if ($includeEmpty) {
            if ($includeEmpty !== true) {
                $list[''] = $includeEmpty;
            } else {
                $list[''] = "-- Empty --";
            }
            $list['---'] = '---';
        }

        foreach ($raw as $r) {
            if ($withClass) {
                $list[$r['class']] = $r['name'];
            } else {
                $list[$r['name']] = $r['name'];
            }
        }

        return $list;
    }

    public static function fillMenuItems(&$list) {
        if (!is_array($list)) {
            $list = [];
        }

        $markCollapsed = 'collapsed';

        foreach ($list as $k => $v) {
            if (isset($v['url']) && is_string($v['url'])) {
                $v['url'] = str_replace("index.php?", "!@#$%^&*()~~~", $v['url']);

                $list[$k]['url'] = str_replace('?', '&', $v['url']);
                $list[$k]['url'] = str_replace("!@#$%^&*()~~~", "index.php?", $list[$k]['url']);
            }


            if (!isset($v['items'])) {
                $list[$k]['items'] = [];
            } else {
                $list[$k]['state'] = MenuTree::fillMenuItems($list[$k]['items']);
                $markCollapsed = $list[$k]['state'];
            }

            if (isset($v['active']) && $v['active']) {
                $markCollapsed = '';
            }
        }

        return $markCollapsed;
    }

    public static function formatMenuItems(&$list, $recursed = false) {
        foreach ($list as $k => $v) {
            if (@$v['icon'] != '') {
                $list[$k]['label'] = '<i class="fa fa-fw ' . $v['icon'] . '"></i> ' . $list[$k]['label'];
            }

            if ($v['label'] == '---') {
                $list[$k]['template'] = '<hr/>';
            }

            if (!isset($v['url'])) {
                $list[$k]['url'] = ['#'];
            } else {
                if (!is_array($v['url'])) {
                    if ($v['url'] == '#') {
                        $v['url'] = ['#'];
                    } else if (substr($v['url'], 0, 4) != 'http') {
                        $list[$k]['url'] = [str_replace(["\n", "\r"], "", str_replace('?', '&', $v['url']))];
                    }
                }
            }

            if (isset($v['items'])) {
                if (!$recursed) {
                    $list[$k]['label'] = ' <span class="caret"></span> ' . $list[$k]['label'];
                }
                MenuTree::formatMenuItems($list[$k]['items'], true);
            } else {
                $list[$k]['itemOptions'] = [
                    'class' => 'no-menu'
                ];
            }
        }
    }

    public static function cleanMenuItems(&$list) {
        foreach ($list as $k => $v) {
            if (isset($v['items'])) {
                MenuTree::cleanMenuItems($list[$k]['items']);
            }
            if (isset($list[$k]['items']) && empty($list[$k]['items'])) {
                unset($list[$k]['items']);
            }
            if (@$list[$k]['state'] == "") {
                unset($list[$k]['state']);
            }
        }
    }

    public $title = "";
    public $list = "";
    public $icon = "";
    public $class = "";
    public $classpath = "";
    public $options = "";
    public $sections = [];
    public $inlineJS = '';

    public static function load($classpath, $options = null) {
        $mt = new MenuTree;
        $mt->title = @$options['title'];
        $mt->icon = @$options['icon'];
        $mt->options = @$options['options'];
        $mt->inlineJS = @$options['inlineJS'];
        $mt->sections = !is_array(@$options['sections']) ? [] : @$options['sections'];
        $mt->classpath = $classpath;
        $mt->class = Helper::explodeLast(".", $classpath);
        $mt->list = include(Yii::getPathOfAlias($classpath) . ".php");
        MenuTree::fillMenuItems($mt->list);
        return $mt;
    }

    public function renderScript() {
        $inlineJS = '';
        if (is_string($this->inlineJS) && $this->inlineJS != '') {
            $inlineJSPath = dirname(Yii::getPathOfAlias($this->classpath)) . DIRECTORY_SEPARATOR . $this->inlineJS;
            if (is_file($inlineJSPath)) {
                $tab = '                ';
                $inlineJS = file($inlineJSPath);
                $inlineJS = $tab . implode($tab, $inlineJS);
            }
        }

        $script = Yii::app()->controller->renderPartial('//layouts/menu.js', [
            'list'     => $this->list,
            'class'    => $this->class,
            'options'  => $this->options,
            'sections' => $this->sections,
            'inlineJS' => $inlineJS
                ], true);

        return str_replace(["<script>", "</script>"], "", $script);
    }

    public function render($registerScript = true) {
        $ctrl = Yii::app()->controller;

        if ($registerScript) {
            $id = "NGCTRLMENUTREE_{$this->class}_" . rand(0, 1000);
            $script = false;
            Yii::app()->clientScript->registerScript($id, $this->renderScript(), CClientScript::POS_END);
        } else {
            $script = $this->renderScript();
        }

        return $ctrl->renderPartial("//layouts/menu", [
                    'class'     => $this->class,
                    'classpath' => $this->classpath,
                    'title'     => $this->title,
                    'icon'      => $this->icon,
                    'options'   => $this->options,
                    'script'    => $script,
                        ], true);
    }

}
