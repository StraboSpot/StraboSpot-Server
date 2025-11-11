package org.strabospot.datatypes;

public class GrainSizeType {
    public String[] phases;
    public Double mean;
    public Double median;
    public Double mode;
    public Double standardDeviation;
    public String sizeUnit;

    public GrainSizeType() {

    }

    public GrainSizeType(String[] phases, Double mean, Double median, Double mode, Double standardDeviation, String sizeUnit) {
        this.phases = phases;
        this.mean = mean;
        this.median = median;
        this.mode = mode;
        this.standardDeviation = standardDeviation;
        this.sizeUnit = sizeUnit;
    }

    public String[] getPhases() {
        return phases;
    }

    public void setPhases(String[] phases) {
        this.phases = phases;
    }

    public Double getMean() {
        return mean;
    }

    public void setMean(Double mean) {
        this.mean = mean;
    }

    public Double getMedian() {
        return median;
    }

    public void setMedian(Double median) {
        this.median = median;
    }

    public Double getMode() {
        return mode;
    }

    public void setMode(Double mode) {
        this.mode = mode;
    }

    public Double getStandardDeviation() {
        return standardDeviation;
    }

    public void setStandardDeviation(Double standardDeviation) {
        this.standardDeviation = standardDeviation;
    }

    public String getSizeUnit() {
        return sizeUnit;
    }

    public void setSizeUnit(String sizeUnit) {
        this.sizeUnit = sizeUnit;
    }

}
