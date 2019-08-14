<?php

use Illuminate\Http\Request;

/* ================= Rotas Aluno ================= */
/*
Route::get('/', 'AlunoController@index');
Route::get('/aluno/{id}', 'AlunoController@select');
Route::get('/relatorio', 'AlunoController@relatorio');
Route::post('/aluno', 'AlunoController@store');
Route::post('/aluno/{id}', 'AlunoController@update');
Route::delete('/aluno/{id}', 'AlunoController@delete');
*/
/* ================= Rotas Curso ================= */

Route::get('/curso', 'Api\CursoController@index');
Route::get('/curso/{id}', 'Api\CursoController@select');
Route::post('/curso', 'Api\CursoController@store');
Route::post('/curso/{id}', 'Api\CursoController@update');
Route::delete('/curso/{id}', 'Api\CursoController@delete');

/* ================= Rotas Professor ================= */

Route::get('/professor', 'Api\ProfessorController@index');
Route::get('/professor/{id}', 'Api\ProfessorController@select');
Route::post('/professor', 'Api\ProfessorController@store');
Route::post('/professor/{id}', 'Api\ProfessorController@update');
Route::delete('/professor/{id}', 'Api\ProfessorController@delete');
