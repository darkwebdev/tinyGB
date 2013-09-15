<!DOCTYPE html>
<html>
	<head>
		<title>Tiny GuestBook<?= " &ndash; ". $html_title ?></title>
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
            </ul>
        </nav>