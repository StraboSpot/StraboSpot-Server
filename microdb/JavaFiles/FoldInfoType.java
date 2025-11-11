package org.strabospot.datatypes;

public class FoldInfoType {

    public FoldType[] folds;
    public String notes;

    public FoldInfoType() {
    }

    public FoldType[] getFolds() {
        return folds;
    }

    public void setFolds(FoldType[] folds) {
        this.folds = folds;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
