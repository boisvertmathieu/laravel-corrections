@extends('layouts.app')

@section('content')
<div class="container">


    <h1>Mes grilles</h1>

    <div class="row">
        <div class="col-2-tiers">

            <form method="post" action="{{ route('store') }}">
                @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    Veuillez régler les erreurs suivantes
                </div>
                @endif

                @csrf
                <div class="row">
                    <div class="col-tier form-group {{ $errors->has('nom') ? 'has-error' : '' }}">
                        <label for="nom">Nom :</label>
                        <input type="text" class="form-control" id="nom" name="nom"
                            placeholder="Nom de la grille, de l'évaluation" value="{{ old('nom') }}">
                        @if($errors->has('nom'))
                        <span class="help-block">{{ $errors->first('nom') }}</span>
                        @endif
                    </div>
                    <div class="col-tier form-group {{ $errors->has('cours') ? 'has-error' : '' }}">
                        <label for="cours">Cours :</label>
                        <input type="text" class="form-control" id="cours" name="cours"
                            placeholder="Cours en lien avec la grille" value="{{ old('cours') }}">
                        @if($errors->has('cours'))
                        <span class="help-block">{{ $errors->first('cours') }}</span>
                        @endif
                    </div>
                    <div class="col-tier d-flex align-items-end">
                        <button id="ajouterGrille" type="submit" class="btn btn-primary">Ajouter une grille</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section id="mesGrilles" class="row">
        @foreach ($grilles as $grille)
        <div class="grille col-tier">
            <div class="card">
                <div class="card-body">
                    <h2 class="h4">{{$grille->nom}}</h2>
                    <p><strong>Cours:</strong> {{$grille->cours}}</p>
                    <p>
                        <div>
                            Création:
                            <span class="badge badge-primary">{{substr($grille->created_at, 0, 10)}}</span>
                        </div>
                        <div>
                            Modification:
                            <span class="badge badge-primary">{{substr($grille->updated_at, 0, 10)}}</span>
                        </div>
                    </p>
                </div>

                <div class="card-actions">
                    <a class="btn btn-primary" href="{{route('grille.show', $grille->id)}}">Voir la grille</a>
                    <form action="{{ route('grille.destroy', $grille->id)}}" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-secondary delete-btn" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </section>

    <div id="pagination" class="d-flex justify-content-center">
        {{ $grilles->links() }}
    </div>

</div>
@endsection
