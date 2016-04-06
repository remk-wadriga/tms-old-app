<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 23.09.14
 * Time: 10:37
 */

class ConfigurationController extends Controller
{

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessList()
    {
        return array(
            'getRegions',
            'getCityList',
            'getVkCity',
            'parseCity',
        );
    }

    public function actionGetCountry()
    {
        $countryId = Yii::app()->request->getParam('id');
        $country = Country::model()->findByPk($countryId);
        $city = City::model()->findByAttributes(array('country_id' => $countryId));
        $hasCities = !empty($city);
        $data = array(
            'name' => $country->name,
            'status' => $country->status,
            'hasCities' => $hasCities
        );
        echo json_encode($data);
    }



    public function actionGetAllDescendants()
    {
        $cityId = Yii::app()->request->getParam('id');
        $data = array($cityId);
        echo json_encode($data);
    }

    public function actionCity()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/style-tree.js", CClientScript::POS_END);

        $city = Yii::app()->request->getParam("City");
        $country = Yii::app()->request->getParam("Country");
        $countryId = Yii::app()->request->getParam('country_id');
        $cityId = Yii::app()->request->getParam('city_id_val');

        Yii::app()->clientScript->registerScript("country", "
            var countrySelect = $('#country_id');
            if (countrySelect.val()) countrySelect.change();
        ", CClientScript::POS_LOAD);

        Yii::app()->clientScript->registerScript("modals", "
            $(document).on('hide.bs.modal','#newCountry', function () {
                $('#new-country-form')[0].reset();
            });

            $(document).on('hide.bs.modal','#newCity', function () {
                $('#new-city-form')[0].reset();
            });

            $(document).on('click', '.new-country', function(e) {
                var delCountry = $('.delete-country');
                if (!delCountry.hasClass('hidden')) delCountry.addClass('hidden');
                $('.modal-country-caption').text('Створити країну');
                $('#Country_status').attr('checked', 'checked');
                $('#Country_id').attr('value', null);
            });

            $(document).on('click', '.update-country', function(e) {
                var id = $('#country_id').val();
                var delCountry = $('.delete-country');
                if (delCountry.hasClass('hidden')) delCountry.removeClass('hidden');
                $.post('" . $this->createUrl('getCountry') . "',
                {id: id},
                function(data){
                    var country = JSON.parse(data);
                    $('#Country_name').val(country.name);
                    if(country.status == 1) {
                        $('#Country_status').attr('checked', 'checked');
                    }
                    else {
                        $('#Country_status').removeAttr('checked');
                    }
                    $('#Country_id').val(id);
                    $('#Country_id').attr('has-city', country.hasCities);
                    delCountry.attr('href', '" . $this->createUrl('deleteCountry') . "?id='+id);
                    $('.modal-country-caption').text('Редагувати країну');
                });
            });

            $(document).on('click', '.delete-country', function(e) {
                var hasCities = $('#Country_id').attr('has-city');
                if(hasCities == 'true') {
                    if (!confirm('У країни є дочірні елементи. Всеодно видалити країну?')) return false;
                }
                else {
                    if (!confirm('Видалити країну?')) return false;
                }
            });

            $(document).on('click', '.new-city', function(e) {
                var id = $('#country_id').val();
                var delCity = $('.delete-city');
                if (!delCity.hasClass('hidden')) delCity.addClass('hidden');
                $('#City_country_id').val(id);
                $('#City_status').attr('checked', 'checked');
                $('.modal-city-caption').text('Створити новий елемент');
                $('#city_id_val').val(0);
                $('#City_parent').find('option').removeAttr('disabled');
            });

            $(document).on('click', '.update-city', function(e) {
                var countryId = $('#country_id').val();
                var delCity = $('.delete-city');
                if (delCity.hasClass('hidden')) delCity.removeClass('hidden');
                $('#City_country_id').val(countryId);
                $('.modal-city-caption').text('Редагувати елемент');
                var cityData = $(this).prev();
                $('#City_name').val(cityData.text());
                var status = cityData.attr('data-status');
                if (status == 1) {
                    $('#City_status').attr('checked', 'checked');
                }
                else {
                    $('#City_status').removeAttr('checked');
                }
                $('#City_lng').val(cityData.attr('data-lng'));
                $('#City_lat').val(cityData.attr('data-lat'));
                var parent = cityData.attr('data-parent');
                var parentSelect = $('#City_parent');
                if (parent) {
                    parentSelect.val(parent);
                }

                var id = cityData.attr('data-pk');

                $('#city_id_val').val(id);
                delCity.attr('href', '" . $this->createUrl('deleteCity') . "?id='+id);

                $.post(
                    '" . $this->createUrl('getAllDescendants') . "',
                    {id: id},
                    function(data){
                        data = JSON.parse(data);
                        parentSelect.find('option').removeAttr('disabled');
                        parentSelect.find('option[value='+id+']').attr('disabled', 'disabled');
                        var length = data.length;
                        if (length > 0) {
                            for (var i = 0; i < length; i++)
                                parentSelect.find('option[value='+data[i]+']').attr('disabled', 'disabled');
                        }
                    }
                );

            });

            $(document).on('click', '.delete-city', function(e) {
                if(!confirm('Видалити елемент?')) return false;
            });

            $('#country_id').on('change', function(){
                var id = $(this).val();
                $('#City_country_id').val(id);
                var updateButton = $('.update-country');
                if(id != 0) {
                    if(updateButton.hasClass('hidden')) {
                        updateButton.removeClass('hidden');
                    }
                }
                else {
                    updateButton.addClass('hidden');
                }
                $.post(
                '" . $this->createUrl("updateCityList") . "',
                {id: id},
                function(data){
                    $('#City_parent').html(data);
                });
            });

        ", CClientScript::POS_READY);
        Yii::app()->clientScript->registerCssFile(Yii::app()->getBaseUrl() . "/css/bootstrap-editable.css");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl() . "/js/bootstrap-datepicker.min.js");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl() . "/js/bootstrap-datepicker-noconflict.js");
        Yii::app()->clientScript->registerScriptFile(Yii::app()->getBaseUrl() . "/js/bootstrap-editable.min.js");
        $model = new City();
        $model->setScenario('create');
        $model->unsetAttributes();
        $modelCountry = new Country();
        $modelCountry->unsetAttributes();


