@extends('landing.base')
@section('cuerpo')
    <nav class="navbar fixed-top navbar-toggleable-md navbar-inverse" id="mainNav">
        <div class="container">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">{{ config('app.name') }}</a>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#services">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#portfolio">Establecimiento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#about">Acerca</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#team">Equipo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#contact">Contacto</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link"  href="{{route('comun.login')}}" role="button" >Ingresar</a>
                        {{--<a class="nav-link" data-toggle="dropdown" href="{{route('comun.login')}}" role="button" aria-haspopup="true" aria-expanded="false">Login</a>
                        <div class="dropdown-menu animated fadeInDown">
                            <div class="container-fluid">
                                <form>
                                    <div id="ingreso">
                                        <div class="alert alert-info" role="alert">
                                            <span class="fa fa-info"></span> Ingrese al sistema
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control form-control-sm" placeholder="usuario">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <input type="password" class="form-control form-control-sm" placeholder="contraseña">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-sm btn-outline-secondary">
                                            Ingresar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>--}}
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro"><img src="img/logos/asecont_logo.png" alt="ASECONT PUYO LOGO" class="img-responsive"></div>
                <div class="intro-lead-in">Asesoría - Contable - Tributaria</div>
                <a href="#services" class="page-scroll btn btn-xl">Conece mas</a>
            </div>
        </div>
    </header>
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading" title="Servicios Contables y Tributarios">Servicios</h2>
                    <h3 class="section-subheading text-muted">Para personas naturales y jurídicas.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-6 wow fadeInLeft">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-handshake-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Tributación</h4>
                    <p class="text-muted">Nuestra experiencia profesional llevando obligaciones del <strong title="RUC" >Registro Único de Contribuyentes</strong> nos califica con excelencia ante el <strong title="SRI">Servicio de Rentas Internas</strong> para el fortalecimiento de la carga pública.</p>
                </div>
                <div class="col-md-6 wow fadeInRight">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-book fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Contabilidad</h4>
                    <p class="text-muted">Contamos con herramientas de última tecnología que ayuda al Cliente a organizar sus estados financieros para la toma de decisiones criticas de su Organización.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="portfolio" class="bg-faded">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Establecimiento</h2>
                    <h3 class="section-subheading text-muted">Siempre cerca de sus necesidades.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 portfolio-item wow fadeInLeft">
                    <a href="#certificados" class="portfolio-link">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-check fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/asecont_sri.jpg" class="img-fluid" alt="certificados asecont">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Certificados</h4>
                        <p class="text-muted">Contamos con toda la aprobación del SRI</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item wow fadeInUp">
                    <a href="#portfolioModal2" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-plus fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/asecont_archivo.jpg" class="img-fluid" alt="archivo asecont">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Archivo</h4>
                        <p class="text-muted">Nuestros archivos son digitales y físicos</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item wow fadeInRight">
                    <a href="#portfolioModal3" class="portfolio-link" data-toggle="modal">
                        <div class="portfolio-hover">
                            <div class="portfolio-hover-content">
                                <i class="fa fa-thumbs-up fa-3x"></i>
                            </div>
                        </div>
                        <img src="img/portfolio/asecont_system.jpg" class="img-fluid" alt="sistemas asecont">
                    </a>
                    <div class="portfolio-caption">
                        <h4>Sistemas</h4>
                        <p class="text-muted">Sistemas de última tecnología para beneficio del cliente</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Acerca</h2>
                    <h3 class="section-subheading text-muted">Nuestra experiencia en el tiempo.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <ul class="timeline wow zoomIn">
                        <li class="wow fadeInLeft">
                            <div class="timeline-image">
                                <img class="rounded-circle img-fluid" src="img/about/1999-asecont.jpg" alt="">
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
                        <li class="wow fadeInRight">
                            <div class="timeline-image post">
                                <img class="rounded-circle img-fluid" src="img/about/2015-asecont.jpg" alt="">
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
                        <li class="timeline-inverted post wow fadeInLeft">
                            <div class="timeline-image">
                                <img class="rounded-circle img-fluid" src="img/about/2017-asecont.jpg" alt="">
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
    </section>
    <section id="team" class="bg-faded">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Nuestro Equipo</h2>
                    <h3 class="section-subheading text-muted">Siempre con las últimas actualizaciones para su asesoramiento.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="team-member">
                        <img src="img/team/asecont-fany.jpg" class="mx-auto rounded-circle" alt="asecont equipo">
                        <h4>Lic. Fany Robalino</h4>
                        <p class="text-muted">Contadora CPA</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="team-member">
                        <img src="img/team/asecont-sebastian.jpg" class="mx-auto rounded-circle" alt="asecont equipo">
                        <h4>Ing. Sebastian Robalino</h4>
                        <p class="text-muted">Sistemas Contables</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 offset-lg-2 text-center">
                    <p class="large text-muted">La integración de nuestros profesionales nos da expertica en solución de requerimientos para nuestros clientes.</p>
                </div>
            </div>
        </div>
    </section>
    <aside class="clients">
        <div class="row" id="map" style="height: 400px">
        </div>
    </aside>
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contáctanos</h2>
                    <h3 class="section-subheading text-muted">Necesitas más información? <br> Envíanos tu inquietud y gustosos te ayudaremos</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form name="sentMessage" id="contactForm" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Nombre" id="name" required data-validation-required-message="Por favor ingrese su nombre.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Correo electrónico" id="email" required data-validation-required-message="Por favor ingrese su correo electrónico.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" placeholder="Teléfono" id="phone" required data-validation-required-message="Por favor ingrese su teléfono">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Mensaje o Comentario" id="message" required data-validation-required-message="Por favor ingrese su mensaje o comentario, gustosos lo atenderemos."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; ASECONT - PUYO 2017</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li class="list-inline-item"><a href="https://www.facebook.com/ASECONT.PUYO" target="_blank"><i class="fa fa-facebook"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li class="list-inline-item"><a href="#">Políticas de Privacidad</a>
                        </li>
                        <li class="list-inline-item"><a href="#">Terminos de Uso</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection