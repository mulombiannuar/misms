return '
<div class="margin">
    <div class="btn-group">
        <a href="/admin/users/'.$user->id.'" class="btn btn-xs btn-info"><i class="fa fa-bars"></i> Show</a>
    </div>
    <div class="btn-group">
        <a href="/admin/users/'.$user->id.'/edit" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>
    </div>
    <div class="btn-group">
        <form action="'.route('admin.users.destroy', $user->id).'" method="post">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="'.csrf_token().'">
            <button type="submit" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i>
                Delete
            </button>
        </form>
    </div>
</div>
';
