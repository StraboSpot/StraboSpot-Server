package org.strabospot.datatypes;

public class ClasticDeformationBandType {
    public ClasticDeformationBandTypeType[] types;
    public Double thickness;
    public String thicknessUnit;
    public String cements;

    public ClasticDeformationBandType() {
    }

    public ClasticDeformationBandType(ClasticDeformationBandTypeType[] types, Double thickness, String thicknessUnit, String cements) {
        this.types = types;
        this.thickness = thickness;
        this.thicknessUnit = thicknessUnit;
        this.cements = cements;
    }

    public ClasticDeformationBandTypeType[] getTypes() {
        return types;
    }

    public void setTypes(ClasticDeformationBandTypeType[] types) {
        this.types = types;
    }

    public Double getThickness() {
        return thickness;
    }

    public void setThickness(Double thickness) {
        this.thickness = thickness;
    }

    public String getThicknessUnit() {
        return thicknessUnit;
    }

    public void setThicknessUnit(String thicknessUnit) {
        this.thicknessUnit = thicknessUnit;
    }

    public String getCements() {
        return cements;
    }

    public void setCements(String cements) {
        this.cements = cements;
    }
}
