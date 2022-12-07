<?php
	require_once("Common.php"); 
	$common=new Common();
	$domainurl = $common->domainurl();
	
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
				
				<label for="fromversiontime">From:</label>
				<input type="datetime-local" class="form-control" id="fromversiontime" name="fromversiontime">
				
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
				
				<label for="toversiontime">To:</label>
				<input type="datetime-local" class="form-control" id="toversiontime" name="toversiontime">
				
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top:2%;">
				<input type="button" class="btn btn-primary" value="GetData" onclick="getData();"/>
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12">
			<table id="datasTable"  width="100%" style="border-bottom: 1px solid #ddd">
			</table>
			</div>
		</div>
	</div>


<script type="text/javascript">
	
	 $(document).ready(function(){
		
		
		$.ajax({
					type: "POST",
					data: {
						'action': 'getVersionData'						
						},
					url: "<?php echo $domainurl;?>/Service_Call.php", 
					success:function(data){
						var json_obj = $.parseJSON(data);
						var tablestring='';
						tablestring += '<tr>';
						tablestring += '<th>Datetime</th>';
						tablestring += '<th>Previous Version</th>';
						tablestring += '<th>Updated Version</th>';
						tablestring += '<th>Action</th>';
						tablestring += '</tr>';
					
						for(var i in json_obj){
							
							tablestring += '<tr>';
							tablestring += '<td>'+json_obj[i].date_time+'</td>';
							tablestring += '<td>'+json_obj[i].p_version+'</td>';
							tablestring += '<td>'+json_obj[i].n_version+'</td>';							
							tablestring += '<td><button onclick="revertVersion('+json_obj[i].p_version+','+json_obj[i].n_version+')">Revert</button></td>';
							tablestring += '</tr>';
						}
						
						$('#datasTable').html(tablestring); 
					} 
			});
		})

	function revertVersion(p_ver,n_ver){
		var current_date = new Date().toJSON().slice(0,19);		
		$.ajax({
					type: "POST",
					data: {
						'action': 'createVersionData',
						'datetime':current_date,
						'p_version':p_ver,
						'n_version':n_ver
						},
					url: "<?php echo $domainurl;?>/Service_Call.php", 
					success:function(data){
						var json_obj = $.parseJSON(data);						
						alert("Version Reverted");
                        window.location.href = "http://localhost/wordpress/wp-admin/admin.php?page=version_listing";
						
					} 
			});
	}
	function getData(){ 
		
		var fromversiontime = $("#fromversiontime").val();
		var toversiontime = $("#toversiontime").val();
		
		$.ajax({
					type: "POST",
					data: {
						'action': 'getFilteredData',
						'fromversiontime':fromversiontime,
						'toversiontime':toversiontime,
						
						},
					url: "<?php echo $domainurl;?>/Service_Call.php", 
					success:function(data){
						$('#datasTable').html('');
						var json_obj = $.parseJSON(data);
						var tablestring='';
						tablestring += '<tr>';
						tablestring += '<th>Datetime</th>';
						tablestring += '<th>Previous Version</th>';
						tablestring += '<th>Updated Version</th>';
						tablestring += '<th>Action</th>';
						tablestring += '</tr>';
					
						for(var i in json_obj){
							
							tablestring += '<tr>';
							tablestring += '<td>'+json_obj[i].date_time+'</td>';
							tablestring += '<td>'+json_obj[i].p_version+'</td>';
							tablestring += '<td>'+json_obj[i].n_version+'</td>';							
							tablestring += '<td><button onclick="revertVersion('+json_obj[i].p_version+','+json_obj[i].n_version+')">Revert</button></td>';
							tablestring += '</tr>';
						}
						
						$('#datasTable').html(tablestring);  
						
					} 
			});
	}	
</script>
</body>
</html>