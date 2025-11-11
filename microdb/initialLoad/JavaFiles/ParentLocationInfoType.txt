package org.strabospot.datatypes;

public class ParentLocationInfoType {

    public Double scalePixelsPerCentimeter;
    public SimpleCoordType offsetInParent;
    public Double rotation;
    public Double width;
    public Double height;

    public Double getScalePixelsPerCentimeter() {
        return scalePixelsPerCentimeter;
    }

    public void setScalePixelsPerCentimeter(Double scalePixelsPerCentimeter) {
        this.scalePixelsPerCentimeter = scalePixelsPerCentimeter;
    }

    public SimpleCoordType getOffsetInParent() {
        return offsetInParent;
    }

    public void setOffsetInParent(SimpleCoordType offsetInParent) {
        this.offsetInParent = offsetInParent;
    }

    public Double getRotation() {
        return rotation;
    }

    public void setRotation(Double rotation) {
        this.rotation = rotation;
    }

    public Double getWidth() {
        return width;
    }

    public void setWidth(Double width) {
        this.width = width;
    }

    public Double getHeight() {
        return height;
    }

    public void setHeight(Double height) {
        this.height = height;
    }
}
