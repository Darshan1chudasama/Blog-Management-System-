<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::all(); // or with likes: Blog::with('likes')->get();
        return view('blog', compact('blogs'));

        
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,gif,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $img = $request->image;
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . "." . $ext;
        $img->move(public_path() . '/uploads', $imageName);

        $blog = Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
            'user_id'     => auth()->id(),
        ]);

        return response()->json(['message' => 'Blog created successfully'], 201);
    }
    public function show(string $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json([
            'data' => [
                'blog' => [$blog] // keeping same array format as your existing code
            ]
        ]);
    }
    public function update(Request $request, string $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json([
                'status' => false,
                'message' => 'Blog not found'
            ], 404);
        }

        // Handle image update
        if ($request->hasFile('image')) {
            $path = public_path('uploads/');
            if ($blog->image && file_exists($path . $blog->image)) {
                unlink($path . $blog->image);
            }
            $img = $request->file('image');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $img->move($path, $imageName);
            $blog->image = $imageName;
        }

        // Update other fields
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->save();

        return response()->json([
            'status' => true,
            'message' => 'Blog updated successfully',
            'data' => $blog
        ], 200);
    }
    public function destroy(string $id)
    {
        // Find blog post by id
        $post = Blog::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        // Delete image file if exists
        $filePath = public_path('uploads/' . $post->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete blog post
        $post->delete();

        return response()->json(['message' => 'Your post has been removed'], 200);
    }

    public  function addBlog()
    {
        return view('addBlog');
    }
    public function editPage()
    {
        return view('editBlog');
    }
    public function getBlog()
    {
        $userId = auth()->id();

        $blogs = Blog::latest()->paginate(3);

        $blogs->getCollection()->transform(function ($blog) use ($userId) {
            $blog->isLikedByUser = $blog->isLikedByUser($userId);
            $blog->likesCount = $blog->likeCount();
            return $blog;
        });

        return response()->json([
            'data' => [
                'blogs' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'total' => $blogs->total(),
                ]
            ]
        ]);
    }
    public function toggleLike($id)
    {
        $blog = Blog::find($id);
        $user = auth()->user();

        if ($blog->likes()->where('user_id', $user->id)->exists()) {
            $blog->likes()->where('user_id', $user->id)->delete();
        } else {
            $blog->likes()->create(['user_id' => $user->id]);
        }

        return response()->json([
            'likes_count' => $blog->likeCount(),
            'is_liked_by_user' => $blog->isLikedByUser($user->id)
        ]);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        $posts = Blog::withCount('likes') // get total likes
            ->with(['likes' => function ($q) {
                $q->where('user_id', auth()->id());
            }])
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get()
            ->map(function ($blog) {
                $blog->is_liked_by_user = $blog->likes->isNotEmpty();
                unset($blog->likes); // clean unnecessary data
                return $blog;
            });

        return response()->json($posts);
    }
}
