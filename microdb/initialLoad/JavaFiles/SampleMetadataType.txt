package org.strabospot.datatypes;

import javafx.util.StringConverter;

public class SampleMetadataType {

    public String id;
    public Boolean existsOnServer;
    public String label;
    public String sampleID;
    public String mainSamplingPurpose;
    public String sampleDescription;
    public String materialType;
    public String inplacenessOfSample;
    public String orientedSample;
    public String sampleSize;
    public String degreeOfWeathering;
    public String sampleNotes;
    public String sampleType;
    public String color;
    public String lithology;
    public String sampleUnit;
    public String otherMaterialType;
    public String sampleOrientationNotes;
    public String otherSamplingPurpose;
    public MicrographMetadataType[] micrographs;

    public String getLabel() {
        return label;
    }

    public void setLabel(String label) {
        this.label = label;
    }

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getSampleID() {
        return sampleID;
    }

    public void setSampleID(String sampleID) {
        this.sampleID = sampleID;
    }

    public String getMainSamplingPurpose() {
        return mainSamplingPurpose;
    }

    public void setMainSamplingPurpose(String mainSamplingPurpose) {
        this.mainSamplingPurpose = mainSamplingPurpose;
    }

    public String getSampleDescription() {
        return sampleDescription;
    }

    public void setSampleDescription(String sampleDescription) {
        this.sampleDescription = sampleDescription;
    }

    public String getMaterialType() {
        return materialType;
    }

    public void setMaterialType(String materialType) {
        this.materialType = materialType;
    }

    public String getInplacenessOfSample() {
        return inplacenessOfSample;
    }

    public void setInplacenessOfSample(String inplacenessOfSample) {
        this.inplacenessOfSample = inplacenessOfSample;
    }

    public String getOrientedSample() {
        return orientedSample;
    }

    public void setOrientedSample(String orientedSample) {
        this.orientedSample = orientedSample;
    }

    public String getSampleSize() {
        return sampleSize;
    }

    public void setSampleSize(String sampleSize) {
        this.sampleSize = sampleSize;
    }

    public String getDegreeOfWeathering() {
        return degreeOfWeathering;
    }

    public void setDegreeOfWeathering(String degreeOfWeathering) {
        this.degreeOfWeathering = degreeOfWeathering;
    }

    public String getSampleNotes() {
        return sampleNotes;
    }

    public void setSampleNotes(String sampleNotes) {
        this.sampleNotes = sampleNotes;
    }

    public String getSampleType() {
        return sampleType;
    }

    public void setSampleType(String sampleType) {
        this.sampleType = sampleType;
    }

    public String getColor() {
        return color;
    }

    public void setColor(String color) {
        this.color = color;
    }

    public String getLithology() {
        return lithology;
    }

    public void setLithology(String lithology) {
        this.lithology = lithology;
    }

    public String getSampleUnit() {
        return sampleUnit;
    }

    public void setSampleUnit(String sampleUnit) {
        this.sampleUnit = sampleUnit;
    }

    public String getOtherMaterialType() {
        return otherMaterialType;
    }

    public void setOtherMaterialType(String otherMaterialType) {
        this.otherMaterialType = otherMaterialType;
    }

    public String getSampleOrientationNotes() {
        return sampleOrientationNotes;
    }

    public void setSampleOrientationNotes(String sampleOrientationNotes) {
        this.sampleOrientationNotes = sampleOrientationNotes;
    }

    public String getOtherSamplingPurpose() {
        return otherSamplingPurpose;
    }

    public void setOtherSamplingPurpose(String otherSamplingPurpose) {
        this.otherSamplingPurpose = otherSamplingPurpose;
    }

    public MicrographMetadataType getMicrograph(String micrographID){
        for (Integer i = 0; i < micrographs.length; i++) {

            if(micrographs[i].id.equals(micrographID)){
                return micrographs[i];
            }
        }
        return null;
    }

    public Boolean getExistsOnServer() {
        return existsOnServer;
    }

    public void setExistsOnServer(Boolean existsOnServer) {
        this.existsOnServer = existsOnServer;
    }
}
