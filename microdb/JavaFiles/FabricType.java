package org.strabospot.datatypes;

public class FabricType {
    public String fabricLabel;
    public String fabricElement;
    public String fabricCategory;
    public String fabricSpacing;
    public String[] fabricDefinedBy;
    public FabricCompositionType fabricCompositionInfo;
    public FabricGrainSizeType fabricGrainSizeInfo;
    public FabricGrainShapeType fabricGrainShapeInfo;
    public FabricCleavageType fabricCleavageInfo;

    public FabricType() {
    }

    public FabricType(String fabricLabel, String fabricElement, String fabricCategory, String fabricSpacing, String[] fabricDefinedBy, FabricCompositionType fabricCompositionInfo, FabricGrainSizeType fabricGrainSizeInfo, FabricGrainShapeType fabricGrainShapeInfo, FabricCleavageType fabricCleavageInfo) {
        this.fabricLabel = fabricLabel;
        this.fabricElement = fabricElement;
        this.fabricCategory = fabricCategory;
        this.fabricSpacing = fabricSpacing;
        this.fabricDefinedBy = fabricDefinedBy;
        this.fabricCompositionInfo = fabricCompositionInfo;
        this.fabricGrainSizeInfo = fabricGrainSizeInfo;
        this.fabricGrainShapeInfo = fabricGrainShapeInfo;
        this.fabricCleavageInfo = fabricCleavageInfo;
    }

    public String getFabricLabel() {
        return fabricLabel;
    }

    public void setFabricLabel(String fabricLabel) {
        this.fabricLabel = fabricLabel;
    }

    public String getFabricElement() {
        return fabricElement;
    }

    public void setFabricElement(String fabricElement) {
        this.fabricElement = fabricElement;
    }

    public String getFabricCategory() {
        return fabricCategory;
    }

    public void setFabricCategory(String fabricCategory) {
        this.fabricCategory = fabricCategory;
    }

    public String getFabricSpacing() {
        return fabricSpacing;
    }

    public void setFabricSpacing(String fabricSpacing) {
        this.fabricSpacing = fabricSpacing;
    }

    public String[] getFabricDefinedBy() {
        return fabricDefinedBy;
    }

    public void setFabricDefinedBy(String[] fabricDefinedBy) {
        this.fabricDefinedBy = fabricDefinedBy;
    }

    public FabricCompositionType getFabricCompositionInfo() {
        return fabricCompositionInfo;
    }

    public void setFabricCompositionInfo(FabricCompositionType fabricCompositionInfo) { this.fabricCompositionInfo = fabricCompositionInfo; }

    public FabricGrainSizeType getFabricGrainSizeInfo() {
        return fabricGrainSizeInfo;
    }

    public void setFabricGrainSizeInfo(FabricGrainSizeType fabricGrainSizeInfo) { this.fabricGrainSizeInfo = fabricGrainSizeInfo; }

    public FabricGrainShapeType getFabricGrainShapeInfo() {
        return fabricGrainShapeInfo;
    }

    public void setFabricGrainShapeInfo(FabricGrainShapeType fabricGrainShapeInfo) { this.fabricGrainShapeInfo = fabricGrainShapeInfo; }

    public FabricCleavageType getFabricCleavageInfo() {
        return fabricCleavageInfo;
    }

    public void setFabricCleavageInfo(FabricCleavageType fabricCleavageInfo) { this.fabricCleavageInfo = fabricCleavageInfo; }

}
