package org.strabospot.datatypes;

public class GrainBoundaryInfoType {
    public GrainBoundaryType[] boundaries;
    public String notes;

    public GrainBoundaryType[] getBoundaries() {
        return boundaries;
    }

    public void setBoundaries(GrainBoundaryType[] boundaries) {
        this.boundaries = boundaries;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
