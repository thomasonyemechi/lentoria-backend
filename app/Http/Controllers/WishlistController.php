<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        Wishlist::updateOrCreate([
            'user_id' => auth()->user()->id,
            'course_id' => $request->course_id,
        ]);

        return response(['message' => 'Item added to Wishlist'], 200);
    }

    public function userWishList()
    {
        $whishlist = Wishlist::mycourse()->paginate(100);

        return response(['data' => $whishlist], 200);
    }

    // public function userWishList2()
    // {
    //     $wishlist = Course::whereIn('id', function ($query) {
    //         $query->select('course_id')->from('wishlists')->where('user_id', auth()->id());
    //     })->paginate(100);

    //     return response(['data' => $wishlist], 200);
    // }

    public function deleteFromWishlist(Request $request)
    {
        Wishlist::destroy($request->id);

        return response(['message' => 'Item removed from wishlist'], 200);
    }
}
