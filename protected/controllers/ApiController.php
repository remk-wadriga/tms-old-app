<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 03.10.14
 * Time: 11:13
 *
 * {"user_id":"6", "token":"9823647150", "old_password":"testtest", "new_password":"test"}
 */
Yii::import("application.modules.configuration.models.*");
Yii::import("application.modules.location.models.*");
Yii::import("application.modules.event.models.*");
Yii::import("application.modules.order.models.*");

class ApiController extends CController{

    public $_platform;
    public $_authorized = false;
    public $_user;
    public $_token;
    public $_error;
    public $_tmssec;
    public $ticket;
    public $_places = array();
    public $_ticket = array();

    public static function getActions() {
        return array();
    }

    public function beforeAction($action){
        if ($action->id == "getMap")
            return true;
        $platform_id = Yii::app()->request->getParam('token');
        if ($platform_id) {
            $this->_platform = Platform::model()->findByAttributes(array("partner_id" => $platform_id));
            if ($this->_platform) {
                $allHeaders = getallheaders();
                $tms_sec = isset($allHeaders['tmssec']) ? CJSON::decode(base64_decode($allHeaders['tmssec'])) : false;
                $this->_tmssec = $tms_sec;
                if ($tms_sec) {
                    $userInfo = Yii::app()->request->getParam("userInfo");
                    $userInfoDec = json_decode($userInfo);
                    if ($userInfo && !$userInfoDec)
                        $this->_sendResponse(400, json_encode("bad request"));
                    else
                        $userInfo = $userInfoDec;

                    if (isset($tms_sec['token']) && isset($tms_sec['user_id'])) {
                        $this->_authorized = UserToken::model()->exists("token=:token AND user_id=:user_id AND platform_id=:platform", array(
                            ":token" => $tms_sec['token'],
                            ":user_id" => $tms_sec['user_id'],
                            ":platform" => $this->_platform->id
                        ));

                        if ($this->_authorized)
                            $this->_user = User::model()->findByPk($tms_sec['user_id']);
                    } elseif (isset($tms_sec['user_id']) && isset($tms_sec['service'])) {

                        $user = SocNetworkUser::model()->findByAttributes(array(
                            "network" => $tms_sec['service'],
                            "network_id" => $tms_sec['user_id']
                        ));
                        if ($user)
                            $user = $user->user;

                        if ($action->id == "register") {
                            if ($user && $user instanceof User) {
                                $this->_error = "user with same email is exists";
                                return true;
                            }
                            $name = "";
                            if ($userInfo)
                                $name = $userInfo->name;
                            $user = SocNetworkUser::newUser($tms_sec['user_id'], $tms_sec['service'], $name);

                            if ($userInfo) {
                                $user->name = $name;
                                $user->email = $userInfo->email;
                                $user->surname = $userInfo->last_name;
                                $user->phone = $userInfo->phone;
                                $user->status = User::STATUS_ACTIVE;
                                $user->save(false);
                            }
                        }
                        if (isset($user) && $user instanceof User) {
                            $this->_authorized = true;
                            $this->_user = $user;
                            $this->_token = UserToken::createToken($user, $this->_platform);
                        }
                    } elseif (isset($tms_sec['email']) && isset($tms_sec['password']) && $action->id == "login") {
                        $user = User::model()->findByAttributes(array("email" => $tms_sec['email'], "status" => User::STATUS_ACTIVE));

                        if ($user) {
                            $this->_token = UserToken::createToken($user, $this->_platform);
                            $this->_authorized = $user->validatePassword($tms_sec['password']);
                            if ($this->_authorized)
                                $this->_user = $user;
                        }
                    } elseif (isset($tms_sec['email']) && isset($tms_sec['password']) && $action->id == "register") {

                        $this->_error = "not registered";
                        if (User::model()->exists("email=:email", array(
                            ":email" => $tms_sec['email']
                        ))
                        ) {
                            $this->_error = "user with same email is exists";
                        } else {

                                $user = new User();
                                $user->email = $tms_sec['email'];
                                $user->password = $tms_sec['password'];
                            if ($userInfo) {
                                $user->name = $userInfo->name;
                                $user->surname = $userInfo->last_name;
                                $user->phone = $userInfo->phone;
                            }
                                $user->status = User::STATUS_ACTIVE;
                                $user->type = User::TYPE_SOC_USER;
                                if ($user->save(false)) {
                                    $this->_authorized = true;
                                    $this->_user = $user;
                                    $this->_token = UserToken::createToken($user, $this->_platform);
                                }

                        }
                    }
                }
                return true;
            }
        }
        $this->_sendResponse(400, json_encode("bad request"));
    }

    private function _sendResponse($status = 200, $body = '', $content_type = 'application/json')
    {
        // set the status
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: ' . $content_type);
        // pages with body are easy
        if($body != '')
        {
            // send the body
            echo $body;
        }
        // we need to create the body if none is passed
        else
        {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status)
            {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
                <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
                    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
                </head>
                <body>
                    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
                    <p>' . $message . '</p>
                    <hr />
                    <address>' . $signature . '</address>
                </body>
                </html>';

            echo $body;
        }
        Yii::app()->end();
    }

    private function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    public function isAuthorized()
    {
        if ($this->_authorized)
            return $this->_authorized;
        $this->sendNotAuthorized();
    }

    public function sendNotAuthorized()
    {
        $this->_sendResponse(200, json_encode(array("user"=>"not authorized")));
    }

    public function actionLogin()
    {
        if ($this->_authorized) {
            $this->sendUserInfo();
        } else
            $this->sendNotAuthorized();
    }

    public function sendUserInfo()
    {
        $user = $this->_user;
        if ($user instanceof User) {

            $result = array(
                "user_id"=>$user->id,
                "token"=>$this->_token,
                "name"=>$user->name,
                "last_name"=>$user->surname,
                "phone"=>$user->phone,
                "email"=>$user->email,
                "np_id"=>$user->np_id,
                "country_id"=> $user->country_id,
                "city_id"=> $user->city_id,
                "address"=> $user->address,
                "patr_name"=> $user->patr_name
            );
            $this->_sendResponse(200, json_encode($result));
        }
    }

