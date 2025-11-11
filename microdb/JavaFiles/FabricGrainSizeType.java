package org.strabospot.datatypes;

public class FabricGrainSizeType {
    public String grainSizeNotes;
    public FabricGrainSizeLayerType[] layers;

    public FabricGrainSizeType() {
    }

    public FabricGrainSizeType(String compositionNotes, FabricGrainSizeLayerType[] layers) {
        this.grainSizeNotes = compositionNotes;
        this.layers = layers;
    }

    public String getCompositionNotes() {
        return grainSizeNotes;
    }

    public void setCompositionNotes(String compositionNotes) {
        this.grainSizeNotes = compositionNotes;
    }

    public FabricGrainSizeLayerType[] getLayers() {
        return layers;
    }

    public void setLayers(FabricGrainSizeLayerType[] layers) {
        this.layers = layers;
    }
}
