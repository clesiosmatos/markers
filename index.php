<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
 
  <div id="map"></div>  
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: -25.4379828, lng: -49.2694907},
          zoom: 15
        });

        map.addListener('click', function(event) {
          $('#add_place').modal('toggle');
          document.getElementById('latMap').value = event.latLng.lat();
          document.getElementById('lngMap').value = event.latLng.lng();
          console.log(event);
        });

        var places = <?= readAll() ?>;

        var infowindow = new google.maps.InfoWindow();

        for(var i=0; i < places.length; i++) {

          var marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: {
              lat: parseFloat(places[i].lat), 
              lng: parseFloat(places[i].lng)
              }
          });

          var content = '<div><strong>' + places[i].name + '</strong><br>' +
           'Endereço: ' + places[i].address +'<br>' +
           'Tipo: ' + places[i].type + '</div><br>' +
           '<a href="?action=edit&id=' + places[i].id +'">Edit</a> ' +
           '<a href="crud.php?action=delete&id=' + places[i].id +'">Delete</a>';

          //Um closure (fechamento) é uma função que se "lembra" do ambiente — ou escopo léxico — em que ela foi criada.
           google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
                return function() {
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                };
            })(marker,content,infowindow));

        }

        <?php if($_GET['action'] == 'edit'): ?>
          $('#edit_place').modal('toggle');
        <?php endif; ?>

      }
      
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXeVCRfrym2zwfn5ZxmTq8o_GtmlBr8Os&callback=initMap"
    async defer></script>
  </body>
</html>

<!-- Modal -->
<div class="modal fade" id="add_place" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Place</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form method="POST" action="crud.php?action=create">
        <div class="modal-body">
            <div class="form-group row">
              <div class="col-md-12">
                <label>Nome</label>
                <input class="form-control" type="text" name="name">
              </div>
              <div class="col-md-12">
                <label>Endereço</label>
                <input class="form-control" type="text" name="address">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label>Latitude</label>
                <input id="latMap" class="form-control" type="text" name="lat">
              </div>
              <div class="col-md-6">
                <label>Longitude</label>
                <input id="lngMap" class="form-control" type="text" name="lng">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label>Tipo</label>
                <input class="form-control" type="text" name="type">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit_place" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Place</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="crud.php?action=update&id=<?= $_GET['id'] ?>">
        <div class="modal-body">
        <?php $place = read($_GET['id']) ?>
            <div class="form-group row">
              <div class="col-md-12">
                <label>Nome</label>
                <input class="form-control" type="text" name="name" value="<?= $place['name'] ?>">
              </div>
              <div class="col-md-12">
                <label>Endereço</label>
                <input class="form-control" type="text" name="address" value="<?= $place['address'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label>Latitude</label>
                <input class="form-control" type="text" name="lat" value="<?= $place['lat'] ?>">
              </div>
              <div class="col-md-6">
                <label>Longitude</label>
                <input class="form-control" type="text" name="lng" value="<?= $place['lng'] ?>">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-6">
                <label>Tipo</label>
                <input class="form-control" type="text" name="type" value="<?= $place['type'] ?>">
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>