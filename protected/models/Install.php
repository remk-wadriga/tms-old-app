<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 22.09.14
 * Time: 11:26
 */

class Install extends  CFormModel{
    public $name;
    public $db_host = 'localhost';
    public $db_dbname;
    public $db_username;
    public $db_password;
    public $db_tablePrefix = 'tbl_';

    public $admin_email;

    public function rules()
    {
        return array(
            array('name, db_host, db_dbname, db_username, db_password, db_tablePrefix, admin_email', 'required'),
            array('admin_email', 'email'),
            // Check connection to DB
            array('db_host', 'checkConnect'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'name'=> 'Name System',
            'db_host'=> 'DB Host',
            'db_dbname'=> 'DB Name',
            'db_username'=>'DB User',
            'db_password'=>'DB password',
            'db_tablePrefix'=>'Default Table Prefix',
            'admin_email'=>'Admin email',
        );
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function checkConnect($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            $connection = @mysql_connect($this->db_host, $this->db_username, $this->db_password);
            if ($connection)
                $connection = @mysql_select_db($this->db_dbname, $connection);

            if(!$connection)
                $this->addError('db_host', 'Неможливо з’єднатися з БД');
        }
    }

    /**
     * @return bool
     * @throws CHttpException
     */
    public function setup()
    {
        if ($this->validate())
        {
            $configDir = Yii::app()->basePath.DIRECTORY_SEPARATOR."config";
            $configFile = $configDir.DIRECTORY_SEPARATOR."main.php";
            $consoleFile = $configDir.DIRECTORY_SEPARATOR."console.php";
            $cronFile = $configDir.DIRECTORY_SEPARATOR."clearTemp.php";
            $configString = Yii::app()->controller->renderPartial('configFile',array('model'=>$this), true);
            $consoleString = Yii::app()->controller->renderPartial('consoleFile',array('model'=>$this), true);
            $cronString = Yii::app()->controller->renderPartial('clearTempFile',array('model'=>$this), true);


            $config = fopen($configFile, 'w+');
            if (!$config)
                throw new CHttpException(400, "not permission to config dir");

            @fwrite($config, $configString);

            @fclose($config);

            @chmod($configFile, 0666);

            $cron = fopen($cronFile, 'w+');
            if (!$cron)
                throw new CHttpException(400, "not permission to config dir");

            @fwrite($cron, $cronString);

            @fclose($cron);

            @chmod($cronFile, 0666);


            $console = fopen($consoleFile, 'w+');
            if (!$console)
                throw new CHttpException(400, "not permission to config dir");

            @fwrite($console, $consoleString);

            @fclose($console);

            @chmod($consoleFile, 0666);

            $connectionString = "mysql:host=".$this->db_host.";dbname=".$this->db_dbname;
            $connection=new CDbConnection($connectionString,$this->db_username,$this->db_password);
            $connection->tablePrefix = $this->db_tablePrefix;
            $connection->active=true;
            Yii::app()->setComponent('db', $connection);


            // Install core admin
            $runner=new CConsoleCommandRunner();
            $runner->commands=array(
                'migrate' => array(
                    'class' => 'system.cli.commands.MigrateCommand',
                    'migrationTable' => '{{migration}}',
                    'interactive' => false,
                ),
            );

            ob_start();
            $runner->run(array(
                'yiic',
                'migrate',
            ));
            ob_get_clean();


            return true;
        } else
            return false;

    }
    /**
     * Create default roles ADMIN
     * @return bool
     */
    public static function createDefaultRoles() {

        $auth = Yii::app()->authManager;
        $auth->createRole('admin');
        return true;
    }

    /**
     * Create default admin user
     * @return bool
     */
    public static function createAdminUser()
    {
        self::createDefaultRoles();
        $model = new User("install");
        $model->username = "admin";
        $model->password = "admin";
        $model->name = "Адміністратор";
        $model->email = Yii::app()->params['adminEmail'];
        $model->role = "admin";
        $model->status = User::STATUS_ACTIVE;
        if ($model->save(false))
            return true;
        else
            return false;
    }
} 