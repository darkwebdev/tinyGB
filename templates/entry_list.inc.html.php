<ul class="entries">
    <? foreach($entry_list as $entry) { ?>
        <?/* dump($entry) */?>
        <? if ($entry->is_active || $user->is_admin) { ?>
            <li>
                <article>
                    <header>
                        <div class="author"><?= $entry->author ?></div>
                        <div class="title"><?= $entry->name ?></div>
                        <div class="time"><?= $entry->created ?></div>
                    </header>

                    <section>
                        <?= $entry->text ?>
                    </section>

                    <? if ($user->is_admin) { ?>
                        <footer>
                            <ul>

                                <li>
                                    <? if (!$entry->is_active) { ?>
                                        <div class="notice">HIDDEN</div>
                                    <? } ?>
                                    <a href="./?action=edit&entry=<?= $entry->id ?>">edit</a>
                                </li>

                                <li>
                                    <a href="./?action=delete&entry=<?= $entry->id ?>">delete</a>
                                </li>

                            </ul>
                        </footer>
                    <? } ?>

                </article>
            </li>
        <? } ?>
    <? } ?>
</ul>
