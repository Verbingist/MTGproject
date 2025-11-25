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

use App\Http\Middleware\Authenification;
use App\Http\Middleware\UserValidateRegistration;
use App\Http\Middleware\UserValidateLogin;
use App\Http\Middleware\UpdateUserValidate;
use App\Http\Middleware\DeckCreationValidation;
use App\Http\Middleware\DeckUpdateValidation;
use App\Http\Middleware\IsOwnDeck;
use App\Http\Middleware\CardCreateValidation;
use App\Http\Middleware\CardUpdateValidation;
use App\Http\Middleware\TournamentCreationValidation;
use App\Http\Middleware\TournamentUpdateValidation;
use App\Http\Middleware\TypeCreateOrUpdateValidation;
use App\Http\Middleware\SubypeCreateOrUpdateValidation;
use App\Http\Middleware\SupertypeCreateOrUpdateValidation;
use App\Http\Middleware\SetCreateValidation;
use App\Http\Middleware\SetUpdateValidation;
use App\Http\Middleware\FormatCreateValidation;
use App\Http\Middleware\FormatUpdateValidation;
use App\Http\Middleware\RestrictedCardCreateValidation;
use App\Http\Middleware\RestrictedCardUpdateValidation;
use App\Http\Middleware\LotCreationValidation;
use App\Http\Middleware\LotUpdationValidation;

Route::post('/auth/registration', [UserController::class, 'registration'])->middleware(UserValidateRegistration::class);
Route::post('/auth/login', [UserController::class, 'login'])->middleware(UserValidateLogin::class);
Route::get('/auth/logout', [UserController::class, 'logout']);
Route::get('/Collection/{user_id?}', [UserController::class, 'getCollection']);
Route::post('/signUpTournament/{tournamentId}', [UserController::class, 'signUpTournament'])->middleware(Authenification::class);
Route::delete('/signDownTournament/{tournamentId}', [UserController::class, 'signDownTournament'])->middleware(Authenification::class);
Route::post('/addCardToCollection', [UserController::class, 'addCardToCollection'])->middleware(Authenification::class);
Route::delete('/removeCardFromCollection', [UserController::class, 'removeCardFromCollection'])->middleware(Authenification::class);
Route::get("/User/{id?}", [UserController::class, 'getUser']);
Route::get("/Users", [UserController::class, 'getUsers']);
Route::put('/User/{id?}', [UserController::class, 'updateUser'])->middleware([Authenification::class, UpdateUserValidate::class]);
Route::delete('/User/{id}', [UserController::class, 'deleteUser'])->middleware(Authenification::class);
Route::get('/IsAuth', [UserController::class, 'isAuth']);

Route::get('/Deck/{id}', [DeckController::class, 'getDeck']);
Route::get('/Decks/{id?}', [DeckController::class, 'getDecks']);
Route::get('/MyDecks', [DeckController::class, 'getMyDecks'])->middleware(Authenification::class);
Route::post('/Deck', [DeckController::class, 'createDeck'])->middleware([Authenification::class, DeckCreationValidation::class]);
Route::put('/Deck/{id}', [DeckController::class, 'updateDeck'])->middleware([Authenification::class, DeckUpdateValidation::class]);
Route::delete('/Deck/{id}', [DeckController::class, 'deleteDecks'])->middleware(Authenification::class);
Route::post('/addCardToDeck/{id}', [DeckController::class, 'addCardToDeck'])->middleware(Authenification::class);
Route::delete('/removeCardFromDeck/{id}', [DeckController::class, 'removeCardFromDeck'])->middleware(Authenification::class);
Route::get('/isOwnDeck/{id}', [DeckController::class, 'isOwnDeck'])->middleware(IsOwnDeck::class);
Route::get('/CardsInDeck/{deck_id}', [DeckController::class, 'getCards']);
Route::post('/setCommander/{id}', [DeckController::class, 'setCommander'])->middleware(Authenification::class);
Route::delete('/deleteCommander/{id}', [DeckController::class, 'deleteCommander'])->middleware(Authenification::class);

Route::get('/Card/{id}', [CardController::class, 'getCard']);
Route::get('/Cards', [CardController::class, 'getCards']);
Route::post('/Card', [CardController::class, 'createCard'])->middleware([Authenification::class, CardCreateValidation::class]);
Route::put('/Card/{id}', [CardController::class, 'updateCard'])->middleware([Authenification::class, CardUpdateValidation::class]);
Route::delete('/Card/{id}', [CardController::class, 'deleteCard'])->middleware(Authenification::class);
Route::get('/RestrictedCards', [CardController::class, 'getRestrictedCards']);
Route::post('/RestrictedCard', [CardController::class, 'createRestrictedCard'])->middleware([Authenification::class, RestrictedCardCreateValidation::class]);
Route::put('/RestrictedCard/{id}', [CardController::class, 'updateRestrictedCard'])->middleware([Authenification::class, RestrictedCardUpdateValidation::class]);
Route::delete('/RestrictedCard/{id}', [CardController::class, 'deleteRestrictedCard'])->middleware(Authenification::class);