    public function actionRegister()
    {
        if ($this->_authorized) {
            $this->sendUserInfo();
        } else
            $this->_sendResponse(200, json_encode(array(
                "user"=>$this->_error
            )));
    }

    public function actionUpdateUserInfo()
    {
        if ($this->_authorized) {
            $name = Yii::app()->request->getParams('name');
            $lastName = Yii::app()->request->getParams('lastName');
            $phone = Yii::app()->request->getParams('phone');
            $email = Yii::app()->request->getParams('email');
            /**
             * обовязкові поля
            name: "name_user_from_system”,
            last_name: “Ivanov”,
            phone: “+38068420634”,
            email: “zhenya@gmail.com”

            не обовязкові поля
            country: “Україна”,
            region: “Львівська”
            city: “Львів”
            address: “вул. Володимира Великого, 4”
            number_new_post: “14”

             */
            $this->sendUserInfo();
        } else
            $this->_sendResponse(401, json_encode(array(
                "user"=>$this->_error
            )));
    }

    public function actionCheckIsUserInSystem()
    {
        $email = Yii::app()->request->getParam("email");
        if (!$email)
            $this->_sendResponse(200, json_encode("property `email` not defined"));
        if (User::model()->exists("email=:email AND status=:status", array(
            ":email"=>$email,
            ":status"=>User::STATUS_ACTIVE
        )))
            $this->_sendResponse(200, json_encode(array("msg"=>true)));
        else
            $this->_sendResponse(200, json_encode(array("msg"=>false)));
    }

    public function actionRestorePassword()
    {
        $email = Yii::app()->request->getParam("email");
        if (!$email)
            $this->_sendResponse(200, json_encode("property `email` not defined"));

    }

    public function actionIndex()
    {
        $this->_sendResponse(404);
        Yii::app()->end();
    }

    public function actionChangePassword()
    {
        if ($this->_authorized) {

            if(isset($this->_tmssec['old_password'])&&isset($this->_tmssec['new_password'])) {
                $user = $this->_user;
                if (!$user->validatePassword($this->_tmssec['old_password']))
                    $this->_sendResponse(200, json_encode(array("msg"=>"Старий пароль невірний")));

                $result = "false";
                if ($user->saveAttributes(array("password"=>$user->hashpassword($this->_tmssec['new_password'])))) {
                    $result="true";
                }
                $this->_sendResponse(200, json_encode(array("change_password"=>$result)));
            } else
                $this->_sendResponse(200, json_encode(array("msg"=>"Немає паролю")));

        } else
            $this->sendNotAuthorized();
    }

    public function actionGetUserData()
    {
        if ($this->_authorized) {

            $user = $this->_user;
            if ($user instanceof User) {

                $result = array(
                    "name" => $user->name,
                    "surname" => $user->surname,
                    "phone" => $user->phone,
                    "email" => $user->email,
                    "country_id"=> $user->country_id,
                    "city_id"=> $user->city_id,
                    "address"=> $user->address,
                    "np_id"=> $user->np_id,
                    "patr_name"=> $user->patr_name
                );
                $this->_sendResponse(200, json_encode($result));
            }
        } else {
            $this->sendNotAuthorized();
        }

    }

    public function actionChangeUserData()
    {
        if ($this->_authorized) {
            $userInfo = Yii::app()->request->getParam("userInfo");
            $userInfo = json_decode($userInfo);
            if(isset($this->_tmssec['email'])&&isset($userInfo->name)&&isset($userInfo->surname)) {
                $user = $this->_user;
                $result = "false";
                $dataArr = ["email"=>$this->_tmssec['email'], "name"=>$userInfo->name,
                    "surname"=>$userInfo->surname, "phone"=>$userInfo->phone,
                    "country_id"=>$userInfo->country_id, "city_id"=>$userInfo->city_id,
                    "address"=>$userInfo->address, "np_id"=>$userInfo->np_id, "patr_name"=>$userInfo->patr_name];

                if ($user->saveAttributes($dataArr)) {
                    $result="true";
                }
                if($this->_platform->partner_id = '4657817')
                    $this->_sendResponse(200, CJSON::encode(array("change_user_data"=>$result)));
                else
                    $this->_sendResponse(200, json_encode(array("change_user_data"=>$result)));
            } else {
                if($this->_platform->partner_id = '4657817')
                    $this->_sendResponse(200, CJSON::encode(array("msg"=>"no required fields")));
                else
                    $this->_sendResponse(200, json_encode(array("msg"=>"no required fields")));
            }
        } else
            $this->sendNotAuthorized();
    }

    public function actionGetCountries()
    {
        $models = Country::model()->findAllByAttributes(array("status"=>Country::STATUS_ACTIVE));
        $countries = array();
        foreach ($models as $model) {
            $countries[] = array(
                "id"=>$model->id,
                "name"=>$model->name
            );
        }

        $this->_sendResponse(200, json_encode($countries));
        Yii::app()->end();
    }

    public function actionGetRegions()
    {
        $country_id = Yii::app()->request->getParam("country_id");
        if (!$country_id)
            $this->_sendResponse(200, json_encode("property `country_id` is defined"));
//        $regions =
    }

    public function actionGetCountryById()
    {
        $id = Yii::app()->request->getParam("id");
        if (!$id)
            $this->_sendResponse(400, json_encode("property `id` not defined"));
        $model = Country::model()->findByPk($id, "status=:status", array(":status"=>Country::STATUS_ACTIVE));
        if (!$model)
            $this->_sendResponse(404, json_encode("Country not found!"));
        $country = array(
            "name"=>$model->name
        );
        $this->_sendResponse(200, json_encode($country));
        Yii::app()->end();
    }

    public function actionGetCities()
    {
        $all = Yii::app()->request->getParam("all");
        $translit = Yii::app()->request->getParam("translit");
        $cities = array("Всі міста") + City::getList(false, $all, true);
        $result = array();
        $i = 0;
        foreach ($cities as $k => $city) {
            $temp = array(
                "id"=>$k,
                "name"=>is_array($city) ? $city['name'] : $city,
                "lat"=>is_array($city) ? $city['lat'] : null,
                "lng"=>is_array($city) ? $city['lng'] : null
            );
            if ($translit && is_array($city))
                $temp = $temp + array("translit"=>ucfirst(UrlTranslit::translit($city['name'])));
            $result[$i] = $temp;
            $i++;
        }
        $this->_sendResponse(200, CJSON::encode($result));
        Yii::app()->end();
    }

