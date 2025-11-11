package org.strabospot.datatypes;

public class IntraGrainType {

    public String mineral;
    public IntraGrainTexturalFeatureType[] grainTextures;

    public IntraGrainType() {
    }

    public IntraGrainType(String mineral, IntraGrainTexturalFeatureType[] grainTextures) {
        this.mineral = mineral;
        this.grainTextures = grainTextures;
    }

    public String getMineral() {
        return mineral;
    }

    public void setMineral(String mineral) {
        this.mineral = mineral;
    }

    public IntraGrainTexturalFeatureType[] getGrainTextures() {
        return grainTextures;
    }

    public void setGrainTextures(IntraGrainTexturalFeatureType[] grainTextures) {
        this.grainTextures = grainTextures;
    }
}
