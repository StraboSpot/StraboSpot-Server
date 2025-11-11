package org.strabospot.datatypes;

public class SpotMetadataType {
    public String id;
    public String name;
    public String labelColor;
    public Boolean showLabel;
    public String color;
    public String date;
    public String time;
    public String notes;
    public Long modifiedTimestamp;
    public String geometryType;
    public SimpleCoordType[] points;
    public MineralogyType mineralogy;
    public GrainInfoType grainInfo;
    public FabricInfoType fabricInfo;
    public AssociatedFileType[] associatedFiles;
    public LinkType[] links;
    public ClasticDeformationBandInfoType clasticDeformationBandInfo;
    public GrainBoundaryInfoType grainBoundaryInfo;
    public IntraGrainInfoType intraGrainInfo;
    public VeinInfoType veinInfo;
    public PseudotachylyteInfoType pseudotachylyteInfo;
    public FoldInfoType foldInfo;

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

    public String getLabelColor() { return labelColor; }

    public void setLabelColor(String labelColor) { this.labelColor = labelColor; }

    public Boolean getShowLabel() { return showLabel; }

    public void setShowLabel(Boolean showLabel) { this.showLabel = showLabel; }

    public String getColor() { return color; }

    public void setColor(String color) { this.color = color; }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }

    public Long getModifiedTimestamp() {
        return modifiedTimestamp;
    }

    public void setModifiedTimestamp(Long modifiedTimestamp) {
        this.modifiedTimestamp = modifiedTimestamp;
    }

    public String getGeometryType() {
        return geometryType;
    }

    public void setGeometryType(String geometryType) {
        this.geometryType = geometryType;
    }

    public SimpleCoordType[] getPoints() {
        return points;
    }

    public void setPoints(SimpleCoordType[] points) {
        this.points = points;
    }

    public MineralogyType getMineralogy() {
        return mineralogy;
    }

    public void setMineralogy(MineralogyType mineralogy) {
        this.mineralogy = mineralogy;
    }

    public GrainInfoType getGrainInfo() { return grainInfo; }

    public void setGrainInfo(GrainInfoType grainInfo) { this.grainInfo = grainInfo; }

    public FabricInfoType getFabricInfo() { return fabricInfo; }

    public void setFabricInfo(FabricInfoType fabricInfo) { this.fabricInfo = fabricInfo; }

    public AssociatedFileType[] getAssociatedFiles() {
        return associatedFiles;
    }

    public void setAssociatedFiles(AssociatedFileType[] associatedFiles) {
        this.associatedFiles = associatedFiles;
    }

    public LinkType[] getLinks() { return links; }

    public void setLinks(LinkType[] links) { this.links = links; }

    public ClasticDeformationBandInfoType getClasticDeformationBandInfo() { return clasticDeformationBandInfo; }

    public void setClasticDeformationBandInfo(ClasticDeformationBandInfoType clasticDeformationBandInfo) { this.clasticDeformationBandInfo = clasticDeformationBandInfo; }

    public GrainBoundaryInfoType getGrainBoundaryInfo() { return grainBoundaryInfo; }

    public void setGrainBoundaryInfo(GrainBoundaryInfoType grainBoundaryInfo) { this.grainBoundaryInfo = grainBoundaryInfo; }

    public IntraGrainInfoType getIntraGrainInfo() { return intraGrainInfo; }

    public void setIntraGrainInfo(IntraGrainInfoType intraGrainInfo) { this.intraGrainInfo = intraGrainInfo; }

    public VeinInfoType getVeinInfo() { return veinInfo; }

    public void setVeinInfo(VeinInfoType veinInfo) { this.veinInfo = veinInfo; }

    public PseudotachylyteInfoType getPseudotachylyteInfo() { return pseudotachylyteInfo; }

    public void setPseudotachylyteInfo(PseudotachylyteInfoType pseudotachylyteInfo) { this.pseudotachylyteInfo = pseudotachylyteInfo; }

    public FoldInfoType getFoldInfo() { return foldInfo; }

    public void setFoldInfo(FoldInfoType foldInfo) { this.foldInfo = foldInfo; }
}