        if ($country) {
            $successText = "Успішно створено!";
            if (isset($country['id']) && $country['id'] != "") {
                $model->setScenario('update');
                $modelCountry = Country::model()->findByPk($country['id']);
                $successText = "Успішно збережено!";
            }
            $this->performAjaxValidation($modelCountry);
            $modelCountry->attributes = $country;
            if ($modelCountry->save()) {
                Yii::app()->user->setFlash("success", $successText);
                $this->redirect(array("/configuration/configuration/city", "country_id" => $modelCountry->id));
            }
        }

        if ($city) {
            $countryId = $city['country_id'];
            $successText = "Успішно створено!";
            if ($cityId) {
                $model = City::model()->findByPk($cityId);
                $successText = "Успішно збережено!";
            }
            $this->performAjaxValidation($model);
            $model->attributes = $city;
            $saved = $model->saveCity($cityId);

            if ($saved) {
                $model->saveNode();
                Yii::app()->user->setFlash("success", $successText);
                $this->redirect(array("/configuration/configuration/city", "country_id" => $countryId));
            } else {
                Yii::app()->user->setFlash("danger", "Помилка!");
                $this->redirect("city");
            }
        }
        $countries = CHtml::listData(Country::model()->findAll(), "id", 'name');

        $this->render("city", array(
            "modelCountry" => $modelCountry,
            "countries" => $countries,
            "countryId" => $countryId,
            "model" => $model,
        ));
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax'])) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionLocation()
    {
        $this->formJs();
        $locationCategory = Yii::app()->request->getParam("LocationCategory");
        $model = new LocationCategory();
        $model->unsetAttributes();
        $this->performAjaxValidation($model);

        if ($locationCategory) {
            $model->attributes = $locationCategory;
            if ($model->save())
                $model->unsetAttributes();
        }

        $delUrl = "deleteLocationCategory";
        $name = "Локація";
        $this->render("location", array(
            "model" => $model,
            "delUrl" => $delUrl,
            "name" => $name
        ));
    }

