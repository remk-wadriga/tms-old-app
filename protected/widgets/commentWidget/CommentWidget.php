<?php
/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 01.10.14
 * Time: 23:04
 */

class CommentWidget extends CWidget {
    public $model_id;
    public function init()
    {
        $modelName = Yii::app()->controller->id;
        Yii::app()->clientScript->registerScript('comments', '

            var button = $(".addComment"),
                area = $("#comment");

            function initEditable() {
                $(document).find("p.comment_text a").editable(
                    {"name":"text","title":"Введіть текст","url":"'.Yii::app()->createUrl('/comment/update').'","type":"textarea","params":{"scenario":"update"},"validate": function(value) {
                          if ($.trim(value) == "")
                            return "Заповніть поле";
                        }}
                );
            }

            $(document).on("click", ".addComment", function(e){
                e.preventDefault();
                var user_id = '.Yii::app()->user->id.',
                    model_name = "'.$modelName.'",
                    model_id = '.$this->model_id.',
                    text = area.val().trim();


                if (text.length > 0)
                    $.fn.yiiListView.update("comment-list", {
                        url: "'.Yii::app()->createUrl('/comment/addComment').'",
                        data: {
                            text : text,
                            user_id : user_id,
                            model_name : model_name,
                            model_id : model_id
                        },
                        complete: function() {
                            area.val("");
                            initEditable();
                        }
                    });
            });

            $(document).on("click", ".deleteComment", function(e) {
                e.preventDefault();
                if (confirm("Видалити коментар?")) {
                    var id = $(this).parent().parent().parent().attr("data-id");
                    $.fn.yiiListView.update("comment-list", {
                        url: "'.Yii::app()->createUrl('/comment/delComment').'",
                        data: {
                            id : id
                        },
                        complete: function() {
                            initEditable();
                        }
                    });
                }
            });




        ', CClientScript::POS_READY);

        Yii::app()->clientScript->registerCss("comment", "
            .comment span:hover {
                cursor: pointer
            }
        ");
        $comments = Comment::model()->findAllByAttributes(array("model"=>$modelName, "model_id"=>$this->model_id), array("order"=>"dateadd DESC"));

        $dataProvider = new CArrayDataProvider($comments);
        $this->render('comment',array("dataProvider"=>$dataProvider));
    }
} 