<?php
/**
 * @version     1.0.0
 * @package     com_bts
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$assetUrl = JURI::root().'components/com_bts/assets/';
?>

<?php
$canView = JFactory::getUser()->authorise('core.edit', 'com_bts');
$canMaintenance = (JFactory::getUser()->authorise('core.edit.state', 'com_bts')) ? 1 : 0;
$canApproval = (JFactory::getUser()->authorise('core.delete', 'com_bts')) ? 1 : 0;
if ($canApproval) $canMaintenance = 1;

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_bts');
$userID = JFactory::getUser()->id;

$iconWarningStates = array(
	0	=> JURI::root().'administrator/templates/vinaphone_admin/images/admin/upgrade_cross.png',
	1	=> JURI::root().'administrator/templates/vinaphone_admin/images/admin/upgrade_tick.png'
)

?>

<link rel="stylesheet" href="<?php echo $assetUrl.'css/vnpbts.css'; ?>" />
<link rel="stylesheet" href="<?php echo $assetUrl.'ui-lightness/jquery-ui-1.10.3.custom.min.css'; ?>" />
<!--
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyA4znz5H6T2NKskWfIW6e0ve_g38Gg_NiM&sensor=true&language=vi"></script>
-->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyBlW40FBESAIH0sOGTFZSnB2xsXuBUxGPA&sensor=true&language=vi&libraries=geometry"></script>
<!--
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery-1.9.1.js'; ?> "></script>
-->
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery-ui-1.10.3.custom.min.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery.ui.map.min.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery.ui.map.services.min.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/markerclusterer.min.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery.ui.map.overlays.min.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/jquery.ui.map.extensions.js'; ?> "></script>
<script type="text/javascript" src="<?php echo $assetUrl.'js/markerwithlabel.js'; ?> "></script>

<script type="text/javascript">

//jQuery.noConflict();
jQuery( document ).ready(function( $ ) {

	// override constructor of gmap to create Marker with label
	$.extend($.ui.gmap.prototype, {
		addMarker: function(markerOptions, callback) {
			markerOptions.map = this.get('map');
			markerOptions.position = this._latLng(markerOptions.position);
			// var marker = new (markerOptions.marker || google.maps.Marker)(markerOptions);
			MarkerWithLabelOptions = markerOptions;
			MarkerWithLabelOptions.labelContent = markerOptions.bts_name;
			MarkerWithLabelOptions.labelAnchor = new google.maps.Point(20, -5);
			MarkerWithLabelOptions.labelClass = "marker-label";
			
			var marker = new MarkerWithLabel(MarkerWithLabelOptions);
			var markers = this.get('markers');
			if ( marker.id ) {
				markers[marker.id] = marker;
			} else {
				markers.push(marker);
			}
			if ( marker.bounds ) {
				this.addBounds(marker.getPosition());
			}
			this._call(callback, markerOptions.map, marker);
			return $(marker);
		}
	});
	// end override

	var center = new google.maps.LatLng(21.029071,105.855761);
	var data = <?php echo json_encode($this->items); ?>;
	var warningLevelText = <?php echo json_encode(BtsHelper::getWarningLevel()); ?>;
	var iconWarningStates = <?php echo json_encode($iconWarningStates); ?>;
	var addressData = [];
	$.each( data, function(i, markerData) {
		addressData.push({label: markerData.bts_name+' '+markerData.address, value: markerData.bts_name});
	});
	
//	$('#map_canvas').gmap({'disableDefaultUI':true, 'center':center, 'callback': function() {
	$('#map_canvas').gmap({'center':center, 'draggable': true, 'zoomControl':false, 'panControl':false, 'scaleControl':true, 'streetViewControl':false, 'callback': function() {
		var self = this;
        var map = self.get('map');
		console.log(map); 
		// add marker
		$.each( data, function(i, markerData) {
		
			var latLng = new google.maps.LatLng(markerData.latitude, markerData.longitude);
			self
				.addMarker({
					'bts_id':markerData.id,
					'bts_name':markerData.bts_name,
					'bts_alias':markerData.bts_alias,
					'bts_address':markerData.address,
					'position':latLng,
					'icon':markerData.icon_url,
					'icon_size': { x: 20, y: 34 },
					'activitystatus':markerData.activitystatus,
					'network':markerData.network,
					'co_site':markerData.co_site,
					'level':markerData.level,
					'duplicate':markerData.duplicate,
					'bounds':true
					}, function(map, marker) {})
				.click(function() {
					var marker = $(this)[0];
					console.log(marker);
					$('#dialog').dialog({
						modal:true, 
                        minWidth: 780,
                        minHeight: 500,
                        draggable: false,
						position:'center',
						title:'<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>', 
						open: function(event, ui) {
							station.showDetail(marker, $(this));
						},
						close: function() {
							station.resetForm();
						},
						buttons: {
                            'Đóng': function() {
								$('#tblWarningList tbody').empty();
								$('#warningHistory').hide();
								$('#warningListStationID').val('');
								$('#tblNotesList tbody').empty();
								$('#notesHistory').hide();
                                $(this).dialog('close');
                                return false; 
                            }
						}
						
					});
				});
		});
		
		// set clusterer
		var cluster = new MarkerClusterer(self.get('map'), self.get('markers'), {
			'maxZoom':14, 'duplicate_makers':true
		});
		self.set('MarkerClusterer', cluster);
		
		// console.log(addressData); 
		$('#map_search_input').autocomplete({
			minLength: 2,
			source: addressData,
			select: function(event, ui) {
				
				var _marker = '';
				self.find('markers', { 'property': 'bts_name', 'value': ui.item.value }, function(marker, isFound) {
					if (marker.bts_name == ui.item.value) {
						_marker = marker;
					}
					});
				
				// reset filter before showing station
				filter(true);
				
				map.setZoom(15);
				map.panTo(_marker.position);
				self.openInfoWindow({
					content:  _marker.bts_address
				}, _marker);
			}
		});
		
		var point1 = '';
		var point2 = '';
		$('#map_way_o').autocomplete({
			minLength: 2,
			source: addressData,
			select: function(event, ui) {
				var _marker = '';
				self.find('markers', { 'property': 'bts_name', 'value': ui.item.value }, function(marker, isFound) {
					if (marker.bts_name == ui.item.value) {
						_marker = marker;
					}
				});
				point1 = _marker;
			}
		});
		$('#map_way_d').autocomplete({
			minLength: 2,
			source: addressData,
			select: function(event, ui) {
				var _marker = '';
				self.find('markers', { 'property': 'bts_name', 'value': ui.item.value }, function(marker, isFound) {
					if (marker.bts_name == ui.item.value) {
						_marker = marker;
					}
				});
				point2 = _marker;
			}
		});
		
		$('#btn_show_way').on('click', function() {
			if (point1 != '' && point2 != '') {
				$('#map_canvas').gmap('displayDirections', { 'origin': point1.position, 'destination': point2.position, 'travelMode': google.maps.DirectionsTravelMode.DRIVING }, function(success, result) {
					self.openInfoWindow({
						content:  point1.bts_address
					}, point1);
					self.openInfoWindow({
						content:  point2.bts_address
					}, point2);
				});
			}
		});
		var markers = self.get('markers');
		// filter
		$('#btn_filter').on('click', function() { 
			filter(false);
		});
		$('#btn_filter_reset').on('click', function() {
			filter(true);
		});
		
		$('#chkMarkerLabel').on('change', function() {
			var checked = $(this).prop('checked');
			$.each(markers, function(index, marker) {
				marker.setVisibleLabel(checked);
			});
		});
		
		var distancePoints = [];
		var distanceLine = null;
		$('#accordion2 .panel-title a').on('click', function() {
			if ($(this).attr('id') == 'titleMeasureTool') {
				google.maps.event.addListener(map, 'click', function(event) {
					if (distancePoints.length<2) {
						distancePoints.push(
							self.addMarker({
								icon        : '<?php echo JURI::root(); ?>components/com_bts/assets/images/point.png',
								position    : event.latLng, 
								draggable   : true, 
								bounds      : false
							})
							.dragend( function(event) {
								getDistance();
							})
						)
					}
					if (distancePoints.length==2) {
						getDistance();
					}
				});
			} else {
				resetDistance();
				google.maps.event.clearListeners(map, 'click');
			}
		});
		
		$('#btnMeasureToolReset').on('click', function() {
			resetDistance();
		});
		
		// filter station
        function filter(reset) {
			
			if (reset) {
				$('#map_filter_type').val('');
				$('#map_filter_status').val('');
				$('#map_filter_stype').val('');
				$('#map_filter_site').val('');
			}
			
			var warningType	= $('#map_filter_type').val();
			var status 		= $('#map_filter_status').val();
			var stationType = $('#map_filter_stype').val();
			var coSite 		= $('#map_filter_site').val();
			
			var visibleMarkers = [];
			cluster.clearMarkers();
			$.each(markers, function(index, marker) {
				// marker.setVisible(false);
				// cluster.removeMarker(marker);
				if (
					(warningType == '' || (warningType != '' && marker.level == warningType)) &&
					(status == '' || (status != '' && marker.activitystatus == status)) &&
					(stationType == '' || (stationType != '' && marker.network == stationType)) &&
					(coSite == '' || (coSite != '' && marker.co_site == coSite))
				) {
					// marker.setVisible(true);
					// cluster.addMarker(marker);
					visibleMarkers.push(marker);
				}
			});
			cluster.addMarkers(visibleMarkers);
		}
		
		function resetDistance() {
			for (var i=0; i<distancePoints.length; i++) {
				distancePoints[i][0].setMap(null);
			}
			distanceLine.setMap(null);
			distancePoints = [];
			$('#txtMeasureToolResult').text('0');
		}
		function getDistance() {
			if (distancePoints.length == 2) {
				// set line between 2 points
				var point1 = distancePoints[0][0];
				var point2 = distancePoints[1][0];
				var polyOptions = {
					strokeColor: '#FF0000',
					strokeOpacity: 0.6,
					strokeWeight: 4,
					map: map,
				};
				if (distanceLine != null) distanceLine.setMap(null);
				distanceLine = new google.maps.Polyline(polyOptions);
				var path = [point1.position, point2.position];
				distanceLine.setPath(path);
				
				// print distance result
				$('#txtMeasureToolResult').text((google.maps.geometry.spherical.computeDistanceBetween(point1.position, point2.position)/1000).toFixed(2));

				// console.log('computeLength',google.maps.geometry.spherical.computeLength(path)); 
				// console.log('computeHeading', google.maps.geometry.spherical.computeHeading(path[0], path[1])); 
			}
		}
	}});
	
	// helper class for handling BTS data
	station = {
        getMoreInfo: function(id) {
            $.getJSON(
				'<?php echo JURI::root(); ?>index.php?option=com_bts&view=station&id='+id+'&format=single', 
				function( data ) {
                    $('#dialogMoreRows').empty();
                    $.each(data, function (index, item) {
                        var divTitle = $('<div/>',{'class': 'span4', text: item.text});
                        var divValue = $('<div/>',{'class': 'span8', text: item.value});
                        $('#dialogMoreRows')
                            .append(
                                $('<div/>',{'class':'row-fluid'})
                                    .append(divTitle)
                                    .append(divValue)
                            )
                    })
				}
			);
        },
		setForm: function(id) {
			$.getJSON(
				'<?php echo JURI::root(); ?>index.php?option=com_bts&view=station&id='+id+'&format=json', 
				function( data ) {
					var stationData = data;
					$('#frm_station_name').text(stationData.bts_name);
					$('#station_address').val(stationData.address);
					$('#bts_id').val(id);
				}
			);
		},
		resetForm: function(){
			$('#frm_station_id').text('');
			$('#frm_station_name').text('');
			$('#station_address').val('');
			$('#bts_id').val('');
		},
		saveForm: function() {
			$.ajax({
				type: "POST",
				dataType: 'json',
				url: '<?php echo JURI::root(); ?>index.php?option=com_bts&task=station.save',
				data: $('#form_marker_details').serialize(),
				success: function(data) {
					console.log(data); 
				},
				error: function() {
					// console.log('no no no'); 
				}
			});
		},
		remove: function() {
			$.ajax({
				type: "POST",
				dataType: 'json',
				url: '<?php echo JURI::root(); ?>index.php?option=com_bts&task=station.remove',
				data: $('#form_marker_details').serialize(),
				success: function(data) {
					console.log(data); 
				},
				error: function() {
					// console.log('no no no'); 
				}
			});
		},
		find: function(id) {
			for(var i=0; i<data.length; i++) {
				if (data[i].id == id) return data[i];
			}
		},
		showDetail: function(marker, dialog) {
			if (marker.id) marker.bts_id = marker.id;
			
			// set dialog title
			dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network);
			
			$('#frm_station_id').text(marker.bts_id);
			$('#frm_station_name').text(marker.bts_name);
			$('#frm_station_level').html('<span class="warning'+marker.level+'">'+warningLevelText[marker.level]+'</span>');
			$('#frm_station_address').text(marker.bts_address);
			$('#frm_station_network').text(marker.network);
			
			// show duplicated station
			$('#frm_station_duplicate').hide();
			if (marker.duplicate) {
				var duplicatedBts = station.find(marker.duplicate);
				if (duplicatedBts) {
					$('#frm_station_duplicate').show();
					$('#frm_station_duplicate .span8').html(duplicatedBts.bts_name + '. Network: '+ duplicatedBts.network);
					var linkViewDuplicatedBts = $('<a/>', {
						href: 'javascript:void(0)',
						text: '. <?php echo JText::_('COM_BTS_VIEW_DETAILS'); ?>'
					});
					linkViewDuplicatedBts.on('click', function() {
						station.showDetail(duplicatedBts, dialog);
					});
					$('#frm_station_duplicate .span8').append(linkViewDuplicatedBts);
				}
			}
			station.getMoreInfo(marker.bts_id);
			
			// add Event for viewing notes
			$('#btnViewNotes').off('click');
			$('#btnViewNotes').on('click', function() {
				$('#notesHistory').show();
				$('#notesHistoryLoading').show();
				
				// update dialog title
				dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network + ' - <?php echo JText::_('COM_BTS_TITLE_LIST_VIEW_NOTES'); ?>');
				
				// add Back button
				if ($('#btnBackWarningList').length==0) {
					var btnBack = $('<button/>', {
						type: 'button',
						'class':'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
						id: 'btnBackWarningList',
						role: 'button',
						'aria-disabled': 'false',
						html: '<span class="ui-button-text">Quay lại</span>'
					});
					btnBack.on('click', function() {
						$('#tblNotesList tbody').empty();
						$('#notesHistory').hide();
						$('#notesListStationID').val('');
						dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network);
						$(this).remove();
					});
					$('.ui-dialog-buttonset').prepend(btnBack);
				}
				
				// get warning list
				$.ajax({
					type: "GET",
					dataType: 'json',
					url: '<?php echo JURI::root(); ?>index.php?option=com_bts&view=notes&format=json&station_id='+marker.bts_id,
					success: function(data) {
						$('#notesHistoryLoading').hide();
						console.log(data); 
						$.each(data, function(index, item) {
							var tdNote = $('<td/>', {text: item.note});
							var tdCreatedBy = $('<td/>', {text: item.created_by});
							var tdCreatedTime = $('<td/>', {text: item.created_time});
							var tdApprovedBy = $('<td/>', {text: item.approved_by});
							var tdApprovedTime = $('<td/>', {text: item.approved_time});
							var tdState = $('<td/>', {'class':'center', html: btsHelper.getCheckbox('note_approval_'+item.id, item.approved, 0)});
							$('#tblNotesList tbody').append(
								$('<tr/>')
									.append(tdNote)
									.append(tdCreatedBy)
									.append(tdCreatedTime)
									.append(tdState)
									.append(tdApprovedBy)
									.append(tdApprovedTime)
							)
						});
					},
					error: function() {
						// console.log('no no no'); 
					}
				});
			});
			
			// add Event for viewing history
			$('#btnViewHistory').off('click');
			$('#btnViewHistory').on('click', function() {
				$('#warningHistory').show();
				$('#warningHistoryLoading').show();
				
				// update dialog title
				dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network + ' - <?php echo JText::_('COM_BTS_TITLE_WARNING_LIST'); ?>');
								
				// add Back button
				if ($('#btnBackWarningList').length==0) {
					var btnBack = $('<button/>', {
						type: 'button',
						'class':'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
						id: 'btnBackWarningList',
						role: 'button',
						'aria-disabled': 'false',
						html: '<span class="ui-button-text">Quay lại</span>'
					});
					btnBack.on('click', function() {
						$('#tblWarningList tbody').empty();
						$('#warningHistory').hide();
						$('#warningListStationID').val('');
						// if ($('#btnSaveWarningList').length!=0) $('#btnSaveWarningList').remove();
						dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network);
						$(this).remove();
					});
					$('.ui-dialog-buttonset').prepend(btnBack);
				}
				
				// add Save button
				/*
				if ($('#btnSaveWarningList').length==0) {
					var btnSave = $('<button/>', {
						type: 'button',
						'class':'ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only',
						id: 'btnSaveWarningList',
						role: 'button',
						'aria-disabled': 'false',
						html: '<span class="ui-button-text">Lưu</span>'
					});
					btnSave.on('click', function() {
						$('#warningHistoryLoading').show();
						$.ajax({
							type: "POST",
							dataType: 'json',
							url: '<?php echo JURI::root(); ?>index.php?option=com_bts&task=warnings.save',
							data: $('#frmWarningList').serialize(),
							success: function(data) {
								$('#warningHistoryLoading').hide();
								
								// back to previous screen
								$('#tblWarningList tbody').empty();
								$('#warningHistory').hide();
								$('#warningListStationID').val('');
								
								dialog.dialog('option', 'title', '<?php echo JText::_('COM_BTS_TITLE_STATION_DETAILS'); ?>: ' + marker.bts_name + ' ' + marker.network);
								$('#btnBackWarningList').remove();
								$('#btnSaveWarningList').remove();
								
							}
						});
					});
					$('.ui-dialog-buttonset').prepend(btnSave);
				}
				*/
				
				// set ID for station in warning list form
				$('#warningListStationID').val(marker.bts_id);
				
				// get warning list
				$.ajax({
					type: "GET",
					dataType: 'json',
					url: '<?php echo JURI::root(); ?>index.php?option=com_bts&view=warnings&format=json&station_id='+marker.bts_id,
					success: function(data) {
						$('#warningHistoryLoading').hide();
						console.log(data); 
						$.each(data, function(index, item) {
							var tdID = $('<td/>', {text: item.id});
							var tdLevel = $('<td/>', {html: '<span class="warning'+item.level+'">'+warningLevelText[item.level]+'</span>'});
							var tdDesc = $('<td/>', {text: item.warning_description});
							// var tdMtState = $('<td/>', {html: btsHelper.warningStateCb(item.id, item.maintenance_state, <?php echo $canMaintenance; ?>)});
							var tdMtState = $('<td/>', {html: '<img src="'+iconWarningStates[item.maintenance_state]+'" alt="'+item.maintenance_state+'" />'});
							var tdApState = $('<td/>', {html: '<img src="'+iconWarningStates[item.approve_state]+'" alt="'+item.approve_state+'" />'});
							var tdMtBy = $('<td/>', {text: item.maintenance_by});
							var tdMtTime = $('<td/>', {text: item.maintenance_time});
							var tdApBy = $('<td/>', {text: item.approve_by});
							var tdApTime = $('<td/>', {text: item.approve_time});
							$('#tblWarningList tbody').append(
								$('<tr/>')
									.append(tdID)
									.append(tdLevel)
									.append(tdDesc)
									.append(tdMtState)
									.append(tdMtBy)
									.append(tdMtTime)
									.append(tdApState)
									.append(tdApBy)
									.append(tdApTime)
							)
						});
					},
					error: function() {
						// console.log('no no no'); 
					}
				});
			});
		}
	};
	
	btsHelper = {
		getCheckbox: function(name, value, permission) {
			var chkBox =  $('<input/>', {
				type: 'checkbox',
				name: name,
				value: value
			});
			
			if (value==1) chkBox.attr('checked','checked');
			if (permission==0) chkBox.attr('disabled','disabled');
			
			return chkBox;
		},
		warningStateCb: function(name, value, permission) {
			var cb =  $('<select/>', {
				name: name
			});
			if (permission == 0) cb.attr('disabled','disabled');
			
			var warningStateData = [
				{value: 0, text: 'Chưa sửa chữa'},
				{value: 1, text: 'Đã sửa, chờ xác nhận'},
				{value: 2, text: 'Đã xác nhận'},
			];
			$.each(warningStateData, function(index, item) {
				var option = $('<option/>', {value: item.value, text: item.text});
				if (value == item.value) option.attr('selected','selected');
				cb.append(option);
			});
			return cb;
		}
	}
    
    $('#accordion2 .panel-title a').on('click', function() {
        $('#accordion2 .panel-title a').removeClass('active');
        $(this).addClass('active');
    });
    
    $('#btnCollapse').on('click', function() {
        var btn = $(this);
        if (!btn.hasClass('active')) {
            $('#map_tools .tool_content').hide();
            $('#map_tools').animate({
                width: '22px'
            }, function() {
                btn.addClass('active');
            });
        } else {
            $('#map_tools').animate({
                width: '220px'
            }, function() {
                btn.removeClass('active');
                $('#map_tools .tool_content').show();
            });
            
        }
    });
    
});