    private function formJs()
    {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/common.js");
        Yii::app()->clientScript->registerScript("LocationCategory",
            "
                function resetForm() {
                    $('#new-location-category')[0].reset();
                }

                $('#myModal').on('hidden.bs.modal', function(){
                    resetForm();
                });

            ", CClientScript::POS_READY
        );
    }

    public function actionSector()
    {
        $this->formJs();
        $TypeSector = Yii::app()->request->getParam("TypeSector");
        $model = new TypeSector();
        $model->unsetAttributes();
        $this->performAjaxValidation($model);

        if ($TypeSector) {
            $model->attributes = $TypeSector;
            if ($model->save()) {
                $model->unsetAttributes();
                if (Yii::app()->request->isAjaxRequest) {
                    $sector = TypeSector::model()->findByAttributes(array('name' => $TypeSector));
                    echo json_encode($sector->id);
                    Yii::app()->end();
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    echo json_encode('Помилка!');
                    Yii::app()->end();
                }
            }
            $model->unsetAttributes();
        }


        $delUrl = "deleteTypeSector";
        $name = "Префікси секторів";
        $this->render("location", array(
            "model" => $model,
            "delUrl" => $delUrl,
            "name" => $name
        ));
    }

    public function actionRow()
    {
        $this->formJs();
        $TypeRow = Yii::app()->request->getParam("TypeRow");
        $model = new TypeRow();
        $model->unsetAttributes();
        $this->performAjaxValidation($model);

        if ($TypeRow) {
            $model->attributes = $TypeRow;
            if ($model->save()) {
                $model->unsetAttributes();
                if (Yii::app()->request->isAjaxRequest) {
                    $row = TypeRow::model()->findByAttributes(array('name' => $TypeRow));
                    echo json_encode($row->id);
                    Yii::app()->end();
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    echo json_encode('Помилка!');
                    Yii::app()->end();
                }
            }
            $model->unsetAttributes();
        }

        $delUrl = "deleteTypeRow";
        $name = "Ряди";
        $this->render("location", array(
            "model" => $model,
            "delUrl" => $delUrl,
            "name" => $name
        ));
    }

    public function actionPlace()
    {
        $this->formJs();
        $TypePlace = Yii::app()->request->getParam("TypePlace");
        $model = new TypePlace();
        $model->unsetAttributes();
        $this->performAjaxValidation($model);

        if ($TypePlace) {
            $model->attributes = $TypePlace;
            if ($model->save()) {
                $model->unsetAttributes();
                if (Yii::app()->request->isAjaxRequest) {
                    $place = TypePlace::model()->findByAttributes(array('name' => $TypePlace));
                    echo json_encode($place->id);
                    Yii::app()->end();
                }
            } else {
                if (Yii::app()->request->isAjaxRequest) {
                    echo json_encode('Помилка!');
                    Yii::app()->end();
                }
            }
            $model->unsetAttributes();
        }

        $name = "Місця";
        $delUrl = "deleteTypePlace";

        $this->render("location", array(
            "model" => $model,
            "delUrl" => $delUrl,
            "name" => $name
        ));
    }

    public function actionDeleteLocationCategory($id)
    {
        $model = LocationCategory::model()->findByPk($id);
        if ($model)
            $model->delete();
    }

    public function actionDeleteTypeSector()
    {
        $id = Yii::app()->request->getParam('id');
        $model = TypeSector::model()->findByPk($id);
        if ($model)
            if ($model->delete())
                echo CController::createUrl('/configuration/configuration/sector');

        Yii::app()->end();
    }

    public function actionDeleteTypeRow()
    {
        $id = Yii::app()->request->getParam('id');
        $model = TypeRow::model()->findByPk($id);
        if ($model)
            if ($model->delete())
                echo CController::createUrl('/configuration/configuration/row');

        Yii::app()->end();
    }

    public function actionDeleteTypePlace()
    {
        $id = Yii::app()->request->getParam('id');
        $model = TypePlace::model()->findByPk($id);
        if ($model)
            if ($model->delete())
                echo CController::createUrl('/configuration/configuration/place');

        Yii::app()->end();
    }

