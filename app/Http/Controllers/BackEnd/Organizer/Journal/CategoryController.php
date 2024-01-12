<?php

namespace App\Http\Controllers\BackEnd\Journal;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryRequest;
use App\Models\Journal\BlogCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['categories'] = BlogCategory::where('language_id', $language->id)->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.journal.category.index', $information);
  }

  public function store(BlogCategoryRequest $request)
  {
    $ins = $request->all();
    $ins['slug'] = createSlug($request->name);
    BlogCategory::create($ins);

    Session::flash('success', 'New blog category added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function update(BlogCategoryRequest $request)
  {
    $ins = $request->all();
    $ins['slug'] = createSlug($request->name);
    BlogCategory::find($request->id)->update($ins);

    Session::flash('success', 'Blog category updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $category = BlogCategory::find($id);

    if ($category->blogInfo()->count() > 0) {
      return redirect()->back()->with('warning', 'First delete all the blog related to this category!');
    } else {
      $category->delete();

      return redirect()->back()->with('success', 'Blog category deleted successfully!');
    }
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $category = BlogCategory::find($id);

      if ($category->blogInfo()->count() > 0) {
        Session::flash('warning', 'First delete all the blog related to this categories!');
        break;
      } else {
        $category->delete();

        Session::flash('success', 'Blog categories deleted successfully!');
      }
    }

    return response()->json(['status' => 'success'], 200);
  }
}
