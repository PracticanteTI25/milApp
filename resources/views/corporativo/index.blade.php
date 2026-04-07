@extends('layouts.admin')

@section('title', 'Corporativo')

@section('content_header')
<h1 class="colorgris body">Corporativo</h1>
@stop

@section('content')

<div class="row">

    {{-- Innova --}}
    <div class="col-md-6 mb-5">
        <a href="https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/Lists/INNOVA/NewForm.aspx?Source=https%3A%2F%2Fmilagrosenterprisegroup.sharepoint.com%2Fsites%2FIntranetgrupomilagros%2FLists%2FINNOVA%2FAllItems.aspx%3Fenv%3DWebViewList%26npsAction%3DcreateList&ContentTypeId=0x0100FE4D0C843418844E96C198EB0F6EC007007461DDD6E2C22D4BBDB509EFE772998E&id=%2Fsites%2FIntranetgrupomilagros%2FLists%2FINNOVA"
            target="_blank" class="text-decoration-none">

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

{{-- =========================
ACCESOS CORPORATIVOS
========================= --}}

<h4 class="colorgris mb-3">Accesos corporativos</h4>

@php
    $accesos = [
        [
            'label' => 'Módulo autogestión TH',
            'url' => 'https://portal.heinsohn.com.co/',
            'icon' => 'fas fa-user-cog',
        ],
        [
            'label' => 'Solicitud personal',
            'url' => 'https://forms.office.com/pages/responsepage.aspx?id=lRxTOSl_NkOErqCKUsFg3EpK_-2GLZlKgheTfN5nH0hUQklXT05JUlpZNjNORDNJWDREV1dFVjIzMi4u&route=shorturl',
            'icon' => 'fas fa-id-card',
        ],
        [
            'label' => 'Solicitud mantenimiento',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/ReconocimientosInternos/_layouts/15/AccessDenied.aspx?Source=https%3A%2F%2Fmilagrosenterprisegroup%2Esharepoint%2Ecom%2Fsites%2FReconocimientosInternos%2F%5Flayouts%2F15%2Flistforms%2Easpx%3Fcid%3DOTAwMjY5YTQtMzUzMC00YzMyLWEzNmYtNzIwYzBjNjA0YjM3%26nav%3DZjU3NTA0OWQtYTQ5YS00OWU1LWE0ZDktOTA5ZjM4ODJjNDI2&correlation=026d05a2%2D7092%2D1000%2D76f6%2Db75901c602a4&Type=web&SiteId=0c5c052c%2D6857%2D40db%2Db33f%2D6cfde2b3ae50',
            'icon' => 'fas fa-tools',
        ],
        [
            'label' => 'Solicitud diseño',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/_layouts/15/listforms.aspx?cid=NzRlYWMxOTAtMWFlNy00ODk4LTk5MmMtYjRjNjcyZGJlYTc0&nav=NTc4ZmE2NDktMGZiZC00ZjBmLTllMDEtZDZhZWE5MDRiMDMz',
            'icon' => 'fas fa-pencil-ruler',
        ],
        [
            'label' => 'Documentos Generales',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/Documentos%20compartidos/Forms/AllItems.aspx?id=%2Fsites%2FIntranetgrupomilagros%2FDocumentos%20compartidos%2FGesti%C3%B3n%20documental&p=true&ga=1',
            'icon' => 'fas fa-folder-open',
        ],
        [
            'label' => 'Proceso de compras',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/Documentos%20compartidos/Forms/AllItems.aspx?id=%2Fsites%2FIntranetgrupomilagros%2FDocumentos%20compartidos%2FProceso%20de%20Compras&p=true&ga=1',
            'icon' => 'fas fa-shopping-cart',
        ],
        [
            'label' => 'Indicadores',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/Documentos%20compartidos/Forms/AllItems.aspx?as=json&FolderCTID=0x0120009CFABDF7DB82F04B831A495D087AF694&id=%2Fsites%2FIntranetgrupomilagros%2FDocumentos%20compartidos%2FIndicadores',
            'icon' => 'fas fa-chart-line',
        ],
        [
            'label' => 'Go mango',
            'url' => 'https://beneficiosmilagros.gomango.co/',
            'icon' => 'fas fa-seedling',
        ],
        [
            'label' => 'Solicitud debida diligencia',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/_layouts/15/listforms.aspx?cid=OTA2ZDY0ZTYtZWI2NC00YzE2LTlkYTYtYWI3MmY5NDMxMWEy&nav=N2M0NzRiZDEtOTE3Ni00OGU3LTgwOGMtMGRjZTczM2YxNDVm',
            'icon' => 'fas fa-clipboard-check',
        ],
        [
            'label' => 'Requerimientos jurídicos',
            'url' => 'https://milagrosenterprisegroup.sharepoint.com/sites/Intranetgrupomilagros/_layouts/15/AccessDenied.aspx?Source=https%3A%2F%2Fmilagrosenterprisegroup%2Esharepoint%2Ecom%2Fsites%2FIntranetgrupomilagros%2FLists%2FRequerimientos%20Juridicos%2FNewForm%2Easpx%3FSource%3Dhttps%253A%252F%252Fmilagrosenterprisegroup%252Esharepoint%252Ecom%252Fsites%252FIntranetgrupomilagros%252FLists%252FRequerimientos%252520Juridicos%252FAllItems%252Easpx%253Fenv%253DWebViewList%26ContentTypeId%3D0x01003B52BED696FFCD4D963AC33C0166399700C087544EF437764F8C5893E5646AB9BC%26RootFolder%3D%252Fsites%252FIntranetgrupomilagros%252FLists%252FRequerimientos%2520Juridicos&correlation=256d05a2%2D70c3%2D1000%2D76f6%2Db26278733890&Type=list&name=8951a10e%2Dc6c8%2D4058%2D8d25%2De5da357bf7d5',
            'icon' => 'fas fa-balance-scale',
        ],
        [
            'label' => 'Imagen corporativa Milagros',
            'url' => 'https://milagrosenterprisegroup-my.sharepoint.com/personal/yesenia_garcia_grupomilagros_com/_layouts/15/onedrive.aspx?id=%2Fpersonal%2Fyesenia%5Fgarcia%5Fgrupomilagros%5Fcom%2FDocuments%2FEscritorio%2FImagen%20corporativa%20intranet&ga=1',
            'icon' => 'fas fa-images',
        ],
        [
            'label' => 'Mila',
            'url' => 'http://192.168.2.46/glpi/front/central.php',
            'icon' => 'fas fa-desktop',
        ],
    ];