    public function actionDeleteCity($id)
    {
        $model = City::model()->findByPk($id);
        if ($model->hasEvents) {
            Yii::app()->user->setFlash("danger", "Елемент '" . $model->name . "' неможливо видалити, є створені події!");
            $this->redirect("city");
            Yii::app()->end();
        }

        $countryId = $model->country_id;
        $cityName = $model->name;
        if ($model)
            $model->deleteNode();
        Yii::app()->user->setFlash("success", "Елемент '" . $cityName . "' успішно видалено!");
        $this->redirect(array("/configuration/configuration/city", "country_id_val" => $countryId));
    }

    public function actionDeleteCountry($id)
    {
        $model = Country::model()->findByPk($id);
        if ($model->hasEvents) {
            Yii::app()->user->setFlash("danger", "Країну '" . $model->name . "' неможливо видалити, в цій країні є події!");
            $this->redirect("city");
            Yii::app()->end();
        }

        $countryName = $model->name;
        $city = City::model()->findByAttributes(array('country_id' => $id));

        $hasCities = !empty($city);
        if ($hasCities) {
            $roots = City::model()->roots()->findAllByAttributes(array('country_id' => $id));
            foreach ($roots as $root) {
                $root->deleteNode();
            }
        }
        if ($model->delete()) {
            Yii::app()->user->setFlash("success", "Країну '" . $countryName . "' успішно видалено!");
            $this->redirect("city");
        }
        Yii::app()->end();
    }

    public function actionGetCityList()
    {
        $country_id = Yii::app()->request->getParam('country_id');
        $criteria = new CDbCriteria();
        $criteria->join = "INNER JOIN (SELECT region_id FROM ".City::model()->tableName()." GROUP BY region_id) c2 ON t.region_id=c2.region_id";
        $criteria->compare("country_id", $country_id);
        $criteria->addCondition("t.region_id IS NOT NULL");
        $criteria->order = "name ASC";
        $criteria->limit = 100;
        $cities = City::model()->findAll($criteria);
        CVarDumper::dump($cities);
        exit;
        echo json_encode($this->renderPartial('_cityList', array("cities" => $cities), true));
        Yii::app()->end();
    }

    public function actionUpdateCityList()
    {
        $city = Yii::app()->request->getParam('id');
        echo CHtml::tag('option',
            array('value' => 0), CHtml::encode("Нікому не підпорядковується"), true);
        if (!$city)
            Yii::app()->end();

        $data = City::getList($city, true);

        foreach ($data as $value => $name) {
            echo CHtml::tag('option',
                array('value' => $value), CHtml::encode($name), true);
        }
    }

    public function actionGetRegions()
    {
        $country_id = Yii::app()->request->getParam('id');
        if ($country_id) {
            $regions = CHtml::listData(Region::model()->findAllByAttributes(array("country_id"=>$country_id)), "id", "name");
            foreach ($regions as $k=>$region) {
                echo CHtml::tag("option", array(
                    "value"=>$k
                ), $region, true);
            }
        }
    }

    public function actionUpdate($model)
    {
        Yii::import('ext.bootstrap.components.TbEditableSaver');
        $es = new TbEditableSaver($model);
        $es->update();
    }

    public function actionGetVkCity()
    {
        $country = Yii::app()->request->getParam('country_id');
        $region = Yii::app()->request->getParam('region');
        $count = Yii::app()->request->getParam('count');
        $offset = Yii::app()->request->getParam("offset");
        $method = Yii::app()->request->getParam("method");

        $params = array();

        if ($country)
            $params['country_id'] = $country;
        if ($region)
            $params['region_id'] = $region;
        if ($count)
            $params['count'] = $count;
        if ($offset)
            $params['offset'] = $offset;
        $params["lang"] = "ua";

        $param = http_build_query($params);
        $res = $this->queryApi($method, $param);

        $country = Yii::app()->db->createCommand()
            ->select("id")
            ->from(Country::model()->tableName())
            ->where("name=:name", array(
                ":name" => "Україна"
            ))
            ->queryScalar();
        if (!$country)
            return false;
        $response = $res;
        $result = array();
        foreach ($response as $res)
            $result[] = "(" . implode(",", array(
                    "name" => "'$res->title'",
                    "country_id" => $country,
                    "vk_id" => $res->region_id,
                )) . ")";

        $sql = "INSERT INTO " . Region::model()->tableName() . " (name, country_id, vk_id) VALUES " . implode(",", $result);
        $count = Yii::app()->db->createCommand($sql)->execute();
        CVarDumper::dump($count, 10, 1);
        exit;
    }

