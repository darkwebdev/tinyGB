<!DOCTYPE html>
<html>
    <head>
        <title>Tiny GuestBook<?= " &ndash; ". $html_title ?></title>
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
                             <a href="./?action=user_edit&id=<?= $user_object->id ?>"><?= $user_object->name ?></a>
                        <? } ?>
                    </li>
                <? } ?>
                <li>
                    <a href="./?action=entry_new">New message</a>
                </li>
            </ul>
        </nav>

        <h1>Guest book</h1>
