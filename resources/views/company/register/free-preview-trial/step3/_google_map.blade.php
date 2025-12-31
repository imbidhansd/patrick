<div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="miles_selection">Please select a radius : *</label>
                <select name="mile_range" id="mile_range" class="form-control">
                    <option value="">Select Zip radius</option>
                    <option value="5">5 Miles</option>
                    <option value="10">10 Miles</option>
                    <option value="15">15 Miles</option>
                    <option value="20">20 Miles</option>
                    <option value="25">25 Miles</option>
                    <option value="30">30 Miles</option>
                    <option value="35">35 Miles</option>
                    <option value="40">40 Miles</option>
                    <option value="45">45 Miles</option>
                    <option value="50">50 Miles</option>
                </select>
            </div>
        </div>

        <div class="col-md-9 text-info">
            <p>All zip codes within a <span class="selected_miles">50</span> mile radius of have been
                selected and will be displayed on your Company Profile Page.</p>
            <p>Editing zip code radius and adding or removing individual zip codes can be done from your
                company dashboard once registration is complete.</p>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>


    <div class="googlemapborder">
        <div id="map-canvas" style="height:300px;"></div>
    </div>
