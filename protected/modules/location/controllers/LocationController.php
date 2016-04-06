<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 26.09.14
 * Time: 4:29
 */

class LocationController extends Controller{


    protected  $startLng = 31.409912109375;
    protected  $startLat = 49.32512199104003;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessList()
    {
        return array(
            'getCitiesById',
        );
    }

    public function actionIndex()
    {
        $city = Yii::app()->request->getParam("city_id");
        $location_id = Yii::app()->request->getParam("location_id");
        $category = Yii::app()->request->getParam("category_id");
        $name = Yii::app()->request->getParam("name");
        $newScheme = Yii::app()->request->getParam("Scheme");
        $cities = City::getList();
        $categories = LocationCategory::getList();
        $criteria = new CDbCriteria();
        $scheme = new Scheme();
        $scheme->unsetAttributes();
        if ($newScheme) {
            $this->performAjaxValidation($scheme);
            $scheme->attributes = $newScheme;
            if ($scheme->save()) {
                $scheme->unsetAttributes();
                if(!$location_id)
                    $location_id = $scheme->location_id;
            }
        }
        if (!$location_id)
            $location_id = 0;
        Yii::app()->clientScript->registerScript("newScheme", "
            function addNewScheme(dataId) {
                resetForm();
                $('#Scheme_location_id').val(dataId);
            }
            function resetForm() {
                $(document).find('#new-scheme-form')[0].reset();
            }
            $('#closeModal').on('click', function(){
                resetForm();
            });
            $('.close').on('click', function(){
                resetForm();
            });


            function hideSchemes() {
                $('.location_name').parent('.parent-item').next('.child-item').css({'display':'none'});
            }

            $('.location_name').on('click', function(e) {
                e.preventDefault();
                var childItem = $(this).parent('.parent-item').next('.child-item');
                if(childItem.is(':hidden'))
                    hideSchemes();
                childItem.toggle(200);
            });


            hideSchemes();
            if (".$location_id.">0) {
                $('.location_".$location_id."').click();
            }
        ", CClientScript::POS_BEGIN);


        if ($name) {
            $criteria->compare("name", $name, true, "OR");
            $criteria->compare("short_name", $name, true, "OR");
            $criteria->compare("sys_name", $name, true, "OR");
        }
        if ($city) {
//            $cityArray = City::getSearchList($city);
            $criteria->compare("city_id", $city);
        }
        if ($category)
            $criteria->compare("location_category_id", $category);


        $dataProvider = new CActiveDataProvider('Location', array(
            "criteria"=>$criteria
        ));


        $this->render('index',array(
            'city'=>$city,
            'name'=>$name,
            'category'=>$category,
            'cities'=>$cities,
            'categories'=>$categories,
            'dataProvider'=>$dataProvider,
            'scheme'=>$scheme
        ));
    }

    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']))
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetCitiesById() {
        $country_id = Yii::app()->request->getParam('id');
        $region_id = Yii::app()->request->getParam('region_id');
        $text = Yii::app()->request->getParam('text');
        $allCities = Yii::app()->request->getParam('all');

        $result = City::getSearchList($country_id, $text, $region_id, $allCities);
        if($result)
            echo json_encode($result);
        Yii::app()->end();
    }

    public function actionCreate()
    {
        $location = Yii::app()->request->getParam('Location');
        $model = new Location();
        $model->unsetAttributes();
        $this->performAjaxValidation($model);
        $this->map("Create",$this->startLat, $this->startLng);

        if ($location) {
            $model->attributes = $location;
            if ($model->save())
                $this->redirect("index");
        }
        $countries = CHtml::listData(Country::model()->findAll(), "id", 'name');
        $this->render("create", array(
            "model"=>$model,
            'countries'=>$countries
        ));
    }

    private function map($command,$lat,$lng)
    {
        $javascript = "
        var LatLng = new google.maps.LatLng(".$lat.",".$lng.");

        var mapProp = {
            center: LatLng,
            zoom:15,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map=new google.maps.Map(document.getElementById('googleMap'),mapProp);

        //Create a marker here
        var marker=new google.maps.Marker({

            animation:google.maps.Animation.DROP,
            title: 'Marker'
        });

        if('".$command."' == 'View' || '".$command."' == 'Update' )
        {
        //setting marker on map
        marker.setPosition(LatLng);
        marker.setMap(map);
        }
        if('".$command."' == 'Create' || '".$command."' == 'Update' )
        {
        //if using Create or Update location - editing marker to set him draggable
            marker.setDraggable(true);
            google.maps.event.addListener(marker, 'drag', function(event) {
            refresh(event.latLng);

                    });
                var markerBool = false;

        if('".$command."' == 'Create')
        {
        //change map zoom to view all Ukraine while using Create location
        map.setZoom(6);
        }

        function createMarker(location)
        {

        if(markerBool == false)
        {
            /*var marker=new google.maps.Marker({
            position:location,
            map: map,
            animation:google.maps.Animation.DROP,
            title: 'Marker',
            draggable:true
            });*/
            google.maps.event.addListener(marker, 'drag', function(event) {
            refresh(event.latLng);
                    });
             markerBool = true;
             marker.setPosition(location);
             marker.setMap(map);
        }
        if(markerBool == true)
        {
            //if marker already created then just change the position of marker on click
            marker.setPosition(location);
        }
        }

        //saving marker position while moving
        function refresh(latLng)
            {
                document.getElementById('Location_lat').value = latLng.lat();
                document.getElementById('Location_lng').value = latLng.lng();
            }

        if('".$command."' == 'Create')
        {
        google.maps.event.addListener(map, 'click', function(event) {
        createMarker(event.latLng);
        refresh(event.latLng);
        });
        }

       }
       ";
        $cs = Yii::app()->clientScript;
        $cs->registerScriptFile("http://maps.googleapis.com/maps/api/js?v=3.exp");
        $cs->registerScript('map', $javascript, CClientScript::POS_LOAD);
    }

    public function actionCopyScheme($scheme_id)
    {
        $old = Scheme::model()->with("sectors")->findByPk($scheme_id);
        if ($old) {
            $new = new Scheme();
            $prefix = "(копія)";
            $newPrefix = $prefix;
            while(Scheme::model()->exists("name=:name AND location_id=:location_id", array(
                ":name"=>$old->name.$newPrefix,
                ":location_id"=>$old->location_id
            ))) {
                $newPrefix .= $prefix;
            }
                $new->name = $old->name.$newPrefix;
            $new->location_id = $old->location_id;
            $new->import = json_encode($old->import);

            if ($new->saveCopy($old))
                $this->redirect(array("index", "location_id"=>$old->location_id));
        } else
            throw new CHttpException(404, "Схему не знайдено");
    }

    public function actionUpdate($id)
    {
        $location = Yii::app()->request->getParam('Location');
        $model = Location::model()->findByPk($id);
        $this->performAjaxValidation($model);
        $lat = $model->lat;
        $lng = $model->lng;
        $city = City::model()->findByPk($model->city_id);
        Yii::app()->clientScript->registerScript("locEdit", "
            $('#country_id').select2('val', ".$city->country_id.");
            $('#country_id').change();

            $(document).on('click', '#deleteLocationButton', function() {
			    if(confirm('Ви впевнені що хочете видалити локацію \"".$model->name."\" ?')) {
			     $.post('".$this->createUrl('delete')."', {
                     id: $(this).attr('data-id')
                     }, function(result){
                        if(result.includes('Локація'))
						showAlert('danger', result, 10000);
					 else
						window.location.replace(result);
                 });
                 }
			});
        ", CClientScript::POS_LOAD);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl."/js/common.js");

        $this->map("Update",$lat, $lng);

        if ($location) {
            $model->attributes = $location;
            if ($model->save())
                $this->redirect("index");
        }
        $countries = CHtml::listData(Country::model()->findAll(), "id", 'name');
        $this->render("update", array(
            "model"=>$model,
            'countries'=>$countries
        ));
    }

    public function actionView($id)
    {
        $model = Location::model()->findByPk($id);
        $lat = $model->lat;
        $lng = $model->lng;
        $this->map("View",$lat, $lng);
        $this->render("view", array(
            "model"=>$model
        ));


    }

    public function actionDelete()
    {
        $id = Yii::app()->request->getParam('id');
        $location = Location::model()->findByPk($id);
        $schemes = Scheme::model()->findAllByAttributes(array('location_id'=>$id));
        if (!empty($schemes)) {
            foreach ($schemes as $scheme) {
                $sectors = Sector::model()->findAllByAttributes(array('scheme_id'=>$scheme->id));
                if (!empty($sectors)) {
                    foreach ($sectors as $sector) {
                        $sector->delete();
                    }
                }
                $scheme->delete();
            }
        }
        if($location->delete())
            echo CController::createUrl('/location/location/index');
        Yii::app()->end();
    }
} 