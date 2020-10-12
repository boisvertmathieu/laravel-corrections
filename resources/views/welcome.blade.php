@extends('layouts.app')

@section('content')
<div id="welcome">
  <div class="container">
    <div class="banner">
      <div class="banner-info">
        <h1>Correct-o-matic</h1>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, ducimus. Cumque fuga maiores reiciendis fugit, minus quibusdam sunt impedit officia, aliquam eveniet vel consectetur libero! Pariatur reprehenderit id similique sit.</p>
        <a class="btn btn-secondary" href="{{ route('register') }}">Créer un compte</a>
      </div>
    </div>

  </div>
</div>
@endsection

