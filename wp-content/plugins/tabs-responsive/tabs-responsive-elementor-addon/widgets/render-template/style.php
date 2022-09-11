<?php $post_id=get_the_ID(); ?>
#tab_container_<?php echo $post_id; ?> {
	overflow:hidden;
	display:block;
	width:100%;
	border:0px solid #ddd;
	margin-bottom:30px;
	}

#tab_container_<?php echo $post_id; ?> .tab-content{
	padding:20px;
	border-width: 1px !important;
	border-style:solid !important;
	border-color:<?php //echo $tab_content_border_color; ?> 
	margin-top: 0px;	
	<?php if($settings['display_tabs_border']=="yes"){ ?>
	border-width: 1px !important;
	border-style:solid !important;	
	<?php 
	} else { ?>
		border-width: 0px !important;
		border-style:solid !important;		
	<?php  } ?>
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs {
    border-bottom: 0px solid #ddd;
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li.active > a, #tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li.active > a:hover, #tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li.active > a:focus {	
	cursor: default;	
	<?php if($settings['display_tabs_border']=="yes"){ ?>
	border-width: 1px !important;
	border-style:solid !important;	
	<?php 
	} else { ?>
		border-width: 0px !important;
		border-style:solid !important;		
	<?php  } ?>
}

#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a {
    margin-right: 0px !important; 
    line-height: 1.42857143 !important;
    <?php if($settings['display_tabs_border']=="yes"){ ?>
	border-width: 1px !important;
	border-style:solid !important;	
	<?php 
	} else { ?>
		border-width: 0px !important;
		border-style:solid !important;		
	<?php  } ?>
    border-radius: 0px 0px 0 0 !important; 	
	padding: 15px 18px 15px 18px !important;
	text-decoration: none !important;	
	text-align:center !important;	
}

#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a:focus {
outline: 0px !important;
}

#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a:before {
	display:none !important;
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a:after {
	display:none !important ;
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li{
padding:0px !important ;
margin:0px;
}

#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a:hover , #tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a:focus {      
	
	
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li > a .fa{

margin-right:5px !important;

margin-left:5px !important;


}

<?php 
 switch($settings['tabs_style']){
		case "1":
		?>
		#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs a{
			background-image: none;
			background-position: 0 0;
			background-repeat: repeat-x;
		}
		<?php
		break;
		case "2":
		 ?>
		#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs a{
			background-image: url(<?php echo wpshopmart_tabs_r_directory_url.'assets/images/style-soft.png'; ?>);
			background-position: 0 0;
			background-repeat: repeat-x;
		}
		<?php
		break;
		case "3":
		?>
		#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs a{
			background-image: url(<?php echo wpshopmart_tabs_r_directory_url.'assets/images/style-noise.png'; ?>);
			background-position: 0 0;
			background-repeat: repeat-x;
			}
		<?php
		break;
	}
	?>	


#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li {
    float:<?php if($settings['tabs_position']=="yes"){
		echo "Left"; }
	else if($settings['tabs_position']!="yes")
	{
		echo "Right";
	}
	?> !important;
    margin-bottom: -1px !important;
	margin-right:0px !important; 
}


#tab_container_<?php echo $post_id; ?> .tab-content{
overflow:hidden !important;
}

<?php if($settings['tabs_alignment']=="2") { ?>

@media (min-width: 769px) {

	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li{
	float:none !important;
	<?php if($settings['tabs_position']=="yes"){ ?>
	margin-right:-1px !important;
	<?php } ?>
	<?php if($settings['tabs_position']!="yes"){ ?>
	margin-left:-1px !important;
	<?php } ?>
	}
	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs{
	float:<?php if($settings['tabs_position']=="yes"){
	echo "Left"; }
	else if($settings['tabs_position']!="yes")
	{
		echo "Right";
	}
	?> !important;
	margin:0px !important;
	}
}

#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li {
    <?php if($settings['tabs_margin_bw_two']=="yes"){ ?>
	margin-bottom: 8px !important;
	<?php } ?>
	
}
#tab_container_<?php echo $post_id; ?> .wpsm_nav{
	<?php if($settings['margin_bw_tabs_content']=="yes"){?>
		<?php if($settings['tabs_position']=="yes"){ ?>
			margin-right: 8px !important;
		<?php } ?>	
		<?php if($settings['tabs_position']!="yes"){ ?>
			margin-left: 8px !important;
		<?php } ?>	
	<?php } ?>
}

