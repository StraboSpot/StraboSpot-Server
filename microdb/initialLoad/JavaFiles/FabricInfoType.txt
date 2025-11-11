package org.strabospot.datatypes;

public class FabricInfoType {
    public FabricType[] fabrics;
    public String notes;

    public FabricInfoType() {
    }

    public FabricInfoType(FabricType[] fabrics) {
        this.fabrics = fabrics;
    }

    public FabricType[] getFabrics() {
        return fabrics;
    }

    public void setFabrics(FabricType[] fabrics) {
        this.fabrics = fabrics;
    }

    public String getNotes() { return notes; }

    public void setNotes(String notes) { this.notes = notes; }
}
