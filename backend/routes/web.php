<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\TradingLotController;
use App\Http\Controllers\FormatController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\SupertypeController;
use App\Http\Controllers\SubtypeController;
use App\Http\Controllers\SetController;

Route::post('/auth/registration', [UserController::class, 'registration']);
Route::post('/auth/login', [UserController::class, 'login']);
Route::get('/auth/logout', [UserController::class, 'logout']);
Route::get('/Collection/{user_id}', [UserController::class, 'getCollection']);
Route::post('/signUpTournament/{tournamentId}', [UserController::class, 'signUpTournament']);
Route::delete('/signDownTournament/{tournamentId}', [UserController::class, 'signDownTournament']);
Route::post('/addCardToCollection', [UserController::class, 'addCardToCollection']);
Route::delete('/removeCardFromCollection', [UserController::class, 'removeCardFromCollection']);
Route::get("/User/{id}", [UserController::class, 'getUser']);
Route::get("/Users", [UserController::class, 'getUsers']);
Route::put('/User/{id}', [UserController::class, 'updateUser']);
Route::delete('/User/{id}', [UserController::class, 'deleteUser']);

Route::get('/Deck', [DeckController::class, 'getDeck']);
Route::get('/Decks', [DeckController::class, 'getDecks']);
Route::delete('/Deck', [DeckController::class, 'deleteDecks']);
Route::post('/addCardToDeck', [DeckController::class, 'addCardToDeck']);
Route::delete('/removeCardFromDeck', [DeckController::class, 'removeCardFromDeck']);

Route::get('/Card', [CardController::class, 'getCard']);
Route::get('/Cards', [CardController::class, 'getCards']);
Route::post('/Card', [CardController::class, 'createCard']);
Route::put('/Card', [CardController::class, 'updateCard']);
Route::delete('/Card', [CardController::class, 'deleteCard']);
Route::get('/RestrictedCards', [CardController::class, 'getRestrictedCards']);
Route::post('/RestrictedCard', [CardController::class, 'createRestrictedCard']);
Route::put('/RestrictedCard', [CardController::class, 'updateRestrictedCard']);
Route::delete('/RestrictedCard', [CardController::class, 'deleteRestrictedCard']);

Route::get('/Tournament', [TournamentController::class, 'getTournament']);
Route::get('/Tournaments', [TournamentController::class, 'getTournaments']);
Route::post('/Tournament', [TournamentController::class, 'createTournament']);
Route::put('/Tournament', [TournamentController::class, 'updateTournament']);
Route::delete('/Tournament', [TournamentController::class, 'deleteTournament']);

Route::get('/Lot', [TradingLotController::class, 'getLot']);
Route::get('/Lots', [TradingLotController::class, 'getLots']);
Route::post('/Lot', [TradingLotController::class, 'createLot']);
Route::put('/Lot', [TradingLotController::class, 'updateLot']);
Route::delete('/Lot', [TradingLotController::class, 'deleteLot']);

Route::get('/Format', [FormatController::class, 'getFormat']);
Route::get('/Formats', [FormatController::class, 'getFormats']);
Route::post('/Format', [FormatController::class, 'createFormat']);
Route::put('/Format', [FormatController::class, 'updateFormat']);
Route::delete('/Format', [FormatController::class, 'deleteFormat']);

Route::get('/Type', [TypeController::class, 'getType']);
Route::get('/Types', [TypeController::class, 'getTypes']);
Route::post('/Type', [TypeController::class, 'createType']);
Route::put('/Type', [TypeController::class, 'updateType']);
Route::delete('/Type', [TypeController::class, 'deleteType']);

Route::get('/Supertype', [SupertypeController::class, 'getSupertype']);
Route::get('/Supertypes', [SupertypeController::class, 'getSupertypes']);
Route::post('/Supertype', [SupertypeController::class, 'createSupertype']);
Route::put('/Supertype', [SupertypeController::class, 'updateSupertype']);
Route::delete('/Supertype', [SupertypeController::class, 'deleteSupertype']);

Route::get('/Subtype', [SubtypeController::class, 'getSubtype']);
Route::get('/Subtypes', [SubtypeController::class, 'getSubtypes']);
Route::post('/Subtype', [SubtypeController::class, 'createSubtype']);
Route::put('/Subtype', [SubtypeController::class, 'updateSubtype']);
Route::delete('/Subtype', [SubtypeController::class, 'deleteSubtype']);

Route::get('/Set', [SetController::class, 'getSet']);
Route::get('/Sets', [SetController::class, 'getSets']);
Route::post('/Set', [SetController::class, 'createSet']);
Route::put('/Set', [SetController::class, 'updateSet']);
Route::delete('/Set', [SetController::class, 'deleteSet']);
Route::post('/addCardToSet', [SetController::class, 'addCardToSet']);
Route::delete('/removeCardToSet', [SetController::class, 'removeCardToSet']);