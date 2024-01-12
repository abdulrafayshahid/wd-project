<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\OrganizerUser;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Admin\StoreRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Models\Organizer;
use App\Models\RolePermission;
use Carbon\Carbon;

class OrganizerUserController extends Controller
{
  public function index()
  {
    $information['admins'] = Organizer::where('created_by',auth()->user()->id)->get();
    return view('organizer.site-user.index', $information);
  }

  public function store(StoreRequest $request)
  {
    
    $imageName = UploadFile::store(public_path('assets/admin/img/organizer-photo/'), $request->file('image'));

    
$organizerData = $request->except('image', 'password');
$organizerData['photo'] = $imageName;
$organizerData['password'] = Hash::make($request->password);
$organizerData['email_verified_at'] = Carbon::now()->format('Y-m-d H:i:s');

Organizer::create($organizerData);

    $request->session()->flash('success', 'New User added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateStatus(Request $request, $id)
  {
    $admin = Organizer::find($id);

    if ($request->status == 1) {
      $admin->update(['status' => 1]);
    } else {
      $admin->update(['status' => 0]);
    }

    $request->session()->flash('success', 'Status updated successfully!');

    return redirect()->back();
  }

  public function update(UpdateRequest $request)
  {
    $admin = Organizer::find($request->id);

    if ($request->hasFile('photo')) {
      $imageName = UploadFile::update(public_path('assets/admin/img/organizer-photo/'), $request->file('photo'), $admin->photo);
    }

    $admin->update($request->except('photo') + [
      'photo' => $request->hasFile('photo') ? $imageName : $admin->photo
    ]);

    $request->session()->flash('success', 'User updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $admin = Organizer::find($id);

    // delete admin profile picture
    @unlink(public_path('assets/admin/img/organizer-photo/') . $admin->image);

    $admin->delete();

    return redirect()->back()->with('success', 'User deleted successfully!');
  }
}