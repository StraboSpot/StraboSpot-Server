<?php
/**
 * File: globe/index.php
 * Description: StraboSpot 3D Globe Browser - Interactive globe interface for exploring public datasets
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../includes/mheader.php");
?>

<!-- Main -->
<div id="main" class="wrapper style1">
    <div class="container">
        <header class="major">
            <h2>StraboSpot Globe Browser</h2>
            <p>Explore public StraboField datasets on an interactive 3D globe</p>
        </header>

        <!-- Globe Container -->
        <section id="globe-section">
            <div id="globe-controls" style="margin-bottom: 15px;display: none;">
                <button id="reset-view" class="button small">Reset View</button>
                <span id="loading-status" style="margin-left: 15px; font-style: italic;"></span>
            </div>
            <div id="cesiumContainer" style="width: 100%; height: 700px; position: relative;"></div>
        </section>

        <!-- Dataset Modal -->
        <div id="dataset-modal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <div id="modal-body">
                    <h3 id="modal-title"></h3>
                    <div id="modal-details"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Load CesiumJS -->
<script src="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Cesium.js"></script>
<link href="https://cesium.com/downloads/cesiumjs/releases/1.111/Build/Cesium/Widgets/widgets.css" rel="stylesheet">

<!-- Load custom styles and scripts -->
<link rel="stylesheet" href="assets/css/globe.css">
<script src="assets/js/globe.js"></script>
<script src="assets/js/modal.js"></script>

<?php
include("../includes/mfooter.php");
?>
