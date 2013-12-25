<?php
/**
 * @version     1.0.0
 * @package     com_vnpbtsnodeb
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Chuyen Trung Tran <chuyentt@gmail.com> - http://www.geomatics.com.vn
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
?>
<style type="text/css">
#map_canvas {
    margin: 0;
    padding: 0;
    width:100%;
    height: 500px;
}
.labels {
    color: white;
    background-color: red;
    font-family: "Lucida Grande", "Arial", sans-serif;
    font-size: 10px;
    text-align: center;
    width: 60px;     
    white-space: nowrap;
}
</style>

<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css');
// $doc->addScript('http://maps.google.com/maps/api/js?key=AIzaSyA4znz5H6T2NKskWfIW6e0ve_g38Gg_NiM&sensor=false');

// localhost API Key
$doc->addScript('http://maps.google.com/maps/api/js?key=AIzaSyBlW40FBESAIH0sOGTFZSnB2xsXuBUxGPA&sensor=false');

$doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
$doc->addScript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js');
$doc->addScript('media/jui/js/jquery.ui.map.js');
$doc->addScript('media/jui/js/jquery.ui.map.services.js');
$doc->addScript('media/jui/js/markerclusterer.min.js');
$doc->addScript('media/jui/js/jquery.ui.map.overlays.js');
$doc->addScript('media/jui/js/jquery.ui.map.extensions.js');


//$doc->addStyleSheet('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.css');
//$doc->addStyleSheet('http://getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.css');
//$doc->addStyleSheet('http://jschr.github.io/bootstrap-modal/css/bootstrap-modal-bs3patch.css');
//$doc->addStyleSheet('http://jschr.github.io/bootstrap-modal/css/bootstrap-modal.css');

//$doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js');
//$doc->addScript('http://getbootstrap.com/2.3.2/assets/js/google-code-prettify/prettify.js');
//$doc->addScript('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.js');
//$doc->addScript('http://jschr.github.io/bootstrap-modal/js/bootstrap-modalmanager.js');
//$doc->addScript('http://jschr.github.io/bootstrap-modal/js/bootstrap-modal.js');
//$doc->addScript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js');
//$doc->addScript('media/jui/js/jquery.ui.map.js');
//$doc->addScript('media/jui/js/jquery.ui.map.services.js');
//$doc->addScript('media/jui/js/markerclusterer.min.js');


$canView = JFactory::getUser()->authorise('core.edit.own', 'com_vnpbtsnodeb');
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vnpbtsnodeb');
$userID = JFactory::getUser()->id;
?>
<script type="text/javascript">
var center = new google.maps.LatLng(21.029071,105.855761);
$(function() { 
	$('#map_canvas').gmap({'disableDefaultUI':true, 'center':center, 'callback': function() {
		var self = this;
		self.set('openDialog', function(marker) {
			$('#dialog'+marker.__gm_id).dialog({'modal':true, 'title': 'Edit and save point', 'buttons': {
				'Delete': function() { $(this).dialog('close'); marker.setMap(null); return false; },
				'Save': function() { $(this).dialog('close'); return false; }
			}});
		});
		self.set('findLocation', function(location, marker) {
			self.search({'location': location}, function(results, status) {
				if ( status === 'OK' ) {
					$.each(results[0].address_components, function(i,v) {
						if ( v.types[0] == "administrative_area_level_1" || v.types[0] == "administrative_area_level_2" ) {
							$('#state'+marker.__gm_id).val(v.long_name);
						} else if ( v.types[0] == "country") {
							$('#country'+marker.__gm_id).val(v.long_name);
						}
					});
					marker.setTitle(results[0].formatted_address);
					$('#address'+marker.__gm_id).val(results[0].formatted_address);
					self.get('openDialog')(marker);
				}
			});
		});
		

		$(function() {
			var data = <?php echo json_encode($this->items); ?>; 
			$.each( data, function(i, marker) {
				var icon = 'http://vnp1.igeotrans.com/media/jui/img/btsnode-custom.png';
				var latLng = new google.maps.LatLng(marker.latitude, marker.longitude);
				var address = marker.address;
				var station_id = marker.station_id;
				var comment = marker.note;
				if (marker.state == 1) {
					self.addMarker({'position':latLng,'icon':icon,'bounds':true}, function(map, marker) {
						$('#dialog').append(
							'<form id="dialog'+marker.__gm_id+'" method="get" action="/" style="display:none;">'+
							'<p><label for="station_id'+marker.__gm_id+'">Station ID</label>'+
							'<input id="station_id'+marker.__gm_id+'" class="txt" name="country" value="'+station_id+'"/></p>'+
							'<p><label for="state'+marker.__gm_id+'">State</label>'+
							'<input id="state'+marker.__gm_id+'" class="txt" name="state" value=""/></p>'+
							'<p><label for="address'+marker.__gm_id+'">Address</label>'+
							'<input id="address'+marker.__gm_id+'" class="txt" name="address" value="'+address+'"/></p>'+
							'<p><label for="coordinate'+marker.__gm_id+'">Coordinate</label>'+
							'<input id="coordinate'+marker.__gm_id+'" class="txt" name="coordinate" value="'+marker.getPosition()+'"/></p>'+
							'<p><label for="comment'+marker.__gm_id+'">Comment</label>'+
							'<textarea id="comment'+marker.__gm_id+'" class="txt" name="comment" cols="40" rows="5">'+
							comment+'</textarea></p>'+
							'<?php echo JHtml::_("form.token"); ?></form>'					
						);
					}).click(function() {
						self.get('openDialog')(this);
					});
				}
			});
			self.set('MarkerClusterer', new MarkerClusterer(self.get('map'), self.get('markers'), {
                    		'maxZoom':14
                	}));
		});
	}});
});

</script>


<?php
/*
$scripttext = '<script type="text/javascript">';
$scripttext .= 'var data =  '. json_encode($this->items) . ';';
$scripttext .= 'var center = new google.maps.LatLng(21.029071,105.855761);';
$scripttext .= '$(function() {';
$scripttext .= "$('#map_canvas').gmap({'zoom': 10, 'center': center, 'disableDefaultUI':true}).bind('init', function(evt, map) { ";

if($canView):
    $scripttext .= 'var markers = [];';
    $scripttext .= 'for (var i = 0; i < data.length; i++) {';
    $scripttext .= 'var dataNode = data[i];';
    $scripttext .= 'var latLng = new google.maps.LatLng(dataNode.latitude,dataNode.longitude);';
    if($canEdit):
        $scripttext .= "$(this).gmap('addMarker', {position:latLng,draggable:true,icon:'http://vnp1.igeotrans.com/media/jui/img/btsnode-custom.png'}).click(";
        $scripttext .= 'function() {';
        $scripttext .= "$('#map_canvas').gmap('openInfoWindow', { content : dataNode.address }, this);";
        $scripttext .= '});';
    else:
        $scripttext .= 'if(dataNode.created_by == ' . $userID . ') {';
        $scripttext .= "$(this).gmap('addMarker', {position:latLng,draggable:true,icon:'http://vnp1.igeotrans.com/media/jui/img/btsnode-custom.png'}).click(";
        $scripttext .= 'function() {';
        $scripttext .= "$('#map_canvas').gmap('openInfoWindow', { content : 'dataNode.station_id' }, this);";
        $scripttext .= '});';
        $scripttext .= '} else {';
        $scripttext .= "$(this).gmap('addMarker', {position:latLng,icon:'http://vnp1.igeotrans.com/media/jui/img/btsnode-custom.png'}).click(";
        $scripttext .= 'function() {';
        $scripttext .= "$('#map_canvas').gmap('openInfoWindow', { content : dataNode.latitude }, this);";
        $scripttext .= '});';
        $scripttext .= '}';
    endif;
    $scripttext .= '}';
    $scripttext .= "$(this).gmap('set', 'MarkerClusterer', new MarkerClusterer(map, $(this).gmap('get', 'markers')));";
    
endif;
$scripttext .= '});';
$scripttext .= '});';
$scripttext .= "</script>";

echo $scripttext; 
*/

// $mapDetail=$this->item;
$activeMenu = JFactory::getApplication()->getMenu()->getActive();
$mapwidth = $activeMenu->query['mapwidth'];
$mapheight = $activeMenu->query['mapheight'];

?>
<div id="map_canvas"></div>
<div id="dialog"></div>