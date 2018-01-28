@extends('landing.base')
@section('cuerpo')
    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">{{config('app.name')}}</a>
            </div>
            <div class="collapse navbar-collapse" id="navigation-example">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="{{route('comun.login')}}" target="_blank">
                            <i class="material-icons">fingerprint</i> Ingresar
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/ASECONT.PUYO/" target="_blank" class="btn btn-simple btn-white btn-just-icon">
                            <i class="fa fa-facebook-square"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <div class="header header-filter wow fadeIn">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="center-block text-center">
                            <img src="{{asset('img/asecont_logo.png')}}" alt="ASECONT PUYO LOGO" class="img-responsive center-block"></div>
                            <h1 class="text-center subtitulo">Asesoría - Contable - Tributaria</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main main-raised" id="invitado">
            <div class="container">
                <div class="section text-center section-landing">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h2 class="title">Servicios</h2>
                            <h5 class="description">Para personas naturales y jurídicas.</h5>
                        </div>
                    </div>
                    <div class="features">
                        <div class="row text-center">
                            <div class="col-md-6 wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                                <span class="fa-stack fa-4x">
                                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa fa-handshake-o fa-stack-1x fa-inverse"></i>
                                </span>
                                <h4 class="service-heading">Tributación</h4>
                                <p class="text-muted">Nuestra experiencia profesional llevando obligaciones del <strong title="RUC">Registro Único de Contribuyentes</strong> nos califica con excelencia ante el <strong title="SRI">Servicio de Rentas Internas</strong> para el fortalecimiento de la carga pública.</p>
                            </div>
                            <div class="col-md-6 wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                                <span class="fa-stack fa-4x">
                                    <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                    <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                                </span>
                                <h4 class="service-heading">Contabilidad</h4>
                                <p class="text-muted">Contamos con herramientas de última tecnología que ayuda al Cliente a organizar sus estados financieros para la toma de decisiones criticas de su Organización.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section text-center">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <h2 class="title">Establecimiento</h2>
                            <h5 class="description">Siempre cerca de sus necesidades.</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 portfolio-item wow fadeInLeft" style="visibility: visible; animation-name: fadeInLeft;">
                            <a href="#certificados" class="portfolio-link">
                                <img src="{{asset('img/imagenes/asecont_sri.jpg')}}" class="img-raised img-rounded img-responsive" alt="certificados asecont">
                            </a>
                            <div class="portfolio-caption">
                                <h4>Certificados</h4>
                                <p class="text-muted">Contamos con toda la aprobación del SRI</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 portfolio-item wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                            <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                                <img src="{{asset('img/imagenes/asecont_archivo.jpg')}}" class="img-raised img-rounded img-responsive" alt="archivo asecont">
                            </a>
                            <div class="portfolio-caption">
                                <h4>Archivo</h4>
                                <p class="text-muted">Nuestros archivos son digitales y físicos</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 portfolio-item wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                            <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                                <img src="{{asset('img/imagenes/asecont_system.jpg')}}" class="img-raised img-rounded img-responsive" alt="sistemas asecont">
                            </a>
                            <div class="portfolio-caption">
                                <h4>Sistemas</h4>
                                <p class="text-muted">Sistemas de última tecnología para beneficio del cliente</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2 class="title">Acerca</h2>
                            <h3 class="description">Nuestra experiencia en el tiempo.</h3>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="timeline wow zoomIn">
                                <li class="timeline-inverted wow fadeInRight">
                                    <div class="timeline-image">
                                        <img class="rounded-circle img-fluid" src="{{asset('img/imagenes/1999-asecont.jpg')}}" alt="Asecont Puyo 1999" >
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4>1999-2012</h4>
                                            <h4 class="subheading">Nuestros comienzos</h4>
                                        </div>
                                        <div class="timeline-body">
                                            <p class="text-muted">Desde el 99' contribuyendo al país.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="wow fadeInLeft">
                                    <div class="timeline-image post">
                                        <img class="rounded-circle img-fluid" src="{{asset('img/imagenes/2015-asecont.jpg')}}" alt="Asecont Puyo 2015">
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4>2015</h4>
                                            <h4 class="subheading">Facturación electrónica</h4>
                                        </div>
                                        <div class="timeline-body">
                                            <p class="text-muted"><strong>SRI</strong> implanta Facturación electrónica en el Ecuador, ASECONT - PUYO integra a sus funciones los comprobantes electrónicos emitidos.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-inverted post wow fadeInRight">
                                    <div class="timeline-image">
                                        <img class="rounded-circle img-fluid" src="{{asset('img/imagenes/2017-asecont.jpg')}}" alt="Asecont Puyo 2017">
                                    </div>
                                    <div class="timeline-panel">
                                        <div class="timeline-heading">
                                            <h4>2017</h4>
                                            <h4 class="subheading">Tributación electrónica</h4>
                                        </div>
                                        <div class="timeline-body">
                                            <p class="text-muted">Sistema de tributación electrónica online.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="timeline-inverted post wow fadeInUpBig">
                                    <div class="timeline-image">
                                        <h4>Seguimos<br>avanzando.</h4>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-lg-12 text-center">
                            <h2 class="title">Nuestro Equipo</h2>
                            <h3 class="description">Siempre con las últimas actualizaciones para su asesoramiento.</h3>
                        </div>
                    </div>
                    <div class="team">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="team-player">
                                    <img src="{{asset('img/imagenes/asecont-fany.jpg')}}" alt="Asecont Puyo - Fany Robalino" class="img-raised img-circle">
                                    <h4 class="title">Lic. Fany Robalino<br/>
                                        <small class="text-muted">Contadora CPA</small>
                                    </h4>
                                    <p class="description">30 años de experticia garantiza nuestro trabajo contable.</p>
                                    <a href="https://www.facebook.com/asecont.puyo.fra?hc_ref=ARS2mXg8by_YPFDm3O-DWOnscxZZTaroLS2-h7nRSIWHfM5vsoAFW-LdnX9RIHuH8PM" target="_blank" class="btn btn-simple btn-just-icon btn-default"><i class="fa fa-facebook-square"></i></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="team-player">
                                    <img src="{{asset('img/imagenes/asecont-sebastian.jpg')}}" alt="Asecont Puyo - Sebastian Robalino" class="img-raised img-circle">
                                    <h4 class="title">Sebastian Robalino<br />
                                        <small class="text-muted">Ing. Sistemas</small>
                                    </h4>
                                    <p class="description">Integracion en Sistemas y Servicios de facturación electronica y sistemas contables para mejorar tiempos.</p>
                                    <a href="https://github.com/serobalino" target="_blank" class="btn btn-simple btn-just-icon"><i class="fa fa-github"></i></a>
                                    <a href="https://www.facebook.com/srobalinoa" target="_blank" class="btn btn-simple btn-just-icon"><i class="fa fa-facebook-square"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <h4 class="description">La integración de nuestros profesionales nos da expertica en solución de requerimientos para nuestros clientes.</h4>
                    </div>
                </div>
                <enviar-email></enviar-email>
            </div>
            <mapa></mapa>
        </div>
        <footer class="footer">
            <div class="container">
                <nav class="pull-left">
                    <ul>
                        <li>
                            <a href="http://www.creative-tim.com">
                                SRI
                            </a>
                        </li>
                        <li>
                            <a href="http://presentation.creative-tim.com">
                                IESS
                            </a>
                        </li>
                        <li>
                            <a href="http://blog.creative-tim.com">
                                Compras Publicas
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="copyright pull-right">
                    &copy; {{date('Y')}}, made with <i class="fa fa-heart heart"></i> by GoldenBytes
                </div>
            </div>
        </footer>
    </div>
@endsection