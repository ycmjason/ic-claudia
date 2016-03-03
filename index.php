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
    <h2>Claudia 2.0 - the only interface that you will need for your database coursework.</h2>
  </div>
  <div class="col-lg-1">
    <a href="http://www.doc.ic.ac.uk/~cmy14">Jason Yu</a> &copy; 2015
  </div>
  <div class="col-lg-12">
    <h4>As usual, use it at your own risk.</h4>
  </div>
      <div class="col-lg-6">
        <b>Main Relations</b><br />
        <p>
        <a href="#">clonedtdb.staff</a>&nbsp;<a href="#">[show]</a>(login, email, lastname, firstname, telephone, room, deptrole, department, validfrom, validto)<br />
        <a href="#">clonedtdb.student</a>&nbsp;<a href="#">[show]</a>(login, email, lastname, status, entryyear, externaldept, validfrom, validto)<br />
        <a href="#">clonedtdb.course</a>&nbsp;<a href="#">[show]</a>(code, title, syllabus, term, classes, popestimate, validfrom, validto)<br />
        <a href="#">clonedtdb.class</a>&nbsp;<a href="#">[show]</a>(degreeid, yr, degree, degreeyr, major, majoryr, letter, letteryr, validfrom, validto)<br />
        <a href="#">clonedtdb.degree</a>&nbsp;<a href="#">[show]</a>(title, code, major, grp, letter, years, validfrom, validto)<br />
        <a href="#">clonedtdb.book</a>&nbsp;<a href="#">[show]</a>(code, title, authors, publisher)<br />
        </p>
        <b>Many-to-Many Joining Relations</b><br />
        <p>
        <a href="#">clonedtdb.xcourseclass</a>&nbsp;<a href="#">[show]</a>(courseid, classid, required, examcode)<br />
        <a href="#">clonedtdb.xcoursebook</a>&nbsp;<a href="#">[show]</a>(courseid, bookid, rating)<br />
        <a href="#">clonedtdb.xcoursestaff</a>&nbsp;<a href="#">[show]</a>(courseid, staffid, staffhours, role, term)<br />
        <a href="#">clonedtdb.xstudentclass</a>&nbsp;<a href="#">[show]</a>(studentid, classid)<br />
        <a href="#">clonedtdb.xstudentstaff</a>&nbsp;<a href="#">[show]</a>(studentid, staffid, role, grp, projecttitle)<br />
        </p>
      </div>
      <div class="col-lg-6">
        <b>Questions</b>
        <ol>
          <li>List the last name of staff whose first name either is Alex, Alexandra or Alexander.</li>
          <li>List the last name of staff who have started working at the college before 2008.</li>
          <li>List the last names of pairs of staff who started working in the same year.</li>
        <li>Find the earliest any member of staff could have joined the department of computing (use the validfrom
        attribute).</li>
        <li>Find all unique names of staff and students sorted alphabetically.</li>
        <li>List the last name of all staff that teach a student with login 'rf6111'.</li>
        <li>List all titles of books of which the title contains 'book' which are used in a course not taught by Paul Kelly.</li>
        <li>Count the number of books not used in any course.</li>
        <li>Using a nested query, find the names of all students who take a course which is either Programming,
        Architecture or Hardware.</li>
        <li>Using a nested query, find the titles of courses students have to take to complete a degree related to
        Bioinformatics.</li>
        <ol>
      </div>
    </div> 
    <textarea style="width:100%; font-size:20px;" rows="5" placeholder="Your query here."></textarea>
    <br />
    <label for="direct" style="cursor:pointer">
      <input id="direct" style="cursor:pointer" type="checkbox" checked="checked">
      nice outlook (non-official php script to connect to the database, but still modified from the official script.)
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
