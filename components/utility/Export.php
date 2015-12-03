<?php
class Export extends CComponent {
    public static function toExcel($model, $config = null) {
        
    }
    
    public static function download($fileNameResource, $extResource,  $fileNameResult, $data, $mode = []) {
        $fileNameResult = $fileNameResult.'.'.$extResource;

        # checking tmp directory for save result
        $assetsPath = Setting::getAssetPath();
        $tmpDir = $assetsPath . DIRECTORY_SEPARATOR . "exports";
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, TRUE);
        }

        # checking fileNameResourse
        $fileNameResourceExplode = explode('.', $fileNameResource);
        if (count($fileNameResourceExplode) == 2) {
            $pathResources = Yii::getPathOfAlias('app.modules.' . $fileNameResourceExplode[0] . '.reports.' . $fileNameResourceExplode[1]);
        } else {
            $pathResources = Yii::getPathOfAlias('app.reports.' . $fileNameResourceExplode[0]);
        }
        $pathResources.='.' . $extResource;
        
        if(file_exists($pathResources) != true) {
            // throw new CDbException('The specified file cannot be found.');
            echo ('The specified file cannot be found.');
            exit();
        } 
        
        $allow_ext = array('odt', 'ods', 'odp', 'odg', 'odf', 'docx', 'xlsx', 'pptx');
        if(!in_array($extResource, $allow_ext)) {
            echo ('Extension File yang diijinkan hanya [odt, ods, odp, odg, odf, docx, xlsx, pptx]');
            exit();
        }

        // spl_autoload_unregister(array('YiiBase','autoload'));
        // Include classes
        require_once Yii::getPathOfAlias('ext.ertong.tbs.opentbs.demo.tbs_class') . '.php';
        require_once Yii::getPathOfAlias('ext.ertong.tbs.opentbs.tbs_plugin_opentbs') . '.php';
// 		spl_autoload_register(array('YiiBase','autoload')); 
        
        // prevent from a PHP configuration problem when using mktime() and date()
        if (version_compare(PHP_VERSION, '5.1.0') >= 0) {
            if (ini_get('date.timezone') == '') {
                date_default_timezone_set('UTC');
            }
        }

        // Initialize the TBS instance
        $TBS = new clsTinyButStrong; // new instance of TBS
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
        
        $template = $pathResources;
        
         $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        
        # get Key of array data
        foreach (array_keys($data) as $key) {
            $TBS->MergeBlock($key, $data[$key]);
        }
        
        if (isset($mode['x_picture'])) {
            $x_picture = $mode['x_picture'];
            $TBS->PlugIn(OPENTBS_MERGE_SPECIAL_ITEMS);
        }
        
        $save_as = (isset($_POST['save_as']) && (trim($_POST['save_as']) !== '') && ($_SERVER['SERVER_NAME'] == 'localhost')) ? trim($_POST['save_as']) : '';
        $output_file_name = $fileNameResult;
        if ($save_as === '') {
            // Output the result as a downloadable file (only streaming, no data saved in the server)
            $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name);
            // Be sure that no more output is done, otherwise the download file is corrupted with extra data.
            exit();
        } else {
            // Output the result as a file on the server.
            $TBS->Show(OPENTBS_FILE, $output_file_name); // Also merges all [onshow] automatic fields.
            // The script can continue.
            exit("File [$output_file_name] has been created.");
        }
    }
 }