Route::get('/Tournament/{id}', [TournamentController::class, 'getTournament']);
Route::get('/Tournaments', [TournamentController::class, 'getTournaments']);
Route::post('/Tournament', [TournamentController::class, 'createTournament'])->middleware([Authenification::class, TournamentCreationValidation::class]);
Route::put('/Tournament/{id}', [TournamentController::class, 'updateTournament'])->middleware([Authenification::class, TournamentUpdateValidation::class]);
Route::delete('/Tournament/{id}', [TournamentController::class, 'deleteTournament'])->middleware(Authenification::class);

Route::get('/Lot/{id}', [TradingLotController::class, 'getLot']);
Route::get('/Lots', [TradingLotController::class, 'getLots']);
Route::get('/getUserLots/{id?}', [TradingLotController::class, 'getUserLots']);
Route::post('/Lot', [TradingLotController::class, 'createLot'])->middleware([Authenification::class, LotCreationValidation::class]);
Route::put('/Lot/{id}', [TradingLotController::class, 'updateLot'])->middleware([Authenification::class, LotUpdationValidation::class]);
Route::delete('/Lot/{id}', [TradingLotController::class, 'deleteLot'])->middleware(Authenification::class);

Route::get('/Format/{format_name}', [FormatController::class, 'getFormat']);
Route::get('/Formats', [FormatController::class, 'getFormats']);
Route::post('/Format', [FormatController::class, 'createFormat'])->middleware([Authenification::class, FormatCreateValidation::class]);
Route::put('/Format/{format_name}', [FormatController::class, 'updateFormat'])->middleware([Authenification::class, FormatUpdateValidation::class]);
Route::delete('/Format/{format_name}', [FormatController::class, 'deleteFormat'])->middleware(Authenification::class);

Route::get('/Type/{id}', [TypeController::class, 'getType']);
Route::get('/Types', [TypeController::class, 'getTypes']);
Route::post('/Type', [TypeController::class, 'createType'])->middleware([Authenification::class, TypeCreateOrUpdateValidation::class]);
Route::put('/Type/{id}', [TypeController::class, 'updateType'])->middleware([Authenification::class, TypeCreateOrUpdateValidation::class]);
Route::delete('/Type/{id}', [TypeController::class, 'deleteType'])->middleware(Authenification::class);

Route::get('/Supertype/{id}', [SupertypeController::class, 'getSupertype']);
Route::get('/Supertypes', [SupertypeController::class, 'getSupertypes']);
Route::post('/Supertype', [SupertypeController::class, 'createSupertype'])->middleware([Authenification::class, SupertypeCreateOrUpdateValidation::class]);
Route::put('/Supertype/{id}', [SupertypeController::class, 'updateSupertype'])->middleware([Authenification::class, SupertypeCreateOrUpdateValidation::class]);
Route::delete('/Supertype/{id}', [SupertypeController::class, 'deleteSupertype'])->middleware(Authenification::class);

Route::get('/Subtype/{id}', [SubtypeController::class, 'getSubtype']);
Route::get('/Subtypes', [SubtypeController::class, 'getSubtypes']);
Route::post('/Subtype', [SubtypeController::class, 'createSubtype'])->middleware([Authenification::class, SubypeCreateOrUpdateValidation::class]);
Route::put('/Subtype/{id}', [SubtypeController::class, 'updateSubtype'])->middleware([Authenification::class, SubypeCreateOrUpdateValidation::class]);
Route::delete('/Subtype/{id}', [SubtypeController::class, 'deleteSubtype'])->middleware(Authenification::class);

Route::get('/Set/{id}', [SetController::class, 'getSet']);
Route::get('/Sets', [SetController::class, 'getSets']);
Route::post('/Set', [SetController::class, 'createSet'])->middleware([Authenification::class, SetCreateValidation::class]);
Route::put('/Set/{id}', [SetController::class, 'updateSet'])->middleware([Authenification::class, SetUpdateValidation::class]);
Route::delete('/Set/{id}', [SetController::class, 'deleteSet'])->middleware(Authenification::class);
Route::post('/addCardToSet', [SetController::class, 'addCardToSet'])->middleware(Authenification::class);
Route::delete('/removeCardFromSet', [SetController::class, 'removeCardFromSet'])->middleware(Authenification::class);