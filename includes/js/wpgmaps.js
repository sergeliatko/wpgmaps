/* global wpgmaps, google */
document.addEventListener('DOMContentLoaded', function () {
    let infoWindow;
    console.log(wpgmaps);
    //detect high density screens
    let isHighDensity = function () {
        let minResolutionQuery = 'only screen and (min-resolution: 124dpi),'
            + ' only screen and (min-resolution: 1.3dppx),'
            + ' only screen and (min-resolution: 48.8dpcm)';
        let devicePixelRatioQuery = 'only screen and (-webkit-min-device-pixel-ratio: 1.3),'
            + ' only screen and (-o-min-device-pixel-ratio: 1.3),'
            + ' only screen and (min--moz-device-pixel-ratio: 1.3),'
            + ' only screen and (min-device-pixel-ratio: 1.3)';
        return (
            (window.devicePixelRatio && window.devicePixelRatio > 1.3)
            || (
                window.matchMedia
                && (
                    window.matchMedia(minResolutionQuery).matches
                    || window.matchMedia(devicePixelRatioQuery).matches
                )
            )
        );
    };

    //get map image scale
    let getScale = function () {
        return isHighDensity() ? '2' : '1';
    };

    //load static map
    let loadStaticMap = function (map) {
        let staticMap = map.querySelector('.wpgmap-static');
        let scale = getScale();
        let url = staticMap.getAttribute('data-url')
            + `&scale=${scale}&size=${staticMap.clientWidth}x${staticMap.clientHeight}`;
        staticMap.style.backgroundImage = `url(${url})`;
        staticMap.addEventListener('click', loadDynamicMap);
    };

    // noinspection JSValidateJSDoc
    /**
     * Translates location to LatLng
     * @param location
     * @returns {google.maps.LatLng}
     */
    let translateLocation = function (location) {
        // noinspection JSUnresolvedVariable,JSUnresolvedFunction
        return new google.maps.LatLng(
            parseFloat(location.latitude),
            parseFloat(location.longitude)
        );
    };

    /**
     * Returns 5% of map horizontal size
     * @param mapElement
     * @returns {number}
     */
    let getMapHorizontalPadding = function (mapElement) {
        return Math.round(mapElement.clientWidth * 0.05);
    }

    /**
     * Returns 5% of map vertical size
     * @param mapElement
     * @returns {number}
     */
    let getMapVerticalPadding = function (mapElement) {
        return Math.round(mapElement.clientHeight * 0.05);
    }

    let loadDynamicMap = function () {
        let mapContainer = this.parentNode;
        let mapKey = mapContainer.getAttribute('data-key');
        // noinspection JSUnresolvedVariable
        let mapData = wpgmaps.maps[mapKey];
        // noinspection JSUnresolvedVariable,JSUnresolvedFunction
        let loader = new google.maps.plugins.loader.Loader({
            apiKey: mapData.key,
            version: 'weekly',
            id: 'wpgmaps-dynamic-maps'
        });
        this.remove();
        loader.load().then(() => {
            //instantiate infowindow
            if (undefined === infoWindow) {
                // noinspection JSUnresolvedVariable,JSUnresolvedFunction
                infoWindow = new google.maps.InfoWindow();
            }
            //create and insert map element
            let mapElement = document.createElement('div');
            mapElement.id = mapContainer.id + '-dynamic';
            mapElement.className = 'wpgmap-dynamic';
            mapContainer.appendChild(mapElement);
            //build the map
            // noinspection JSUnresolvedVariable,JSUnresolvedFunction
            let map = new google.maps.Map(mapElement, {});
            //create bounds for markers to display on the map
            // noinspection JSUnresolvedFunction,JSUnresolvedVariable
            let bounds = new google.maps.LatLngBounds();
            //load markers
            // noinspection JSUnresolvedVariable
            mapData.pins.forEach(function (markerData) {
                // noinspection JSUnresolvedFunction,JSUnresolvedVariable
                let marker = new google.maps.Marker({
                    position: translateLocation(markerData.marker.location),
                    map: map
                });
                //add infowindow data directly to the marker
                marker.infowindow = markerData.window;
                //open infowindow on marker click
                // noinspection JSUnresolvedVariable,JSCheckFunctionSignatures,JSDeprecatedSymbols
                google.maps.event.addListener(marker, 'click', (function (marker) {
                    return function () {
                        // noinspection JSUnresolvedFunction
                        infoWindow.setContent(marker.infowindow);
                        infoWindow.open(marker.map, marker);
                    }
                })(marker));
                //add marker to the bounds
                bounds.extend(marker.position);
            });
            // noinspection JSUnresolvedFunction,JSUnresolvedVariable
            map.fitBounds(bounds, {
                bottom: getMapVerticalPadding(mapElement),
                left: getMapHorizontalPadding(mapElement),
                right: getMapHorizontalPadding(mapElement),
                top: getMapVerticalPadding(mapElement)
            });
            // noinspection JSUnresolvedFunction
            map.setCenter(bounds.getCenter());
            //if map has zoom set it now
            // noinspection JSDeprecatedSymbols
            if ('' !== mapData.zoom) {
                // noinspection JSUnresolvedVariable,JSCheckFunctionSignatures,JSDeprecatedSymbols
                let zoomHandler = google.maps.event.addListener(map, 'idle', function () {
                    // noinspection JSUnresolvedFunction,JSDeprecatedSymbols
                    map.setZoom(parseInt(mapData.zoom));
                    // noinspection JSUnresolvedVariable,JSDeprecatedSymbols
                    google.maps.event.removeListener(zoomHandler);
                });
            }
        });
    };

    //load maps on the page
    let loadMaps = function () {
        let maps = document.querySelectorAll('div.wpgmap');
        maps.forEach(loadStaticMap);
    };

    //start loading maps after all content is loaded
    loadMaps();
});
