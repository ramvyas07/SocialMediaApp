<?php
session_start();
require("nav.php");


$user_id = get_user_id();
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
}

//logged in user:
$logged_user = get_username();

$isMe = $user_id == get_user_id();
require(__DIR__ . "/../lib/db.php");
$user_id = mysqli_real_escape_string($db, $user_id);
$query = "SELECT email, username,password, created, visibility,role FROM rv8_users where id = $user_id";
if (!$isMe) {
    $query .= " AND visibility > 0";
}

$retVal = mysqli_query($db, $query);
$result = [];
if ($retVal) {
    $result = mysqli_fetch_array($retVal, MYSQLI_ASSOC);
}


?>


<head>
    <link rel="stylesheet" href="../styles/home.css">
</head>

<body>

    <?php if ($isMe) : ?>
    <?php if ($result['role'] == 'admin') : ?>


    <div class="main_admin">
        <aside class="search">
            <h3> Find People </h3>
            <form method="GET">
                <input class="search_people" type="text" name="searchbar" placeholder="Search for Friend">
                <input class="btn-search" type="submit" name="submitSearch" value="Search">
            </form>
            <?php searchUser($db); ?>
        </aside>

        <main>
            <div id="insert_post">
                <h1 id='page-title'>Admin Page</h1>
                <section id='main-sec'>
                    <form action="home.php?id=<?php echo $isMe; ?>" method="post" id="postForm"
                        enctype="multipart/form-data">
                        <textarea class="form-control" id="content" rows="4" name="content"
                            placeholder="share your thoughts"></textarea>
                        <div class="add_post">
                            <input id="image_upload" type="file" name="upload_image" size="30">
                            <button id="btn-post" name="submitPost">Post</button>
                        </div>
                    </form>
                    <?php insertPost($db); ?>
                    <br>
                    <?php getPostAdmin($db); ?>
                </section>
            </div>
        </main>
        <aside class="api">
            <h1 id='vin-title'>VIN SEARCH</h1>
            <section>
                <link rel="stylesheet" href="../styles/api.css" />
                <script defer src="../js/vin.js"></script>
                <div class="container">
                    <form action="">
                        <input class="search_people" id="vin" type="text" />
                        <button id="sbmt-btn">Submit</button>
                    </form>
                    <div id="res"></div>
                </div>
            </section>
        </aside>
    </div>
    <?php endif; ?>


    <?php if ($result['role'] != 'admin') : ?>


    <div class="main_reg">
        <aside class="search">
            <h3> Find People </h3>
            <form method="GET">
                <input class="search_people" type="text" name="searchbar" placeholder="Search for Friend">
                <input class="btn-search" type="submit" name="submitSearch" value="Search">
            </form>
            <?php searchUser($db); ?>
        </aside>

        <main>
            <div id="insert_post">
                <h1 id='page-title'><?php echo $logged_user; ?> Page</h1>
                <section>
                    <form action="home.php?id=<?php echo $user_id; ?>" method="post" id="postForm"
                        enctype="multipart/form-data">
                        <textarea class="form-control" id="content" rows="4" name="content"
                            placeholder="share your thoughts"></textarea>
                        <div class="add_post">
                            <input id="image_upload" type="file" name="upload_image" size="30">
                            <button id="btn-post" name="submitPost">Post</button>
                        </div>
                    </form>
                    <?php insertPost($db); ?>
                    <br>
                    <?php getPost($db); ?>
                </section>
            </div>
        </main>

        <aside class="api">
            <h1 id='vin-title'>VIN SEARCH</h1>
            <section>
                <link rel="stylesheet" href="../styles/api.css" />
                <script defer src="../js/vin.js"></script>
                <div class="container">
                    <form action="">
                        <input class="search_people" id="vin" type="text" />
                        <button id="sbmt-btn">Submit</button>
                    </form>
                    <div id="res"></div>
                </div>
            </section>
        </aside>
    </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</body>
