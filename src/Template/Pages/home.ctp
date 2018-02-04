<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 250px;
        width: 400px;
    }    
</style>

<h1>Let's to Knowledge World</h1>

<div class="col-lg-6">       
    <div id='map'></div>
</div>
<div class="col-lg-12" id="error"></div>       

<?php echo $this->Html->script('location') ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDh7rAgn-5Eqj4VU2MZ2DxU_uWuJmhyHoY&libraries=places&callback=getLocation" async defer></script>
