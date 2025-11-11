package org.strabospot.datatypes;

public class VeinType {
    public String mineralogy;
    public VeinSubType[] crystalShapes;
    public VeinSubType[] growthMorphologies;
    public VeinSubType[] inclusionTrails;
    public VeinSubType[] kinematics;

    public VeinType() {
    }

    public VeinType(String mineralogy, VeinSubType[] crystalShapes, VeinSubType[] growthMorphologies, VeinSubType[] inclusionTrails, VeinSubType[] kinematics) {
        this.mineralogy = mineralogy;
        this.crystalShapes = crystalShapes;
        this.growthMorphologies = growthMorphologies;
        this.inclusionTrails = inclusionTrails;
        this.kinematics = kinematics;
    }

    public String getMineralogy() {
        return mineralogy;
    }

    public void setMineralogy(String mineralogy) {
        this.mineralogy = mineralogy;
    }

    public VeinSubType[] getCrystalShapes() {
        return crystalShapes;
    }

    public void setCrystalShapes(VeinSubType[] crystalShapes) {
        this.crystalShapes = crystalShapes;
    }

    public VeinSubType[] getGrowthMorphologies() {
        return growthMorphologies;
    }

    public void setGrowthMorphologies(VeinSubType[] growthMorphologies) {
        this.growthMorphologies = growthMorphologies;
    }

    public VeinSubType[] getInclusionTrails() {
        return inclusionTrails;
    }

    public void setInclusionTrails(VeinSubType[] inclusionTrails) {
        this.inclusionTrails = inclusionTrails;
    }

    public VeinSubType[] getKinematics() {
        return kinematics;
    }

    public void setKinematics(VeinSubType[] kinematics) {
        this.kinematics = kinematics;
    }
}
