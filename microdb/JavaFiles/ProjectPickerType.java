package org.strabospot.datatypes;

//This class is used to set up a data type for the Project Picker listview

public class ProjectPickerType {
    public String id;
    public String name;

    public ProjectPickerType(String id, String name) {
        this.id = id;
        this.name = name;
    }

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
}
