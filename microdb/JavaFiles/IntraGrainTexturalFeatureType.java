package org.strabospot.datatypes;

public class IntraGrainTexturalFeatureType {
    public String type;
    public String otherType;

    public IntraGrainTexturalFeatureType() {
    }

    public IntraGrainTexturalFeatureType(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getOtherType() {
        return otherType;
    }

    public void setOtherType(String otherType) {
        this.otherType = otherType;
    }
}
