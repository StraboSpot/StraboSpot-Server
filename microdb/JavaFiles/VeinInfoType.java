package org.strabospot.datatypes;

public class VeinInfoType {
    public VeinType[] veins;
    public String notes;

    public VeinInfoType() {
    }

    public VeinInfoType(VeinType[] veins, String notes) {
        this.veins = veins;
        this.notes = notes;
    }

    public VeinType[] getVeins() {
        return veins;
    }

    public void setVeins(VeinType[] veins) {
        this.veins = veins;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
