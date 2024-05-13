$(document).ready(function() {
    var geocoder = new google.maps.Geocoder();
    var map = null;
    var marker = null;

    function initialize() {
        var latitude = parseFloat($("#latitude").val()) || 0;
        var longitude = parseFloat($("#longitude").val()) || 0;
        var zoom = 16;
        var LatLng = new google.maps.LatLng(latitude, longitude);

        var mapOptions = {
            zoom: zoom,
            center: LatLng,
            panControl: false,
            zoomControl: false,
            scaleControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        map = new google.maps.Map(document.getElementById('map'), mapOptions);
        marker = new google.maps.Marker({
            position: LatLng,
            map: map,
            title: 'Drag Me!',
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({ 'latLng': marker.getPosition() }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK && results[0]) {
                    var formattedAddress = results[0].formatted_address;
                    var $focusedInput = $('input:focus');

                    if ($focusedInput.length > 0) {
                        $focusedInput.val(formattedAddress);
                    } else {
                        alert("Please focus on an input field within the form repeater.");
                    }
                }
            });
        });
    }

    initialize(); // Initialize map and marker on page load

    // Add new row on "+" button click
    $(document).on('click', '.repeater-add', function() {
        var $template = $('#repeaterContainer .repeater-row').first().clone();
        $template.find('input').val(''); // Clear input value of cloned row
        $('#repeaterContainer').append($template);
        $template.find('input').focus(); // Set focus to the newly added input field
    });

    // Remove row on "-" button click
    $(document).on('click', '.repeater-remove', function() {
        if ($('#repeaterContainer .repeater-row').length > 1) {
            $(this).closest('.repeater-row').remove();
        } else {
            alert("At least one input is required.");
        }
    });

    // Prevent form submission for demo purposes
    $('#repeaterForm').submit(function(event) {
        event.preventDefault();
        var values = $(this).serializeArray();
        console.log(values); // Display form values in console (for demo)
        // You can process form submission here
    });

    // Click event for finding address based on input
    $('#findbutton').click(function(e) {
        var address = $("#Postcode").val();
        geocoder.geocode({ 'address': address }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK && results[0]) {
                map.setCenter(results[0].geometry.location);
                marker.setPosition(results[0].geometry.location);
                $("#latitude").val(marker.getPosition().lat());
                $("#longitude").val(marker.getPosition().lng());
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
        e.preventDefault();
    });

    // Handle focusing on input fields within the form repeater
    $(document).on('focus', '.repeater-row input', function() {
        // Update map marker dragend listener to set value in focused input
        google.maps.event.clearListeners(marker, 'dragend');
        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({ 'latLng': marker.getPosition() }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK && results[0]) {
                    $(this).val(results[0].formatted_address);
                }
            }.bind(this));
        }.bind(this));
    });
});
