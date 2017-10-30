<main class="container">
	<section>
		<div class="header">
            <h4>Les questions</h4>
        </div>
		<ul class="collapsible">
			<?php
			foreach ($data['questions'] as $question)
			{
                $student = $data['students'][$question->numEtudiant];
                ?>
                <li>
                    <div class="collapsible-header">
                        <div>
                            <?= $question->titre ?>
                        </div>
                        <div><?= $question->numEtudiant . ' - '
                        . $student->prenom . ' ' . $student->nom ?></div>
                    </div>
                    <div class="collapsible-body">
                        <ul>
                            <p class="right-align"><?= $question->texte ?></p>
                            <?php
                            foreach($data['answers'][$question->idQuestion] as $answer){
                                $isTeacher = $answer->prof ? 'right-align' : '';
                                ?>
                                <li class="divider"></li>
                                <li><p class="<?= $isTeacher ?>"><?= $answer->texte ?></p></li>
                                <?php
                            } ?>
                            <form action="/Process_professeur/repondreQuestion" method="POST">
                                <input type="hidden" name="idQuestion" value ="<?= $question->idQuestion;?>"/>
                                <div class="input-field">
                                    <textarea class="materialize-textarea" name="texte" id="texte"></textarea>
                                    <label for="texte">Réponse</label>
                                </div>
                                <p>
                                    <input type="checkbox" name="public" id="public">
                                    <label for="public">Rendre publique</label>
                                </p>
                                <div class="btn-footer">
                                    <button class="btn" type="submit">Envoyer</button>
                                </div>
                            </form>
                        </ul>
                    </div>
                </li>
			    <?php
			} ?>
		</ul>
	</section>
</main>

