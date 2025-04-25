<?php

class LogoutController extends Controller{

    public function index(){
        session_destroy();
        header('Location: http://localhost:8000/');
        exit;
    }
}