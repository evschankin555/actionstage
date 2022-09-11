<#
var post_id='<?php echo get_the_ID(); ?>';
#>
				<div id="tab_container_{{post_id}}" >
	 
					<ul class="wpsm_nav wpsm_nav-tabs" role="tablist" id="myTab_{{post_id}}">
						<#
						var i=1;
						var j=1;
						if ( settings.tabs_list.length ) {
						_.each( settings.tabs_list, function( item,index ) {
						
						#>	
						
							<li role="presentation" '<# if(i==1){ #>' class="active" '<# } #>' >
								<a href="#tabs_desc_{{post_id}}_{{i}}" aria-controls="tabs_desc_{{post_id}}_{{i}}" role="tab" data-toggle="tab" class="tabs-anchor-class">
									
											<# if(settings.tabs_ic_pt_align=="1"){ #>
												<# if(settings.dis_op_title_icon=="1" || settings.dis_op_title_icon=="3") { #>
													<# if(item.tabs_responsive_show_above_icon=="yes") { #>	<i class="{{item.tabs_responsive_icon}}"></i> <# } #>
												<# } 
											} #>
											
											<# if(settings.dis_op_title_icon=="1" || settings.dis_op_title_icon=="2") { #>
											
											<span>{{item.tabs_responsive_title}}</span>
											
											<# } #>
											
											<# if(settings.tabs_ic_pt_align=="2"){ #>
												<# if(settings.dis_op_title_icon=="1" || #settings.dis_op_title_icon=="3") { #>
													<# if(item.tabs_responsive_show_above_icon=="yes") { #>	<i class="{{item.tabs_responsive_icon}}"></i> <# }#>
												<# } 
											} #>			
										
									
								</a>
							</li>
						<# i++; }); #>
					 </ul>

					  <!-- Tab panes -->
					  <div class="tab-content tab-content-border" id="tab-content_{{post_id}}">
						<# _.each( settings.tabs_list, function( item,index ) {
							
							tab_responsive_Key = view.getRepeaterSettingKey( 'tabs_responsive_description', 'tabs_list', index );
							view.addInlineEditingAttributes( tab_responsive_Key, 'none' );							
						
						#>
						 <div role="tabpanel" class="tab-pane '<# if(j==1){ #>' in active '<# } #>'" id="tabs_desc_{{post_id}}_{{j}}">
								<span {{{ view.getRenderAttributeString( tab_responsive_Key ) }}}>{{item.tabs_responsive_description}}</span>
						 </div>
						<# j++; });
						}
						#>	
					 </div>
					 
				 </div>
