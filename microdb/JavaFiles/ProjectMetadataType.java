package org.strabospot.datatypes;

import org.apache.commons.lang3.ArrayUtils;

public class ProjectMetadataType {

    public String id;
    public String name;
    public String startDate;
    public String endDate;
    public String purposeOfStudy;
    public String otherTeamMembers;
    public String areaOfInterest;
    public String instrumentsUsed;
    public String gpsDatum;
    public String magneticDeclination;
    public String notes;
    public String date;
    public String modifiedTimestamp;
    public String projectLocation;
    public DatasetMetadataType[] datasets;
    public GroupMetadataType[] groups;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getStartDate() {
        return startDate;
    }

    public void setStartDate(String startDate) {
        this.startDate = startDate;
    }

    public String getEndDate() {
        return endDate;
    }

    public void setEndDate(String endDate) {
        this.endDate = endDate;
    }

    public String getPurposeOfStudy() {
        return purposeOfStudy;
    }

    public void setPurposeOfStudy(String purposeOfStudy) {
        this.purposeOfStudy = purposeOfStudy;
    }

    public String getOtherTeamMembers() {
        return otherTeamMembers;
    }

    public void setOtherTeamMembers(String otherTeamMembers) {
        this.otherTeamMembers = otherTeamMembers;
    }

    public String getAreaOfInterest() {
        return areaOfInterest;
    }

    public void setAreaOfInterest(String areaOfInterest) {
        this.areaOfInterest = areaOfInterest;
    }

    public String getInstrumentsUsed() {
        return instrumentsUsed;
    }

    public void setInstrumentsUsed(String instrumentsUsed) {
        this.instrumentsUsed = instrumentsUsed;
    }

    public String getGpsDatum() {
        return gpsDatum;
    }

    public void setGpsDatum(String gpsDatum) {
        this.gpsDatum = gpsDatum;
    }

    public String getMagneticDeclination() {
        return magneticDeclination;
    }

    public void setMagneticDeclination(String magneticDeclination) {
        this.magneticDeclination = magneticDeclination;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getModifiedTimestamp() {
        return modifiedTimestamp;
    }

    public void setModifiedTimestamp(String modifiedTimestamp) {
        this.modifiedTimestamp = modifiedTimestamp;
    }

    public String getProjectLocation() {
        return projectLocation;
    }

    public void setProjectLocation(String projectLocation) {
        this.projectLocation = projectLocation;
    }

    @Override
    public String toString() {
        return getClass().getSimpleName() + "[name=" + name + "]";
    }

    public DatasetMetadataType getDataset(String datasetID){
        for (Integer i = 0; i < datasets.length; i++) {

            if(datasets[i].id.equals(datasetID)){
                return datasets[i];
            }
        }

        return null;
    }

    public MicrographMetadataType getMicrograph(String micrographID){
        for (Integer i = 0; i < datasets.length; i++) {
            DatasetMetadataType thisDataset = datasets[i];
            for(Integer j = 0; j< thisDataset.samples.length; j++){
                SampleMetadataType thisSample = thisDataset.samples[j];
                for(Integer k = 0; k < thisSample.micrographs.length; k++){
                    if(thisSample.micrographs[k].id.equals(micrographID)){
                        return thisSample.micrographs[k];
                    }
                }
            }
        }

        return null;
    }

    public MicrographMetadataType[] getChildMicrographs(String micrographID){
        MicrographMetadataType[] outMicrographs = {};

        for (Integer i = 0; i < datasets.length; i++) {
            DatasetMetadataType thisDataset = datasets[i];
            for(Integer j = 0; j< thisDataset.samples.length; j++){
                SampleMetadataType thisSample = thisDataset.samples[j];
                for(Integer k = 0; k < thisSample.micrographs.length; k++){
                    if(thisSample.micrographs[k].parentID != null) {
                        if (thisSample.micrographs[k].parentID.equals(micrographID)) {
                            outMicrographs = ArrayUtils.add(outMicrographs, thisSample.micrographs[k]);
                        }
                    }
                }
            }
        }

        return outMicrographs;
    }

    public MicrographMetadataType[] getAllMicrographs(){
        MicrographMetadataType[] outMicrographs = {};

        for (Integer i = 0; i < datasets.length; i++) {
            DatasetMetadataType thisDataset = datasets[i];
            for(Integer j = 0; j< thisDataset.samples.length; j++){
                SampleMetadataType thisSample = thisDataset.samples[j];
                for(Integer k = 0; k < thisSample.micrographs.length; k++){
                    outMicrographs = ArrayUtils.add(outMicrographs, thisSample.micrographs[k]);
                }
            }
        }

        return outMicrographs;
    }

    public String[] getSampleIds(){
        String[] outSampleIds = {};

        for(DatasetMetadataType dataset : datasets){
            for(SampleMetadataType sample : dataset.samples){
                outSampleIds = ArrayUtils.add(outSampleIds, sample.id);
            }
        }

        return outSampleIds;
    }

}
