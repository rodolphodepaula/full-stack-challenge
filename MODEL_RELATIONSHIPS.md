# Relacionamentos dos Modelos

Este documento descreve os relacionamentos entre os modelos na aplicação.

## Users

- `User`
  - Pertence a uma `Company`.
  - Possui um ou zero `Person`.
  - `belongsTo(Company::class)`

## Person

- `Person`
  - Pertence a um `User`.
  - Pertence a uma `Company`.
  - `belongsTo(User::class)`
  - `belongsTo(Company::class)`

## Companies

- `Company`
  - Tem muitos `User`.
  - Tem muitos `Person`.
  - Tem muitos `Album`.
  - `hasMany(User::class)`
  - `hasMany(Person::class)`
  - `hasMany(Album::class)`

## Albums

- `Album`
  - Pertence a uma `Company`.
  - Tem muitos `Track`.
  - `belongsTo(Company::class)`
  - `hasMany(Track::class)`

## Tracks

- `Track`
  - Pertence a um `Album`.
  - Tem e pertence a muitos `Artist` (Relacionamento Muitos Para Muitos).
  - `belongsTo(Album::class)`
  - `belongsToMany(Artist::class, 'artist_track')`

## Artists

- `Artist`
  - Tem e pertence a muitos `Track` (Relacionamento Muitos Para Muitos).
  - `belongsToMany(Track::class, 'artist_track')`
