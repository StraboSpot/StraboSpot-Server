package org.strabospot.datatypes;

public class GrainBoundaryDescriptorType {
    public String type;
    public GrainBoundaryDescriptorSubType[] subTypes;

    public GrainBoundaryDescriptorType(String type) {
        this.type = type;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public GrainBoundaryDescriptorSubType[] getSubTypes() {
        return subTypes;
    }

    public void setSubTypes(GrainBoundaryDescriptorSubType[] subTypes) {
        this.subTypes = subTypes;
    }
}
