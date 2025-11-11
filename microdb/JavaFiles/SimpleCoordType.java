package org.strabospot.datatypes;

public class SimpleCoordType {
    public Double X;
    public Double Y;

    public SimpleCoordType(){
        //overload default
    }

    public SimpleCoordType(Double x, Double y) {
        X = x;
        Y = y;
    }

    public Double getX() {
        return X;
    }

    public void setX(Double x) {
        X = x;
    }

    public Double getY() {
        return Y;
    }

    public void setY(Double y) {
        Y = y;
    }
}
