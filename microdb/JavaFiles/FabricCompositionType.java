package org.strabospot.datatypes;

public class FabricCompositionType {

    public String compositionNotes;
    public FabricCompositionLayerType[] layers;

    public FabricCompositionType() {
    }

    public FabricCompositionType(String compositionNotes, FabricCompositionLayerType[] layers) {
        this.compositionNotes = compositionNotes;
        this.layers = layers;
    }

    public String getCompositionNotes() {
        return compositionNotes;
    }

    public void setCompositionNotes(String compositionNotes) {
        this.compositionNotes = compositionNotes;
    }

    public FabricCompositionLayerType[] getLayers() {
        return layers;
    }

    public void setLayers(FabricCompositionLayerType[] layers) {
        this.layers = layers;
    }
}
