package org.strabospot.datatypes;

public class GrainBoundaryType {
    public String phase1;
    public String phase2;
    public GrainBoundaryMorphologyType[] morphologies;
    public GrainBoundaryDescriptorType[] descriptors;

    public String getPhase1() {
        return phase1;
    }

    public void setPhase1(String phase1) {
        this.phase1 = phase1;
    }

    public String getPhase2() {
        return phase2;
    }

    public void setPhase2(String phase2) {
        this.phase2 = phase2;
    }

    public GrainBoundaryMorphologyType[] getMorphologies() {
        return morphologies;
    }

    public void setMorphologies(GrainBoundaryMorphologyType[] morphologies) {
        this.morphologies = morphologies;
    }

    public GrainBoundaryDescriptorType[] getDescriptors() {
        return descriptors;
    }

    public void setDescriptors(GrainBoundaryDescriptorType[] descriptors) {
        this.descriptors = descriptors;
    }

}
