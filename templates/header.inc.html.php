<!DOCTYPE html>
<html>
    <head>
        <title>Tiny GuestBook</title>
<!--        <script src="/static/js/jquery-2.0.3.min.js"></script>-->
        <script src="/static/js/routie.min.js"></script>
        <script src="/static/js/nunjucks.js"></script>
        <script src="/static/js/templates.min.js"></script>
        <script src="/static/js/main.js"></script>
        <link rel="stylesheet" href="/static/css/main.css">
    </head>
    <body>

        <div class="page-header">
            <h1><a href="/">Tiny Guestbook</a> <small class="subheader"></small></h1>
        </div>

        <? if ($user) { ?>
            <div class="greetings">
                <p class="lead">Hello, <span class="user-name"><?= $user->name ?></span></p>
            </div>
        <? } ?>

        <nav>
            <ul class="nav nav-pills">

                <li>
                    <a href="#">Home</a>
                </li>

                <? if ($user) { ?>
                    <li>
                        <a href="#new">New message</a>
                    </li>
                    <li>
                         <a href="#logout">Logout</a>
                    </li>

                <? } else { ?>
                    <li>
                        <a href="#login">Login</a>
                    </li>
                    <li>
                        <a href="#reg">or Register</a>
                    </li>
                <? } ?>

            </ul>
        </nav>

        <div class="msg"><span class="alert alert-info">Loading data...</span></div>

        <main>
