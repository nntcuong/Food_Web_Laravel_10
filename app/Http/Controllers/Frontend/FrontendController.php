<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SectionTitle;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Collection;

use Illuminate\View\View;

class FrontendController extends Controller
{
    function index():View{
        $sectionTitles=$this->getSectionTitles();
        $sliders=Slider::where('status',1)->get();
        $whyChooseUs=WhyChooseUs::where('status',1)->get();
        $categories=Category::where(['show_at_home'=>1,'status'=>1])->get();
        return view('frontend.home.index',compact('sliders','sectionTitles','whyChooseUs','categories'));
    }
    function getSectionTitles():Collection{
        
        $keys=[
            'why_choose_top_title',
            'why_choose_main_title',
            'why_choose_sub_title'];
        return SectionTitle::whereIn('key',$keys)->pluck('value','key');
    }
    function showProduct(string $slug): View
    {
        $product = Product::with(['productImages','productSizes','productOptions'])->where(['slug' => $slug, 'status' => 1])->first();
        if (!$product) {
            abort(404);
        }
        $relatedProducts=Product::where('category_id',$product->category_id)->where('id','!=',$product->id)->take(8)->latest()->get();
        return view('frontend.pages.product-view', compact('product','relatedProducts'));
    }
    function loadProductModal($productId){
        $product=Product::with(['productSizes','productOptions'])->findOrFail($productId);
        return view('frontend.layouts.ajax-files.product-popup-modal', compact('product'))->render();
    }

    function applyCoupon(Request $request) {

        $subtotal = $request->subtotal;
        $code = $request->code;

        $coupon = Coupon::where('code', $code)->first();

        if(!$coupon) {
            return response(['message' => 'Invalid Coupon Code.'], 422);
        }
        if($coupon->quantity <= 0){
            return response(['message' => 'Coupon has been fully redeemed.'], 422);
        }
        if($coupon->expire_date < now()){
            return response(['message' => 'Coupon hs expired.'], 422);
        }

        if($coupon->discount_type === 'percent') {
            $discount = number_format($subtotal * ($coupon->discount / 100), 2);
        }elseif ($coupon->discount_type === 'amount'){
            $discount = number_format($coupon->discount, 2);
        }

        $finalTotal = $subtotal - $discount;

        session()->put('coupon', ['code' => $code, 'discount' => $discount]);

        return response(['message' => 'Coupon Applied Successfully.', 'discount' => $discount, 'finalTotal' => $finalTotal, 'coupon_code' => $code]);

    }

    function destroyCoupon() {
        try{
            session()->forget('coupon');
            return response(['message' => 'Coupon Removed!', 'grand_cart_total' => grandCartTotal()]);
        }catch(\Exception $e){
            logger($e);
            return response(['message' => 'Something went wrong']);

        }
    }
}
