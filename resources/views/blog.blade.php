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
            margin-top: 4px;

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


        #filterSelect {
            padding: 8px 14px;
            font-size: 14px;
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            outline: none;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%23666' height='12' viewBox='0 0 24 24' width='12' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }

        /* Hover effect */
        #filterSelect:hover {
            border-color: #888;
        }

        /* Focus effect */
        #filterSelect:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 5px rgba(74, 144, 226, 0.4);
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
        <div id="welcomeUser"></div>
    </div>
    <header>
        <h1>Blog Management System</h1>
    </header>



    <div class="container">

        <div class="actions">
            <select id="filterSelect">
                <option value="">Sort By</option>
                <option value="latest">Latest Blogs</option>
                <option value="most_liked">Most Liked</option>
            </select>
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
    //pangination of page
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
    // loaddata
    function loadData(page = 1) {
        const token = localStorage.getItem('token');
        const search = document.getElementById('searchBar').value;
        const filter = document.getElementById('filterSelect')?.value || '';

        fetch(`/api/get-blogs?page=${page}&search=${search}&filter=${filter}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.message === "Unauthenticated.") {
                    alert("Please login to view blogs.");
                    window.location.href = '/login';
                    return;
                }

                if (!data || !data.data || !data.data.blogs) {
                    throw new Error('Invalid API response');
                }

                const blogs = data.data.blogs.data ?? data.data.blogs;
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
                        <a href="/editBlog/${blog.id}">
                            <button type="button" class="edit-btn">✏️ Edit</button>
                        </a>
                        <button onclick="deleteBlog(${blog.id})" class="delete-btn">🗑️ Delete</button>
                    </div>
                </div>
            `;
                });

                html += renderpagination(pagination);

                blogListContainer.innerHTML = html;

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

    // First load
    loadData();

    // Real-time search
    document.getElementById('searchBar').addEventListener('input', () => loadData());

    // Filter change
    document.getElementById('filterSelect')?.addEventListener('change', () => loadData());

    

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

    //like
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
                if (response.is_liked_by_user) {
                    btn.addClass('liked');
                } else {
                    btn.removeClass('liked');
                }
            }
        });
    });



    //search bar
    document.querySelector("#searchBar").addEventListener("keyup", function() {
        let query = this.value.trim();
        const token = localStorage.getItem('token');

        fetch(`/api/get-blogs?search=${encodeURIComponent(query)}`, {
                headers: {
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => response.json())
            .then(res => {
                if (!res.status || !res.data || !res.data.blogs) {
                    document.getElementById("blogList").innerHTML = `<p>No blogs found.</p>`;
                    return;
                }

                let blogs = res.data.blogs; // ✅ Correct data path
                let html = '';

                blogs.forEach(blog => {
                    let likedClass = blog.isLikedByUser ? 'liked' : '';
                    let likesCount = blog.likes_count ?? 0;

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
                    <a href="/editBlog/${blog.id}">
                        <button type="button" class="edit-btn">✏️ Edit</button>
                    </a>
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

    // show user name 
    document.addEventListener("DOMContentLoaded", function() {
        const name = localStorage.getItem('name');
        if (name) {
            document.getElementById("welcomeUser").innerText = `Welcome, ${name} 👋`;
        }
    });
</script>
