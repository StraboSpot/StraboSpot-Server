/**
 * File: globe/assets/js/modal.js
 * Description: Modal dialog handling for dataset details
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 */

(function() {
    'use strict';

    // Create DatasetModal namespace
    window.DatasetModal = {
        /**
         * Show the modal with dataset information and feature summary
         */
        show: function(dataset) {
            const modal = document.getElementById('dataset-modal');
            const modalTitle = document.getElementById('modal-title');
            const modalDetails = document.getElementById('modal-details');

            if (!modal || !modalTitle || !modalDetails) {
                console.error('Modal elements not found');
                return;
            }

            // Set title with explore link
            const datasetName = dataset.name || 'Dataset Details';
            const datasetId = dataset.id || '';
            const splitDatasetId = datasetId.includes('-') ? datasetId.split('-')[1] : datasetId;

            modalTitle.innerHTML = `${this.escapeHtml(datasetName)} <a href="/search/?datasetid=${this.escapeHtml(splitDatasetId)}" target="_blank" style="font-size: 0.7em; color: #d32f2f; text-decoration: none;">(Explore Further)</a>`;

            // Show loading state
            modalDetails.innerHTML = '<div style="text-align: center; padding: 20px;">Loading features...</div>';

            // Scroll everything to top before showing modal
            if (modalDetails) {
                modalDetails.scrollTop = 0;
            }
            const modalBody = document.getElementById('modal-body');
            if (modalBody) {
                modalBody.scrollTop = 0;
            }
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.scrollTop = 0;
            }

            // Show modal
            modal.style.display = 'flex';

            // Fetch features for this dataset
            this.fetchAndDisplayFeatures(dataset.id);
        },

        /**
         * Fetch features from interfacesearch.php and display summary
         */
        fetchAndDisplayFeatures: async function(datasetId) {
            const modalDetails = document.getElementById('modal-details');

            try {
                const response = await fetch(`/search/interfacesearch.php?dsids=${datasetId}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Build and display feature summary
                const summaryHTML = this.buildFeatureSummaryHTML(data);
                modalDetails.innerHTML = summaryHTML;

                // Force scroll to top after content loads
                setTimeout(() => {
                    const featureList = modalDetails.querySelector('div[style*="max-height"]');
                    if (featureList) {
                        featureList.scrollTop = 0;
                    }
                    if (modalDetails) {
                        modalDetails.scrollTop = 0;
                    }
                }, 0);

            } catch (error) {
                console.error('Error fetching features:', error);
                modalDetails.innerHTML = '<div style="color: #dc3545; text-align: center; padding: 20px;">Error loading features. Please try again.</div>';
            }
        },

        /**
         * Build HTML for feature summary - list individual features with their properties
         */
        buildFeatureSummaryHTML: function(data) {
            if (!data || !data.features || data.features.length === 0) {
                return '<p>No features found for this dataset.</p>';
            }

            const features = data.features;
            const featureCount = features.length;

            let html = '<div class="feature-summary">';

            // Total features count header
            html += `
                <div class="detail-row" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 0px solid #333;">
                    <span class="detail-label">Spot Count:</span>
                    <span class="detail-value"><strong style="color: #000;">${featureCount}</strong></span>
                </div>
            `;

            // List each feature
            html += '<div style="max-height: calc(70vh - 150px); overflow-y: auto;">';

            features.forEach((feature, index) => {
                html += this.buildFeatureHTML(feature, index + 1);
            });

            html += '</div></div>';

            return html;
        },

        /**
         * Build HTML for a single feature
         */
        buildFeatureHTML: function(feature, featureNumber) {
            let html = '<div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid #d32f2f;">';

            // Feature header
            const featureName = feature.properties?.name || `Feature ${featureNumber}`;
            const geometryType = feature.geometry?.type || 'Unknown';

            html += `<div style="font-weight: bold; font-size: 1.1em; margin-bottom: 10px; color: #333;">
                        ${this.escapeHtml(featureName)}
                        <span style="font-size: 0.85em; color: #666; font-weight: normal;"> (${geometryType})</span>
                     </div>`;

            // Feature properties
            if (feature.properties) {
                html += this.buildPropertiesHTML(feature.properties);
            } else {
                html += '<p style="color: #666; font-style: italic;">No properties available</p>';
            }

            html += '</div>';
            return html;
        },

        /**
         * Build HTML for feature properties
         */
        buildPropertiesHTML: function(properties) {
            // Define which properties to skip (internal/redundant ones)
            const skipProperties = [
                'datasetid',
                'owner',
                'time',
                'viewed_timestamp',
                'symbology',
                'id',
                'modified_timestamp',
                'self',
                'notes_timestamp'
            ];

            // Define priority properties to show first
            const priorityProps = ['name', 'date', 'rock_type', 'description', 'notes'];

            let html = '<div style="font-size: 0.95em;">';

            // Collect all properties
            const allProps = Object.keys(properties).filter(key => {
                const value = properties[key];
                return value !== null &&
                       value !== undefined &&
                       value !== '' &&
                       !skipProperties.includes(key);
            });

            // Show priority properties first
            const displayedProps = new Set();
            priorityProps.forEach(key => {
                if (allProps.includes(key)) {
                    html += this.buildPropertyRow(key, properties[key]);
                    displayedProps.add(key);
                }
            });

            // Show remaining properties
            allProps.forEach(key => {
                if (!displayedProps.has(key)) {
                    html += this.buildPropertyRow(key, properties[key]);
                }
            });

            html += '</div>';
            return html;
        },

        /**
         * Build HTML for a single property row
         */
        buildPropertyRow: function(key, value) {
            // Handle complex values (objects, arrays)
            if (typeof value === 'object' && value !== null) {
                return this.buildComplexPropertyRow(key, value);
            }

            // Simple value
            let displayValue = String(value);

            // Truncate very long values
            if (displayValue.length > 150) {
                displayValue = displayValue.substring(0, 150) + '...';
            }

            return `
                <div style="margin-bottom: 5px;">
                    <span style="color: #666; font-weight: 500;">${this.formatLabel(key)}:</span>
                    <span style="margin-left: 8px;">${this.escapeHtml(displayValue)}</span>
                </div>
            `;
        },

        /**
         * Build HTML for complex property (object or array)
         */
        buildComplexPropertyRow: function(key, value) {
            let html = `
                <div style="margin-bottom: 10px;">
                    <div style="color: #666; font-weight: 500; margin-bottom: 5px;">${this.formatLabel(key)}:</div>
            `;

            if (Array.isArray(value)) {
                // Handle arrays
                html += '<div style="margin-left: 20px; padding-left: 10px; border-left: 2px solid #ddd;">';

                if (value.length === 0) {
                    html += '<span style="color: #999; font-style: italic;">Empty</span>';
                } else {
                    // Check if this is an images array
                    const isImagesArray = key.toLowerCase() === 'images' || key.toLowerCase() === 'image';

                    value.forEach((item, index) => {
                        if (typeof item === 'object' && item !== null) {
                            // Object in array - generate a smart label
                            const itemLabel = this.generateItemLabel(key, item, index);
                            html += `<div style="margin-bottom: 8px; padding: 5px; background: #fff; border-radius: 3px;">`;
                            html += `<div style="font-size: 0.9em; color: #999; margin-bottom: 3px;">${this.escapeHtml(itemLabel)}:</div>`;
                            html += this.buildObjectSummary(item, isImagesArray);

                            // If this is an image and has an id, display the thumbnail
                            if (isImagesArray && item.id) {
                                html += `<div style="margin-top: 8px;">
                                            <img src="https://strabospot.org/pi_thumbnail/${this.escapeHtml(item.id)}"
                                                 alt="Image thumbnail"
                                                 style="max-width: 200px; border: 1px solid #ddd; border-radius: 3px; cursor: pointer;"
                                                 onerror="this.style.display='none'"
                                                 onclick="window.DatasetModal.showImageModal('${this.escapeHtml(item.id)}', '${this.escapeHtml(itemLabel)}')">
                                         </div>`;
                            }

                            html += '</div>';
                        } else {
                            // Simple value in array
                            html += `<div style="margin-bottom: 3px;">• ${this.escapeHtml(String(item))}</div>`;
                        }
                    });
                }

                html += '</div>';
            } else {
                // Handle objects
                html += '<div style="margin-left: 20px; padding-left: 10px; border-left: 2px solid #ddd;">';
                html += this.buildObjectSummary(value, false);
                html += '</div>';
            }

            html += '</div>';
            return html;
        },

        /**
         * Generate a smart label for array items based on context
         */
        generateItemLabel: function(arrayKey, item, index) {
            // Special case for orientation_data
            if (arrayKey === 'orientation_data') {
                return `Orientation ${index + 1}`;
            }

            // Try to use a title, name, or label property if available
            if (item.title && typeof item.title === 'string' && item.title.trim()) {
                return item.title;
            }
            if (item.name && typeof item.name === 'string' && item.name.trim()) {
                return item.name;
            }
            if (item.label && typeof item.label === 'string' && item.label.trim()) {
                return item.label;
            }

            // Generate a contextual label based on the array key name
            const singularLabel = this.getSingularForm(arrayKey);
            return `${singularLabel} ${index + 1}`;
        },

        /**
         * Get singular form of a plural word (or just clean up the key)
         */
        getSingularForm: function(key) {
            const normalized = this.formatLabel(key);

            // Common plural patterns
            if (normalized.endsWith('ies')) {
                return normalized.slice(0, -3) + 'y';
            }
            if (normalized.endsWith('ses')) {
                return normalized.slice(0, -2);
            }
            if (normalized.endsWith('xes')) {
                return normalized.slice(0, -2);
            }
            if (normalized.endsWith('ches') || normalized.endsWith('shes')) {
                return normalized.slice(0, -2);
            }
            if (normalized.endsWith('s') && !normalized.endsWith('ss')) {
                return normalized.slice(0, -1);
            }

            return normalized;
        },

        /**
         * Build HTML summary for an object
         */
        buildObjectSummary: function(obj, isImage = false) {
            // Define properties to skip in sub-items
            const skipSubProperties = [
                'id',
                'unix_timestamp',
                'modified_timestamp',
                'self'
            ];

            const keys = Object.keys(obj);

            if (keys.length === 0) {
                return '<span style="color: #999; font-style: italic;">Empty object</span>';
            }

            let html = '';
            keys.forEach(key => {
                const value = obj[key];

                // For images, only show image_type
                if (isImage && key !== 'image_type') {
                    return;
                }

                // Skip properties in the ignore list
                if (skipSubProperties.includes(key)) {
                    return;
                }

                // Skip null/undefined/empty
                if (value === null || value === undefined || value === '') {
                    return;
                }

                // Handle nested objects/arrays
                if (typeof value === 'object' && value !== null) {
                    if (Array.isArray(value)) {
                        html += `<div style="margin-bottom: 3px; font-size: 0.9em;">
                                    <span style="color: #888;">${this.formatLabel(key)}:</span>
                                    <span style="color: #666;">[${value.length} items]</span>
                                 </div>`;
                    } else {
                        const nestedKeys = Object.keys(value);
                        html += `<div style="margin-bottom: 3px; font-size: 0.9em;">
                                    <span style="color: #888;">${this.formatLabel(key)}:</span>
                                    <span style="color: #666;">{${nestedKeys.length} properties}</span>
                                 </div>`;
                    }
                } else {
                    // Simple value
                    let displayValue = String(value);
                    if (displayValue.length > 100) {
                        displayValue = displayValue.substring(0, 100) + '...';
                    }
                    html += `<div style="margin-bottom: 3px; font-size: 0.9em;">
                                <span style="color: #888;">${this.formatLabel(key)}:</span>
                                <span>${this.escapeHtml(displayValue)}</span>
                             </div>`;
                }
            });

            return html;
        },

        /**
         * Hide the modal
         */
        hide: function() {
            const modal = document.getElementById('dataset-modal');
            if (modal) {
                modal.style.display = 'none';
            }
        },

        /**
         * Show image in a separate modal
         */
        showImageModal: function(imageId, imageLabel) {
            // Create or get image modal
            let imageModal = document.getElementById('image-modal');

            if (!imageModal) {
                // Create the image modal if it doesn't exist
                imageModal = document.createElement('div');
                imageModal.id = 'image-modal';
                imageModal.className = 'modal';
                imageModal.style.display = 'none';
                imageModal.style.zIndex = '10001'; // Higher than dataset modal

                imageModal.innerHTML = `
                    <div class="modal-content" style="max-width: 90%; max-height: 90vh; overflow: visible;">
                        <span class="close-modal" onclick="window.DatasetModal.hideImageModal()">&times;</span>
                        <div id="image-modal-body" style="text-align: center;">
                            <h3 id="image-modal-title" style="margin-bottom: 20px;"></h3>
                            <div id="image-modal-content"></div>
                        </div>
                    </div>
                `;

                document.body.appendChild(imageModal);

                // Add click outside to close
                imageModal.addEventListener('click', function(event) {
                    if (event.target === imageModal) {
                        window.DatasetModal.hideImageModal();
                    }
                });
            }

            // Set title and image
            const modalTitle = document.getElementById('image-modal-title');
            const modalContent = document.getElementById('image-modal-content');

            if (modalTitle && modalContent) {
                modalTitle.textContent = imageLabel;
                modalContent.innerHTML = `
                    <img src="https://strabospot.org/pi/${this.escapeHtml(imageId)}"
                         alt="${this.escapeHtml(imageLabel)}"
                         style="max-width: 100%; max-height: calc(90vh - 120px); width: auto; height: auto; border: 1px solid #ddd; border-radius: 5px;"
                         onerror="this.parentElement.innerHTML='<p style=\\'color: #dc3545;\\'>Failed to load image</p>'">
                `;
            }

            // Show modal
            imageModal.style.display = 'flex';
        },

        /**
         * Hide the image modal
         */
        hideImageModal: function() {
            const imageModal = document.getElementById('image-modal');
            if (imageModal) {
                imageModal.style.display = 'none';
            }
        },

        /**
         * Format field key as readable label
         */
        formatLabel: function(key) {
            return key
                .replace(/([A-Z])/g, ' $1')
                .replace(/^./, str => str.toUpperCase())
                .trim();
        },

        /**
         * Escape HTML to prevent XSS
         */
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Setup modal event listeners when DOM is ready
    function setupModalListeners() {
        const modal = document.getElementById('dataset-modal');
        const closeBtn = document.querySelector('.close-modal');

        // Close button click
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                window.DatasetModal.hide();
            });
        }

        // Click outside modal to close
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    window.DatasetModal.hide();
                }
            });
        }

        // Escape key to close
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // Close image modal first if open, otherwise close dataset modal
                const imageModal = document.getElementById('image-modal');
                if (imageModal && imageModal.style.display === 'flex') {
                    window.DatasetModal.hideImageModal();
                } else {
                    window.DatasetModal.hide();
                }
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupModalListeners);
    } else {
        setupModalListeners();
    }

})();
