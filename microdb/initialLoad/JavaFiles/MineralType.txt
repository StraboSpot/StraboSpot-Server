package org.strabospot.datatypes;

public class MineralType {
    public String name;
    public String operator;
    public Double percentage;

    public MineralType() {
    }

    public MineralType(String name, String operator, Double percentage) {
        this.name = name;
        this.operator = operator;
        this.percentage = percentage;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getOperator() {
        return operator;
    }

    public void setOperator(String operator) {
        this.operator = operator;
    }

    public Double getPercentage() {
        return percentage;
    }

    public void setPercentage(Double percentage) {
        this.percentage = percentage;
    }
}
