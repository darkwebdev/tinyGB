<!DOCTYPE html>
<html>
    <head>
        <title>Tiny GuestBook<?= " &ndash; ". $title ?></title>
        <script src="/static/js/routie.min.js"></script>
        <script src="/static/js/nunjucks-min.js"></script>
        <script src="/static/js/templates.min.js"></script>
        <script src="/static/js/main.js"></script>
        <link rel="stylesheet" href="/static/css/main.min.css"
    </head>
    <body>

        <div class="page-header">
            <h1>Tiny Guestbook <small class="subheader"></small></h1>
        </div>

        <nav>
            <ul class="nav nav-pills">
                <li>
                    <a href="/">Home</a>
                </li>
                <? if ($user && $user->is_admin) { ?>
                    <li>
                        Users:
                        <? foreach ($user_list as $user_object) { ?>
                            <a href="#user/<?= $user_object->id ?>"><?= $user_object->name ?></a>
                        <? } ?>
                    </li>
                <? } ?>
                <? if ($user) { ?>
                    <li>
                        <span class="user-name"><?= $user->name ?></span> (<a href="#logout">Logout</a>)
                    </li>
                    <li>
                        <a href="#new">New message</a>
                    </li>
                <? } else { ?>
                    <li>
                        <a href="#login">Login</a>
                    </li>
                <? } ?>

            </ul>
        </nav>

        <div class="msg"></div>

        <main>
