<?php
	require_once("Common.php"); 
	$common=new Common();
	$domainurl = $common->domainurl();   
    $plugin_version =get_bloginfo( 'version' );

?> 
<!DOCTYPE html>
	<html lang="en">
	<head>
		<title>Wordpress Version</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>		
		
  		<style>
	  		table {
				border: 0px solid #cecece;
				}
			th {
				margin-top:20px;
				padding-top: .6em;
				padding-bottom: .6em;
				width: 20%;
				font-size: 130%;
				}
			td {
				margin-top:20px;
				padding-top: .6em;
				padding-bottom: .6em;
			}
		
  	</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
	
	<div class="content-wrapper">
		<div class="row">		
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="col-lg-4 col-md-4 col-sm-4">
				
				<label for="fromversiontime">Date Time</label>
				<input type="datetime-local" class="form-control" id="versiontime"  name="versiontime" disabled />
				
				</div>
                <div class="col-lg-4 col-md-4 col-sm-4">
				
				<label for="versiontime">Current Version</label>
				<input type="text" class="form-control" id="p_version" name="p_version" value="<?php echo $plugin_version; ?>" disabled />
				
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
				
				<label for="p_version">Version</label>
				<input type="text" class="form-control" id="n_version" name="n_version" />
				
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top:2%;">
				<input type="button" class="btn btn-primary" value="Update" onclick="createData();"/>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
			
			</div>
		</div>
	</div>


<script type="text/javascript">
	
	 $(document).ready(function(){
		
		$('input[type=datetime-local]').val(new Date().toJSON().slice(0,19));
	})

	
	function createData(){
		
		var datetime = $("#versiontime").val();
		var p_version =$("#p_version").val();
		var n_version = $("#n_version").val();
		$.ajax({
					type: "POST",
					data: {
						'action': 'createVersionData',
						'datetime':datetime,
						'p_version':p_version,
						'n_version':n_version
						},
					url: "<?php echo $domainurl;?>/Service_Call.php", 
					success:function(data){
						var json_obj = $.parseJSON(data);						
						alert("Saved");
                        window.location.href = "http://localhost/wordpress/wp-admin/admin.php?page=version_listing";
						
					} 
			});
	}	
</script>
</body>
</html>