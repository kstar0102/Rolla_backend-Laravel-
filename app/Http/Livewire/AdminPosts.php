<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AdminPost;
use Illuminate\Support\Facades\Auth;

class AdminPosts extends Component
{
    public $posts;

    public function mount()
    {
        $this->posts = AdminPost::with('admin')->orderBy('created_at', 'desc')->get();
    }

    public function remove($id)
    {
        $post = AdminPost::find($id);
        if ($post) {
            $post->delete();
            $this->posts = AdminPost::with('admin')->orderBy('created_at', 'desc')->get();
            session()->flash('message', 'Admin post deleted successfully.');
        }
    }

    public function toggleActive($id)
    {
        $post = AdminPost::find($id);
        if ($post) {
            $post->is_active = !$post->is_active;
            $post->save();
            $this->posts = AdminPost::with('admin')->orderBy('created_at', 'desc')->get();
            session()->flash('message', 'Admin post status updated successfully.');
        }
    }

    public function render()
    {
        return view('livewire.admin-posts');
    }
}