<?php } else { ?>

@media (min-width: 769px) {

	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li{
		float:<?php if($settings['tabs_position']=="yes"){
		echo "Left"; }
		else if($settings['tabs_position']!="yes")
		{
			echo "Right";
		}
		?> !important;
		<?php if($settings['tabs_position']=="yes"){ ?>
		margin-right:-1px !important;
		<?php } ?>
		<?php if($settings['tabs_position']!="yes"){ ?>
		margin-left:-1px !important;
		<?php } ?>
	}
	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs{
		float:none !important;
		margin:0px !important;
	}

	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li {
		<?php if($settings['tabs_margin_bw_two']=="yes"){ ?>
			<?php if($settings['tabs_position']=="yes"){ ?>
				margin-right: 8px !important;
			<?php } ?>	
			<?php if($settings['tabs_position']!="yes"){ ?>
				margin-left: 8px !important;
			<?php } ?>	
		<?php } ?>
		
	}
	#tab_container_<?php echo $post_id; ?> .wpsm_nav{
		<?php if($settings['margin_bw_tabs_content']=="yes"){?>
		margin-bottom: 8px !important;
		<?php } ?>
	}

}


<?php } ?>

@media (max-width: 768px) {
	#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li {
		<?php if($settings['tabs_margin_bw_two']=="yes"){ ?>
		margin-bottom: 8px !important;
		margin-right:0px !important;
		margin-left:0px !important;
		<?php } ?>
		
	}
	#tab_container_<?php echo $post_id; ?> .wpsm_nav{
		<?php if($settings['margin_bw_tabs_content']=="yes"){?>
		margin-bottom: 8px !important;
		margin-right:0px !important;
		margin-left:0px !important;
		<?php } ?>
	}
}


	.wpsm_nav-tabs li:before{
		display:none !important;
	}

	@media (max-width: 768px) {
		<?php if($settings['tabs_mobile_dis_setting']=="2"){ ?>
			
			#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs  li  a  span{
				display: none !important;
			}
			
		<?php } ?>
		
		<?php if($settings['tabs_mobile_dis_setting']=="3"){ ?>
			
			#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs  li  a  i{
				display: none !important;
			}
			
		<?php } ?>
		.wpsm_nav-tabs{
			margin-left:0px !important;
			margin-right:0px !important; 
			
		}
		<?php if($settings['title_dis_mode_mobile'] == "2") { ?>
		#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li{
			float:none !important;
		}
		<?php } else { ?>
			
			#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li {
			<?php if($settings['tabs_margin_bw_two'] =="yes"){ ?>
				<?php if($settings['tabs_position']=="yes"){ ?>
					margin-right: 8px !important;
				<?php } ?>	
				<?php if($settings['tabs_position']!="yes"){ ?>
					margin-left: 8px !important;
				<?php } ?>	
			<?php } ?>
			
			}
			
			<?php if($settings['tabs_alignment']=="2") { ?>
				#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs > li{
					float:none !important;
				}
				#tab_container_<?php echo $post_id; ?> .wpsm_nav-tabs{
				float:<?php if($settings['tabs_position']=="yes"){
				echo "Left"; }
				else if($settings['tabs_position']!="yes")
				{
					echo "Right";
				}
				?> !important;
				margin:0px !important;
				}
			<?php } ?>
			
		<?php } ?>
	
	}
<?php echo $settings['tabs_custom_css']; ?>