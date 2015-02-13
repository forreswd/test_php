<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript"></script>
    <script>
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
            }
            xhr.send(null);
        }
    </script>
</head>
<body>
<div style="background-color:#ff0000;display:inline;">aaa</div>
<div style="background-color:#ffff00;display:inline;">bbb</div>
<form id="myForm" name="myForm" method="get">
    <p>user<input type="text" id="user">time<input type="text" id="time" ></p>
    <p><input type="text" id="result"></p>
    <p><input type="button" onclick="ajaxFunction()"></p>

</form>
<div style="display:inline-table;" ><span>aaaa</br></span><span>bbbb</span></div>
<div style="display:inline-table;"><span>aaaa</br></span><span>bbbb</span></div>
</body>
</html>
