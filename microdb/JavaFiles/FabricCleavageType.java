package org.strabospot.datatypes;

public class FabricCleavageType {
    public Double spacing;
    public String spacingUnit;
    public boolean styloliticCleavage;
    public String[] geometryOfSeams;
    public String notes;

    public FabricCleavageType() {
    }

    public FabricCleavageType(Double spacing, String spacingUnit, boolean styloliticCleavage, String[] geometryOfSeams, String notes) {
        this.spacing = spacing;
        this.spacingUnit = spacingUnit;
        this.styloliticCleavage = styloliticCleavage;
        this.geometryOfSeams = geometryOfSeams;
        this.notes = notes;
    }

    public Double getSpacing() {
        return spacing;
    }

    public void setSpacing(Double spacing) {
        this.spacing = spacing;
    }

    public String getSpacingUnit() {
        return spacingUnit;
    }

    public void setSpacingUnit(String spacingUnit) {
        this.spacingUnit = spacingUnit;
    }

    public boolean isStyloliticCleavage() {
        return styloliticCleavage;
    }

    public void setStyloliticCleavage(boolean styloliticCleavage) {
        this.styloliticCleavage = styloliticCleavage;
    }

    public String[] getGeometryOfSeams() {
        return geometryOfSeams;
    }

    public void setGeometryOfSeams(String[] geometryOfSeams) {
        this.geometryOfSeams = geometryOfSeams;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
