package org.strabospot.datatypes;

public class FabricCompositionLayerType {
    public String composition;
    public Double thickness;
    public String thicknessUnits;

    public FabricCompositionLayerType() {
    }

    public FabricCompositionLayerType(String composition, Double thickness, String thicknessUnits) {
        this.composition = composition;
        this.thickness = thickness;
        this.thicknessUnits = thicknessUnits;
    }

    public String getComposition() {
        return composition;
    }

    public void setComposition(String composition) {
        this.composition = composition;
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
