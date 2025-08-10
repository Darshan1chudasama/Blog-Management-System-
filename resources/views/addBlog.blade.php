<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Add Blog</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            padding: 0;
        }

        .navbar {
            /* background-color: #2c3e50; */
            padding: 10px 20px;
            /* color: white; */
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
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 5px;
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
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="logo">📝 My Blog System</div>
        <div>
            <a href="/blog">Home</a>
        </div>
    </div>
    <header>
        <h1>Add New Blog</h1>
    </header>
    <div class="container">
        <form id="addBlogForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Blog Title</label>
                <input type="text" id="title" name="title" placeholder="Enter blog title" required>
            </div>

            <div class="form-group">
                <label for="description">Blog description</label>
                <textarea id="description" name="description" placeholder="Write your blog here..." required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Upload Image (optional)</label>
                <input type="file" id="image" name="image">
            </div>

            <button type="submit">➕ Submit Blog</button>
        </form>

    </div>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.querySelector('#addBlogForm').onsubmit = async (e) => {
        e.preventDefault();

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                alert("❌ Please log in first.");
                return;
            }

            const title = document.querySelector('#title').value;
            const description = document.querySelector('#description').value;
            const image = document.querySelector('#image').files[0];

            const formData = new FormData();
            formData.append('title', title);
            formData.append('description', description);
            if (image) formData.append('image', image);

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const response = await fetch('/api/blogs', {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            let data;
            try {
                data = await response.json();
            } catch {
                data = {};
            }

            if (!response.ok) {
                console.error("❌ Error response:", data);
                alert(`Error: ${data.message || 'Unknown error'}`);
                return;
            }

            // alert('✅ Blog submitted successfully!');
            swal("Success", data.message || "Blog submitted successfully!", "success");
            // Redirect *without* calling loadData() here
            window.location.href = "/blog";

        } catch (error) {
            console.error('🚨 Unexpected Error:', error);
            // alert('Failed to submit the blog. Please try again.');
            swal("Error", "Failed to submit the blog. Please try again.", "error");
        }
    };
</script>
