<?php

require_once __DIR__ . '/../models/MenuModel.php';

class MenuController{
    
    public function index(){
        
        $model = new MenuModel();
        $productos = $model -> obtenerProducto();
        require_once __DIR__ . '/../views/menu/index.php';
    }

}



?>