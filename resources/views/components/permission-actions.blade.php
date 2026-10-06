<a href="javascript:void(0)" class="btn btn-warning btn-xs" 
   wire:click="edit({{ $permission->id }})">
   <i class="fas fa-edit"></i>
</a>

<form wire:submit.prevent="delete({{ $permission->id }})" 
      style="display:inline-block;">
    @csrf
    <button type="submit" class="btn btn-danger btn-xs">
        <i class="fa fa-trash"></i>
    </button>
</form>
