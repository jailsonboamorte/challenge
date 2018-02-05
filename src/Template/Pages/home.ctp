<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 250px;
        width: 400px;
    }    
</style>

<div class="row">
    <div class="col-lg-12">    
        <h1>Let's to Knowledge World</h1>
    </div>       
</div>       

<div class="row">
    <div class="col-lg-4 ">    
        <div id='map'></div>
    </div>
    <div class="col-lg-offset-1 col-lg-2">        
        <span id='descplace' class="btn btn-default">Select a place in map</span>    
    </div>
</div>
<div class="row">
    <div class="col-lg-4 ">    
        <ol class="breadcrumb" id='listprice'></ol>
    </div>
    <div class="col-lg-4">
        <span id='ok' class="btn btn-success hidden"></span>            
    </div>    
</div>
<div class="row">
    <div class="col-lg-4">    
        <div class="progress hidden" id="indicator">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">                
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12" id="error"></div>       

<?php echo $this->Html->script('app') ?>
<?php echo $this->Html->script('location') ?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDh7rAgn-5Eqj4VU2MZ2DxU_uWuJmhyHoY&libraries=places&callback=getLocation" async defer></script>
