package org.strabospot.datatypes;

public class VeinSubType {
    public String type;
    public String subType;
    public Double numericValue;
    public String unit;

    public VeinSubType() {
    }

    public VeinSubType(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public String getSubType() {
        return subType;
    }

    public void setSubType(String subType) {
        this.subType = subType;
    }

    public Double getNumericValue() {
        return numericValue;
    }

    public void setNumericValue(Double numericValue) {
        this.numericValue = numericValue;
    }

    public String getUnit() {
        return unit;
    }

    public void setUnit(String unit) {
        this.unit = unit;
    }
}
