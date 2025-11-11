package org.strabospot.datatypes;

public class ClasticDeformationBandTypeType {
    public String type;
    public Double aperture;
    public String apertureUnit;
    public Double offset;
    public String offsetUnit;

    public ClasticDeformationBandTypeType() {
    }

    public ClasticDeformationBandTypeType(String type, Double aperture, String apertureUnit, Double offset, String offsetUnit) {
        this.type = type;
        this.aperture = aperture;
        this.apertureUnit = apertureUnit;
        this.offset = offset;
        this.offsetUnit = offsetUnit;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public Double getAperture() {
        return aperture;
    }

    public void setAperture(Double aperture) {
        this.aperture = aperture;
    }

    public String getApertureUnit() {
        return apertureUnit;
    }

    public void setApertureUnit(String apertureUnit) {
        this.apertureUnit = apertureUnit;
    }

    public Double getOffset() {
        return offset;
    }

    public void setOffset(Double offset) {
        this.offset = offset;
    }

    public String getOffsetUnit() {
        return offsetUnit;
    }

    public void setOffsetUnit(String offsetUnit) {
        this.offsetUnit = offsetUnit;
    }
}
