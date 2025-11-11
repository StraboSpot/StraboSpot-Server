package org.strabospot.datatypes;

import com.mapbox.geojson.Polygon;

public class micrographPolygon {

    //This class holds polygons of coordinates representing sub-micrographs in parent
    // micrographs so clicks can be checked whether they land in sub-micrograph.

    public String id;
    public Polygon polygon;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public Polygon getPolygon() {
        return polygon;
    }

    public void setPolygon(Polygon polygon) {
        this.polygon = polygon;
    }
}
