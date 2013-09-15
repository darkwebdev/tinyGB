<!DOCTYPE html>
<html>
    <head>
        <title>Tiny GuestBook<?= " &ndash; ". $html_title ?></title>
        <script src="/static/js/routie.min.js"></script>
        <script src="/static/js/nunjucks-min.js"></script>
        <script src="/static/js/templates.js"></script>
        <script src="/static/js/main.js"></script>
    </head>
    <body>
        <nav>
            <ul>
                <? if ($user->is_admin) { ?>
                    <li>
                        Users:
                        <? foreach ($user_list as $user_object) { ?>
                             <a href="#user/<?= $user_object->id ?>"><?= $user_object->name ?></a>
                        <? } ?>
                    </li>
                <? } ?>
                <li>
                    <a href="#new">New message</a>
                </li>
            </ul>
        </nav>

        <h1>Guest book</h1>

        <aside class="msg"></aside>

        <main>
