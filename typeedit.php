<?php
require_once('function.php');
dbconnect();
session_start();

if (!is_user()) {
	redirect('index.php');
}

?>


<?php
 $user = $_SESSION['username'];
$usid = $pdo->query("SELECT id FROM register WHERE username='".$user."'");
$usid = $usid->fetch(PDO::FETCH_ASSOC);
 $uid = $usid['id'];
 include ('header.php');
 ?>
 
  <link rel="stylesheet" href="css/bootstrap-wysihtml5.css" />


    <div class="pageheader">
      <h2><i class="fa fa-th-list"></i> EDIT Cloth Type List</h2>
    </div>

    
    <div class="contentpanel">
      <div class="panel panel-default">

        <div class="panel-body">
		
		   
<?php

$eid = $_GET["id"];

if($_POST)
{

$title = $_POST["title"];

$sex = $_POST["sex"];

////////////////////-------------------->> TITLE ki faka??

 if(trim($title)=="")
      {
$err1=1;
}


if(isset($err1))
 $error = $err1;;


if (!isset($error) || $error == 0){
	
$res = $pdo->exec("UPDATE type SET title='".$title."', sex='".$sex."' WHERE id='".$eid."'");

if($res){
	echo "<div class='alert alert-success alert-dismissable'>
<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	

UPDATED Successfully!
<meta http-equiv='refresh' content='2; url=typeview.php' />

</div>";
}else{
	echo "<div class='alert alert-danger alert-dismissable'>
<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	

Some Problem Occurs, Please Try Again. 

</div>";
}
} else {
	
if (!isset($err1) || $err1 == 1){
echo "<div class='alert alert-danger alert-dismissable'>
<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>	

Title Can Not be Empty!!!

</div>";
}		

}
}

$oldd = $pdo->query("SELECT title, sex FROM type WHERE id='".$eid."'");
$old = $oldd->fetch(PDO::FETCH_ASSOC)
?>					
		
		
		
		<form action="" method="post">
				
            <div class="form-group">
              <label class="col-sm-3 control-label">Name</label>
              <div class="col-sm-6">
			  <?php		  
				echo " <input name='title' value='$old[title]' class='form-control' type='text'>";
			  ?>
			  </div>
            </div>
            <div class="form-group">
					
					<label class="col-sm-3 control-label">Sex</label>
					
					<div class="col-sm-6"><select name="sex" class="form-control">
                        <option value="0" <?php if($old['sex']== 0) echo('selected = "selected"'); ?>>Male</option>
                        <option value="1"<?php if($old['sex']== 1) echo('selected = "selected"'); ?>>Female</option>
					</select></div>
                
				<div class="col-sm-3 col-sm-offset-3" style="width:200px;"><br/>
				  <input type="submit" class="btn btn-lg btn-success btn-block" value="Submit">
				</div>
			
			 
			 
          </form>
          
		  
		  
        </div>
      </div>
                  
      

      
    </div><!-- contentpanel -->
    
  </div><!-- mainpanel -->

  
</section>


<?php
 include ('footer.php');
 ?>


<script src="js/bootstrap-timepicker.min.js"></script>


<script src="js/wysihtml5-0.3.0.min.js"></script>
<script src="js/bootstrap-wysihtml5.js"></script>
<script src="js/ckeditor/ckeditor.js"></script>
<script src="js/ckeditor/adapters/jquery.js"></script>



<script>
jQuery(document).ready(function(){
    
    "use strict";
    
  // HTML5 WYSIWYG Editor
  jQuery('#wysiwyg').wysihtml5({color: true,html:true});
  
  // CKEditor
  jQuery('#ckeditor').ckeditor();
  
  jQuery('#inlineedit1, #inlineedit2').ckeditor();
  
  // Uncomment the following code to test the "Timeout Loading Method".
  // CKEDITOR.loadFullCoreTimeout = 5;

  window.onload = function() {
  // Listen to the double click event.
  if ( window.addEventListener )
	document.body.addEventListener( 'dblclick', onDoubleClick, false );
  else if ( window.attachEvent )
	document.body.attachEvent( 'ondblclick', onDoubleClick );
  };

  function onDoubleClick( ev ) {
	// Get the element which fired the event. This is not necessarily the
	// element to which the event has been attached.
	var element = ev.target || ev.srcElement;

	// Find out the div that holds this element.
	var name;

	do {
		element = element.parentNode;
	}
	while ( element && ( name = element.nodeName.toLowerCase() ) &&
		( name != 'div' || element.className.indexOf( 'editable' ) == -1 ) && name != 'body' );

	if ( name == 'div' && element.className.indexOf( 'editable' ) != -1 )
		replaceDiv( element );
	}

	var editor;

	function replaceDiv( div ) {
		if ( editor )
			editor.destroy();
		editor = CKEDITOR.replace( div );
	}

	 jQuery('#timepicker').timepicker({defaultTIme: false});
  jQuery('#timepicker2').timepicker({showMeridian: false});
  jQuery('#timepicker3').timepicker({minuteStep: 15});

	
	
});



</script>
</body>
</html>