    private function queryApi($method, $params, $result=array())
    {
        $res = $this->curlPost("https://api.vk.com/method/$method?$params", array());
        $res = json_decode($res);
        $result += $res->response;
        if (isset($params["count"])&&count($res->response)==$params["count"]) {
            $params["offset"] = $params['offset']+$params['count'];
            $this->queryApi($method, $params, $result);
        }
        return $result;
    }

    private function curlPost($url, $data=array())
    {

        if ( ! isset($url))
        {
            return false;
        }

        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_URL, $url);

        if (count($data) > 0)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;

    }

    public function actionParseCity($country_name)
    {
        $country = Yii::app()->db->createCommand()
            ->select("id")
            ->from(Country::model()->tableName())
            ->where("name=:name", array(
                ":name" => $country_name
            ))
            ->queryScalar();
        if (!$country)
            return false;


        $regions = Yii::app()->db->createCommand()
            ->select("t.id, t.vk_id")
            ->from(Region::model()->tableName() . " t")
            ->leftJoin(City::model()->tableName() . " c", "t.id=c.region_id")
            ->where("t.country_id=:country_id", array(
                ":country_id" => $country
            ))
            ->andWhere("c.region_id IS NULL")
            ->queryAll();
        if (!$regions)
            return false;

        $params = array(
            "count" => 1000,
            "offset" => 0,
            "lang" => "ua",
            "country_id" => 2
        );
        $sql = array();
        if (!empty($regions))
            foreach ($regions as $region) {
                $params['region_id'] = $region['vk_id'];
                $cities = $this->queryApi("database.getCities", http_build_query($params));
                foreach ($cities as $city)
                    $sql[] = "(" . implode(",", array(
                            "name" => "'$city->title'",
                            "vk_id" => $city->cid,
                            "region_id" => $region['id'],
                            "country_id" => $country
                        )) . ")";
                break;
            }
        $command = "INSERT INTO " . City::model()->tableName() . " (name, vk_id, region_id, country_id) VALUES " . implode(",", $sql);
        $count = Yii::app()->db->createCommand($command)->execute();

        CVarDumper::dump($count, 10, 1);
        exit;

    }

    /**
     * https://translate.yandex.net/api/v1.5/tr.json/translate ?
    key=<API-ключ>
    & text=<переводимый текст>
    & lang=<направление перевода>
    & [format=<формат текста>]
    & [options=<опции перевода>]
    & [callback=<имя callback-функции>]
     */

    public function actionTranslateCity()
    {
        $cities = Yii::app()->db->createCommand()
            ->select("id, name")
            ->from(City::model()->tableName())
            ->where("region_id IS NOT NULL AND status=:status", array(
                ":status"=>City::STATUS_ACTIVE
            ))
            ->limit(1000)
            ->queryAll();
        $sql = array();
        foreach ($cities as $city) {
            $params = array(
                "text"=>$city['name'],
                "lang"=>"ru-uk",
                "key"=>"trnsl.1.1.20150928T151443Z.88dba487f26751f7.adeff4815dc10c213b762cc31584136950793037"
            );

            $res = $this->curlPost("https://translate.yandex.net/api/v1.5/tr.json/translate?".
                http_build_query($params));
            $res = json_decode($res);

            if ($res->code==200) {
                $sql[$city['id']] = CHtml::encode($res->text[0]);
            }
        }
        $when = "";
        $ids = array();
        foreach ($sql as $k=>$city) {
            $ids[] = $k;
            $when .=  " WHEN id=".$k." THEN '".$city."' ";
        }


        $query = "UPDATE ".City::model()->tableName()." SET status=2, name = CASE ".$when." ELSE name END WHERE id IN (".implode(",", $ids).")";
        $count = Yii::app()->db->createCommand($query)->execute();
        CVarDumper::dump($count,10,1);
        exit;

    }

} 