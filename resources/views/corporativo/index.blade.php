@extends('layouts.admin')

@section('title', 'Corporativo')

@section('content_header')
<h1 class="colorgris body">Corporativo</h1>
@stop

@section('content')

<div class="row">

    {{-- Innova --}}
    <div class="col-md-6 mb-5">
        <a href="https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/Lists/INNOVA/NewForm.aspx?Source=https%3A%2F%2Fmilagrosenterprisegroup.sharepoint.com%2Fsites%2FIntranetgrupomilagros%2FLists%2FINNOVA%2FAllItems.aspx%3Fenv%3DWebViewList%26npsAction%3DcreateList&ContentTypeId=0x0100FE4D0C843418844E96C198EB0F6EC007007461DDD6E2C22D4BBDB509EFE772998E&id=%2Fsites%2FIntranetgrupomilagros%2FLists%2FINNOVA" target="_blank" class="text-decoration-none">

            <div class="corporativo-card">

                <div class="corporativo-logo-box">
                    <img src="{{ asset('img/corporativo/innova.png') }}" alt="Innova">
                </div>

                <div class="custom-title text-innova">
                    Programa de mejora continua e innovación
                </div>

            </div>


        </a>
    </div>


    {{-- Rockstar --}}
    <div class="col-md-6 mb-5">
        <a href="https://milagrosenterprisegroup.sharepoint.com/sites/ReconocimientosInternos/_layouts/15/listforms.aspx?cid=MDk0ZTQ1NWYtOWIyYy00MzA4LWIzOGItMDcyNWZjYmNlZTRm&nav=MTFjZjIxODItMTVkOS00MGM1LWFmODAtNTVlZTIxNjRjZTQw"
            target="_blank" class="text-decoration-none">

            <div class="corporativo-card">

                <div class="corporativo-logo-box">
                    <img src="{{ asset('img/corporativo/rockstar.png') }}" alt="Rockstar">
                </div>

                <div class="custom-title text-rockstar">
                    Programa de reconocimiento
                </div>

            </div>
        </a>
    </div>

</div>

@stop