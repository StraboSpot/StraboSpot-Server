<?php

class HtmlView extends ApiView {
    public function render($content) {
        header('Content-Type: application/html; charset=utf8');
        print_r($content);
        return true;
    }
}
