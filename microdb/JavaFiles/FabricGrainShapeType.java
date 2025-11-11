package org.strabospot.datatypes;

public class FabricGrainShapeType {
    public String[] phases;
    public String alignment;
    public String shape;
    public String notes;

    public FabricGrainShapeType() {
    }

    public FabricGrainShapeType(String[] phases, String alignment, String shape, String notes) {
        this.phases = phases;
        this.alignment = alignment;
        this.shape = shape;
        this.notes = notes;
    }

    public String[] getPhases() {
        return phases;
    }

    public void setPhases(String[] phases) {
        this.phases = phases;
    }

    public String getAlignment() {
        return alignment;
    }

    public void setAlignment(String alignment) {
        this.alignment = alignment;
    }

    public String getShape() {
        return shape;
    }

    public void setShape(String shape) {
        this.shape = shape;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
