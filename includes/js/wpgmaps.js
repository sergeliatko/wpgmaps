/* global wpgmaps, google */
(function (window, document) {
    // Declare global variables
    let mapPlaceholderContainers;

    /**
     * Returns the map placeholder container element width.
     *
     * @function mapPlaceholderWidth
     * @param {Element} mapPlaceholder - The map container element.
     * @returns {number}
     */
    let mapPlaceholderWidth = function (mapPlaceholder) {
        let width = mapPlaceholder.getAttribute('data-width');
        if (width === null || width === '') {
            width = mapPlaceholder.clientWidth || 640;
        }
        return parseInt(width);
    };

    /**
     * Returns the map placeholder container element height.
     *
     * @function mapPlaceholderHeight
     * @param {Element} mapPlaceholder - The map container element.
     * @returns {number}
     */
    let mapPlaceholderHeight = function (mapPlaceholder) {
        let height = mapPlaceholder.getAttribute('data-height');
        if (height === null || height === '') {
            height = mapPlaceholder.clientHeight || 480;
        }
        return parseInt(height);
    };

    // set maps sizes on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        mapPlaceholderContainers = document.querySelectorAll('div.wpgmap');
        if (mapPlaceholderContainers.length > 0) {
            for (let i = 0; i < mapPlaceholderContainers.length; i++) {
                let width = mapPlaceholderWidth(mapPlaceholderContainers[i]);
                let height = mapPlaceholderHeight(mapPlaceholderContainers[i]);
                mapPlaceholderContainers[i].setAttribute('data-width', width.toString());
                mapPlaceholderContainers[i].setAttribute('data-height', height.toString());
            }
        }
    });
    // start processing after window is loaded
    window.addEventListener('load', function () {
        // define global infowindow
        let infoWindow;

        // define global map loader
        let mapLoader = false;

        /**
         * Detects high density screen.
         *
         * @function isHighDensity
         * @returns {boolean}
         */
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

        /**
         * Get map image scale parameter for high density screens.
         *
         * @function getScale
         * @returns {string}
         */
        let getScale = function () {
            return isHighDensity() ? '2' : '1';
        };

        // noinspection JSValidateJSDoc
        /**
         * Translates location to LatLng.
         *
         * @function translateLocation
         * @param {Object} location
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
         * Returns 5% of element horizontal size.
         *
         * @function getMapHorizontalPadding
         * @param {Element} mapElement
         * @returns {number}
         */
        let getMapHorizontalPadding = function (mapElement) {
            return Math.round(mapElement.clientWidth * 0.05);
        };

        /**
         * Returns 5% of element vertical size.
         *
         * @function getMapVerticalPadding
         * @param {Element} mapElement
         * @returns {number}
         */
        let getMapVerticalPadding = function (mapElement) {
            return Math.round(mapElement.clientHeight * 0.05);
        };

        /**
         * Loads map loader script if not already loaded.
         *
         * @function initMapLoader
         * @param {Element} mapPlaceholder - The map placeholder element.
         */
        let initMapLoader = function (mapPlaceholder) {
            if (!mapLoader) {
                let loader = document.createElement('script');
                loader.type = 'text/javascript';
                loader.async = true;
                // noinspection JSUnresolvedVariable
                loader.src = wpgmaps.loaderUrl;
                loader.onload = function () {
                    mapLoader = true;
                    window.setTimeout(function () {
                        loadDynamicMap(mapPlaceholder);
                    }, 250);
                };
                let location = document.getElementsByTagName('script')[0];
                location.parentNode.insertBefore(loader, location);
            }
        };

        /**
         * Loads dynamic map.
         *
         * @function loadDynamicMap
         * @param {Element} mapPlaceholder - The map placeholder element.
         */
        let loadDynamicMap = function (mapPlaceholder) {
            if (!mapLoader) {
                initMapLoader(mapPlaceholder);
                return;
            }
            let mapContainer = mapPlaceholder.parentNode;
            let mapKey = mapContainer.getAttribute('data-key');
            // noinspection JSUnresolvedVariable
            let mapData = wpgmaps.maps[mapKey];
            // noinspection JSUnresolvedVariable,JSUnresolvedFunction
            let loader = new google.maps.plugins.loader.Loader({
                apiKey: mapData.key,
                version: 'weekly',
                id: 'wpgmaps-dynamic-maps'
            });
            mapPlaceholder.remove();
            loader.load().then(function () {
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
                        title: markerData.marker.title,
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
                //if map has a zoom parameter set it now
                // noinspection JSUnresolvedVariable
                if ('' !== mapData.zoom) {
                    // noinspection JSUnresolvedVariable,JSCheckFunctionSignatures,JSDeprecatedSymbols,JSVoidFunctionReturnValueUsed
                    let zoomHandler = google.maps.event.addListener(map, 'idle', function () {
                        // noinspection JSUnresolvedFunction,JSUnresolvedVariable
                        map.setZoom(parseInt(mapData.zoom));
                        // noinspection JSUnresolvedVariable,JSDeprecatedSymbols
                        google.maps.event.removeListener(zoomHandler);
                    });
                }
            });
        };

        /**
         * Loads static map as a background image for the element.
         *
         * @param {Element} mapPlaceholderContainer
         */
        let loadStaticMap = function (mapPlaceholderContainer) {
            let staticMap = mapPlaceholderContainer.querySelector('.wpgmap-static');
            if (null === staticMap) {
                console.log('no static map placeholder found in #' + mapPlaceholderContainer.id);
                return;
            }
            let width = mapPlaceholderWidth(mapPlaceholderContainer);
            let height = mapPlaceholderHeight(mapPlaceholderContainer);
            let scale = getScale();
            let url = staticMap.getAttribute('data-url') + '&scale=' + scale + '&size=' + width.toString() + 'x' + height.toString();
            staticMap.style.backgroundImage = 'url(' + url + ')';
            staticMap.addEventListener('click', function (event) {
                event.preventDefault();
                loadDynamicMap(this);
            });
        };

        /**
         * Initiates static maps.
         *
         * @function initStaticMaps
         */
        let InitStaticMaps = function () {
            if (mapPlaceholderContainers.length > 0) {
                for (let i = 0; i < mapPlaceholderContainers.length; i++) {
                    let eventName = mapPlaceholderContainers[i].getAttribute('data-event');
                    if (eventName === '' || eventName === null) {
                        loadStaticMap(mapPlaceholderContainers[i]);
                    } else {
                        let mapPlaceholderContainer = mapPlaceholderContainers[i];
                        window.addEventListener(eventName, function () {
                            loadStaticMap(mapPlaceholderContainer);
                        });
                    }
                }
            }
        };

        window.setTimeout(InitStaticMaps, 1000);

    });
})(window, document);
