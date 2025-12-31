$(document).ready(function () {
    getGoogleMaps(1);

    // ----------------- Get Zipcode on select Mile --------------
    $('body').on('change', '#selectMiles', function () {
        if (window.location.pathname == '/referral-list/free-preview-trial' || window.location.pathname == '/referral-list/accredited-listing' || window.location.pathname == '/referral-list/accredited-listing') {
            zipCodePaidMemberAlert($(this).val());
        }
        getMapAndRegions();
    });

    // ----------------- Get Zipcode on select Mile --------------
    $('body').on('blur', '#zipCodeVal', function () {
        if ($(this).prop('readonly') == false)
            getMapAndRegions('no');

        if ($(this).val().length >= 5 && $('#selectMiles').val() == '') {
            $('.zipcode_miles_alert').show();
        } else {
            $('.zipcode_miles_alert').hide();
        }
    });

    $('body').on('click', '#show_company_zipcode', function () {
        setTimeout(function () { getGoogleMaps($('#selectMiles').val()); }, 500);
    });

    $('body').on('ifClicked', '.checkbox_readonly', function () {
        var element = $(this);
        $('.swal2-close').text('× Close');
        swal({
            title: '',
            type: 'warning',
            html: '<span class="text-blue" style="font-size:18px; font-weight:600;">As an approved member, zip codes can be edited individually within your chosen zip code radius for more targeted leads.<br><br>Ready To Upgrade To A Full Member?</span>',
            confirmButtonText: "Yes! Let's Go!",
            confirmButtonColor: "#1ab394",
            showCloseButton: true,
            animation: false,
        }).then(function () {
            window.location.href = '/referral-list/upgrade';
        });

        setTimeout(function () {
            element.prop('checked', 'checked');
            element.parent().addClass('checked');
        }, 20);
    });
});

$('body').on('change', '#selectMiles', function () {
    if ($('#selectMiles').val() != '') {
        $('.zip_code_alert_div').slideDown('slow');
        $('.zip_code_target_alert_trial').slideDown('slow');
        $('.zip_code_required_alert').hide('slow');
    }
});


// ----------------- Google Map ------------------Ma
function getGoogleMaps(getMiles) {
    var zip = $('#zipcode').val();
    var getMiles = $('#mile_range').val();

    var map;
    var address = zip;
    var geocoder = new google.maps.Geocoder();
    if (address != '') {
        address = address;
    } else {
        address = '80301';
    }

    var zoomLevel = (zip == '') ? 3 : 7;

    var lat = '';
    var lng = '';

    geocoder.geocode({ 'address': address }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            lat = results[0].geometry.location.lat();
            lng = results[0].geometry.location.lng();
            formatted_add = results[0].formatted_address;
        }

        var myCenter = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            position: myCenter
        });

        function initialize() {
            var mapProp = {
                center: myCenter,
                zoom: zoomLevel,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            var milesval = 1609.34 * getMiles;
            map = new google.maps.Map(document.getElementById("map-canvas"), mapProp);
            marker.setMap(map);

            // Add circle overlay and bind to marker
            var circle = new google.maps.Circle({
                map: map,
                radius: milesval,  // metres
                fillColor: '#97BEFD',
                fillOpacity: 0.35,
                strokeOpacity: 0.8,
                strokeWeight: 1,
                strokeColor: '#56AA3F',
            });

            circle.bindTo('center', marker, 'position');
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.setContent(contentString);
                infowindow.open(map, marker);
            });
        }
        initialize();
    });
}


function getMapAndRegions(zipAlert = '') {
    if (window.location.pathname == '/referral-list/upgrade-selections' && zipAlert == '')
        zipCodeAlert();

    var getZip = $('#zipCodeVal').val().trim();
    var getMiles = $('#selectMiles').val();
    var uri = window.location.pathname;
    $('.zip-message-popup').show();
    $('.zip-popup-padding').css('padding', '20px 0px 40px');
    getGoogleMaps(getMiles);
    var APIkey = "AIzaSyDvAa4OMquoJyYjp4WkZnqVOtGtFph6cKA";

    if (getZip == '') {
        $('#selectMiles').val('');
        zipCodeAlert();
        return false;
    }
    if (getMiles == 4000) {
        $('#zipValues').hide();
    } else {
        $('#zipValues').show();
    }

    if (getMiles != 0 && getMiles != '4000') {
        $.ajax({
            url: '/getZipContent?zip=' + getZip + '&miles=' + getMiles + '&uri=' + uri,
            beforeSend: function () {
                $(".loading-image").text('Wait Loading now.......');
            },
            success: function (data) {
                if (data == 'fail') {
                    $(".loading-image").html('<div class="alert alert-danger">Not a valid Zipcode!</div>');
                    $('#zipValues').html('');
                } else {
                    $('#zipValues').html(data);
                    if (getMiles == '4000') {
                        $('#zipValues').hide();
                        $(".loading-image").text('Please submit to save.');
                    } else {
                        $(".loading-image").text('');
                        $('#zipValues').show();
                    }
                }
                setTimeout(function () {
                    $('.i-checks').iCheck({
                        checkboxClass: 'icheckbox_square-blue',
                        radioClass: 'iradio_square-blue',
                    });
                }, 100);
            }
        });
    }
}

function zipCodePaidMemberAlert(value) {
    if (value != '') {
        $('#selectMiles').parent().next().hide();
        $('#selectMiles').removeClass('error').addClass('valid');
        $('.zip_code_alert_div').slideDown('slow');
    }
    showZipCodeRequiredAlert();
}

function zipCodeAlert() {
    showZipCodeRequiredAlert();
}

function showZipCodeRequiredAlert() {
    if ($('#zipCodeVal').val() == '' || $('#selectMiles').val() == '') {
        $('.zip_code_required_alert').slideDown('slow');
        $('.zip_code_target_alert').slideUp('slow');
        setTimeout(function () { $('.zipcode_miles_alert').show(); }, 400);
    } else {
        $('.zipcode_miles_alert').hide();
        $('.zip_code_target_alert').slideDown('slow');
        $('.zip_code_required_alert').slideUp('slow');
    }
}
