<?php
    $now = new DateTime();
?>
<main>
    <h2 class="header">Projet <?= $data['group']->nomGroupe ?></h2>
    <section>
        <h4>Membres</h4>
        <?php
        foreach($data['members'] as $member)
        { ?>
            <p><?= $member->nom ?></p>
            <?php
        } ?>
    </section>
    <section>
        <h4>Rendez-vous</h4>
        <?php
        if (!empty($data['lastAppointement']))
        {
            $date = new DateTime($data['lastAppointement']->dateFinale);
            $diff = $date->diff($now);
            ?>
            <div>
                <h5>Dernier rendez-vous</h5>
                <p><?= readableTimeDifference($diff) ?></p>
            </div>
            <?php
        } ?>
        <div>
            <h5>Prochain rendez-vous</h5>
            <?php
            if (!is_null($data['nextAppointement']->dateFinale))
            {
                $date = new DateTime($data['nextAppointement']->dateFinale);
                $diff = $date->diff($now);
                ?>
                <p><?= readableTimeDifference($diff) ?></p>
                <p>Le <?= $date->format('d/m/Y') ?></p>
                <?php
            } else if (empty($data['proposals'])) {
                ?>
                <p>Pas de rendez-vous prévu</p>
                <?php
            } else {
                ?>
                <div>
                    <h5>Propositions de dates</h5>
                    <?php
                    foreach($data['proposals'] as $proposition)
                    { ?>
                        <p><?= (new DateTime($proposition->date))->format('d/m/Y') ?></p>
                        <!-- action="/process_professeur/opt_proposal" -->
                        <form method="POST">
                            <input type="hidden" name="proposalId" value="<?= $proposition->idProposition ?>">
                            <button type="submit" name="accept">Accepter</button>
                            <button type="submit" name="decline">Refuser</button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } ?>
            <div>
                <h6>Proposer un rendez-vous</h6>

                <!-- action="/process_professeur/add_proposal" -->
                <form method="POST">
                    <input type="hidden" name="groupId" value="<?= $data['group']->idGroupe ?>">
                    <div>
                        <input type="date" name="date" id="date">
                        <label for="date">Date proposée</label>
                    </div>
                    <div>
                        <input type="time" name="time" id="time">
                        <label for="time">Heure</label>
                    </div>
                    <button type="submit">Proposer</button>
                </form>
            </div>
        </div>
    </section>
</main>
