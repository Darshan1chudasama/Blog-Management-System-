<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    //blog show homepage
    public function index()
    {
        $blogs = Blog::with('likes')->get();
        return view('blog', compact('blogs'));
    }
    // blog store
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

    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }

        return response()->json([
            'data' => [
                'blog' => [$blog]  // wrap in array
            ]
        ]);
    }


    // blog update
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

    // blog delete
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

    // show add blog page
    public  function addBlog()
    {
        return view('addBlog');
    }

    // show edit blog page
    public function editPage()
    {
        return view('editBlog');
    }

    // blog list 
    public function getBlog(Request $request)
    {
        $user = $request->user();

        $query = Blog::withCount('likes')
            ->with(['likes' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // 🔍 Search filter
        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // 📌 Sorting filter
        if ($request->has('filter')) {
            if ($request->filter === 'most_liked') {
                $query->orderBy('likes_count', 'desc');
            } elseif ($request->filter === 'latest') {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 📄 Pagination
        $blogs = $query->paginate(3);

        // 🎯 Transform data
        $blogs->getCollection()->transform(function ($blog) use ($user) {
            $blog->isLikedByUser = $blog->likes->isNotEmpty();
            $blog->likesCount = $blog->likes_count; // camelCase for frontend
            unset($blog->likes, $blog->likes_count); // Remove unused keys
            return $blog;
        });

        return response()->json([
            'status' => true,
            'message' => 'Blogs fetched successfully',
            'data' => [
                'blogs' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total()
                ]
            ]
        ]);
    }

    // blog like
    public function toggleLike($id, Request $request)
    {
        $user = $request->user();
        $blog = Blog::find($id);

        $alreadyLiked = $blog->likes()->where('user_id', $user->id)->exists();

        if ($alreadyLiked) {
            // Unlike
            $blog->likes()->where('user_id', $user->id)->delete();
            $isLiked = false;
            $message = 'Blog unliked successfully';
        } else {
            // Like
            $blog->likes()->create([
                'user_id' => $user->id
            ]);
            $isLiked = true;
            $message = 'Blog liked successfully';
        }

        $blog->loadCount('likes');

        return response()->json([
            'status' => true,
            'message' => $message,
            'likes_count' => $blog->likes_count,
            'is_liked_by_user' => $isLiked
        ]);
    }
}
