package org.strabospot.datatypes;

public class MicrographMetadataType {

    public String id;
    public String parentID;
    public String name;
    public String imageType;
    public Double width;
    public Double height;
    public String scale;
    public Boolean polish;
    public String polishDescription;
    public String description;
    public String notes;
    public Double scalePixelsPerCentimeter;
    public SimpleCoordType offsetInParent;
    public SimpleCoordType pointInParent;
    public Double rotation;
    public MineralogyType mineralogy;
    public SpotMetadataType[] spots;
    public GrainInfoType grainInfo;
    public FabricInfoType fabricInfo;
    public MicrographOrientationType orientationInfo;
    public AssociatedFileType[] associatedFiles;
    public LinkType[] links;
    public InstrumentType instrument;
    public ClasticDeformationBandInfoType clasticDeformationBandInfo;
    public GrainBoundaryInfoType grainBoundaryInfo;
    public IntraGrainInfoType intraGrainInfo;
    public VeinInfoType veinInfo;
    public PseudotachylyteInfoType pseudotachylyteInfo;
    public FoldInfoType foldInfo;
    public Boolean isMicroVisible;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getParentID() {
        return parentID;
    }

    public void setParentID(String parentID) {
        this.parentID = parentID;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getImageType() {
        return imageType;
    }

    public void setImageType(String imageType) {
        this.imageType = imageType;
    }

    public Double getWidth() {
        return width;
    }

    public void setWidth(Double width) {
        this.width = width;
    }

    public Double getHeight() {
        return height;
    }

    public void setHeight(Double height) {
        this.height = height;
    }

    public String getScale() {
        return scale;
    }

    public void setScale(String scale) {
        this.scale = scale;
    }

    public Boolean getPolish() {
        return polish;
    }

    public void setPolish(Boolean polish) {
        this.polish = polish;
    }

    public String getPolishDescription() {
        return polishDescription;
    }

    public void setPolishDescription(String polishDescription) {
        this.polishDescription = polishDescription;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }

    public Double getScalePixelsPerCentimeter() {
        return scalePixelsPerCentimeter;
    }

    public void setScalePixelsPerCentimeter(Double scalePixelsPerCentimeter) {
        this.scalePixelsPerCentimeter = scalePixelsPerCentimeter;
    }

    public SimpleCoordType getOffsetInParent() {
        return offsetInParent;
    }

    public void setOffsetInParent(SimpleCoordType offsetInParent) {
        this.offsetInParent = offsetInParent;
    }

    public SimpleCoordType getPointInParent() {
        return pointInParent;
    }

    public void setPointInParent(SimpleCoordType pointInParent) {
        this.pointInParent = pointInParent;
    }

    public Double getRotation() {
        return rotation;
    }

    public void setRotation(Double rotation) {
        this.rotation = rotation;
    }

    public MineralogyType getMineralogy() {
        return mineralogy;
    }

    public void setMineralogy(MineralogyType mineralogy) {
        this.mineralogy = mineralogy;
    }

    public SpotMetadataType[] getSpots() {
        return spots;
    }

    public void setSpots(SpotMetadataType[] spots) {
        this.spots = spots;
    }

    public GrainInfoType getGrainInfo() {
        return grainInfo;
    }

    public void setGrainInfo(GrainInfoType grainInfo) {
        this.grainInfo = grainInfo;
    }

    public FabricInfoType getFabricInfo() {
        return fabricInfo;
    }

    public void setFabricInfo(FabricInfoType fabricInfo) {
        this.fabricInfo = fabricInfo;
    }

    public MicrographOrientationType getOrientationInfo() {
        return orientationInfo;
    }

    public void setOrientationInfo(MicrographOrientationType orientationInfo) { this.orientationInfo = orientationInfo; }

    public AssociatedFileType[] getAssociatedFiles() {
        return associatedFiles;
    }

    public void setAssociatedFiles(AssociatedFileType[] associatedFiles) {
        this.associatedFiles = associatedFiles;
    }

    public LinkType[] getLinks() {
        return links;
    }

    public void setLinks(LinkType[] links) {
        this.links = links;
    }

    public InstrumentType getInstrument() {
        return instrument;
    }

    public void setInstrument(InstrumentType instrument) {
        this.instrument = instrument;
    }

    public Boolean getMicroVisible() {
        return isMicroVisible;
    }

    public void setMicroVisible(Boolean microVisible) {
        isMicroVisible = microVisible;
    }

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
