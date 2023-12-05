var autoCompleteInput = document.getElementById('address');
var autocomplete = new google.maps.places.Autocomplete(autoCompleteInput);
autocomplete.addListener("place_changed", fillInAddress);

// Sets the value and triggers
function setAddressInputValue(input, value) {
    let event = new Event("focus");
    input.value = value;
    input.dispatchEvent(event);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    const place = autocomplete.getPlace();
    let address1 = "";

    let address1Input = document.querySelector("#address");
    let cityInput = document.querySelector("#city");
    let stateInput = document.querySelector("#state");
    let postcodeInput = document.querySelector("#zip");
    let countryInput = document.querySelector("#country");

    // Get each component of the address from the place details, and fill in the corresponding fields
    for (const component of place.address_components) {
        const componentType = component.types[0];

        switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                setAddressInputValue(postcodeInput, component.long_name);
                break;
            }

            case "locality":
                setAddressInputValue(cityInput, component.long_name);
                break;

            case "administrative_area_level_1": {
                setAddressInputValue(stateInput, component.short_name);
                break;
            }

            case "country":
                setAddressInputValue(countryInput, component.short_name);
                break;
        }

        // Fill in the built-out values too
        address1Input.value = address1; // Don't need to trigger focus on this one
    }
}