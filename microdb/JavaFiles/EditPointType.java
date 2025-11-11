package org.strabospot.datatypes;

public class EditPointType {
    public Double X;
    public Double Y;
    public Integer pointOrder;

    public EditPointType(){
        //overload default
    }

    public EditPointType(Double x, Double y, Integer inOrder) {
        X = x;
        Y = y;
        pointOrder = inOrder;
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

    public Integer getPointOrder() { return pointOrder; }

    public void setPointOrder(Integer pointOrder) { this.pointOrder = pointOrder; }
}
