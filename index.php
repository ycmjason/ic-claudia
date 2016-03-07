#!/usr/bin/php
<?php
date_default_timezone_set("Europe/London");
function partial_explode($string){
  return explode("|",$string);
}
function withinHour($ip, $logs){
  foreach(array_reverse($logs) as $row){
    if($ip==$row[2]){
      $time=strtotime($row[0]);
      return (time()-$time)/3600 < 1;
    }
  }
  return false;
}
$userIp=$_SERVER['HTTP_X_FORWARDED_FOR'];

//read log file
$filename="./claudia.log";//[TIMESTAMP]|[IP]
$logs=file($filename,FILE_IGNORE_NEW_LINES);

//separate the timestamp and ip
if(count($logs)>0){
  $logs=array_map("partial_explode",$logs);
}

//write log file
if(!withinHour($userIp,$logs)){
  $file=fopen($filename,"a");
  fwrite($file,date("Y-m-d H:i:s")."|".date("D j M, Y g:i a")."|".$userIp."\n");
  fclose($file);
}
?>
<html>
  <head>
    <title>Claudia SQL Query</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
document.domain = "www.doc.ic.ac.uk"
//REFERENCE: http://richonrails.com/articles/text-area-manipulation-with-jquery
    $.fn.extend({
insertAtCursor: function(myValue) {
  return this.each(function(i) {
    if (document.selection) {
      //For browsers like Internet Explorer
      this.focus();
      sel = document.selection.createRange();
      sel.text = myValue;
      this.focus();
    }
    else if (this.selectionStart || this.selectionStart == '0') {
      //For browsers like Firefox and Webkit based
      var startPos = this.selectionStart;
      var endPos = this.selectionEnd;
      var scrollTop = this.scrollTop;
      this.value = this.value.substring(0, startPos) + myValue + 
      this.value.substring(endPos,this.value.length);
      this.focus();
      this.selectionStart = startPos + myValue.length;
      this.selectionEnd = startPos + myValue.length;
      this.scrollTop = scrollTop;
    } else {
      this.value += myValue;
      this.focus();
    }
  })
}
})
    </script>
  </head>
  <body>
  <div class="container-fluid">
  <div class="row">
  <div class="col-lg-11">
    <h2>Claudia 2016 - the only interface that you will need for your database coursework.</h2>
  </div>
  <div class="col-lg-1">
    <a href="http://www.doc.ic.ac.uk/~cmy14">Jason Yu</a> &copy; 2015
  </div>
  <div class="col-lg-12">
    <h4>As usual, use it at your own risk.</h4>
  </div>
      <div class="col-lg-6">
<ul>
<li><a href="#">actor</a> <a href="#">[show]</a> - stores actors  data  including first name  and last  name.</li>
<li><a href="#">film</a> <a href="#">[show]</a> - stores films data  such  as  title,  release year, length, rating, etc.</li>
<li><a href="#">film_actor</a> <a href="#">[show]</a> - stores the relationships between films and actors.</li>
<li><a href="#">category</a> <a href="#">[show]</a> - stores film's categories  data.</li>
<li><a href="#">film_category</a> <a href="#">[show]</a> - stores the relationships between films and categories.</li>
<li><a href="#">store</a> <a href="#">[show]</a> - contains the stores  data  including manager staff and address.</li>
<li><a href="#">inventory</a> <a href="#">[show]</a> - stores inventory data.</li>
<li><a href="#">rental</a> <a href="#">[show]</a> - stores rental  data.</li>
<li><a href="#">payment</a> <a href="#">[show]</a> - stores customer's payments.</li>
<li><a href="#">staff</a> <a href="#">[show]</a> - stores staff data.</li>
<li><a href="#">customer</a> <a href="#">[show]</a> - stores customers data.</li>
<li><a href="#">address</a> <a href="#">[show]</a> - stores address data  for staff and customers</li>
<li><a href="#">city</a> <a href="#">[show]</a> - stores the city  names.</li>
<li><a href="#">country</a> <a href="#">[show]</a> - stores the country names.</li>
</ul>
      </div>
      <div class="col-lg-6">
        <b>Questions</b>
        <ol>
<li>Find all films  that  are not in  the inventory and count them.</li>
<li>Count  the number  of  transactions  each  staff has been  processing and  find  
the  staff  member   (id)  with  the   biggest   number  of  transactions  and   also 
the staff member  with  the biggest sum of  the transaction value.</li>
<li>Find all stores  with  more  than  300 customers. Report the ID of the store.</li>
<li>Find  all   customers   who   spent   more  than  200. Report   the   ID  of  the  
customer  as  well  as  the sum spent.</li>
<li>Find the films whose rental  rate  is  higher  than  the average rental  rate. Use 
ubquery  and count the number  of  films.</li>
<li>Find films  that  have  return  date  between   2005-05-29  and   2005-05-30
and report  the movie titles. Use a subquery.</li>
        <ol>
      </div>
    </div> 
    <textarea style="width:100%; font-size:20px;" rows="5" placeholder="Your query here."></textarea>
    <br />
    <label for="direct" style="cursor:pointer">
      <input id="direct" style="cursor:pointer" type="checkbox" checked="checked">
      nice outlook (non-official php script to connect to the same database.)
    </label>
    <div class="query"></div>
    <iframe style="width:100%; height:550px; border:0px">
    </iframe>

    </div>
    <script>
function query(sql){
  if($("#direct").prop("checked")){
    $.post("https://www.doc.ic.ac.uk/~cmy14/claudia/press3.php",
      {"query":sql},
      changeContent(sql));
  }else{
    $.post("https://www.doc.ic.ac.uk/~theinis/press3.php",
      {"query":sql},
      changeContent(sql));
  }
}
function changeContent(sql){
  return function(html){ 
    $(".query").html("<b>Sent Query: </b><span class=\"querySQL\">"+sql+"</span>");
    $("iframe").contents().find("body").html(html);
  }
}
var typewatch = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();
$("a").click(function(){
    var ss = $(this).html();
    if(ss=="[show]"){
      var table = $(this).prev().html();
      query("SELECT * FROM "+table);
    }
    else{
      $("textarea").insertAtCursor(ss);
      query($("textarea").val());
    }
    });
$("#direct").click(function(){
  query($(".querySQL").html());
});
$("textarea").keyup(function(){
  typewatch(function(){
    query($("textarea").val());
    },700);
  }
);
    </script>
  </body>
</html>
