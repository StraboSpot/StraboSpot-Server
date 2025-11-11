package org.strabospot.datatypes;

public class DatasetMetadataType {

    public String id;
    public String name;
    public String date;
    public String modifiedTimestamp;
    public SampleMetadataType[] samples;

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

    public SampleMetadataType getSample(String sampleID){
        for (Integer i = 0; i < samples.length; i++) {

            if(samples[i].id.equals(sampleID)){
                return samples[i];
            }
        }

        return null;
    }
}
