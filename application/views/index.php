<!DOCTYPE html>
<html>
<head>
<script  src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script src=""></script>
<!--    <link href=" --><?php // echo base_url("/css/css.css");?><!--" rel="stylesheet" type="text/css" />-->
</head>
<div id="date">
</div>

<?php if(!empty($article[0])){
    $j = end($article); $j = $j[0]['date_id']-1;
} else { $j = 0; }
for($i = count($date); $i > 0; $i --) { ?>
<div class="date" id="<?php echo $date[$i-1]['bigDate']; ?>">
        <p>
            <input class="date_button" type="button" id="<?php echo $date[$i-1]['date']; ?>" value="<?php echo $date[$i-1]['bigDate']; ?>">
        </p>
    <div class="content" id="<?php echo $date[$i-1]['content_prev']; ?>" style="display: inline-table;">
        <input class="prev" type="button" id="<?php echo $date[$i-1]['prev']; ?>" value="prev">
    </div>
    <?php  if(!empty($article[$j])) {?>
    <div id="<?php echo $article[$j][0]['time']; ?>" style="display: inline-table;">
        <span>
            <input type="text" class="title" value="<?php echo $article[$j][0]['title']; ?>" id="<?php echo $article[$j][0]['time'].'title'; ?>">
        </span></br>
        <span>
             <input type="text" class="article" value="<?php echo $article[$j][0]['article']; ?>" id="<?php echo $article[$j][0]['article']; ?>">
        </span></br>
        <span>
             <input type="button" class="config" value="config" id="<?php echo $article[$j][0]['config']; ?>">
        </span>
        <span>
             <input type="button" class="delete" value="delete" id="<?php echo $article[$j][0]['delete']; ?>">
        </span>
    </div>
    <?php  } $j--;?>
    <div class="content" id="<?php echo $date[$i-1]['content_next']; ?>" style="display: inline-table;">
        <input class="next" type="button" id="<?php echo $date[$i-1]['next']; ?>" value="next">
    </div>
</div>
<?php }?>
<script>
    $(document).ready(function() {
        $(document).on('click', ".date_button:button", function () {
            var date_id = $(this).attr('id');
            $("#" + date_id).parent().nextAll().toggle();
        });
        $(document).on('click', ".next:button", function () {
            var next_id = $(this).attr('id');
            var time_id = $(this).parent().prev().attr('id');
            ajaxNext(time_id,next_id);
        });
        $(document).on('click',".prev:button",function(){
            var prev_id = $(this).attr('id');
            var time_id = $(this).parent().next().attr('id');
            ajaxPrev(time_id,prev_id);
        });
        $(document).on('click',".config:button",function(){
            var date = $(this).parent().parent().attr('id');
            var now_time = time()[1];
            var title = $(this).parent().prev().prev().prev().prev().children().attr('value');
            var article = $(this).parent().prev().prev().children().attr('value');
            var id = $(this).attr('id');
            ajaxUpdate(date,title,article,now_time,id);
        });
        $(document).on('click',".delete:button",function(){
            var time = $(this).parent().parent().attr('id');
            ajaxDelete(time);
        });
    });
