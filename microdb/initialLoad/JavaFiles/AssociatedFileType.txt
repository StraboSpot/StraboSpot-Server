package org.strabospot.datatypes;

public class AssociatedFileType {
    public String fileName;
    public String originalPath;
    public String fileType;
    public String otherType;
    public String notes;

    public String getFileName() {
        return fileName;
    }

    public void setFileName(String fileName) {
        this.fileName = fileName;
    }

    public String getFileType() {
        return fileType;
    }

    public String getOriginalPath() {
        return originalPath;
    }

    public void setOriginalPath(String originalPath) {
        this.originalPath = originalPath;
    }

    public void setFileType(String fileType) {
        this.fileType = fileType;
    }

    public String getOtherType() {
        return otherType;
    }

    public void setOtherType(String otherType) {
        this.otherType = otherType;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }
}
