package org.strabospot.datatypes;

//This class is used to hold an image and its original width/height

import javafx.scene.image.Image;

public class ReturnImageType {

    Image image;
    Double width;
    Double height;

    public ReturnImageType(){

    }

    public ReturnImageType(Image image, Double width, Double height) {
        this.image = image;
        this.width = width;
        this.height = height;
    }

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;
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
}
