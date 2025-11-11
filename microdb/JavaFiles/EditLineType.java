package org.strabospot.datatypes;

public class EditLineType {
    public Double startX;
    public Double startY;
    public Double endX;
    public Double endY;
    public Integer startPointOrder;
    public Integer endPointOrder;

    public EditLineType(){

    }

    public EditLineType(Double startX, Double startY, Double endX, Double endY, Integer startPointOrder, Integer endPointOrder) {
        this.startX = startX;
        this.startY = startY;
        this.endX = endX;
        this.endY = endY;
        this.startPointOrder = startPointOrder;
        this.endPointOrder = endPointOrder;
    }

    public Double getStartX() {
        return startX;
    }

    public void setStartX(Double startX) {
        this.startX = startX;
    }

    public Double getStartY() {
        return startY;
    }

    public void setStartY(Double startY) {
        this.startY = startY;
    }

    public Double getEndX() {
        return endX;
    }

    public void setEndX(Double endX) {
        this.endX = endX;
    }

    public Double getEndY() {
        return endY;
    }

    public void setEndY(Double endY) {
        this.endY = endY;
    }

    public Integer getStartPointOrder() {
        return startPointOrder;
    }

    public void setStartPointOrder(Integer startPointOrder) {
        this.startPointOrder = startPointOrder;
    }

    public Integer getEndPointOrder() {
        return endPointOrder;
    }

    public void setEndPointOrder(Integer endPointOrder) {
        this.endPointOrder = endPointOrder;
    }
}
