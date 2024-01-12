<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add User') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $roles = \App\Models\RolePermission::where('user_id',auth()->user()->id)->get(); ?>
      <div class="modal-body">
        <form id="ajaxForm" class="modal-form" action="{{ route('organizer.organizer_management.user.store') }}" method="post">
          @csrf
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label for="">{{ __('Name') . '*' }}</label>
                <input type="text" class="form-control" name="name" placeholder="Enter User Name">
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
              <div class="form-group">
                <label for="">{{ __('Email') . '*' }}</label>
                <input type="text" class="form-control" name="email" placeholder="Enter User Email">
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
              <div class="form-group">
                <label for="">{{ __('Password') . '*' }}</label>
                <input type="password" class="form-control" name="password" placeholder="Enter User Password">
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
              <div class="form-group">
                <label for="">{{ __('Role') . '*' }}</label>
                <select name="role_id"  class="form-control">
                  <option value="">Select Role</option>
                  @foreach($roles as $role)
                  <option value="{{$role->id}}">{{$role->name}}</option>
                  @endforeach
                </select>
                <p id="err_name" class="mt-2 mb-0 text-danger em"></p>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
          {{ __('Close') }}
        </button>
        <button id="submitBtn" type="button" class="btn btn-primary btn-sm">
          {{ __('Save') }}
        </button>
      </div>
    </div>
  </div>
</div>
