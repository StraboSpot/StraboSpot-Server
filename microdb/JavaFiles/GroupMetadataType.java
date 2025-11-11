package org.strabospot.datatypes;

public class GroupMetadataType {

    public String id;
    public String name;
    public String[] micrographs;

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

    public String[] getMicrographs() {
        return micrographs;
    }

    public void setMicrographs(String[] micrographs) {
        this.micrographs = micrographs;
    }
}
