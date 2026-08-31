<?php
class ProductController extends Controller {
    public function index() {
        $productModel = $this->loadModel('Product');
        $products = $productModel ? $productModel->all() : [];
        $this->render('products/index', ['products' => $products]);
    }
}
