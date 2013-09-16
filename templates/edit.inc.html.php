<form method="POST">
    <ul>
        <? foreach ($form->fields as $field) { ?>
            <? if ($field->editable || $user->is_admin) { ?>
                <li>

                    <span class="label">
                        <?= $field->label ?>:
                    </span>

                    <span class="input">
                        <? if ($field->type == 'textarea') { ?>
                            <textarea name="<?= $field->name ?>">
                                <?= $field->value ?>
                            </textarea>
                        <? } else { ?>
                            <input
                                type="<?= $field->type ?>"
                                name="<?= $field->name ?>"
                                <? if ($field->type == 'checkbox') { ?>
                                    <? if ($field->value) { ?>checked<? } ?>
                                <? } else { ?>
                                    <? if ($field->value) { ?>
                                        value="<?= $field->value ?>"

                                <? } ?>
                                >
                        <? } ?>
                    </span>

                </li>
            <? } ?>
        <? } ?>

        <li>
            <input type="submit" value="Save">
        </li>
    </ul>
</form>