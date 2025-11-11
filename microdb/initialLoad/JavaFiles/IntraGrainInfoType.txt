package org.strabospot.datatypes;

public class IntraGrainInfoType {

    public IntraGrainType[] grains;
    public String notes;

    public IntraGrainInfoType() {
    }

    public IntraGrainInfoType(IntraGrainType[] grains, String notes) {
        this.grains = grains;
        this.notes = notes;
    }

    public IntraGrainType[] getGrains() {
        return grains;
    }

    public void setGrains(IntraGrainType[] grains) {
        this.grains = grains;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
