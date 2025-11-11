package org.strabospot.datatypes;

public class FabricGrainSizeLayerType {
    public String grainSize;
    public Double thickness;
    public String thicknessUnits;

    public FabricGrainSizeLayerType() {
    }

    public FabricGrainSizeLayerType(String composition, Double thickness, String thicknessUnits) {
        this.grainSize = composition;
        this.thickness = thickness;
        this.thicknessUnits = thicknessUnits;
    }

    public String getComposition() {
        return grainSize;
    }

    public void setComposition(String composition) {
        this.grainSize = composition;
    }

    public Double getThickness() {
        return thickness;
    }

    public void setThickness(Double thickness) {
        this.thickness = thickness;
    }

    public String getThicknessUnits() {
        return thicknessUnits;
    }

    public void setThicknessUnits(String thicknessUnits) {
        this.thicknessUnits = thicknessUnits;
    }
}
