# StraboSpot 3D Globe Browser

An interactive 3D globe interface for exploring and investigating public StraboSpot datasets.

## Overview

The Globe Browser provides users with a three-dimensional, navigable globe interface displaying georeferenced data points as interactive symbols. Users can visually explore the spatial distribution of StraboSpot projects and datasets in an intuitive, immersive environment.

## Features

- **Interactive 3D Globe**: Full 360-degree globe with realistic lighting
- **Dataset Visualization**: Visual markers at geographic coordinates
- **Detail Modals**: Click markers to view dataset information
- **Intuitive Navigation**: Drag to rotate, scroll to zoom
- **Reset View**: Quick return to default camera position

## Technology Stack

- **Globe Rendering**: CesiumJS (WebGL-based 3D globe library)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP with StraboSpot template system
- **Data Source**: `/search/newsearchdatasets.json` API endpoint

## File Structure

```
globe/
├── index.php              # Main entry point with PHP includes
├── assets/
│   ├── css/
│   │   └── globe.css     # Globe-specific styles
│   └── js/
│       ├── globe.js      # Main globe application logic
│       └── modal.js      # Modal dialog handling
└── README.md             # This file
```

## Installation

The globe browser is already integrated into the StraboSpot website structure. Simply navigate to `/globe/` to access it.

## Usage

### For Users

1. Navigate to the Globe Browser page
2. Wait for datasets to load (status shown below controls)
3. Drag the globe to rotate and explore
4. Scroll to zoom in/out
5. Click on any marker to view dataset details
6. Use "Reset View" button to return to default position

### For Developers

#### Data Format

The globe expects data from `/search/newsearchdatasets.json` in the following format:

```json
[
  {
    "id": "unique_dataset_id",
    "name": "Dataset Name",
    "description": "Dataset description",
    "latitude": 40.7128,
    "longitude": -74.0060,
    "authors": "Author names",
    "date": "2024-01-15",
    "projectName": "Project Name",
    "dataType": "core_data"
  }
]
```

Required fields:
- `id`: Unique identifier
- `name`: Display name
- `latitude`: Numeric latitude (-90 to 90)
- `longitude`: Numeric longitude (-180 to 180)

#### Customization

**Marker Appearance**: Edit `CONFIG` object in `globe.js`:
```javascript
const CONFIG = {
    markerColor: Cesium.Color.fromCssColorString('#007bff'),
    markerSize: 8,
    // ... other options
};
```

**Modal Fields**: Modify the `fields` array in `modal.js` `buildDetailsHTML()` method.

**Styling**: Edit `globe.css` for custom colors, sizes, and responsive behavior.

## Browser Compatibility

Requires modern browsers with WebGL support:
- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)

## Performance Considerations

- Optimized for datasets with up to 1000+ markers
- Invalid coordinates are automatically filtered
- Markers use efficient Cesium entity system
- Consider implementing clustering for very large datasets

## Security

- All user input is sanitized to prevent XSS attacks
- Data fetched via HTTPS
- HTML escaping in modal displays
- CORS policy enforcement

## Future Enhancements

Planned features for future releases:
- Advanced filtering by date, author, project type
- Search functionality with autocomplete
- Marker clustering for dense areas
- Custom styling per dataset category
- 3D terrain layers
- Time-based animations
- Export capabilities
- Mobile optimization

## Troubleshooting

**Globe not loading**: Check browser WebGL support and console for errors

**Datasets not appearing**: Verify `/search/newsearchdatasets.json` endpoint is accessible

**Performance issues**: Check number of markers and browser resources

## Credits

- Built for StraboSpot by Jason Ash
- Globe rendering powered by CesiumJS
- Based on PRD version 1.0

## License

MIT License - Copyright 2025 StraboSpot