</script>

<div id="message" class="hidden">There are no matched stations</div>

<div id="map_ctn">
    <div id="map_canvas"></div>
    <div id="map_tools">
        <div class="inner">
            <div class="tool_content">
                <div class="row-fluid"></div>
				<div class="accordion" id="accordion2">
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2"  href="#collapseOne"><?php echo JText::_('COM_BTS_TITLE_MAP_SEARCH'); ?></a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="accordion-body collapse in">
                            <div class="accordion-inner">
                                <form action="#" id="map_search">
                                    <?php echo JText::_('COM_BTS_TITLE_MAP_ENTER_BTS_NAME'); ?>: <br/>
                                    <input type="text" name="q" id="map_search_input" />
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2"  href="#collapseTwo">
									<?php echo JText::_('COM_BTS_TITLE_MAP_FIND_DIRECTION'); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <form action="#" id="map_way" class="form-inline">
                                    <?php echo JText::_('COM_BTS_TITLE_MAP_FIND_DIRECTION_FROM'); ?> <br/>
                                    <input type="text" name="w1" id="map_way_o" />
                                    <br/> <?php echo JText::_('COM_BTS_TITLE_MAP_FIND_DIRECTION_TO'); ?> <br/>
                                    <input type="text" name="w2" id="map_way_d" />
                                    <div class="pull-right"><input type="button" value="<?php echo JText::_('COM_BTS_TITLE_MAP_BTS_SEARCH'); ?>" class="btn btn-default" id="btn_show_way" /></div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2"  href="#collapseThree">
                                    <?php echo JText::_('COM_BTS_TITLE_MAP_FILTER'); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <form action="#" id="map_filter" class="form-filter">
									<div class="row-fluid">
										<label class="checkbox"><input type="checkbox" id="chkMarkerLabel" checked value="1" /> <?php echo JText::_('COM_BTS_TITLE_STATION_SHOW_MARKER_LABEL'); ?></label>
									</div>
                                    <div class="row-fluid">
                                        <?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL'); ?><br/>
                                        <select name="type" id="map_filter_type">
                                            <option value="" selected="selected"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_ALL'); ?></option>
                                            <option value="0"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_NORMAL'); ?></option>
                                            <option value="1"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_WARNING'); ?></option>
                                            <option value="2"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_DANGE'); ?></option>
											<option value="3"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_LEVEL_VERY_DANGE'); ?></option>
                                        </select>
                                    </div>
                                    <div class="row-fluid">
                                        <?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_ACTIVITY_STATUS'); ?><br/>
                                        <select name="status" id="map_filter_status">
                                            <option value="" selected="selected"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_ALL'); ?></option>
                                            <option value="OnAir">On Air</option>
                                            <option value="NotOnAir">Not On Air</option>
                                        </select>
                                    </div>
                                    
                                    <div class="row-fluid">
                                        <?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_NETWORK'); ?> <br/>
                                        <select name="stype" id="map_filter_stype">
                                            <option value="" selected="selected"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_ALL'); ?></option>
                                            <option value="2G">2G</option>
                                            <option value="3G">3G</option>
                                            <option value="2G-3G">2G-3G</option>
                                        </select>
                                    </div>
                                    <div class="row-fluid">
                                        <?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_CO_SITE'); ?> <br/>
                                        <select name="site" id="map_filter_site">
                                            <option value="" selected="selected"><?php echo JText::_('COM_BTS_TITLE_MAP_FILTER_WARNING_ALL'); ?></option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    
                                    <div class="row-fluid">
                                        <div class="pull-right">
                                            <input type="button" value="<?php echo JText::_('COM_BTS_TITLE_MAP_BTS_FILTER'); ?>" class="btn btn-default" id="btn_filter" />
                                            <input type="button" value="<?php echo JText::_('COM_BTS_TITLE_MAP_BTS_RESET'); ?>" class="btn btn-default" id="btn_filter_reset" />
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-group">
                        <div class="accordion-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion2"  href="#collapseFourth" id="titleMeasureTool">
                                    <?php echo JText::_('COM_BTS_TITLE_MAP_TOOLS_DISTANCE_MEASURE'); ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFourth" class="accordion-body collapse">
                            <div class="accordion-inner">
                                <?php echo JText::_('COM_BTS_TITLE_MAP_TOOLS_DISTANCE_MEASURE_GUIDE'); ?>
								<h4 class="center">
									<strong><?php echo JText::_('COM_BTS_TITLE_MAP_TOOLS_DISTANCE_MEASURE_RESULT'); ?> <span id="txtMeasureToolResult">0</span> km</strong>
								</h4>
								<div class="pull-right">
									<input type="button" class="btn btn-warning" id="btnMeasureToolReset" value="<?php echo JText::_('COM_BTS_TITLE_MAP_TOOLS_DISTANCE_MEASURE_RESET'); ?>">
								</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <a href="javascript:void(0)" id="btnCollapse"></a>
        </div>
    </div>
    
