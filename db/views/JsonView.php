<?php

class JsonView extends ApiView {
    public function render($content) {
        header('Content-Type: application/json');
        echo json_encode($content);
        return true;
    }
}
