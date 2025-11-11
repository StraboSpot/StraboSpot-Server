package org.strabospot.datatypes;

public class MineralogyType {
    public String percentageCalculationMethod;
    public String mineralogyMethod;
    public MineralType[] minerals;
    public String notes;

    public MineralogyType() {

    }

    public MineralogyType(String percentageCalculationMethod, String mineralogyMethod, MineralType[] minerals, String notes) {
        this.percentageCalculationMethod = percentageCalculationMethod;
        this.mineralogyMethod = mineralogyMethod;
        this.minerals = minerals;
        this.notes = notes;
    }

    public String getPercentageCalculationMethod() {
        return percentageCalculationMethod;
    }

    public void setPercentageCalculationMethod(String percentageCalculationMethod) {
        this.percentageCalculationMethod = percentageCalculationMethod;
    }

    public String getMineralogyMethod() {
        return mineralogyMethod;
    }

    public void setMineralogyMethod(String mineralogyMethod) {
        this.mineralogyMethod = mineralogyMethod;
    }

    public MineralType[] getMinerals() {
        return minerals;
    }

    public void setMinerals(MineralType[] minerals) {
        this.minerals = minerals;
    }

    public String getNotes() { return notes; }

    public void setNotes(String notes) { this.notes = notes; }
}
