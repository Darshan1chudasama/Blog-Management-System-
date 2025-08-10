<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Edit Blog</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .navbar a {
            color: black;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }


        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        button {
            background-color: blue;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: lightblue;
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .preview-img {
            max-width: 100%;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">📝 My Blog System</div>
        <div>
            <a href="#">Home</a>
            <a href="#">Logout</a>
        </div>
    </div>
    <header>
        <h1>Edit Blog</h1>
    </header>
    <div class="container" id="updateBlogModel">
        <form id="editBlogForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="id" name="id">

            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Blog Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label>Current Image</label>
                <img src="#" alt="Current Blog Image" id="currentImage" class="preview-img">
            </div>

            <div class="form-group">
                <label for="image">Change Image (optional)</label>
                <input type="file" id="image" name="image">
            </div>

            <button type="submit">💾 Save Changes</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const blogId = getBlogIdFromUrl();
            const token = localStorage.getItem('token');

            if (!blogId) {
                alert("Blog ID not found in URL");
                return;
            }

            try {
                const res = await fetch(`/api/get-blogs/${blogId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.data.blog.length > 0) {
                    const blog = data.data.blog[0];
                    document.getElementById('id').value = blog.id;
                    document.getElementById('title').value = blog.title;
                    document.getElementById('description').value = blog.description;
                    document.getElementById('currentImage').src = `/uploads/${blog.image}`;
                } else {
                    alert("Blog not found");
                }
            } catch (err) {
                console.error("Error fetching blog:", err);
            }
        });

        function getBlogIdFromUrl() {
            const parts = window.location.pathname.split('/');
            return parts[parts.length - 1] || null;
        }


        document.getElementById('editBlogForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const blogId = document.getElementById('id').value;
            const token = localStorage.getItem('token');

            let formData = new FormData(this);

            try {
                const res = await fetch(`/api/blogs/${blogId}`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (res.ok) {
                    // alert("✅ Blog updated successfully!");
                    swal("Success", data.message || "Blog updated successfully!", "success");
                    window.location.href = "http://127.0.0.1:8000/blog"; // Redirect to blog list page
                } else {
                    // alert("❌ " + (data.message || "Something went wrong"));
                    swal("Error", data.message || "Something went wrong", "error");
                    console.error(data.errors || data);
                }
            } catch (error) {
                swal("Error", "Something went wrong", "error");
            }
        });
    </script>
</body>

</html>
