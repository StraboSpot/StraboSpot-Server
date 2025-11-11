package org.strabospot.datatypes;

public class PseudotachylyteInfoType {

    public PseudotachylyteType[] pseudotachylytes;
    public String reasoning;
    public String notes;

    public PseudotachylyteInfoType() {
    }

    public PseudotachylyteType[] getPseudotachylytes() {
        return pseudotachylytes;
    }

    public void setPseudotachylytes(PseudotachylyteType[] pseudotachylytes) {
        this.pseudotachylytes = pseudotachylytes;
    }

    public String getReasoning() {
        return reasoning;
    }

    public void setReasoning(String reasoning) {
        this.reasoning = reasoning;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