    public function actionSearchCity()
    {
        $name = Yii::app()->request->getParam('name');
        $country_id = Yii::app()->request->getParam('country_id');
        $region_id = Yii::app()->request->getParam('region_id');
        $all = Yii::app()->request->getParam('all');
        if ($name&&strlen($name)>2&&$country_id) {
            $result = City::getSearchList($country_id,$name, $region_id, $all);
            $this->_sendResponse(200, json_encode($result));

        }
    }

    public function actionGetCityById()
    {
        $id = Yii::app()->request->getParam("id");
        if (!$id)
            $this->_sendResponse(200, json_encode("property `id` not defined"));
        $model = City::model()->findByPk($id);
        if (!$model)
            $this->_sendResponse(200, json_encode("City not found!"));
        $city = array(
            "id"=>$model->id,
            "name"=>$model->name
        );
        $this->_sendResponse(200, json_encode($city));
        Yii::app()->end();
    }

    public function actionGetLocationCats()
    {
        $models = LocationCategory::model()->findAll("status=:status", array(":status"=>LocationCategory::STATUS_ACTIVE));
        $categories = array();
        foreach ($models as $model)
            $categories[] = array(
                'id'=>$model->id,
                'name'=>$model->name,
            );

        $this->_sendResponse(200, json_encode($categories));
    }

