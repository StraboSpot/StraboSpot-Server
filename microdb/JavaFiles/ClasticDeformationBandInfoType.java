package org.strabospot.datatypes;

public class ClasticDeformationBandInfoType {
    public ClasticDeformationBandType[] bands;
    public String notes;

    public ClasticDeformationBandInfoType() {
    }

    public ClasticDeformationBandInfoType(ClasticDeformationBandType[] bands, String notes) {
        this.bands = bands;
        this.notes = notes;
    }

    public ClasticDeformationBandType[] getBands() {
        return bands;
    }

    public void setBands(ClasticDeformationBandType[] bands) {
        this.bands = bands;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
