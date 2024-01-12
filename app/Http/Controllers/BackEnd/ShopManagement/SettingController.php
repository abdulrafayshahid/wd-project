<?php

namespace App\Http\Controllers\BackEnd\ShopManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopManagement\ShippingChargeRequest;
use App\Models\Language;
use App\Models\ShopManagement\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
  public function index(Request $request)
  {

    $lang = Language::where('code', $request->language)->firstOrFail();
    $lang_id = $lang->id;
    $data['collection'] = ShippingCharge::where('language_id', $lang_id)->orderBy('id', 'DESC')->get();
    $data['lang_id'] = $lang_id;
    $data['langs'] = Language::get();
    return view('backend.product.shop_setting.index', $data);
  }
  //store
  public function store(ShippingChargeRequest $request)
  {
    $in = $request->all();
    $store = ShippingCharge::create($in);
    Session::flash('success', 'Shipping Charge added successfully!');
    return response()->json(['status' => 'success'], 200);
  }
  //delete
  public function delete(Request $request)
  {
    $delete = ShippingCharge::where('id', $request->id)->first();
    $delete->delete();
    Session::flash('warning', 'Shipping Charge deleted successfully!');
    return back();
  }
  //bulkdelete
  public function bulkdelete(Request $request)
  {
    foreach ($request->ids as $id) {
      $delete = ShippingCharge::where('id', $id)->first();
      $delete->delete();
    }
    Session::flash('warning', 'Shipping Charges are deleted successfully!');
    return response()->json(['status' => 'success'], 200);
  }
  //update
  public function update(Request $request)
  {
    $in = $request->all();
    $update = ShippingCharge::where('id', $request->id)->first();
    $update->update($in);
    Session::flash('success', 'Shipping Charges Updated successfully!');
    return response()->json(['status' => 'success'], 200);
  }
}
