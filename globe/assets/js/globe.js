/**
 * File: globe/assets/js/globe.js
 * Description: Main globe application logic for StraboSpot 3D Globe Browser
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        dataEndpoint: '/search/newsearchdatasets.json',
        markerColor: Cesium.Color.fromCssColorString('#dc3545'),
        markerHoverColor: Cesium.Color.fromCssColorString('#c82333'),
        markerSize: 8,
        defaultCameraAltitude: 20000000
    };

    // Global variables
    let viewer;
    let datasets = [];
    let markers = [];

    /**
     * Initialize the Cesium viewer
     */
    function initializeGlobe() {
        try {
            // Set Cesium Ion access token
            // TODO: Replace with your own token from https://cesium.com/ion/signup
            // For now, using basic rendering without terrain services
            // Cesium.Ion.defaultAccessToken = 'YOUR_TOKEN_HERE';

            // Create the viewer (using basic imagery, no token required)
            viewer = new Cesium.Viewer('cesiumContainer', {
                baseLayer: Cesium.ImageryLayer.fromProviderAsync(
                    Cesium.TileMapServiceImageryProvider.fromUrl(
                        Cesium.buildModuleUrl('Assets/Textures/NaturalEarthII')
                    )
                ),
                baseLayerPicker: false,
                geocoder: false,
                homeButton: true,
                sceneModePicker: false,
                navigationHelpButton: false,
                animation: false,
                timeline: false,
                fullscreenButton: true,
                vrButton: false,
                infoBox: false,
                selectionIndicator: false
            });

            // Set initial camera position (centered on United States)
            viewer.camera.flyTo({
                destination: Cesium.Cartesian3.fromDegrees(-95.7129, 37.0902, CONFIG.defaultCameraAltitude),
                duration: 0
            });

            // Add click handler for markers
            setupClickHandler();

            console.log('Globe initialized successfully');
            return true;
        } catch (error) {
            console.error('Error initializing globe:', error);
            showError('Failed to initialize 3D globe. Please check your browser compatibility.');
            return false;
        }
    }

    /**
     * Fetch dataset data from the API
     */
    async function fetchDatasets() {
        const loadingStatus = document.getElementById('loading-status');
        loadingStatus.textContent = 'Loading datasets...';
        loadingStatus.classList.add('loading');

        try {
            const response = await fetch(CONFIG.dataEndpoint);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Handle GeoJSON format
            if (data.type === 'FeatureCollection' && data.features) {
                datasets = data.features;
                console.log(`Loaded ${datasets.length} datasets (GeoJSON format)`);
            } else if (Array.isArray(data)) {
                datasets = data;
                console.log(`Loaded ${datasets.length} datasets`);
            } else {
                throw new Error('Unexpected data format');
            }

            loadingStatus.textContent = `Loaded ${datasets.length} datasets`;
            loadingStatus.classList.remove('loading');

            return datasets;
        } catch (error) {
            console.error('Error fetching datasets:', error);
            loadingStatus.textContent = 'Error loading datasets';
            loadingStatus.classList.remove('loading');
            showError('Failed to load dataset information. Please try again later.');
            return [];
        }
    }

    /**
     * Create markers on the globe for each dataset
     */
    function createMarkers(datasets) {
        if (!viewer) {
            console.error('Viewer not initialized');
            return;
        }

        let validCount = 0;
        let invalidCount = 0;

        datasets.forEach((dataset, index) => {
            let lat, lon, properties, datasetId;

            // Handle GeoJSON format
            if (dataset.type === 'Feature' && dataset.geometry) {
                if (dataset.geometry.type === 'Point' && dataset.geometry.coordinates) {
                    lon = parseFloat(dataset.geometry.coordinates[0]);
                    lat = parseFloat(dataset.geometry.coordinates[1]);
                    properties = dataset.properties || {};
                    datasetId = properties.id || index;
                } else {
                    invalidCount++;
                    return;
                }
            } else {
                // Handle simple JSON format with latitude/longitude properties
                lat = parseFloat(dataset.latitude);
                lon = parseFloat(dataset.longitude);
                properties = dataset;
                datasetId = dataset.id || index;
            }

            // Validate coordinates
            if (isNaN(lat) || isNaN(lon) || lat < -90 || lat > 90 || lon < -180 || lon > 180) {
                invalidCount++;
                return;
            }

            try {
                // Create marker entity
                const marker = viewer.entities.add({
                    id: `dataset-${datasetId}`,
                    position: Cesium.Cartesian3.fromDegrees(lon, lat, 0),
                    point: {
                        pixelSize: CONFIG.markerSize,
                        color: CONFIG.markerColor,
                        outlineColor: Cesium.Color.WHITE,
                        outlineWidth: 2,
                        heightReference: Cesium.HeightReference.CLAMP_TO_GROUND
                    },
                    properties: properties
                });

                markers.push(marker);
                validCount++;
            } catch (error) {
                console.error('Error creating marker for dataset:', dataset, error);
                invalidCount++;
            }
        });

        console.log(`Created ${validCount} markers, skipped ${invalidCount} invalid locations`);

        const loadingStatus = document.getElementById('loading-status');
        loadingStatus.textContent = `Displaying ${validCount} datasets`;
    }

    /**
     * Setup click handler for marker selection
     */
    function setupClickHandler() {
        const handler = new Cesium.ScreenSpaceEventHandler(viewer.scene.canvas);

        handler.setInputAction(function(click) {
            const pickedObject = viewer.scene.pick(click.position);

            if (Cesium.defined(pickedObject) && pickedObject.id && pickedObject.id.properties) {
                const dataset = pickedObject.id.properties;
                showDatasetModal(dataset);
            }
        }, Cesium.ScreenSpaceEventType.LEFT_CLICK);

        // Add hover effect
        handler.setInputAction(function(movement) {
            const pickedObject = viewer.scene.pick(movement.endPosition);

            if (Cesium.defined(pickedObject) && pickedObject.id && pickedObject.id.point) {
                document.body.style.cursor = 'pointer';
                pickedObject.id.point.pixelSize = CONFIG.markerSize * 1.5;
                pickedObject.id.point.color = CONFIG.markerHoverColor;
            } else {
                document.body.style.cursor = 'default';
                // Reset all markers to default size and color
                markers.forEach(marker => {
                    if (marker.point) {
                        marker.point.pixelSize = CONFIG.markerSize;
                        marker.point.color = CONFIG.markerColor;
                    }
                });
            }
        }, Cesium.ScreenSpaceEventType.MOUSE_MOVE);
    }

    /**
     * Show dataset details in modal
     */
    function showDatasetModal(datasetProperties) {
        // Extract properties (Cesium wraps them in a PropertyBag)
        const dataset = {};
        const propertyNames = datasetProperties.propertyNames;

        propertyNames.forEach(name => {
            dataset[name] = datasetProperties[name]._value;
        });

        // Use the modal.js functionality
        if (window.DatasetModal) {
            window.DatasetModal.show(dataset);
        }
    }

    /**
     * Reset camera to default view
     */
    function resetView() {
        if (viewer) {
            viewer.camera.flyTo({
                destination: Cesium.Cartesian3.fromDegrees(-95.7129, 37.0902, CONFIG.defaultCameraAltitude),
                duration: 2
            });
        }
    }

    /**
     * Display error message to user
     */
    function showError(message) {
        const loadingStatus = document.getElementById('loading-status');
        loadingStatus.textContent = message;
        loadingStatus.style.color = '#dc3545';
    }

    /**
     * Initialize the application
     */
    async function init() {
        console.log('Initializing StraboSpot Globe Browser...');

        // Initialize globe
        const globeReady = initializeGlobe();
        if (!globeReady) {
            return;
        }

        // Fetch and display datasets
        const data = await fetchDatasets();
        if (data.length > 0) {
            createMarkers(data);
        }

        // Setup reset button
        const resetButton = document.getElementById('reset-view');
        if (resetButton) {
            resetButton.addEventListener('click', resetView);
        }

        console.log('Globe Browser initialized successfully');
    }

    // Start the application when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
