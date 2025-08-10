<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title>Blog Management System</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: #f4f4f4;
            color: #333;
        }

        .navbar {
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            padding: 0;
            margin: 0;
        }

        .navbar ul li {
            margin: 0 15px;
        }

        .navbar ul li a {
            color: black;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .navbar ul li a:hover {
            color: #f39c12;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .actions a {
            text-decoration: none;
        }

        .actions select,
        .actions button {
            padding: 10px 15px;
            margin: 5px 0;
            border: none;
            border-radius: 5px;
        }

        .actions button {
            background-color: #3498db;
            color: white;
            cursor: pointer;
        }

        .actions button:hover {
            background-color: #2980b9;
        }

        .blog-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .blog-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .blog-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .blog-card h3 {
            margin: 0 0 10px 0;
        }

        .blog-card p {
            font-size: 14px;
            color: #555;
        }

        .blog-card .blog-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .blog-card .blog-actions button {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
        }

        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px;
            transition: transform 0.15s ease;
        }

        .like-btn:active {
            transform: scale(1.2);
        }

        .heart-icon {
            width: 24px;
            height: 24px;
            fill: #aaa;
            /* default gray */
            transition: fill 0.25s ease, transform 0.25s ease;
        }

        .like-btn.liked .heart-icon {
            fill: #e0245e;
            /* Instagram red/pink */
            transform: scale(1.3);
        }

        .likes-count {
            font-size: 14px;
            color: #333;
        }


        .edit-btn {
            background-color: blue;
            color: white;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .search-container {
            position: relative;
            width: 300px;
            margin: 10px auto;
        }

        .search-container .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 16px;
            pointer-events: none;
            /* Prevent click on icon */
        }

        .search-container input {
            width: 100%;
            padding: 8px 12px 8px 38px;
            /* space for icon */
            border: 1px solid #ccc;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: 0.3s border-color, 0.3s box-shadow;
        }

        .search-container input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.4);
        }

        .pagination {
            margin-top: 20px;
        }

        .page-btn {
            padding: 5px 10px;
            margin: 3px;
            border: 1px solid #ccc;
            background: #f8f8f8;
            cursor: pointer;
        }

        .page-btn.active {
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">📝 My Blog System</div>
        <div class="search-container">
            <i class="search-icon fas fa-search"></i>
            <input type="text" id="searchBar" class="form-control" placeholder="Search blogs...">
        </div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="/addBlog">Add Blog</a></li>
        </ul>
    </div>
    <header>
        <h1>Blog Management System</h1>
    </header>



    <div class="container">

        <div class="actions">
            <button style="margin: 20px" onclick="window.location.href='/addBlog'">➕ Add Blog</button>

        </div>

        <div class="blog-list" id="blogList">

        </div>
        <div id="paginationContainer"></div>
    </div>

</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    //loadData
    function loadData(page = 1) {
        const token = localStorage.getItem('token');

        fetch(`http://127.0.0.1:8000/api/get-blogs?page=${page}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message === "Unauthenticated.") {
                    alert("Please login to view blogs.");
                    window.location.href = '/login';
                    return;
                }

                if (!data || !data.data || !data.data.blogs) {
                    throw new Error('Invalid API response structure');
                }

                const blogs = data.data.blogs;
                const pagination = data.data.pagination;
                const blogListContainer = document.getElementById('blogList');

                let html = '';

                blogs.forEach(blog => {
                    const likedClass = blog.isLikedByUser ? 'liked' : '';
                    const likesCount = blog.likesCount || 0;

                    html += `
                <div class="blog-card">
                    <img src="/uploads/${blog.image}" alt="Blog Image">
                    <h3>${blog.title}</h3>
                    <p>${blog.description}</p>
                    <div class="blog-actions">
                        <button type="button"
                            class="like-btn ${likedClass}"
                            data-blog-id="${blog.id}">
                            ❤️ <span class="likes-count">${likesCount}</span>
                        </button>
                        <a href="/editBlog/${blog.id}"><button type="button" class="edit-btn">✏️ Edit</button></a>
                        <button onclick="deleteBlog(${blog.id})" class="delete-btn">🗑️ Delete</button>
                    </div>
                </div>
            `;
                });

                // Add pagination buttons below blogs
                html += renderpagination(pagination);

                blogListContainer.innerHTML = html;

                // ✅ Attach event listeners after rendering
                document.querySelectorAll('.page-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const selectedPage = this.getAttribute('data-page');
                        loadData(selectedPage);
                    });
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('blogList').innerHTML =
                    `<p style="color:red;">Error loading blogs: ${error.message}</p>`;
            });
    }

    function renderpagination(pagination) {
        let html = `<div class="pagination">`;

        for (let page = 1; page <= pagination.last_page; page++) {
            html += `
            <button 
                class="page-btn ${pagination.current_page === page ? 'active' : ''}" 
                data-page="${page}">
                ${page}
            </button>`;
        }

        html += `</div>`;
        return html;
    }

    loadData();



    //update Data show in flid
    document.addEventListener('DOMContentLoaded', async function() {
        const blogId = getBlogIdFromUrl();
        const token = localStorage.getItem('token');
        console.log("📦 Retrieved Token:", token);

        if (!blogId) {
            alert("Blog ID not found in URL");
            return;
        }
        console.log("📦 Blog ID:", blogId);

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

    // Extract blog ID from the URL (/editBlog/{id})
    function getBlogIdFromUrl() {
        const parts = window.location.pathname.split('/');
        return parts[parts.length - 1] || null;
    }


    //delete
    async function deleteBlog(blogId) {
        const token = localStorage.getItem('token');

        if (!token) {
            swal("Error", "User not authenticated. Please login.", "error");
            return;
        }

        try {
            const response = await fetch(`http://127.0.0.1:8000/api/blogs/${blogId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(data.message || 'Failed to delete blog');
            }

            swal("Success", data.message || "Blog deleted successfully!", "success");

            setTimeout(() => {
                window.location.href = "http://127.0.0.1:8000/blog";
            }, 1000);

        } catch (error) {
            console.error('Delete error:', error);
            swal("Error", error.message || "Failed to delete blog!", "error");
        }
    }


    //link
    $(document).on('click', '.like-btn', function() {
        let blogId = $(this).data('blog-id');
        let btn = $(this);

        $.ajax({
            url: `/api/blogs/${blogId}/toggle-like`,
            type: 'POST',
            headers: {
                "Authorization": "Bearer " + localStorage.getItem("token"),
                "Accept": "application/json"
            },
            success: function(response) {
                btn.find('.likes-count').text(response.likes_count);
                btn.toggleClass('liked', response.is_liked_by_user);
            }
        });
    });


    //search bar
    document.querySelector("#searchBar").addEventListener("keyup", function() {
        let query = this.value;

        fetch(`/search?query=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                let html = '';
                data.forEach(blog => { // <-- use data instead of blog
                    html += `
                    <div class="blog-card">
                        <img src="/uploads/${blog.image}" alt="Blog Image">
                        <h3>${blog.title}</h3>
                        <p>${blog.description}</p>
                        <div class="blog-actions">
                            <button type="button"
                                class="like-btn ${blog.is_liked_by_user ? 'liked' : ''}"
                                data-blog-id="${blog.id}">
                                <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                                    2 5.42 4.42 3 7.5 3
                                    c1.74 0 3.41.81 4.5 2.09 
                                    C13.09 3.81 14.76 3 16.5 3
                                    19.58 3 22 5.42 22 8.5 
                                    c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                                <span class="likes-count">${blog.likes_count ?? 0}</span>
                            </button>
                            <a href="/editBlog/${blog.id}"><button type="button" class="edit-btn">✏️ Edit</button></a>
                            <button onclick="deleteBlog(${blog.id})" class="delete-btn">🗑️ Delete</button>
                        </div>
                    </div>`;
                });
                document.getElementById("blogList").innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('blogList').innerHTML =
                    `<p style="color:red;">Error loading blogs: ${error.message}</p>`;
            });
    });
</script>
