<?php
	/*
	
	Algorithm is here : 

	1. Read from Excel
	2. Create state records in a loop
		3. Check if the state name already exist
		4. Get state id after creating a record
		5. Use state id and create region records in a loop
			6. Check if the region name already exist
			7. Get region id after creating a record
			8. Use region id and create city records in a loop
				9. Check if the city name already exist
				10. Get city id after creating a record
				11. Use city id and create zipcode records in a loop
	*/
	require_once("Common.php");
	$common = new Common();
	$pluginurl = $common->pluginurl();
	
	require_once("Connect_Db.php");
	$connectDb = new Connect_Db();
	$Conn = $connectDb->Connect();
	
	
?>

<!DOCTYPE html>
			<html lang="en">
			<head>
			  <title>Product Import</title>
			  <meta charset="utf-8">
			  <meta name="viewport" content="width=device-width, initial-scale=1">
			  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
			 
			  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
			  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
			  <style>
				body{
					font-size:18px;
				}
				table {
					border: 0px solid #cecece;
					border-bottom: 1px solid #dddddd;
				}
				th {
					padding-top: .6em;
					padding-bottom: .6em;
					background-color: #d5d8da;
					color: #555;
					border-right: 1px solid #ccc;
					border-left: 1px solid #ccc;
					text-align:center !important;
					padding :2px !important;
				}
				td {
					padding :0.5% !important;
					//padding-top: .6em;
					//padding-bottom: .6em;
					border-right: 1px solid #dddddd;
					border-left: 1px solid #dddddd;
					text-align:center;
					color : #666;
					width:2%;
				}
				
				
				.button {
				  background-color: #a6c954;
				  border: none;
				  color: white;
				  padding: 5%;
				  text-align: center;
				  text-decoration: none;
				  display: inline-block;
				  font-size: 16px;
				  
				}
				
				.scroll {
				   width:1000px;
				   height: 700px;
					background:#dedede;
				   overflow-y: scroll;
				}
				.scroll::-webkit-scrollbar {
					width: 12px;
				}

				.scroll::-webkit-scrollbar-track {
					-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
					border-radius: 10px;
				}

				.scroll::-webkit-scrollbar-thumb {
					border-radius: 10px;
					-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
				}
				
				/**********************/
				.chkcontainer {
				  display: block;
				  position: relative;
				  padding-left: 35px;
				  margin-bottom: 12px;
				  cursor: pointer;
				  font-size: 12px;
				  -webkit-user-select: none;
				  -moz-user-select: none;
				  -ms-user-select: none;
				  user-select: none;
				}

				/* Hide the browser's default checkbox */
				.chkcontainer input {
				  position: absolute;
				  opacity: 0;
				  cursor: pointer;
				  height: 0;
				  width: 0;
				}

				/* Create a custom checkbox */
				.checkmark {
				  position: absolute;
				  top: 0;
				  left: 0;
				  height: 25px;
				  width: 25px;
				  background-color: #eee;
				}

				/* On mouse-over, add a grey background color */
				.container:hover input ~ .checkmark {
				  background-color: #ccc;
				}

				/* When the checkbox is checked, add a blue background */
				.container input:checked ~ .checkmark {
				  background-color: #2196F3;
				}

				/* Create the checkmark/indicator (hidden when not checked) */
				.checkmark:after {
				  content: "";
				  position: absolute;
				  display: none;
				}

				/* Show the checkmark when checked */
				.chkcontainer input:checked ~ .checkmark:after {
				  display: block;
				}

				/* Style the checkmark/indicator */
				.chkcontainer .checkmark:after {
				  left: 9px;
				  top: 5px;
				  width: 5px;
				  height: 10px;
				  border: solid white;
				  border-width: 0 3px 3px 0;
				  -webkit-transform: rotate(45deg);
				  -ms-transform: rotate(45deg);
				  transform: rotate(45deg);
				}
				
				
			  </style> 
			  
			  
			</head>
			<body>

			
			  
			<div class="container" >
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<section class="content-header">        
							<h1>Product Import</h1>
						</section>
					</div>
					
					
				</div>
				<hr style="border-top: 1px solid #ccc;margin-top: 15px;">
			 
				
				
				
				<div style="text-align:center;margin:10px;border: 1px solid #ccc;background-color:#cddee4;border-radius:10px;padding:5%;">
					<form name="import" method="post" enctype="multipart/form-data">				
						<input type="file" name="file" id="file_select"  class="button" accept=".csv" /><br />
						<input type="submit" id="import_location" name="import_location" value="Import" class="btn btn-warning"/>						
					</form>
				</div>
				
				<?php
					if(isset($_POST["import_location"]))
					{ 
						$file = $_FILES['file']['tmp_name'];
						
						//echo $file;
						if($file != ""){
						
							$filePath = $_FILES['file']['name']; 
							$ext = pathinfo($filePath, PATHINFO_EXTENSION);
							if($ext == "csv"){
							
								$handle = fopen($file, "r");
								$fp = file($file);
								$length = count($fp);
								// read the first line and ignore it
								fgets($handle);
								$number_of_records = $length - 1;
								
								echo "Number of Records in Sheet: ".$number_of_records."<br>";
								//echo '<script>console.log("Number of Records:"';$length;');</script>';
								
								$c = 0;
								$nth = 0;
								$errorCount = 0;
								
								 
								while(($filesop = fgetcsv($handle, 1000, ",")) !== false)
								{
									
									$c = $c + 1;
									$id = utf8_encode($filesop[0]);
									$type = utf8_encode($filesop[1]);
									$sku = utf8_encode($filesop[2]);
									$title = utf8_encode($filesop[3]);
									$status = utf8_encode($filesop[4]);
									$is_featured = utf8_encode($filesop[5]);
									$visibility = utf8_encode($filesop[6]);
									$short_description = utf8_encode($filesop[7]);
									$description = utf8_encode($filesop[8]);
									$sale_start_date = utf8_encode($filesop[9]);
									$sale_end_date = utf8_encode($filesop[10]);
									$tax_status = utf8_encode($filesop[11]);
									$tax_class = utf8_encode($filesop[12]);
									$in_stock = utf8_encode($filesop[13]);
									$stock = utf8_encode($filesop[14]);

									$low_stock_amount = utf8_encode($filesop[15]);
									$is_allow_backorder = utf8_encode($filesop[16]);
									$is_sold_indvidually = utf8_encode($filesop[17]);
									$weight = utf8_encode($filesop[18]);
									$length = utf8_encode($filesop[19]);
									$width = utf8_encode($filesop[20]);
									$height = utf8_encode($filesop[21]);

									$is_allow_customer_review = utf8_encode($filesop[22]);
									$purchase_note = utf8_encode($filesop[23]);
									$sale_price = utf8_encode($filesop[24]);
									$regular_price = utf8_encode($filesop[25]);
									$categories = utf8_encode($filesop[26]);

									$tags = utf8_encode($filesop[27]);
									$shipping_class = utf8_encode($filesop[28]);
									$download_limit = utf8_encode($filesop[30]);
									$download_expiry_days = utf8_encode($filesop[31]);
									$parent = utf8_encode($filesop[32]);

									$grouped_products = utf8_encode($filesop[33]);
									$upsells = utf8_encode($filesop[34]);
									$cross_sells = utf8_encode($filesop[35]);
									$external_url = utf8_encode($filesop[36]);
									$button_text = utf8_encode($filesop[37]);

									$position = utf8_encode($filesop[38]);
									$is_service = utf8_encode($filesop[39]);
									$is_used_good = utf8_encode($filesop[40]);
									$is_defective_copy = utf8_encode($filesop[41]);
									$warranty_attachment_id = utf8_encode($filesop[42]);

									$is_differential_taxed = utf8_encode($filesop[43]);
									$has_free_shipping = utf8_encode($filesop[44]);
									$unit_price_regular = utf8_encode($filesop[45]);
									$unit_price_sale = utf8_encode($filesop[46]);
									$unit_price_automatic_calculated = utf8_encode($filesop[47]);

									$unit = utf8_encode($filesop[48]);
									$unit_base = utf8_encode($filesop[49]);
									$unit_product = utf8_encode($filesop[50]);
									$cart_description = utf8_encode($filesop[51]);
									$minimum_age = utf8_encode($filesop[52]);

									$defect_description = utf8_encode($filesop[53]);
									$delivery_time = utf8_encode($filesop[54]);
									$sale_price_label = utf8_encode($filesop[55]);
									$sale_price_regular_label = utf8_encode($filesop[56]);
									$is_food = utf8_encode($filesop[57]);

									$nutrients = utf8_encode($filesop[58]);
									$allergenic = utf8_encode($filesop[59]);
									$deposit_type = utf8_encode($filesop[60]);
									$deposit_quantity = utf8_encode($filesop[61]);
									$ingredients = utf8_encode($filesop[62]);

									$nutrient_reference_value_slug = utf8_encode($filesop[63]);
									$alcohol_content = utf8_encode($filesop[64]);
									$drained_weight = utf8_encode($filesop[65]);
									$net_filling_quantity = utf8_encode($filesop[66]);
									$nutri_score = utf8_encode($filesop[67]);

									$food_description = utf8_encode($filesop[68]);
									$food_place_of_origin = utf8_encode($filesop[69]);
									$food_distributor = utf8_encode($filesop[70]);

									$attribute_1_name = utf8_encode($filesop[71]);
									$attribute_1_values = utf8_encode($filesop[72]);
									$attribute_1_visible = utf8_encode($filesop[73]);
									$attribute_1_global = utf8_encode($filesop[74]);
									$wp_page_template = utf8_encode($filesop[75]);

									$rs_page_bg_color = utf8_encode($filesop[76]);
									$yoast_wpseo_content_score = utf8_encode($filesop[77]);
									$yoast_wpseo_estimated_reading_time_minutes = utf8_encode($filesop[78]);
									$woo_nutrition_reference = utf8_encode($filesop[79]);
									
									$manufacture_country = utf8_encode($filesop[97]);
									$yoast_wpseo_primary_product_cat = utf8_encode($filesop[98]);

									$last_editor_used_jetpack = utf8_encode($filesop[99]);
									$fz_country_restriction_type = utf8_encode($filesop[100]);
									$gzd_version = utf8_encode($filesop[105]);
									$wc_facebook_sync_enabled = utf8_encode($filesop[106]);
									$fb_visibility = utf8_encode($filesop[107]);

									$wc_facebook_commerce_enabled = utf8_encode($filesop[117]);
									$yoast_wpseo_focuskeywords = utf8_encode($filesop[118]);
									$yoast_wpseo_keywordsynonyms = utf8_encode($filesop[119]);
									$wc_gla_mc_status = utf8_encode($filesop[120]);
									$wc_gla_synced_at = utf8_encode($filesop[121]);
									$wc_gla_sync_status = utf8_encode($filesop[122]);
									$wc_gla_visibility = utf8_encode($filesop[123]);
									$default_delivery_time = utf8_encode($filesop[124]);
									$fb_product_group_id = utf8_encode($filesop[125]);

									$fb_product_item_id = utf8_encode($filesop[126]);
									$wcj_add_to_cart_button_disable = utf8_encode($filesop[127]);
									$wcj_add_to_cart_button_disable_content = utf8_encode($filesop[128]);
									$wcj_add_to_cart_button_loop_disable = utf8_encode($filesop[129]);
									$yoast_wpseo_focuskw = utf8_encode($filesop[131]);
									$yoast_wpseo_metadesc = utf8_encode($filesop[132]);
									$yoast_wpseo_linkdex = utf8_encode($filesop[133]);
									$dhl_manufacture_country = utf8_encode($filesop[134]);

									$atrribute_2_name = utf8_encode($filesop[135]);
									$atrribute_2_values = utf8_encode($filesop[136]);
									$atrribute_2_visible = utf8_encode($filesop[137]);
									$atrribute_2_global = utf8_encode($filesop[138]);

									$atrribute_3_name = utf8_encode($filesop[139]);
									$atrribute_3_values = utf8_encode($filesop[140]);
									$atrribute_3_visible = utf8_encode($filesop[141]);
									$atrribute_3_global = utf8_encode($filesop[142]);
									

									$atrribute_4_name = utf8_encode($filesop[143]);
									$atrribute_4_values = utf8_encode($filesop[144]);
									$atrribute_4_visible = utf8_encode($filesop[145]);
									$atrribute_4_global = utf8_encode($filesop[146]);
									
									
									


									if($is_featured == 1){
										$is_featured = 'yes';
									}else{
										$is_featured = 'no';
									}
									
									if($in_stock == 1){
										$in_stock = 'instock';
									}else{
										$in_stock = '';
									}
									
									if($status == 1){
										$status = 'publish';
									}else{
										$status = 'draft';
									}
									if($title != ""){
										//echo $title.'|';
										if($id > 0){
											$product_1=	array( 	'ID'   =>$id,  
																'post_title' => $title,
																'post_content' => $description,
																'post_status' => $status,
																'post_type' => "product",
															);
											$post_id = wp_insert_post( $product_1 );
											wp_set_object_terms( $post_id, 'simple', $type );
											update_post_meta( $post_id, '_price', $sale_price );
											update_post_meta( $post_id, '_featured', $is_featured );
											update_post_meta( $post_id, '_stock', $stock );
											update_post_meta( $post_id, '_stock_status', $in_stock);
											update_post_meta( $post_id, '_sku', $sku );

											update_post_meta( $post_id, '_visibility', $visibility );
											update_post_meta( $post_id, '_short_description', $short_description );
											update_post_meta( $post_id, '_sale_start_date', $sale_start_date );
											update_post_meta( $post_id, '_sale_end_date', $sale_end_date );
											update_post_meta( $post_id, '_tax_status', $tax_status );
											update_post_meta( $post_id, '_tax_class', $tax_class );

											update_post_meta( $post_id, '_low_stock_amount', $low_stock_amount );
											update_post_meta( $post_id, '_is_allow_backorder', $is_allow_backorder );
											update_post_meta( $post_id, '_is_sold_individually', $is_sold_individually );
											update_post_meta( $post_id, '_weight', $weight );
											update_post_meta( $post_id, '_height', $height );
											update_post_meta( $post_id, '_width', $width );

											update_post_meta( $post_id, 'is_allow_customer_review', $is_allow_customer_review );
											update_post_meta( $post_id, '_purchase_note', $purchase_note );
											update_post_meta( $post_id, '_sale_price', $sale_price );
											update_post_meta( $post_id, '_regular_price', $regular_price );
											update_post_meta( $post_id, '_categories', $categories );

											update_post_meta( $post_id, '_tags', $tags );
											update_post_meta( $post_id, '_shipping_class', $shipping_class );
											update_post_meta( $post_id, '_download_limit', $download_limit );
											update_post_meta( $post_id, '_download_expiry_days', $download_expiry_days );
											update_post_meta( $post_id, '_parent', $parent );

											update_post_meta( $post_id, '_grouped_products', $grouped_products );
											update_post_meta( $post_id, '_upsells', $upsells );
											update_post_meta( $post_id, '_cross_sells', $cross_sells );
											update_post_meta( $post_id, '_external_url', $external_url );
											update_post_meta( $post_id, '_button_text', $button_text );

											update_post_meta( $post_id, '_position', $position );
											update_post_meta( $post_id, '_is_service', $is_service );
											update_post_meta( $post_id, '_is_used_good', $is_used_good );
											update_post_meta( $post_id, '_is_defective_copy', $is_defective_copy );
											update_post_meta( $post_id, '_warranty_attachment_id', $warranty_attachment_id );

											update_post_meta( $post_id, '_is_differential_taxed', $is_differential_taxed );
											update_post_meta( $post_id, '_has_free_shipping', $has_free_shipping );
											update_post_meta( $post_id, '_unit_price_regular', $unit_price_regular );
											update_post_meta( $post_id, '_unit_price_sale', $unit_price_sale );
											update_post_meta( $post_id, '_unit_price_automatic_calculated', $unit_price_automatic_calculated );
											
										
											update_post_meta( $post_id, '_unit', $unit );
											update_post_meta( $post_id, '_unit_base', $unit_base );
											update_post_meta( $post_id, '_unit_product', $unit_product );
											update_post_meta( $post_id, '_cart_description', $cart_description );
											update_post_meta( $post_id, '_minimum_age', $minimum_age );

											update_post_meta( $post_id, '_defect_description', $defect_description );
											update_post_meta( $post_id, '_delivery_time', $delivery_time );
											update_post_meta( $post_id, '_sale_price_label', $sale_price_label );
											update_post_meta( $post_id, '_sale_price_regular_label', $sale_price_regular_label );
											update_post_meta( $post_id, '_is_food', $is_food );

											update_post_meta( $post_id, '_nutrients', $nutrients );
											update_post_meta( $post_id, '_allergenic', $allergenic );
											update_post_meta( $post_id, '_deposit_type', $deposit_type );
											update_post_meta( $post_id, '_deposit_quantity', $deposit_quantity );
											update_post_meta( $post_id, '_ingredients', $ingredients );

											update_post_meta( $post_id, '_nutrient_reference_value_slug', $nutrient_reference_value_slug );
											update_post_meta( $post_id, '_alcohol_content', $alcohol_content );
											update_post_meta( $post_id, '_drained_weight', $drained_weight );
										
											update_post_meta( $post_id, '_food_description', $food_description );
											update_post_meta( $post_id, '_food_place_of_origin', $food_place_of_origin );
											update_post_meta( $post_id, '_food_distributor', $food_distributor );
											
											update_post_meta( $post_id, '_attribute_1_name', $attribute_1_name );
											update_post_meta( $post_id, '_attribute_1_values', $attribute_1_values );
											update_post_meta( $post_id, '_attribute_1_visible', $attribute_1_visible );
											update_post_meta( $post_id, '_attribute_1_global', $attribute_1_global );

											update_post_meta( $post_id, '_wp_page_template', $wp_page_template );
											update_post_meta( $post_id, '_manufacture_country', $manufacture_country );
											update_post_meta( $post_id, '_yoast_wpseo_primary_product_cat', $yoast_wpseo_primary_product_cat );
											update_post_meta( $post_id, '_last_editor_used_jetpack', $last_editor_used_jetpack );

											update_post_meta( $post_id, '_wc_facebook_commerce_enabled', $wc_facebook_commerce_enabled );
											update_post_meta( $post_id, '_yoast_wpseo_focuskeywords', $yoast_wpseo_focuskeywords );
											update_post_meta( $post_id, '_yoast_wpseo_keywordsynonyms', $yoast_wpseo_keywordsynonyms );
											update_post_meta( $post_id, '_wc_gla_mc_status', $wc_gla_mc_status );

											update_post_meta( $post_id, '_wc_gla_synced_at', $wc_gla_synced_at );
											update_post_meta( $post_id, '_wc_gla_sync_status', $wc_gla_sync_status );
											update_post_meta( $post_id, '_wc_gla_visibility', $wc_gla_visibility );
											update_post_meta( $post_id, '_default_delivery_time', $default_delivery_time );

											update_post_meta( $post_id, '_fb_product_group_id', $fb_product_group_id );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_disable', $wcj_add_to_cart_button_disable );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_disable_content', $wcj_add_to_cart_button_disable_content );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_loop_disable', $wcj_add_to_cart_button_loop_disable );

											update_post_meta( $post_id, '_yoast_wpseo_focuskw', $yoast_wpseo_focuskw );
											update_post_meta( $post_id, '_yoast_wpseo_metadesc', $yoast_wpseo_metadesc );
											update_post_meta( $post_id, '_yoast_wpseo_linkdex', $yoast_wpseo_linkdex );
											update_post_meta( $post_id, '_dhl_manufacture_country', $dhl_manufacture_country );

											update_post_meta( $post_id, '_attribute_2_name', $attribute_2_name );
											update_post_meta( $post_id, '_attribute_2_values', $attribute_2_values );
											update_post_meta( $post_id, '_attribute_2_visible', $attribute_2_visible );
											update_post_meta( $post_id, '_attribute_2_global', $attribute_2_global );

											update_post_meta( $post_id, '_attribute_3_name', $attribute_3_name );
											update_post_meta( $post_id, '_attribute_3_values', $attribute_3_values );
											update_post_meta( $post_id, '_attribute_3_visible', $attribute_3_visible );
											update_post_meta( $post_id, '_attribute_3_global', $attribute_3_global );

											update_post_meta( $post_id, '_attribute_4_name', $attribute_4_name );

											update_post_meta( $post_id, '_attribute_4_values', $attribute_4_values );
											update_post_meta( $post_id, '_attribute_4_visible', $attribute_4_visible );
											update_post_meta( $post_id, '_attribute_4_global', $attribute_4_global );




										}else{
											// echo $id."".$tax_class."".$tax_status."".$sku."".$visibility;
											$product_1=	array( 	'ID'   =>0,
																'post_title' => $title,
																'post_content' => $description,
																'post_status' => $status,
																'post_type' => "product",
															);
											$post_id = wp_insert_post( $product_1 );
											wp_set_object_terms( $post_id, 'simple', $type );
											update_post_meta( $post_id, '_price', '156' );
											update_post_meta( $post_id, '_featured', $is_featured );
											update_post_meta( $post_id, '_stock', $stock );
											update_post_meta( $post_id, '_stock_status', $in_stock);
											update_post_meta( $post_id, '_sku', $sku );

											update_post_meta( $post_id, '_visibility', $visibility );
											update_post_meta( $post_id, '_short_description', $short_description );
											update_post_meta( $post_id, '_sale_start_date', $sale_start_date );
											update_post_meta( $post_id, '_sale_end_date', $sale_end_date );
											update_post_meta( $post_id, '_tax_status', $tax_status );
											update_post_meta( $post_id, '_tax_class', $tax_class );

											update_post_meta( $post_id, '_low_stock', $low_stock );
											update_post_meta( $post_id, '_is_allow_backorder', $is_allow_backorder );
											update_post_meta( $post_id, '_is_sold_individually', $is_sold_individually );
											update_post_meta( $post_id, '_weight', $weight );
											update_post_meta( $post_id, '_height', $height );
											update_post_meta( $post_id, '_width', $width );

											update_post_meta( $post_id, '_is_allow_customer_review', $is_allow_customer_review );
											update_post_meta( $post_id, '_purchase_note', $purchase_note );
											update_post_meta( $post_id, '_sale_price', $sale_price );
											update_post_meta( $post_id, '_regular_price', $regular_price );
											update_post_meta( $post_id, '_categories', $categories );

											update_post_meta( $post_id, '_tags', $tags );
											update_post_meta( $post_id, '_shipping_class', $shipping_class );
											update_post_meta( $post_id, '_download_limit', $download_limit );
											update_post_meta( $post_id, '_download_expiry_days', $download_expiry_days );
											update_post_meta( $post_id, '_parent', $parent );

											update_post_meta( $post_id, '_grouped_products', $grouped_products );
											update_post_meta( $post_id, '_upsells', $upsells );
											update_post_meta( $post_id, '_cross_sells', $cross_sells );
											update_post_meta( $post_id, '_external_url', $external_url );
											update_post_meta( $post_id, '_button_text', $button_text );

											update_post_meta( $post_id, '_position', $position );
											update_post_meta( $post_id, '_is_service', $is_service );
											update_post_meta( $post_id, '_is_used_good', $is_used_good );
											update_post_meta( $post_id, '_is_defective_copy', $is_defective_copy );
											update_post_meta( $post_id, '_warranty_attachment_id', $warranty_attachment_id );

											update_post_meta( $post_id, '_is_differential_taxed', $is_differential_taxed );
											update_post_meta( $post_id, '_has_free_shipping', $has_free_shipping );
											update_post_meta( $post_id, '_unit_price_regular', $unit_price_regular );
											update_post_meta( $post_id, '_unit_price_sale', $unit_price_sale );
											update_post_meta( $post_id, '_unit_price_automatic_calculated', $unit_price_automatic_calculated );
											
										
											update_post_meta( $post_id, '_unit', $unit );
											update_post_meta( $post_id, '_unit_base', $unit_base );
											update_post_meta( $post_id, '_unit_product', $unit_product );
											update_post_meta( $post_id, '_cart_description', $cart_description );
											update_post_meta( $post_id, '_minimum_age', $minimum_age );

											update_post_meta( $post_id, '_defect_description', $defect_description );
											update_post_meta( $post_id, '_delivery_time', $delivery_time );
											update_post_meta( $post_id, '_sale_price_label', $sale_price_label );
											update_post_meta( $post_id, '_sale_price_regular_label', $sale_price_regular_label );
											update_post_meta( $post_id, '_is_food', $is_food );

											update_post_meta( $post_id, '_nutrients', $nutrients );
											update_post_meta( $post_id, '_allergenic', $allergenic );
											update_post_meta( $post_id, '_deposit_type', $deposit_type );
											update_post_meta( $post_id, '_deposit_quantity', $deposit_quantity );
											update_post_meta( $post_id, '_ingredients', $ingredients );

											update_post_meta( $post_id, '_nutrient_reference_value_slug', $nutrient_reference_value_slug );
											update_post_meta( $post_id, '_alcohol_content', $alcohol_content );
											update_post_meta( $post_id, '_drained_weight', $drained_weight );
										
											update_post_meta( $post_id, '_food_description', $food_description );
											update_post_meta( $post_id, '_food_place_of_origin', $food_place_of_origin );
											update_post_meta( $post_id, '_food_distributor', $food_distributor );
											
											update_post_meta( $post_id, '_attribute_1_name', $attribute_1_name );
											update_post_meta( $post_id, '_attribute_1_values', $attribute_1_values );
											update_post_meta( $post_id, '_attribute_1_visible', $attribute_1_visible );
											update_post_meta( $post_id, '_attribute_1_global', $attribute_1_global );

											update_post_meta( $post_id, '_wp_page_template', $wp_page_template );
											update_post_meta( $post_id, '_manufacture_country', $manufacture_country );
											update_post_meta( $post_id, '_yoast_wpseo_primary_product_cat', $yoast_wpseo_primary_product_cat );
											update_post_meta( $post_id, '_last_editor_used_jetpack', $last_editor_used_jetpack );

											update_post_meta( $post_id, '_wc_facebook_commerce_enabled', $wc_facebook_commerce_enabled );
											update_post_meta( $post_id, '_yoast_wpseo_focuskeywords', $yoast_wpseo_focuskeywords );
											update_post_meta( $post_id, '_yoast_wpseo_keywordsynonyms', $yoast_wpseo_keywordsynonyms );
											update_post_meta( $post_id, '_wc_gla_mc_status', $wc_gla_mc_status );

											update_post_meta( $post_id, '_wc_gla_synced_at', $wc_gla_synced_at );
											update_post_meta( $post_id, '_wc_gla_sync_status', $wc_gla_sync_status );
											update_post_meta( $post_id, '_wc_gla_visibility', $wc_gla_visibility );
											update_post_meta( $post_id, '_default_delivery_time', $default_delivery_time );

											update_post_meta( $post_id, '_fb_product_group_id', $fb_product_group_id );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_disable', $wcj_add_to_cart_button_disable );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_disable_content', $wcj_add_to_cart_button_disable_content );
											update_post_meta( $post_id, '_wcj_add_to_cart_button_loop_disable', $wcj_add_to_cart_button_loop_disable );

											update_post_meta( $post_id, '_yoast_wpseo_focuskw', $yoast_wpseo_focuskw );
											update_post_meta( $post_id, '_yoast_wpseo_metadesc', $yoast_wpseo_metadesc );
											update_post_meta( $post_id, '_yoast_wpseo_linkdex', $yoast_wpseo_linkdex );
											update_post_meta( $post_id, '_dhl_manufacture_country', $dhl_manufacture_country );

											update_post_meta( $post_id, '_attribute_2_name', $attribute_2_name );
											update_post_meta( $post_id, '_attribute_2_values', $attribute_2_values );
											update_post_meta( $post_id, '_attribute_2_visible', $attribute_2_visible );
											update_post_meta( $post_id, '_attribute_2_global', $attribute_2_global );

											update_post_meta( $post_id, '_attribute_3_name', $attribute_3_name );
											update_post_meta( $post_id, '_attribute_3_values', $attribute_3_values );
											update_post_meta( $post_id, '_attribute_3_visible', $attribute_3_visible );
											update_post_meta( $post_id, '_attribute_3_global', $attribute_3_global );

											update_post_meta( $post_id, '_attribute_4_name', $attribute_4_name );

											update_post_meta( $post_id, '_attribute_4_values', $attribute_4_values );
											update_post_meta( $post_id, '_attribute_4_visible', $attribute_4_visible );
											update_post_meta( $post_id, '_attribute_4_global', $attribute_4_global );


										}
									}
								}
							}
						}
					}
			
			  ?>
				
				
			</div>
			
			<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
			<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
			<script src="https://cdn.datatables.net/scroller/1.5.1/js/dataTables.scroller.min.js"></script>
			<script>
			
			
				var pluginurl = '<?php Print($pluginurl); ?>';
				
			</script>
			
			</body>
			</html>