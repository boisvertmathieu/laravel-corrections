@extends('layouts.app')

@section('content')
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                Veuillez régler les erreurs suivantes
                {{$errors}}
            </div>
        @endif

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Mes grilles</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $grille->nom }}</li>
            </ol>
        </nav>

        <section id="sect-grille">
            <header class="entete-grille">
                <form class="form-auto-submit form-ghost" method="POST"
                      action="{{ route('grille.update', $grille->id) }}">
                    @method('PATCH')

                    @csrf
                    <h1>
                        <input type="text" id="nom-grille" name="nom" value="{{ $grille->nom }}">
                    </h1>
                    <h2>
                        <strong>Cours :</strong>
                        <input type="text" id="cours-grille" name="cours" value="{{ $grille->cours }}">
                    </h2>
                    <input id="modifierGrille" class="sr-only" type="submit" value="Soumettre">
                </form>
                <a href="#sect-corrections" class="btn btn-primary mb-4"><i class="fas fa-arrow-down mr-2"></i>Corrections
                    pour cette grille</a>

            </header>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Compétence</th>
                    <th scope="col">Pondération</th>
                    <th scope="col">Critères</th>
                    <th scope="col">Supprimer</th>
                </tr>
                </thead>
                <tbody>

                <?php $i = 0 ?>
                @foreach ($competences as $competence)
                    <tr id="competence-{{ $competence->id }}">
                        <td>
                            <form action="{{ route('updateCompetence', [$grille->id, $competence->id])}}"
                                  class="form-auto-submit form-ghost" method="post">
                                @method('POST')
                                @csrf
                                <input class="h4 competence" type="text" id="nomComp" name="nomComp" size="30"
                                       value="{{ $competence->nom }}">
                                <textarea class="competence" type="text" id="descComp" name="descComp" cols="50"
                                          rows="10">{{ $competence->description }}</textarea>
                                <input id="modifierComp" class="sr-only" type="submit" value="Soumettre">
                            </form>
                        </td>
                        <td class="td-big">
                            {{-- Affichage de la pondération des compétences --}}
                            <form action="{{ route('updateCompetence', [$grille->id, $competence->id])}}"
                                  class="form-auto-submit form-ghost" method="post">
                                @method('POST')
                                @csrf
                                <input class="h4 text-center competence" type="text" id="ponderation" name="ponderation"
                                       size="5" value="{{ $competence->ponderation }}">
                            </form>
                        </td>
                        <td>
                            <?php $criteres = $listeCriteres[$i]; ?>
                            <ul class="ul-critere">
                                @foreach ($criteres as $critere)
                                    <div>
                                        <li class="critere">
                                            <form action="{{ route('destroyCritere', [$critere->id, $grille->id])}}"
                                                  method="post">
                                                @method('DELETE')
                                                @csrf
                                                <div class="texte-critere"> {{ $critere->cote }} </div>
                                                <div class="texte-critere">{{ $critere->description }} </div>
                                                <button id="deleteCrit" class="icon" type="submit">
                                                    <i class="fa fa-times-circle"></i>
                                                </button>
                                            </form>
                                        </li>
                                    </div>
                                @endforeach
                            </ul>
                            <?php $i++; ?>

                            <form action="{{ route('createCritere', [$competence->id]) }}" method="post">
                                @csrf
                                <input type="text" id="cote" name="cote" size="3" placeholder="0-1">
                                <input type="text" id="descCritere" name="descCritere" size="20"
                                       placeholder="description">
                                <input id="ajoutCrit" type="submit" value="+">
                            </form>
                        </td>

                        <td class="td-big">
                            <form action="{{ route('destroyCompetence', [$grille->id, $competence->id])}}"
                                  method="post">
                                @method('DELETE')
                                @csrf
                                <button id="deleteComp" class="btn btn-secondary" type="submit">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach


                </tbody>
                <tfoot>
                <tr>
                    <th scope="row">
                        <div class="text-right">Total</div>
                    </th>
                    <td>
                        {{-- Affiche du total de la pondération--}}
                        <span>{{ $grille->getTotalPoints($grille->id) }}</span>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>
        </section>

        <section id="sect-ajout-competence">
            <form action="{{ route('createCompetence', [$grille->id]) }}" method="post">
                @method('POST')
                @csrf
                <div class="row form-group">
                    <div class="col-md">
                        <label for="nom">Compétence: </label>
                        <input id="createNomComp" type="text" name="nom" class="form-control"
                               placeholder="Entrez le nom de la compétence"/>
                    </div>
                    <div class="col-md">
                        <label for="description">Description: </label>
                        <input id="createDescComp" type="text" name="description" class="form-control"
                               placeholder="Entrez la descriptiond de la compétence"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="ponderation">Pondération</label>
                        <input id="createPondComp" type="number" name="ponderation" class="form-control" placeholder="Pondération"/>
                    </div>
                    <input id="ajoutComp" type="submit" class="btn btn-primary" value="Envoyez"/>
                </div>
            </form>
        </section>

        <!-- Section pour l'ajout de correction à la grille -->
        <section id="sect-corrections">
            <div class="pt-4">
                <form action="{{ route('importationCSV', [$grille->id]) }}" enctype="multipart/form-data" method="post" class="form-inline float-right">
                    <input type="file" name="fichier" id="fichier" required/>
                    <button type="submit" class="btn btn-dark">Importer le fichier</button>
                    {{csrf_field()}}
                </form>
                <h2>Ajouter une correction</h2>
            </div>
            @if(isset($corrections))
                @foreach($corrections as $correction)
                    <div class="row corrections mb-1">
                        <div class="col m-auto">
                            {{ $correction->da . " - " . $correction->nom . ", " . $correction->prenom }}
                        </div>
                        <div class="col-md-2 d-flex flex-row justify-content-start">
                            <form class="p-1" action="{{ route('showDetails', [$grille->id, $correction->id]) }}" method="get">
                                <input type="submit" class="btn btn-primary" value="Corriger"/>
                            </form>
                            <form class="p-1" action="{{ route('destroyCorrection', [$correction->id]) }}"
                                  method="post">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"
                                                                                aria-hidden="true"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
            <form action="{{ route('createCorrection', [$grille->id]) }}" method="post">
                @method('POST')
                @csrf
                <div class="row form-group">
                    <div class="col-md">
                        <label for="correction-nom">Nom: </label>
                        <input id="correction-nom" type="text" name="correction-nom" class="form-control" placeholder="Nom"/>
                    </div>
                    <div class="col-md">
                        <label for="correction-prenom">Prénom: </label>
                        <input id="correction-prenom" type="text" name="correction-prenom" class="form-control" placeholder="Prénom"/>
                    </div>
                    <div class="col-sm-2">
                        <label for="correction-da">DA: </label>
                        <input id="correction-da" type="number" name="correction-da" class="form-control" placeholder="DA"/>
                    </div>
                    <input id="ajoutCorrection" type="submit" class="btn btn-primary" value="Envoyez"/>
                </div>
            </form>
        </section>

    </div>
@endsection
