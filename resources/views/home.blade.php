@extends('layouts.app')

@section('content')

<?php $colors = ["#558b2f","#fbc02d","#d84315"]?>

<div class="container">
    
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Aggiungi un ToDo</h4>
            </div>
            <div class="modal-body">
                <form action="/" method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{Auth::id()}}">
                    <div class="form-group">
                        <label for="nameTodo">Nome</label>
                        <input type="text" class="form-control" id="nameTodo" name="title" placeholder="Fare la spesa" required>
                    </div>
                    <div class="form-group">
                        <label for="descriptionText">Descrizione</label>
                        <textarea class="form-control" name="description" id="descriptionText" placeholder="Ci sono le offerte al supermercato vicino casa." rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="prioritySelect">Priorità</label>
                        <select class="form-control" name="priority" id="prioritySelect" required>
                        <option value="1">Bassa</option>
                        <option value="2">Media</option>
                        <option value="3">Alta</option>
                        </select>
                    </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>

                    <input type="submit" class="btn btn-default">
                </div>
            </form>
        </div>
        
        </div>
    </div>
    <div class="row px-3">
        <h1 class="h4">Ciao {{Auth::user()->name}}, ecco le tue cose da fare</h1>
    </div>
    <form action="">        
        <div class="row">
            <div class="form-group col-sm">
                        <select class="form-control" name="type" id="type" required onChange = "this.form.submit();">
                            <option value="priority" {{$type == "priority" ? 'selected':''}}>Priorità</option>
                            <option value="created_at" {{$type == "created_at" ? 'selected':''}}>Data di aggiunta</option>
                        </select>
            </div>
            <div class="form-group col-sm">
                        <select class="form-control" name="order" id="order" required onChange = "this.form.submit();">
                            <option value="DESC" {{$order == "DESC" ? 'selected':''}}>Decrescente</option>
                            <option value="ASC" {{$order == "ASC" ? 'selected':''}}>Crescente</option>
                        </select>
            </div>
        </div>
    </form>
    <div class="row">
        @foreach($todos as $todo)
            <div class="col-md-3 py-3">
                <div class="card text-white {{$todo->completed ? 'bg-secondary':''}}" style="{{!$todo->completed ? 'background-color:'.$colors[$todo->priority-1]:''}}">
                  <div class="card-body">
                    <h5 class="card-title">{{$todo->title}}</h5>
                    <p class="card-text">
                        {{$todo->description}}
                    </p>
                    <form action="/{{$todo->id}}" method="post">
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-outline-light pull-left">Elimina</button>
                    </form>
                    <form action="/{{$todo->id}}" method="post">
                        @method('patch')
                        @csrf
                        @if(!$todo->completed)
                            <input type="hidden" name="completed">
                        @endif
                        <button type="submit" class="btn btn-outline-light pull-right">{{$todo->completed ? 'Completato':'Completa'}}</a>
                    </form>
                  </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <button type="button" class="float"" id="addTodo" data-toggle="modal" data-target="#myModal"><i style="display:inline" class="fa fa-plus my-float"></i></button>
</div>
@endsection
