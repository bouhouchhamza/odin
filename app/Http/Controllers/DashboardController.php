<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $linksCount = Link::where('user_id', $userId)->count();
        $categoriesCount = Category::where('user_id', $userId)->count();

        // عدد التاغات المستعملة فـ روابط ديالك (via pivot)
        $tagsUsedCount = Tag::whereHas('links', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        // آخر 5 روابط
        $latestLinks = Link::where('user_id', $userId)
            ->with(['category', 'tags'])
            ->latest()
            ->take(5)
            ->get();

        // Top categories (عدد الروابط فكل كاتيجوري)
        $topCategories = Category::where('user_id', $userId)
            ->withCount('links')
            ->orderByDesc('links_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'linksCount',
            'categoriesCount',
            'tagsUsedCount',
            'latestLinks',
            'topCategories'
        ));
    }
}