</div>
<div id="dialog" style="display:none">
    <div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_STATIONS_ID'); ?></div>
        <div class="span8" id="frm_station_id"></div>
    </div>
    <div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_STATIONS_BTS_NAME'); ?></div>
        <div class="span8" id="frm_station_name"></div>
    </div>
	<div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_STATIONS_NETWORK'); ?></div>
        <div class="span8" id="frm_station_network"></div>
    </div>
    <div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_FORM_LBL_STATION_ADDRESS'); ?></div>
        <div class="span8" id="frm_station_address"></div>
    </div>
    <div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_FORM_LBL_STATION_WARNING'); ?></div>
        <div class="span8"><span id="frm_station_level"></span> - <a href="javascript:void(0)" id="btnViewHistory"><?php echo JText::_('COM_BTS_FORM_LBL_STATION_WARNING_HISTORY'); ?></a></div>
    </div>
	<div class="row-fluid">
        <div class="span4"><?php echo JText::_('COM_BTS_TITLE_LIST_VIEW_NOTES'); ?></div>
        <div class="span8"><a href="javascript:void(0)" id="btnViewNotes"><?php echo JText::_('COM_BTS_NOTES_VIEW_HISTORY'); ?></a></div>
    </div>
	<div class="row-fluid"  id="frm_station_duplicate">
        <div class="span4"><?php echo JText::_('COM_BTS_FORM_LBL_STATION_DUPLICATE'); ?></div>
        <div class="span8"></div>
    </div>
    <div id="dialogMoreRows"></div>
	 
	<div id="warningHistory">
		<div id="warningHistoryLoading"><img src="<?php echo $assetUrl.'images/loading.gif'; ?>" alt="" /></div>
		<form action="#" id="frmWarningList">
			<table class="table table-hover" id="tblWarningList">
				<thead>
					<tr>
						<th><a href="javascript:void(0)">ID</a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_LEVEL'); ?>"><?php echo JText::_('COM_BTS_FORM_LBL_STATION_WARNING'); ?></a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_WARNING_DESCRIPTION'); ?>">Mô tả</a> </th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_FORM_DESC_WARNING_MAINTENANCE_STATE'); ?>">TT KP</a> </th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_MAINTENANCE_BY'); ?>">KP</a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_MAINTENANCE_TIME'); ?>">TG KP</a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_FORM_DESC_WARNING_APPROVE_STATE'); ?>">TT XN</a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_APPROVE_BY'); ?>">XN</a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_WARNINGS_APPROVE_TIME'); ?>">TG XN</a></th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<input type="hidden" name="station_id" id="warningListStationID" />
		</form>
	</div>
	
	<div id="notesHistory">
		<div id="notesHistoryLoading"><img src="<?php echo $assetUrl.'images/loading.gif'; ?>" alt="" /></div>
		<form action="#" id="frmNotesList">
			<table class="table table-hover" id="tblNotesList">
				<thead>
					<tr>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_STATIONS_NOTE'); ?>"><?php echo JText::_('COM_BTS_STATIONS_NOTE'); ?></a></th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_NOTES_CREATED_BY'); ?>"><?php echo JText::_('COM_BTS_NOTES_CREATED_BY'); ?></a> </th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_NOTES_CREATED_TIME'); ?>"><?php echo JText::_('COM_BTS_NOTES_CREATED_TIME'); ?></a> </th>
						<th class="center"><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_NOTES_APPROVED'); ?>"><?php echo JText::_('COM_BTS_NOTES_APPROVED'); ?></a> </th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_NOTES_APPROVED_BY'); ?>"><?php echo JText::_('COM_BTS_NOTES_APPROVED_BY'); ?></a> </th>
						<th><a href="javascript:void(0)" title="<?php echo JText::_('COM_BTS_NOTES_APPROVED_TIME'); ?>"><?php echo JText::_('COM_BTS_NOTES_APPROVED_TIME'); ?></a> </th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			<input type="hidden" name="station_id" id="notesListStationID" />
		</form>
	</div>
</div>

<!--
<div id="dialog" style="display:none">
	<form action="#" id="form_marker_details">
		<label for="station_id">BTS ID: <span id="frm_station_id"></span></label>
		<label for="frm_station_name">BTS name: <span id="frm_station_name"></span></label>
		<label for="station_address">Address:</label>
		<textarea name="jform[address]" id="station_address" cols="30" rows="2"></textarea>
		<input type="hidden" name="jform[id]" id="bts_id" />
		<input type="hidden" name="jform[ajax]" value="1" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
-->