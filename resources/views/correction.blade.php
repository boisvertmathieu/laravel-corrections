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

                <?php $i = 0; $j = 0; $total = 0; ?>
                @foreach ($competences as $competence)
                <?php
                    if(sizeof($notes) > $j)
                    {
                        $note = $notes[$j];
                        if($note->competence_id > $competence->id)
                        {
                            $j--;
                            $note = null;
                        }
                        else
                        {
                            $total += $note->note;
                        }
                    }
                    else
                    {
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
                        {{-- Affichage de la pondération des compétences --}}
                        <label class="h4 text-center" type="text" id="ponderation"
                            name="ponderation">{{ $competence->ponderation }}</label>
                        </form>
                    </td>
                    <td>
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
                        <form
                            action="{{ route('updateNote', [$correction->id, $competence->id, empty($note) ? '' : $note->id])}}"
                            id="selectNote . {{$i}}" method="post">
                            @method('POST')
                            @csrf
                            <select name="selectCrit" id="selectCrit" form="selectNote . {{$i}}" onchange="this.form.submit()">
                                <option value="null">Critères de correction</option>
                                @foreach ($criteres as $critere)
                                    <option value="{{ $critere->cote }}">
                                        {{ $critere->cote . " - " . $critere->description }}</option>
                                @endforeach
                            </select>
                        </form>

                        <form
                            action="{{ route('updateNote', [$correction->id, $competence->id, empty($note) ? '' : $note->id])}}"
                            class="form-auto-submit form-ghost" method="post">
                            @method('POST')
                            @csrf
                            <textarea type="text" id="descCorr" name="descCorr" cols="100"
                                rows="10">{{ empty($note) ? '' : $note->description }}</textarea>
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
