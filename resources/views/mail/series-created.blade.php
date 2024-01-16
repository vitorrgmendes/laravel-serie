@component('mail::message')
# {{ $nomeSerie }} adicionada!

A série {{ $nomeSerie }} com {{ $qtdTemporadas }} temporadas e {{ $episodiosPorTemporada }} episódios por temporada foi adicionada.

Acesse aqui:

@component('mail::button', ['url' => route('seasons.index', $idSerie)])
    Ver série
@endcomponent

@endcomponent
