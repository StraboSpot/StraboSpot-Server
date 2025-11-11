package org.strabospot.datatypes;

public class GrainOrientationType {
    public String[] phases;
    public Double meanOrientation;
    public String relativeTo;
    public String software;
    public String spoTechnique;
    public String spoOther;

    public GrainOrientationType(String[] phases, Double meanOrientation, String relativeTo, String software, String spoTechnique, String spoOther) {
        this.phases = phases;
        this.meanOrientation = meanOrientation;
        this.relativeTo = relativeTo;
        this.software = software;
        this.spoTechnique = spoTechnique;
        this.spoOther = spoOther;
    }

    public GrainOrientationType(){

    }

    public String[] getPhases() {
        return phases;
    }

    public void setPhases(String[] phases) {
        this.phases = phases;
    }

    public Double getMeanOrientation() {
        return meanOrientation;
    }

    public void setMeanOrientation(Double meanOrientation) {
        this.meanOrientation = meanOrientation;
    }

    public String getRelativeTo() {
        return relativeTo;
    }

    public void setRelativeTo(String relativeTo) {
        this.relativeTo = relativeTo;
    }

    public String getSoftware() {
        return software;
    }

    public void setSoftware(String software) {
        this.software = software;
    }

    public String getSpoTechnique() {
        return spoTechnique;
    }

    public void setSpoTechnique(String spoTechnique) {
        this.spoTechnique = spoTechnique;
    }

    public String getSpoOther() {
        return spoOther;
    }

    public void setSpoOther(String spoOther) {
        this.spoOther = spoOther;
    }

}