    public function actionGetMapInfo()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        if ($event_id) {
            $event = Event::model()->findByPk($event_id);
            $result['hasMacro'] = $event->scheme->hasMacro;
            $result['funzones'] = Sector::getFunSectors($event->scheme_id, true);
            $this->_sendResponse(200, json_encode($result));
        }
    }

    public function actionSector()
    {
        switch(Yii::app()->request->requestType) {
            case "POST":
                $this->createSector();
                break;
            case "GET":
                $this->getSector();
                break;
            case "PUT":
                $this->updateSector();
                break;
            case "DELETE":
                $this->deleteSector();
                break;
            default:
                break;
        }
    }

    public function actionGetSliderEvent()
    {
        $city_id = Yii::app()->request->getParam('city_id');
        $criteria = new CDbCriteria();
        $criteria->with = array('poster', 'scheme', 'scheme.location');
        if ($city_id && $city_id!=0) {
            $criteria->compare("location.city_id", $city_id);
            $criteria->compare('t.slider_city', 1);
        } else {
            $criteria->compare('t.slider_main', 1);
        }
        $criteria->compare('t.status', Event::STATUS_ACTIVE);

        $events = Event::model()->findAll($criteria);
        $events = array_map(function($event){
            return array(
                "id"=>$event->id,
                "poster"=>$this->getPoster($event)
            );
        }, $events);

        $this->_sendResponse(200, CJSON::encode($events));
    }

    /**
     * @param $event Event
     * @return array
     */
    private function getPoster($event)
    {
        return $event->poster ? $event->getPoster() : new stdClass();
    }

    public function actionGetEventList()
    {
        $limit = Yii::app()->request->getParam('limit');
        $offset = Yii::app()->request->getParam('offset');
        $city_id = Yii::app()->request->getParam('city_id');
        $name = Yii::app()->request->getParam('name');
        $category_id = Yii::app()->request->getParam('category_id');
        $modelName = CHtml::modelName(Event::model());
        $event_ids = [];

        if ($category_id) {
            $tags = Tag::model()->findAllByAttributes(["model_name"=>$modelName, "relation_id"=>$category_id]);
            if(!empty($tags)) {
                foreach ($tags as $tag) {
                    $event_ids[] = $tag->model_id;
                }
            } else
                $event_ids[] = 0;
        }

        $criteria = new CDbCriteria();
        $timingTable = Timing::model()->tableName();
        $criteria->select = "*, (SELECT max(stop_sale) from $timingTable where $timingTable.event_id=t.id) as timing";
        $criteria->with = array('poster', 'scheme', 'scheme.location', 'scheme.location.city');

        if ($limit)
            $criteria->limit = $limit;

        if ($offset)
            $criteria->offset = $offset;

        if ($city_id && $city_id!=0)
            $criteria->compare("location.city_id", $city_id);

        if(!empty($event_ids))
            $criteria->addInCondition("t.id",$event_ids);


//        $criteria->addInCondition("id", $this->_platform->getEvents);

        if ($name && $name != "")
            $criteria->compare("t.name", $name, true);

//        $criteria->addCondition("(SELECT MAX(stop_sale) FROM {{timing}} WHERE {{timing}}.event_id=t.id) >= NOW()");
//        $criteria->addCondition("start_sale<=NOW() AND start_sale<>0 AND (end_sale=0 OR end_sale>=NOW())");

        $criteria->compare("t.status", Event::STATUS_ACTIVE);

        $criteria->order = "timing ASC";
        $events = Event::model()->findAll($criteria);

        $events = array_map(function($event){
//            if (!isset($event->places))
//                return false;

            return array(
                "id"=>$event->id,
                "name"=>$event->name,
                "alias"=>$event->url,
                "start_time"=>$event->startTime,
                "location"=>array(
                    "name"=>$event->scheme->location->name,
                    "short_name"=>$event->scheme->location->short_name
                ),
                "city"=>array(
                    "id"=>$event->scheme->location->city_id,
                    "name"=>$event->scheme->location->city->name
                ),
                "poster"=>$this->getPoster($event)
            );

        }, $events);
        $events = array_filter($events);
        $this->_sendResponse(200, CJSON::encode($events));
    }

    public function actionGetEventsByTag()
    {
        $id = Yii::app()->request->getParam('tag_id');
        $city_id = Yii::app()->request->getParam("city_id");

        if ($id) {
            if ($city_id) {
                $events = Tag::getEventsByTag($id, $city_id);
            } else {
                $events = Tag::getEventsByTag($id);
            }

            $events = array_map(function ($event) {

                return array(
                    "id" => $event->id,
                    "name" => $event->name,
                    "start_time" => $event->startTime,
                    "location" => array(
                        "name" => $event->scheme->location->name
                    ),
                    "city" => array(
                        "id" => $event->scheme->location->city_id,
                        "name" => $event->scheme->location->city->name
                    ),
                    "poster" => $this->getPoster($event)
                );

            }, $events);
            $events = array_filter($events);
            $this->_sendResponse(200, json_encode($events));
        } else
            $this->_sendResponse(404, json_encode("Tag not found"));
    }

    public function actionGetEventTags()
    {
        $tags = Tag::getEventTags(Tag::EVENT_CATEGORIES_TREE);

        if(!empty($tags)) {
            $this->_sendResponse(200, json_encode($tags));
        } else
            $this->_sendResponse(200, json_encode("Tags not found"));
    }

    public function actionGetTags()
    {
        $tree_id = Yii::app()->request->getParam("tree_id");
        if ($tree_id){
            $tags = Tag::getEventTags($tree_id);
        } else
            $tags = Tag::getEventTags();
        if(!empty($tags)) {
            $this->_sendResponse(200, json_encode($tags));
        } else
            $this->_sendResponse(200, json_encode("Tags not found"));
    }

    public function actionGetTrees()
    {
        $trees = Tree::getTreeRoots();
        if (!empty($trees)) {
            $this->_sendResponse(200, json_encode($trees));
        } else
            $this->_sendResponse(200, json_encode("Trees not found"));
    }

    public function actionGetEvent()
    {
        $id = Yii::app()->request->getParam('id');
        $alias = Yii::app()->request->getParam('alias');
        if ($id||$alias) {
            $criteria = new CDbCriteria();
            $criteria->with = array('poster', 'timings', 'multimedias', 'scheme', 'scheme.location', 'scheme.location.city');
            if ($id)
                $criteria->compare('t.id', $id);
            else
                $criteria->compare('t.url', $alias);
            $criteria->select = "*, poster.*, scheme.*";
            $model = Event::model()->find($criteria);
            if (!$model)
                $this->_sendResponse(200, json_encode(array("msg"=>"no event was found")));
            $categories = [];
            $modelName = CHtml::modelName(Event::model());
            $tree_ids = [];

            $tags = Tag::model()->findAllByAttributes(["model_name"=>$modelName, "model_id"=>$id]);
            if(!empty($tags)) {
                foreach ($tags as $tag) {
                    $tree_ids[] = $tag->relation_id;
                }
            }
            $trees = Tree::model()->findAllByPk($tree_ids);

            foreach ($trees as $tree) {
                $categories[] = array(
                    "id"=>$tree->id,
                    "name" => $tree->name
                );
            }
            $name = DIRECTORY_SEPARATOR.$model->scheme_id.DIRECTORY_SEPARATOR."bitMap.png";
            $dir = Yii::getPathOfAlias("webroot.scheme").$name;
            $bitMap = null;
            if (file_exists($dir)) {
                $bitMap = Yii::app()->getBaseUrl(true).DIRECTORY_SEPARATOR."scheme".$name;
            }
            if ($model) {
                $result = array(
                    "id"=>$model->id,
                    "name"=>$model->name,
                    "alias"=>$model->url,
                    "location"=>array(
                        "id"=>$model->scheme->location_id,
                        "name"=>$model->scheme->location->name,
                        "lng"=>$model->scheme->location->lng,
                        "lat"=>$model->scheme->location->lat,
                        "city"=>array(
                            "id"=>$model->scheme->location->city_id,
                            "name"=>$model->scheme->location->city->name,
                        )
                    ),
                    "start_time"=>$model->startTime,
                    "status_sale"=>array(
                        "status"=>$model->saleStatus,
                        "message"=>$model->saleStatusText
                    ),
                    "minMaxPrice"=>$model->getMinMaxPrice(),
                    "description"=>$model->description_id,
                    "images"=>$model->getImages(),
                    "poster"=>$this->getPoster($model),
                    "bitmap"=>$bitMap,
                    "categories"=>$categories,
                );
                $this->_sendResponse(200, CJSON::encode($result));
            } else
                $this->_sendResponse(200, json_encode("Event not found"));
        }
    }

    public function actionGetPrices()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        $sector_id = Yii::app()->request->getParam("sector_id");

        if (!$event_id)
            $this->_sendResponse(200, array("msg"=>"property `event_id` not defined"));

        $sectors = Place::getSectors($event_id);
        $result = array();
        
        if (!empty($sectors))
            foreach ($sectors as $sector) {
                if ($event_id) {
                    if (empty($sector->_colors))
                        $sector->setColors(Place::getPrices($event_id, $sector_id, false));
                    $prices = Place::getPrices($event_id, $sector->id, true);
                    foreach ($sector->_colors as $k=>$color) {
                        if (in_array($k, $prices)&&!isset($result[$k]))
                            $result[$k] = array(
                                "price"=>$k,
                                "color"=>$color,
                                "sectors"=>array(
                                    $sector->id
                                )
                            );
                        elseif (in_array($k,$prices))
                            array_push($result[$k]["sectors"],$sector->id);
                    }
                }
            }
        ksort($result);
        $result = array_values($result);

        $this->_sendResponse(200, json_encode($result));
    }

    public function actionGetSectors()
    {
        $event_id = Yii::app()->request->getParam('event_id');
        if ($event_id) {
            $event = Event::model()->with('scheme', 'scheme.sectors')->findByPk($event_id);
            if ($event) {
                if ($event->scheme->sectors) {
                    $sectors = array_map(function($sector) {
                        return (object)array(
                            "id"=>$sector->id,
                            "name"=>$sector->sectorName,
                            "type"=>$sector->type
                        );
                    }, $event->scheme->sectors);
                    $this->_sendResponse(200, CJSON::encode($sectors));
                }
            }
        }
    }

    public function actionGetSectorInfo()
    {
        $sector_id = Yii::app()->request->getParam('sector_id');
        if ($sector_id) {
            $sector = Sector::model()->findByPk($sector_id);
            if ($sector) {
                CVarDumper::dump($sector,10,1);
            }
        }
    }

    /**
     * @TODO need return place info for funzone
     * {sector_id:"row0col0sectorN", event_id:integer, places:[]}
     */
    public function actionGetPlaces()
    {
        $places = Yii::app()->request->getParam('places');
        $sector_id = Yii::app()->request->getParam('sector_id');
        $event_id = Yii::app()->request->getParam('event_id');
        if ($sector_id&&$event_id) {
            $sector_name = Sector::getRowPlaceSector($sector_id);
            $conditionArray = array();
            if ($places)
                $conditionArray = array("not in", "id", json_decode($places));
            $place = Yii::app()->db->createCommand()
                ->select("id")
                ->from("{{place}}")
                ->where("sector_id=:sector_id AND event_id=:event_id AND status=:status", array(
                    ":sector_id"=>$sector_name['sector_id'],
                    ":event_id"=>$event_id,
                    ":status"=>Place::STATUS_SALE
                ))
                ->andWhere($conditionArray)
                ->limit(1)
                ->queryColumn();
            $places = json_encode($place);
        }
        if ($places) {
            $places = $this->formPlaces($places);
            $this->_sendResponse(200, CJSON::encode($places));
        }
    }

    private function formPlaces($places)
    {
        $places = Place::model()->with('sector', 'event')->findAllByAttributes(array("id"=>json_decode($places)));
        $places = array_map(function($place) {
            return array(
                "id"=>$place->id,
                "label"=>$place->type==Place::TYPE_SEAT ? $place->sector->getPlaceName(array("row"=>$place->row, "place"=>$place->place)) : $place->sector->sectorName,
                "row"=>array(
                    "prefix"=>$place->type==Place::TYPE_SEAT ? $place->sector->typeRow->name: "",
                    "name"=>$place->row,
                ),
                "place"=>array(
                    "prefix"=>$place->type==Place::TYPE_SEAT ? $place->sector->typePlace->name : "",
                    "name"=>$place->place
                ),
                "price"=>$place->price,
                "event"=>array(
                    "id"=>$place->event_id,
                    "name"=>$place->event->name
                ),
                "sector"=>array(
                    "id"=>$place->sector_id,
                    "name"=>$place->sector->sectorName
                ),
                "type"=>$place->type,
                "status"=>$place->status
            );
        }, $places);

        return $places;
    }

    public function actionGetPlacesByOrder()
    {
        $order_id = Yii::app()->request->getParam("order_id");
        $places = Yii::app()->db->createCommand()
            ->select("place_id")
            ->from(Ticket::model()->tableName())
            ->where("status=:status AND order_id=:order_id", array(
                ":status"=>Ticket::STATUS_SOLD,
                ":order_id"=>$order_id
            ))
            ->queryColumn();
        if ($places)
            $places = $this->formPlaces(json_encode($places));
        $this->_sendResponse(200, CJSON::encode($places));
    }

    public function actionGetPlacesByTempOrder()
    {
        $order_id = Yii::app()->request->getParam("temp_order");
        $token = Yii::app()->request->getParam("order_token");
        if ($order_id&&$token) {
            $temp = OrderTemp::model()->with('ticketTemps')->findByAttributes(array(
                "id"=>$order_id,
                "token"=>$token
            ));
            if (!$temp&&empty($temp->ticketTemps))
                $this->_sendResponse(200, json_encode(array("msg"=>"no places in cart, or temp order is overdue")));
            $places = $this->formPlaces(json_encode(array_map(function($ticket){ return $ticket->place_id;}, $temp->ticketTemps)));
            $result = array();

            foreach ($places as $place)
                $result[$place['id']] = $place;
            $this->_sendResponse(200, CJSON::encode($result));
        }

    }

    public function actionSaveOrder()
    {
//        $paid = Yii::app()->request->getParam('paid');
        $name = Yii::app()->request->getParam('name');
        $patr_name = Yii::app()->request->getParam('patr_name');
        $np = Yii::app()->request->getParam('np_id');
        $comment = Yii::app()->request->getParam('comment');
        $surname = Yii::app()->request->getParam('surname');
        $phone = Yii::app()->request->getParam('phone');
        $email = Yii::app()->request->getParam('email');
        $country_id = Yii::app()->request->getParam('country_id');
        $city_id = Yii::app()->request->getParam('city_id');
        $address = Yii::app()->request->getParam('address');

        $pay_type = Yii::app()->request->getParam('orderType');
        $this->_ticket = Yii::app()->request->getParam('ticket');
        $tempOrder = Yii::app()->request->getParam('temp_order');
        $token = Yii::app()->request->getParam('order_token');
        if ($tempOrder&&$token) {
            $tempOrder = OrderTemp::model()->findByAttributes(array(
                "id"=>$tempOrder,
                "token"=>$token
            ));
        }

        if ($tempOrder instanceof OrderTemp) {
            foreach ($tempOrder->ticketTemps as $ticketTemp)
                $this->_places[] = $ticketTemp->place;

            if (empty($this->_places))
                $this->_sendResponse(200, json_encode(array("msg"=>"no places in cart, or temp order is overdue")));
            $check = Order::checkPlaceStatus($this->_places, false, $this->_ticket);
            if (is_array($check))
                $this->sendBusyPlace($check);


            if ($this->_places) {
                if ($this->_user)
                    $user = $this->_user;
                else
                    $user = User::model()->findByAttributes(array("email"=>$email));
                if (!$user) {
                    $user = new User();
                    $user->surname = CHtml::encode($surname);
                    $user->name = CHtml::encode($name);
                    $user->patr_name = CHtml::encode($patr_name);
                    $user->np_id = CHtml::encode($np);
                    $user->phone = CHtml::encode($phone);
                    $user->email = CHtml::encode($email);
                    $user->type = User::TYPE_SOC_USER;
                    $user->save(false);
                    $user->refresh();
                }

                $order = new Order();
                $order->type = Order::TYPE_ORDER;
                $order->api = $this->_platform->id;
                $order->city_id = $city_id;
                $order->np_id = $np;
                $order->address = $address;
                $order->comment = $comment;
                $order->role_id = $this->_platform->role_id;
                $order->user_id = $user->id;
                $order->name = CHtml::encode($name);
                $order->patr_name = CHtml::encode($patr_name);
                $order->surname = CHtml::encode($surname);
                $order->email = CHtml::encode($email);
                $order->phone = CHtml::encode($phone);

                if ($order->save(false)) {
                    $tempOrder->delete();
                    Ticket::saveTickets($this->_places,$order, $this->_platform, $pay_type, $this->_ticket);
                    $placeIds = array_map(function($place){
                        return $place->id;
                    }, $this->_places);
                    Ticket::saveState($placeIds, true, $order->user_id);


//                    $this->_sendResponse(200,json_encode(array(
//                        "order_id"=>$order->id
//                    )));

                    $msg = "";
                    if (in_array($pay_type, Order::$physicalPay))
                        $msg = $order->getSuccessMessage($pay_type);
                    $this->_sendResponse(200, json_encode(array(
                        "order_id"=>$order->id,
                        "msg"=>$msg)));
                }
            }
        }
    }

    private function sendBusyPlace($ids)
    {
        $this->_sendResponse(200, json_encode(array("msg"=>"places already sold OR event with this places not in sale",
            "place_ids"=>$ids,
        )));

        Yii::app()->end();
    }

    private function checkPlaceStatus($places, $temp=false, &$eTicket=array())
    {
        $events = array();
        $placeIds = array();
        foreach ($places as $place) {
            if (!isset($events[$place->event_id]))
                $events[$place->event_id] = $place->event_id;
            $placeIds[] = $place->id;
        }
        $tempPlaces = array();
        if ($temp)
            $tempPlaces = Yii::app()->db->createCommand()
                ->select("place_id")
                ->from(TicketTemp::model()->tableName())
                ->where(array("in","place_id", $placeIds))
                ->queryColumn();
        $ticketPlaces = Yii::app()->db->createCommand()
            ->select("place_id")
            ->from(Ticket::model()->tableName())
            ->where(array("in","place_id", $placeIds))
            ->andWhere("status!=:status", array(
                ":status"=>Ticket::STATUS_CANCEL
            ))
            ->queryColumn();
        $check = array();
        $tempPlaces = array_merge($tempPlaces, $ticketPlaces);


        $events = Order::checkIsInSale($events);
        $tempFunIds = array();
        foreach ($places as $k=>$place) {
                if (in_array($place->id, $tempPlaces)||in_array($place->id, $tempFunIds)||in_array($place->event_id, $events)||$place->status != Place::STATUS_SALE) {
                    if ($place->type == Place::TYPE_FUN) {
                        $criteria = new CDbCriteria();
                        $criteria->compare("sector_id", $place->sector_id);
                        $criteria->compare("event_id", $place->event_id);
                        $criteria->compare("status", Place::STATUS_SALE);
                        $criteria->addNotInCondition("id", array_merge($tempPlaces, $events, $tempFunIds));
                        $newPlace = Place::model()->find($criteria);
                        if ($newPlace) {
                            $this->_places[] = $newPlace;
                            $tempFunIds[] = $newPlace->id;
                            if (!empty($this->_ticket)) {
                                $ticket = $this->_ticket[$place->id];
                                $this->_ticket[$newPlace->id] = $ticket;
                                unset($this->_ticket[$place->id]);
                            }
                            unset($this->_places[$k]);
                            continue;
                        }
                    }
                    $check[] = $place->id;
                }
        }
        if (empty($check)&&empty($events))
            return true;
        else
           return $check;
    }

    public function actionCreateTempOrder()
    {
        $placeIds = Yii::app()->request->getParam('places');
        if (!$placeIds)
            $this->_sendResponse(400, json_encode("property `places` not defined"));
        usleep(mt_rand(1, 999999));

        $placeIds = json_decode($placeIds);
        $this->_places = Place::model()->findAllByAttributes(array("id"=>$placeIds));

        if(!empty($this->_places)) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $check = Order::checkPlaceStatus($this->_places, true, $this->_ticket);
                if (is_array($check)) {
                    $transaction->rollBack();
                    $this->sendBusyPlace($check);
                }
                $order = new OrderTemp();
                if ($this->_user)
                    $order->user_id = $this->_user->id;
                $order->role_id = $this->_platform->role_id;
                $order->api = $this->_platform->id;
                $order->_tickets = $this->_places;
                $order->token = OrderTemp::generateRandomString(15);
                if ($order->save()) {
                    $order->saveAttributes(array("total"=>$order->_total));
                    $transaction->commit();
                    $this->_sendResponse(200, json_encode(array("order_id"=>$order->id, "token"=>$order->token)));
                }

            } catch (Exception $e){
                $transaction->rollBack();
            }

        }


        $this->_sendResponse(500, json_encode("can't save temp order"));
    }

    public function actionDeleteTempOrder()
    {
        $order_id = Yii::app()->request->getParam('order_id');
        $token = Yii::app()->request->getParam('order_token');

        if (!$token||!$order_id)
            $this->_sendResponse(200, json_encode("not enough properties given (order_id and token)"));

        Yii::app()->db->createCommand()
            ->delete("{{order_temp}}", "id=:order_id AND token=:token", array(
                ":order_id"=>$order_id,
                ":token"=>$token
            ));
        $this->_sendResponse(200, json_encode("successfully deleted"));
    }

    public function actionCheckAppUser()
    {
        if ($this->_authorized)
            $this->sendUserInfo();
        else
            $this->sendNotAuthorized();
    }

    public function actionGetMap()
    {
        $event_id = Yii::app()->request->getParam('event_id');
        $model = Event::model()->with('scheme')->findByPk($event_id);

        if ($event_id&&$model) {
            if (!$model->isInSale)
                $this->_sendResponse(200, json_encode(array("msg"=>"event not for sale")));
            Scheme::getVisualInfo($model, $event_id);
        }
        else
            $this->_sendResponse(404, json_encode("Event not found"));
    }

    public function actionPreviewScheme()
    {
//        $id = Yii::app()->request->getParam("scheme_id");
        $event_id = Yii::app()->request->getParam("event_id");
        if (!$event_id)
            $this->_sendResponse(400, json_encode("property `id` not defined"));

        $model = Event::model()->with('scheme','scheme.sectors')->findByPk($event_id);

        $detect = Yii::app()->detect;
        $isMobile = ($detect->isMobile()||$detect->isTablet());
        $cs = Yii::app()->clientScript;
        $cs->scriptMap = array(
            "jquery.ui.min.js"=>false
        );
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery.mousewheel.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery-ui.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/config.js");
        if ($isMobile)
            $cs->registerScriptFile(Yii::app()->baseUrl."/js/m.svg.min.js");
        else
            $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.import.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.pan-zoom.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/webservice_editor.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/script.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/svg.parser.min.js");
        $cs->registerCssFile(Yii::app()->baseUrl."/css/redactor/editor.css");

        if (Yii::app()->request->isAjaxRequest)
            Scheme::getVisualInfo($model);


        $this->renderPartial('preview', array(
            'model'=>$model
        ), false, true);

    }

    public function actionGetLocationCatById()
    {
        $id = Yii::app()->request->getParam("id");
        if (!$id)
            $this->_sendResponse(400, json_encode("property `id` not defined"));
        $model = LocationCategory::model()->findByPk($id, "status=:status", array(":status"=>LocationCategory::STATUS_ACTIVE));
        if (!$model)
            $this->_sendResponse(404, json_encode("Location category not found!"));
        $city = array(
            "name"=>$model->name,
        );
        $this->_sendResponse(200, json_encode($city));
        Yii::app()->end();
    }

    public function actionGetSectorSettings()
    {
        $id = Yii::app()->request->getParam("sector_id");
    }

    public function actionGetPaymentMethod()
    {
        $event_id = Yii::app()->request->getParam("event_id");
        if (!$event_id)
            $this->_sendResponse(200, json_encode("property `event_id` not defined"));
        $result = array_merge(Order::$physical,Order::$eTicket);
        $this->_sendResponse(200, json_encode($result));
        Yii::app()->end();
    }

    public function actionGetPost()
    {
        $id = Yii::app()->request->getParam("post_id");
        $result = [];
        if ($id) {
            $post = Post::model()->findByPk($id);
            if ($post) {
                if ($post->multimedia_id)
                    $url = $post->getImageUrl(true);
                else
                    $url["path"] = '';

                $result = $this->generatePostsData($post,$url);
            }
        } else {
            $posts = Post::model()->findAllByAttributes(["status"=>Post::STATUS_ACTIVE]);
            foreach ($posts as $post) {
                if ($post->multimedia_id)
                    $url = $post->getImageUrl(true);
                else
                    $url["path"] = '';
                $result[] = $this->generatePostsData($post,$url);
            }
        }
        $this->_sendResponse(200,json_encode($result));
        Yii::app()->end();
    }

    private function generatePostsData($post,$url)
    {
        return ["id"=>$post->id,
            "name"=>$post->name,
            "description"=>$post->description,
            "alias"=>$post->alias_url,
            "header"=>$post->html_header,
            "meta"=>$post->meta_description,
            "keywords"=>$post->keywords,
            "picture_url"=>$url["path"],
        ];
    }

    public function actionGetSlider()
    {
        $id = Yii::app()->request->getParam("slider_id");
        if ($id) {
            $slider = Slider::model()->findByAttributes(["id"=>$id, "status"=>Slider::STATUS_ACTIVE]);
            if ($slider) {
                if ($slider->multimedia_id && $slider->small_multimedia_id) {
                    $result = $this->getSliderData($slider);
                    $this->_sendResponse(200,json_encode($result));
                } else
                    $this->_sendResponse(200,json_encode("no data available"));
            } else
                $this->_sendResponse(200,json_encode("no slider found"));
        } else {
            $sliders = Slider::model()->findAllByAttributes(["is_on_main"=>Slider::STATUS_ON_MAIN, "status"=>Slider::STATUS_ACTIVE]);
            $result = [];
            if ($sliders) {
                foreach ($sliders as $slider) {
                    if ($slider->multimedia_id && $slider->small_multimedia_id) {
                        $result[] = $this->getSliderData($slider);
                    }
                }
            }
            if (!empty($result)) {
                ksort($result);
                $result = array_slice($result, 0, 8);
                foreach ($result as $key=>$data)
                    $result[$key] = current(array_values($data));
                $this->_sendResponse(200,json_encode($result));
            }
            else
                $this->_sendResponse(200,json_encode("no data available"));
        }
        Yii::app()->end();
    }

    private function getSliderData($slider) {
        $url = $slider->getImageUrl(true);
        $event = current(Slider::getEventData($slider->event_id));
        $event_start = strtotime($event["start"]);
        $location_name = $event["location_short_name"] ? $event["location_short_name"] : $event["location_name"];
        $event["start"] = Yii::app()->dateFormatter->format("dd MMMM HH:mm", $event["start"]);
        return [$event_start => ["event_id"=>$event["id"], "event_name"=>$event["name"], "event_date"=>$event["start"],
            "event_location"=>$location_name." ".$event["city_name"],
            "slider_background"=>$slider->background_color, "slider_text"=>$slider->text_color,
            "picture_full"=>$url["path_full"], "picture_small"=> $url["path_small"], "url"=>$event["url"]]];
    }

    public function actionGetSliderByCity()
    {
        $id = Yii::app()->request->getParam("city_id");
        if ($id) {
            $result = [];
            $sliders = Slider::getSliderByCity($id);
            if ($sliders) {
                foreach ($sliders as $slider) {
                    if ($slider->multimedia_id && $slider->small_multimedia_id) {
                        $result[] = $this->getSliderData($slider);
                    }
                }
            }
            if (!empty($result)) {
                ksort($result);
                $result = array_slice($result, 0, 8);
                foreach ($result as $key=>$data)
                    $result[$key] = current(array_values($data));
                $this->_sendResponse(200,json_encode($result));
            }
            else
                $this->_sendResponse(200,json_encode("no data available"));

        }else
            $this->_sendResponse(200,json_encode("null city param given"));
    }

    public function actionGetOrders()
    {
        if (!$this->_authorized)
            $this->sendNotAuthorized();
        $orders = Order::model()->with(array('tickets','tickets.place'))->findAllByAttributes(array(
            "user_id"=>$this->_user->id,
            "type"=>Order::TYPE_ORDER
        ), array("order"=>"t.date_add DESC"));
        $result = array();
        $events = array();
        $eventTemp[] = array();
        $cityTemp[] = array();
        $sectorTemp[] = array();
        $placeName[] = array();
        $rowName[] = array();
        foreach ($orders as $order) {
            $events[$order->id] = array();
            $i = 0;
            $total = 0;

            foreach ($order->tickets as $ticket) {
                if (!isset($eventTemp[$ticket->place->event_id]))
                    $eventTemp[$ticket->place->event_id] = $ticket->place->event;
                if (!isset($cityTemp[$eventTemp[$ticket->place->event_id]->scheme_id]))
                    $cityTemp[$eventTemp[$ticket->place->event_id]->scheme_id] = $ticket->place->event->scheme->location->city;
                if (!isset($sectorTemp[$ticket->place->sector_id]))
                    $sectorTemp[$ticket->place->sector_id] = $ticket->place->sector;
                if (!isset($sectorTemp[$ticket->place->sector_id]))
                    $sectorTemp[$ticket->place->sector_id] = $ticket->place->sector;
                $ticket->place->sector = $sectorTemp[$ticket->place->sector_id];

                if (!isset($placeName[$sectorTemp[$ticket->place->sector_id]->type_place_id]))
                    $placeName[$sectorTemp[$ticket->place->sector_id]->type_place_id] = $sectorTemp[$ticket->place->sector_id]->typePlace ? $sectorTemp[$ticket->place->sector_id]->typePlace->name : "" ;
                if (!isset($rowName[$sectorTemp[$ticket->place->sector_id]->type_row_id]))
                    $rowName[$sectorTemp[$ticket->place->sector_id]->type_row_id] = $sectorTemp[$ticket->place->sector_id]->typeRow ? $sectorTemp[$ticket->place->sector_id]->typeRow->name : "";


                if (!(isset($events[$order->id][$ticket->place->event_id]))) {
                    $events[$order->id][$ticket->place->event_id]["event_info"] = array(
                        "id"=>$ticket->place->event_id,
                        "name"=>$eventTemp[$ticket->place->event_id]->name,
                        "date_start"=>$eventTemp[$ticket->place->event_id]->startTime,
                        "city"=>array(
                            "id"=>$cityTemp[$eventTemp[$ticket->place->event_id]->scheme_id]->id,
                            "name"=>$cityTemp[$eventTemp[$ticket->place->event_id]->scheme_id]->name,
                        )
                    );
                }

                $events[$order->id][$ticket->place->event_id]["tickets"][] = array(
                    "id"=>$ticket->id,
                    "sector"=>$sectorTemp[$ticket->place->sector_id]->sectorName,
                    "row"=>$rowName[$sectorTemp[$ticket->place->sector_id]->type_row_id]." ".$ticket->place->editedRow,
                    "place"=>$placeName[$sectorTemp[$ticket->place->sector_id]->type_place_id]." ".$ticket->place->editedPlace,
                    "price"=>$ticket->price,
                    "type"=>$ticket->type,
                    "status"=>$ticket->status
                );
                $total += $ticket->price;
                $i++;
            }

            $result[$order->id] = array(
                "id"=>$order->id,
                "tickets"=>array_values($events[$order->id]),
                "date_create"=>$order->date_add,
                "delivery"=>$order->ticketsDeliveries,
                "delivery_status"=>$order->ticketsDeliveryStatus,
                "pay_type"=>$order->ticketsPayTypes,
                "pay_status"=>$order->ticketsPayStatus,
                "count"=>$i,
                "total"=>$total,
                "status"=>$order->status
            );
        }
        $this->_sendResponse(200, json_encode(array_values($result)));
    }

    public function actionChangePayStatus()
    {
        $order_id = Yii::app()->request->getParam("order_id");
        if (!$order_id)
            $this->_sendResponse(200, json_encode(array("not `order_id` property is defined")));
        $tickets = Yii::app()->db->createCommand()
            ->select("id, delivery_type, pay_type")
            ->from(Ticket::model()->tableName())
            ->where("order_id=:order_id AND status=:status AND pay_status=:pay_status", array(
                ":order_id"=>$order_id,
                ":status"=>Ticket::STATUS_SOLD,
                ":pay_status"=>Ticket::PAY_NOT_PAY
            ))
            ->queryAll();

        if (empty($tickets))
            $this->_sendResponse(200, json_encode(array("no tickets in this order")));

        $status = Ticket::STATUS_SOLD;
        $pay_status = Ticket::PAY_PAY;
        $delivery_type = array();
        $pay_type = "";
        $ids = array();
        foreach ($tickets as $ticket) {
            if (!in_array($ticket['delivery_type'], $delivery_type))
                $delivery_type[] = $ticket['delivery_type'];
            $pay_type = $ticket['pay_type'];
            $ids[] = $ticket['id'];
        }

        $order = Order::model()->findByPk($order_id);
        $params = array();
        if (count($delivery_type) == 1 && $delivery_type[0]==Order::E_ONLINE) {
            $status = Ticket::STATUS_SEND_TO_EMAIL;
            $params = array(
                "delivery_status"=>Ticket::DELIVERY_RECIEVED,
                "print_status"=>Ticket::STATUS_SEND_TO_EMAIL,
            );
        }

        $criteria = new CDbCriteria();
        $criteria->compare("order_id", $order_id);
        $criteria->addInCondition("id", $ids);
        $builder = new CDbCommandBuilder(Yii::app()->db->getSchema());
        $command = $builder->createUpdateCommand(Ticket::model()->tableName(), array("status"=>$status, "pay_status"=>$pay_status)+$params, $criteria);
        $command->execute();

        if (count($delivery_type) == 1 && $delivery_type[0]==Order::E_ONLINE) {
            $order->sendTickets($tickets);
        }
        Ticket::saveState($ids, false, $order->user_id);
        $this->_sendResponse(200, json_encode(array("order_id"=>$order_id, "msg"=>$order->getSuccessMessage($pay_type))));



    }

    public function actionSuccessMessage()
    {
        $order_id = Yii::app()->request->getParam("order_id");
        if (!$order_id)
            $this->_sendResponse(200, json_encode(array("not `order_id` property is defined")));
        $pay_type = Yii::app()->db->createCommand()
            ->select("pay_type")
            ->from(Ticket::model()->tableName())
            ->where("order_id=:order_id AND status=:status", array(
                ":order_id"=>$order_id,
                ":status"=>Ticket::STATUS_SOLD,
            ))
            ->queryScalar();
        $order = Order::model()->findByPk($order_id);
        $this->_sendResponse(200, json_encode(array("order_id"=>$order_id, "msg"=>$order->getSuccessMessage($pay_type))));
    }

    public function actionCheck()
    {
        echo json_encode("true");
    }


}