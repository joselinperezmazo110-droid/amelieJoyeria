<?php
class HomeController extends Controller {
    public function index() {
        $products = [
            ['id'=>1,'name'=>'Anillo de oro','price'=>150],
            ['id'=>2,'name'=>'Collar plata','price'=>80],
        ];
        $this->render('home/index', ['products' => $products]);
    }
}