//    setTimeout('daily_article()',1000);
//    setTimeout('daily_article()',2000);
    //setInterval("create_article(1)",1000);
    function time() {
        var now= new Date();
        var year=now.getFullYear();
        var month=now.getMonth();
        var date=now.getDate();
        var hour=now.getHours();
        var min=now.getMinutes();
        var sec=now.getSeconds();
        var big_data = year+"-"+(month+1)+"-"+date;
        var data = year+"-"+(month+1)+"-"+date+"-"+hour+"-"+ min + "-" + sec;
        var result = new Array();
        result[0] = big_data;
        result[1] = data ;
        result[2] = data  +  'title'  ;
        result[3] = data  +  'article'  ;
        result[4] = data  +  'next'  ;
        result[5] = data + 'content';
        result[6] = data  +  'prev'  ;
        return result;
    }



    function daily_article(){
        var div = time()[0];
        var date = time()[1];
        var next = time()[4];
        var content = time()[5];
        var content_next = content + 'next';
        var content_prev = content + 'prev';
        var prev = time()[6];
        $("#date").after('<div class="date" id="'+ div +'" ></div>');
        $("#" + div ).prepend('<div class="content" id="'+ content_next +'" style="display:inline-table;"></div>');
        $("#" + content_next ).prepend('<input class="next" type="button"  id="' + next + '" value = "next"  >');
        $("#" + div ).prepend('<div class="content" id="'+ content_prev +'" style="display:inline-table;"></div>');
        $("#" + content_prev ).prepend('<input class="prev" type="button"  id="' + prev  + '" value = "prev"  >');
        //$("#" + add).hide();
        $("#" + div ).prepend('<p><input class="date_button" type="button"  id="' + date +'" value="'+ div +'" ></p>');
        ajaxInsertButton(div,next,prev,content_next,content_prev,date);
    }
    function create_article(id){
        var title = time()[2];
        var article = time()[3];
        var date = time()[1];
        var big_date = time()[0];
        var config = time()[1] + 'config';
        var del = time()[1] + 'delete';
        $("#" + id).parent().before('<div  id="' + date + '" style="display:inline-table;"></div>');
        $("#" + date).prepend('<span><input type="button" class="delete" value="delete" id="' + del +'"></span>');
        $("#" + date).prepend('<span><input type="button" class="config" value="config" id="' + config +'"></span>');
        $("#" + date).prepend('<span><input type="text" class="article" value="' + article +'" id="' + article +'"></span></br>');
        $("#" + date).prepend('<span><input type="text" class="title" value="title" id="' + title +'"></span></br>');
        ajaxInsertArticle(big_date,date, $("#" + title).attr('value'),$("#" + article).attr('value'),$("#" + id).parent().parent().attr('id'),config,del);
        if($("#" + date).prev().attr('class') != 'content'){
            $("#" + date).prev().remove();
        }
    }

    function ajaxDelete(time){
        var xhr;
        xhr = new XMLHttpRequest();
        var url =  "delete_article/" + time  ;
        xhr.open('get', url, true);
        var flag = '';
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                flag = xhr.responseText;
                var result = JSON.parse(flag);
                alert(flag);
                if(flag == 0){
                    ajaxNext();
                } else if(flag == 1){

                }
            }
        };
        xhr.send(null);
    }

    function ajaxUpdate(time,title,article,now_time,id){
        var xhr;
        xhr = new XMLHttpRequest();
        var url =  "update_article/" + time + "/" + title + "/" + article + "/" + now_time  ;
        xhr.open('get', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                $("#" + id).parent().parent().prev().after('<div  id="' + now_time + '" style=" display: inline-table;"></div>');
                $("#" + now_time).prepend('<span><input type="button" class="delete" value="delete" id="' + now_time + "delete" +'"></span>');
                $("#" + now_time).prepend('<span><input type="button" class="config" value="config" id="' + now_time + "config" +'"></span>');
                $("#" + now_time).prepend('<span><input type="text" class="article" value="' + article + '" id="' + now_time + "article" + '"></span></br>');
                $("#" + now_time).prepend('<span><input type="text" class="title" value="' + title + '" id="' + now_time + "title" + '"></span></br>');
                $("#" + id).parent().parent().remove();
            }
        };
        xhr.send(null);
    }

    function ajaxPrev($time,prev_id) {
        var xhr;
        xhr = new XMLHttpRequest();
        var flag = '';
        var url = "search_prev/" + $time ;
        xhr.open('get', url, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                flag = xhr.responseText;
                if (flag != 0) {
                    var result = JSON.parse(flag);
                    $("#" + prev_id).parent().after('<div  id="' + result.time + '" style=" display: inline-table;"></div>');
                    $("#" + result.time).prepend('<span><input type="button" class="delete" value="delete" id="' + result.delete +'"></span>');
                    $("#" + result.time).prepend('<span><input type="button" class="config" value="config" id="' + result.config + '"></span>');
                    $("#" + result.time).prepend('<span><input type="text" class="article" value="' + result.article + '" id="' + result.article + '"></span></br>');
                    $("#" + result.time).prepend('<span><input type="text" class="title" value="' + result.title + '" id="' + result.time + "title" + '"></span></br>');
                    $("#" + result.time).next().remove();
                } else {
                }
            }
        };
        xhr.send(null);
    }

    function ajaxNext($time,next_id){
        var xhr;
        xhr = new XMLHttpRequest();
        var flag = '';
        var url = "search_next/"  + $time  ;
        xhr.open('get',url,true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                flag = xhr.responseText;
                if(flag != 0){
                    var result = JSON.parse(flag);
                    $("#" + next_id).parent().before('<div  id="' + result.time + '" style=" display:inline-table;"></div>');
                    $("#" + result.time).prepend('<span><input type="button" class="delete" value="delete" id="' + result.delete +'"></span>');
                    $("#" + result.time).prepend('<span><input type="button" class="config" value="config" id="' + result.config + '"></span>');
                    $("#" + result.time).prepend('<span><input type="text" class="article" value="' + result.article +'" id="' +result.article +'"></span></br>');
                    $("#" + result.time).prepend('<span><input type="text" class="title" value="' + result.title + '" id="' + result.time + "title" + '"></span></br>');
                    $("#" + result.time).prev().remove();
                } else if(flag == 0){
                    create_article(next_id);
                }
            }
        };
        xhr.send(null);
    }

    function ajaxInsertButton(bigDate,next,prev,content_next,content_prev,date){
        var xhr;
        //
        xhr = new XMLHttpRequest();
        var url = "create_button/" + bigDate + "/" + next + "/" + prev + "/" + content_next  +"/" + content_prev  +"/" + date ;
        xhr.open('get',url,true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
//                document.myForm.result.value = xhr.responseText;
            }
        };
        xhr.send(null);
    }
    function ajaxInsertArticle(date,time,title,article,id,config,del){
        var xhr;
        //
        xhr = new XMLHttpRequest();
        var url = "create_article/" + date + "/" + time + "/" + title + "/" + article + "/" + id + "/" + config + "/" + del;
        xhr.open('post',url,true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
//                document.myForm.result.value = xhr.responseText;
            }
        };
        xhr.send(null);
    }
    function ajaxFunction(){
        var xhr;
        //
        xhr = new XMLHttpRequest();
        var url = "t/" + document.getElementById('user').value + "/" + document.getElementById('time').value ;
        xhr.open('get',url,true);
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4 ){
                document.myForm.result.value = xhr.responseText;
            }
        };
        xhr.send(null);
    }

</script>
<form method="post">
</form>
</html>