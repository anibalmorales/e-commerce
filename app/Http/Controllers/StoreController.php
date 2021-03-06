<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    private function saveCart($cart)
    {
        session(["cart" => $cart]);
    }

    private function getCart()
    {
        return session("cart") ?? [];
    }

    public function emptyCart()
    {
        $this->saveCart([]);
        return redirect()->back()->with("message", __("messages.cart_empty"));
    }

    public function removeFromCart(Request $request)
    {
        $index = $request->input("index");
        $cart = $this->getCart();
        array_splice($cart, $index, 1);
        $this->saveCart($cart);
        return redirect()->back()->with("message", __("messages.removed_from_cart"));
    }

    public function viewCart(Request $request)
    {
        $cart = $this->getCart();
        return view("store.view_cart", ["cart" => $cart]);
    }

    public function addToCart(Product $product, Request $request)
    {
        $cart = $this->getCart();
        $product->quantity = $request->post("quantity");
        array_push($cart, $product);
        $this->saveCart($cart);
        return redirect()->back()->with("message", __("messages.added_to_cart"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = "";
        $categoryId = -1;

        if ($request->get("search")) {
            $search = $request->get("search");
        }
        if ($request->get("category")) {
            $categoryId = $request->get("category");
        }
        // By default, we get all the available products
        $builder = Product::where("stock", ">", 0);
        // If there's category and search
        if ($search && $categoryId != -1) {
            $builder->whereRaw("(name like ? or description like ? ) and category_id = ?",
                ["%$search%", "%$search%", $categoryId]);
        } else if ($search && $categoryId == -1) {
            // If there's only search, but no category
            $builder->whereRaw("(name like ? or description like ?)",
                ["%$search%", "%$search%"]);
        } else if (!$search && $categoryId != -1) {
            // If there's only category
            $builder->whereRaw("category_id = ?",
                [$categoryId]);
        }
        // Anyway, we paginate them
        $products = $builder->paginate(config("project.products_per_page"));
        return view("store.store_index", [
            "search" => $search,
            "categoryId" => $categoryId,
            "categories" => Category::all(),
            "products" => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
