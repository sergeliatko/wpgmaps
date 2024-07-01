/* global wpgmaps */
(function (window, document) {
    // Declare global variables
    // define global map placeholder containers
    let mapPlaceholderContainers;
    // define global map loader
    let mapLoader = false;

    /**
     * Returns the map placeholder container element width.
     *
     * @function elementWidth
     * @param {HTMLElement} element - The map container element.
     * @returns {number}
     */
    let elementWidth = function (element) {
        return element.offsetWidth || element.clientWidth || 640;
    };

    /**
     * Returns the map placeholder container element height.
     *
     * @function elementHeight
     * @param {HTMLElement} element - The map container element.
     * @returns {number}
     */
    let elementHeight = function (element) {
        return element.offsetHeight || element.clientHeight || 480;
    };

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
    /**
     * Translates location to LatLng.
     *
     * @function translateLocation
     * @param {Object} location
     * @param {Object} google
     * @returns {google.maps.LatLng|{lt: number, lg: number}}
     */
    let translateLocation = function (location, google) {
        try {
            return new google.maps.LatLng(
                parseFloat(location.latitude),
                parseFloat(location.longitude)
            );
        } catch (e) {
            console.log('Error translating location to LatLng: ' + e.message + '. Using fallback.');
            return {
                lt: parseFloat(location.latitude),
                lg: parseFloat(location.longitude)
            };
        }
    };

    /**
     * Returns 5% of element horizontal size.
     *
     * @function getMapHorizontalPadding
     * @param {HTMLElement} element
     * @returns {number}
     */
    let getMapHorizontalPadding = function (element) {
        return Math.round(elementWidth(element) * 0.05);
    };

    /**
     * Returns 5% of element vertical size.
     *
     * @function getMapVerticalPadding
     * @param {HTMLElement} element
     * @returns {number}
     */
    let getMapVerticalPadding = function (element) {
        return Math.round(elementHeight(element) * 0.05);
    };

    /**
     * Loads map loader script if not already loaded.
     *
     * @function initMapLoader
     * @param {HTMLElement} element - The map placeholder element.
     */
    let initMapLoader = function (element) {
        if (!mapLoader) {
            let loader = document.createElement('script');
            loader.type = 'text/javascript';
            loader.async = true;
            // noinspection JSUnresolvedVariable
            loader.src = wpgmaps.loaderUrl;
            loader.onload = function () {
                mapLoader = true;
                window.setTimeout(function () {
                    loadDynamicMap(element);
                }, 250);
            };
            let location = document.getElementsByTagName('script')[0];
            location.parentNode.insertBefore(loader, location);
        }
    };

    /**
     * Loads static map as a background image for the element.
     *
     * @param {HTMLElement} element
     */
    let loadStaticMap = function (element) {
        let staticMap = element.querySelector('.wpgmap-static');
        if (null === staticMap) {
            console.log('no static map placeholder found in #' + element.id);
            return;
        }
        let width = elementWidth(element);
        let height = elementHeight(element);
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
    let initStaticMaps = function () {
        mapPlaceholderContainers = document.querySelectorAll('div.wpgmap');
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
            id: 'wpgmaps-dynamic-maps',
            libraries: ['maps', 'marker']
        });
        loader.load().then(
            function (google) {
                //create and insert map element
                let mapElement = document.createElement('div');
                let infoWindow = new google.maps.InfoWindow();
                mapElement.id = mapContainer.id + '-dynamic';
                mapElement.className = 'wpgmap-dynamic';
                mapPlaceholder.remove();
                mapContainer.appendChild(mapElement);
                //build the map
                // noinspection JSUnresolvedVariable,JSUnresolvedFunction,JSCheckFunctionSignatures
                let map = new google.maps.Map(mapElement, {
                    center: translateLocation(mapData.center, google),
                    mapId: mapElement.id
                });
                //create bounds for markers to display on the map
                // noinspection JSUnresolvedFunction,JSUnresolvedVariable
                let bounds = new google.maps.LatLngBounds();
                //load markers
                // noinspection JSUnresolvedVariable
                mapData.pins.forEach(function (markerData) {
                    let pin = new google.maps.marker.PinElement({
                        scale: 1.25
                    });
                    // noinspection JSUnresolvedFunction,JSUnresolvedVariable,JSCheckFunctionSignatures
                    let marker = new google.maps.marker.AdvancedMarkerElement({
                        position: translateLocation(markerData.marker.location, google),
                        map: map,
                        title: markerData.marker.title,
                        content: pin.element,
                        gmpClickable: true
                    });
                    //add marker to the bounds
                    bounds.extend(marker.position);
                    marker.addListener('click', function () {
                        infoWindow.close();
                        infoWindow.setContent(markerData.window);
                        infoWindow.open(marker.map, marker);
                    });
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
            }
        );
    };

    // start processing after window is loaded
    window.addEventListener('load', initStaticMaps);
})(window, document);
