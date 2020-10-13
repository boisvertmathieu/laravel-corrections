@extends('layouts.app')

@section('content')
    <div class="container">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Mes grilles</a></li>
                <li class="breadcrumb-item"><a href="{{route('grille.show', $grille->id)}}">{{ $grille->nom }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $correction->da . "-" . $correction->nom . ", " . $correction->prenom }}</li>
            </ol>
        </nav>

        <section id="sect-grille">
            <header class="entete-grille">
                <h1>{{ $correction->da . "-" . $correction->nom . ", " . $correction->prenom }}</h1>
            </header>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Compétence</th>
                    <th scope="col">Pondération</th>
                    <th scope="col">Résultat</th>
                    <th scope="col">Commentaire</th>
                </tr>
                </thead>
                <tbody>

                <?php $i = 0; $j = 0; $total = 0; $correction_id = 0 ?>
                @foreach ($competences as $competence)
                    <?php
                    if (sizeof($notes) > $j) {
                        $note = $notes[$j];
                        if ($note->competence_id > $competence->id) {
                            $j--;
                            $note = null;
                        } else {
                            $total += $note->note;
                        }
                    } else {
                        $note = null;
                    }
                    ?>
                    <tr id="competence-{{ $competence->id }}">
                        <td>
                            <label class="h4" type="text" id="nomComp" name="nomComp">{{ $competence->nom }}</label>
                            <br>
                            <label type="text" id="descComp" name="descComp">{{ $competence->description }}</label>
                        </td>
                        <td class="td-big">
                            <!-- Affichage de la pondération des compétences -->
                            <label class="h4 text-center" type="text" id="ponderation"
                                   name="ponderation">{{ $competence->ponderation }}</label>
                            </form>
                        </td>
                        <td>
                            <!-- Section pour l'affichage et la modification du résultat -->
                            <form
                                action="{{ route('updateNote', [$correction->id, $competence->id, empty($note) ? '' : $note->id])}}"
                                class="form-auto-submit form-ghost" method="post">
                                @method('POST')
                                @csrf
                                <input class="h4 note text-center" type="text" id="note" name="note" size="5"
                                       value="{{ empty($note) ? '' : $note->note }}">
                                <input id="modifierNote" class="sr-only" type="submit" value="Soumettre">
                            </form>
                        </td>

                        <td style="width: 70%">
                        <?php $criteres = $listeCriteres[$i]; ?>
                        <!-- Section pour la sélection du critère -->
                            <form
                                action="{{ route('updateNote', [$correction->id, $competence->id, empty($note) ? '' : $note->id])}}"
                                id="selectNote . {{$i}}" method="post">
                                @method('POST')
                                @csrf
                                <select name="selectCrit" id="selectCrit" form="selectNote . {{$i}}"
                                        onchange="this.form.submit()">
                                    <option value="null">Critères de correction</option>
                                    @foreach ($criteres as $critere)
                                        <option
                                            value="{{ $critere->cote }}">{{ $critere->cote . " - " . $critere->description }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <!-- Section pour la prise de note -->
                            <form
                                action="{{ route('updateNote', [$correction->id, $competence->id, empty($note) ? '' : $note->id])}}"
                                class="form-auto-submit form-ghost" method="post" id="form-descCorr">
                                @method('POST')
                                @csrf
                                <textarea type="text" id="descCorr" class="description-correction" name="descCorr"
                                          cols="100"
                                          rows="6">{{ empty($note) ? "" : $note->description }}</textarea>
                                <input id="modifierDescNote" class="sr-only" type="submit" value="Soumettre">
                            </form>
                        </td>
                    </tr>
                    <?php $i++; $j++; ?>
                @endforeach


                </tbody>
                <tfoot>
                <tr>
                    <th scope="row">
                        <div class="text-right">Résultat</div>
                    </th>
                    <td>
                        {{-- Affiche du total de la pondération--}}
                        <span>{{ $grille->getTotalPoints($grille->id) }}</span>
                    </td>
                    <td>{{ $total }}</td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </section>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            //Récupération des données de la bd
            var tab_description = {!! json_encode(App\Models\Note::getAllNotes($correction->id)) !!};

            //Nombre de caractère minimum pour démarrer l'autocomplete
            var mL = 1;

            //Permet de séparer une chaine de caractère par son caractère représentant une nouvelle ligne
            function split(val) {
                return val.split("\n");
            }

            function extraireDernierChar(term) {
                return split(term).pop();
            }

            /* Autocomplete jQuery */
            $(".description-correction").on('keydown', function (event) {
                //Empêchement de quitter le champ si la touche TAB est appuyé
                if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            }).autocomplete({
                minLength: mL,
                source: function (request, response) {
                    var lastTerm = extraireDernierChar(request.term);
                    if (lastTerm.length >= mL) {
                        response($.ui.autocomplete.filter(tab_description, lastTerm));
                    }
                },
                focus: function () {
                    return false;
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    // Remove current input
                    terms.pop();
                    // add the selected line
                    terms.push(ui.item.value);
                    // Format value to display
                    terms.push("");
                    this.value = terms.join("\r\n");
                    return false;
                }
            });
        });
    </script>
@endsection
