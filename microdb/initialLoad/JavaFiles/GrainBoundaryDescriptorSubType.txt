package org.strabospot.datatypes;

public class GrainBoundaryDescriptorSubType {
    public String type;
    public String otherType;

    public GrainBoundaryDescriptorSubType(String type) {
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