@endphp


<div class="row">

    @foreach ($accesos as $a)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">

            <a href="{{ $a['url'] }}" target="_blank" rel="noopener noreferrer" class="corp-link">
                <div class="corp-tile">

                    <div class="corp-icon">
                        <i class="{{ $a['icon'] }}"></i>
                    </div>

                    <div class="corp-text">
                        {{ $a['label'] }}
                    </div>

                </div>
            </a>

        </div>
    @endforeach

</div>

{{-- =========================
TRANSFORMACIÓN DIGITAL (SAP)
========================= --}}
<div class="row mt-4 mb-5">
    <div class="col-12 text-center mb-4">
        <h3 class="colorgris body mb-0">¡Ingresa al mundo de la transformación digital!</h3>
    </div>

    {{-- PRUEBAS --}}
    <div class="col-12 col-md-6 mb-4">
        <a href="https://my423410.s4hana.cloud.sap" target="_blank" rel="noopener noreferrer" class="corp-big-link">
            <div class="corp-big-card">
                <div class="corp-big-logo">
                    <img src="{{ asset('img/corporativo/sap/sap.png') }}" alt="SAP - Pruebas">
                </div>

                <div class="corp-big-label">
                    PRUEBAS
                </div>
            </div>
        </a>
    </div>

    {{-- PRODUCCIÓN --}}
    <div class="col-12 col-md-6 mb-4">
        <a href="https://my425704.s4hana.cloud.sap" target="_blank" rel="noopener noreferrer" class="corp-big-link">
            <div class="corp-big-card">
                <div class="corp-big-logo">
                    <img src="{{ asset('img/corporativo/sap/sap.png') }}" alt="SAP - Producción">
                </div>

                <div class="corp-big-label">
                    PRODUCCIÓN
                </div>
            </div>
        </a>
    </div>
</div>

@stop