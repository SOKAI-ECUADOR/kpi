const routesAndValue = [{
        nombre: "Empresa",
        value: 1,
        ver: {
            up: [{
                path: "/administrar/empresa",
                name: "empresa",
                component: () =>
                    import (
                        "../views/Administrar/Empresas/EmpresaLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Empresa",
                            active: true
                        }
                    ],
                    pageTitle: "Empresa",
                    rule: "editor",
                    secure: true
                }
            }],
            ups: [{
                path: "/administrar/empresa",
                name: "empresa",
                component: () =>
                    import ("../views/Administrar/Empresas/EmpresaVer.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                    path: "/administrar/empresa/:id/editar",
                    name: "editarEmpresa",
                    component: () =>
                        import (
                            "../views/Administrar/Empresas/EmpresaEditar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    // path: "/app/agregarEmpresa",
                    path: "/administrar/empresa/agregar",
                    name: "agregarEmpresa",
                    component: () =>
                        import (
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: [{
                    path: "/administrar/empresa",
                    name: "editarEmpresados",
                    component: () =>
                        import (
                            "../views/Administrar/Empresas/EmpresaAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/administrar/empresa",
                    name: "editarEmpresatres",
                    component: () =>
                        import ("../views/Administrar/Empresas/EmpresaVer.vue"),
                    meta: {
                        rule: "editor"
                    }
                }
            ]
        }
    },
    {
        nombre: "Establecimiento",
        value: 2,
        ver: {
            up: [{
                path: "/administrar/establecimiento",
                name: "establecimiento",
                component: () =>
                    import (
                        "../views/Administrar/Establecimiento/EstablecimientoLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Establecimiento",
                            active: true
                        }
                    ],
                    pageTitle: "Establecimiento",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/administrar/establecimiento/:id/editar",
                name: "editaragestable",
                component: () =>
                    import (
                        "../views/Administrar/Establecimiento/EstablecimientoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administración"
                        },
                        {
                            title: "Establecimiento",
                            active: true
                        }
                    ],
                    pageTitle: "Editar agestable",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/administrar/establecimiento/agregar",
                name: "agestable",
                component: () =>
                    import (
                        "../views/Administrar/Establecimiento/EstablecimientoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administración"
                        },
                        {
                            title: "Establecimiento",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Establecimiento",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Punto de emision",
        value: 3,
        ver: {
            up: [{
                path: "/administrar/emision",
                name: "emision",
                component: () =>
                    import (
                        "../views/Administrar/Punto_de_emision/PuntoDeEmisionLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Emision",
                            active: true
                        }
                    ],
                    pageTitle: "Puntos de Emision",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/administrar/punto-de-emision/agregar",
                name: "puntosemision",
                component: () =>
                    import (
                        "../views/Administrar/Punto_de_emision/PuntoDeEmisionAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administración"
                        },
                        {
                            title: "Puntos de Emisión",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Puntos de Emisión",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/administrar/punto-de-emision/:id/editar",
                name: "editarptemision",
                component: () =>
                    import (
                        "../views/Administrar/Punto_de_emision/PuntoDeEmisionAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administración"
                        },
                        {
                            title: "Puntos de Emisión",
                            active: true
                        }
                    ],
                    pageTitle: "Editar Nuevo Puntos de Emisión",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Usuarios",
        value: 4,
        ver: {
            up: [{
                path: "/administrar/usuarios",
                name: "usuarios",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosList.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Usuario",
                            active: true
                        }
                    ],
                    pageTitle: "Usuario",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/administrar/usuarios/:id/editar",
                name: "agregarUsuarioid",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/administrar/usuarios/agregar",
                name: "agregarUsuario",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Cobranzas",
        value: 34,
        ver: {
            up: [{
                path: "/administrar/cobranzas",
                name: "Cobranzas",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosList.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Cobranzas",
                            active: true
                        }
                    ],
                    pageTitle: "Cobranzas",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Mora",
        value: 35,
        ver: {
            up: [{
                path: "/administrar/mora",
                name: "Mora",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosList.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Mora",
                            active: true
                        }
                    ],
                    pageTitle: "Mora",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Forma_pago",
        value: 36,
        ver: {
            up: [{
                path: "/administrar/forma_pagos",
                name: "Forma_pagos",
                component: () =>
                    import (
                        "../views/Administrar/Forma_pagos/Forma_pagos.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Forma de pagos",
                            active: true
                        }
                    ],
                    pageTitle: "Forma de Pagos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Forma_cobros",
        value: 37,
        ver: {
            up: [{
                path: "/administrar/forma_cobros",
                name: "Forma_cobros",
                component: () =>
                    import (
                        "../views/Administrar/Usuarios/UsuariosList.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Administrar"
                        },
                        {
                            title: "Forma de cobros",
                            active: true
                        }
                    ],
                    pageTitle: "Forma de cobros",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Proyecto",
        value: 31,
        ver: {
            up: [{
                path: "/administrar/proyecto",
                name: "listarproyecto",
                component: () =>
                    import (
                        "../views/Administrar/Proyectos/ProyectoListar.vue"
                    ),
                meta: {
                    //pageTitle: "Usuario",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
        /*editar: {
        up: [{
            path: "/administrar/proyecto/:id/editar",
            name: "editarProyecto",
            component: () =>
                import (
                    "../views/Administrar/Proyectos/ProyectoAgregar.vue"
                ),
            meta: {
                rule: "editor"
            }
        }],
        down: []
    },
    crear: {
        up: [{
            path: "/administrar/proyecto/agregar",
            name: "agregarProyecto",
            component: () =>
                import (
                    "../views/Administrar/Proyectos/ProyectoAgregar.vue"
                ),
            meta: {
                rule: "editor"
            }
        }],
        down: []
    }*/
    },
    {
        nombre: "Codigo Impuesto",
        value: 5,
        ver: {
            up: [{
                path: "/administrar/sri/codigo-impuesto",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Codigo_Impuesto/ImpuestosLista.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Codigo ICE",
        value: 65,
        ver: {
            up: [{
                path: "/administrar/sri/codigo-ice",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Codigo_ICE/ICEListar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Tipo comprobante",
        value: 6,
        ver: {
            up: [{
                path: "/administrar/sri/tipo-comprobante",
                name: "tipcomprobante",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Tipo_comprobante/TipocomprobanteLista.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Impuestos retenciones",
        value: 7,
        ver: {
            up: [{
                path: "/administrar/sri/impuestos-rentenciones",
                name: "imprentenciones",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Impuestos_retenciones/ImpuestosRetenciones.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Tipo sustento",
        value: 8,
        ver: {
            up: [{
                path: "/administrar/sri/tipo-sustento",
                name: "tiposustento",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Tipo_sustento/TiposustentoLista.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Plan cuentas",
        value: 9,
        ver: {
            up: [{
                path: "/contabilidad/plan-cuentas",
                name: "contabilidad-cuentas",
                component: () =>
                    import (
                        "../views/Contabilidad/Plan_de_cuentas/PlanDeCuentaLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Contabilidad"
                        },
                        {
                            title: "Plan de cuentas",
                            active: true
                        }
                    ],
                    pageTitle: "Plan de cuentas",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Forma de pagos SRI",
        value: 63,
        ver: {
            up: [{
                path: "/administrar/sri/forma_pagos_sri",
                name: "cuentas",
                component: () =>
                    import (
                        "../views/Administrar/SRI/Forma_pagos_sri/Forma_pagos_sri.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "SRI"
                        },
                        {
                            title: "Forma de pagos SRI",
                            active: true
                        }
                    ],
                    pageTitle: "Forma de pagos SRI",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Ejercicio Contable",
        value: 68,
        ver: {
            up: [{
                path: "/administrar/cierre_contable/ejercicio_contable",
                name: "ejercicio_contable-ver",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Cierre Periodo/CierrePeriodoLista.vue"
                    ),
                meta: {

                    //pageTitle: "Cierre Periodo",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/administrar/cierre_contable/ejercicio_contable/agregar",
                name: "ejercicio_contable-agregar",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Cierre Periodo/CierrePeriodoAgregar.vue"
                    ),
                meta: {


                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/administrar/cierre_contable/ejercicio_contable/:id/editar",
                name: "ejercicio_contable-editar",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Cierre Periodo/CierrePeriodoVer.vue"
                    ),
                meta: {
                    //pageTitle: "Editar asiento contable",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Balance Inicial",
        value: 69,
        ver: {
            up: [{
                path: "/administrar/cierre_contable/balance_inicial",
                name: "balance_inicial-ver",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Balance Inicial/BalanceInicialLista.vue"
                    ),
                meta: {

                    //pageTitle: "Balance Inicial",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/administrar/cierre_contable/balance_inicial/agregar",
                name: "balance_inicial-agregar",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Balance Inicial/BalanceInicialAgregar.vue"
                    ),
                meta: {


                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/administrar/cierre_contable/balance_inicial/:id/editar",
                name: "balance_inicial-editar",
                component: () =>
                    import (
                        "../views/Administrar/Cierre Contable/Balance Inicial/BalanceInicialVer.vue"
                    ),
                meta: {
                    //pageTitle: "Editar asiento contable",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Asientos contables",
        value: 10,
        ver: {
            up: [{
                path: "/contabilidad/asientos-contables",
                name: "asientos-agregar",
                component: () =>
                    import (
                        "../views/Contabilidad/Asientos_contables/AsientosContablesLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Contabilidad"
                        },
                        {
                            title: "Asientos Contables",
                            active: true
                        }
                    ],
                    pageTitle: "Asientos Contables",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/contabilidad/asientos-contables/agregar",
                name: "asientos",
                component: () =>
                    import (
                        "../views/Contabilidad/Asientos_contables/AsientosContables.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Contabilidad"
                        },
                        {
                            title: "Asientos Contables",
                            active: true,
                            url: "/contabilidad/asientos-contables"
                        },
                        {
                            title: "Agregar",
                            active: true
                        }
                    ],
                    pageTitle: "Asientos Contables",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/contabilidad/asientos-contables/:id/editar",
                    name: "asientos-editar",
                    component: () =>
                        import (
                            "../views/Contabilidad/Asientos_contables/AsientosContablesEditar.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true,
                                url: "/contabilidad/asientos-contables"
                            },
                            {
                                title: "Editar asiento"
                            }
                        ],
                        pageTitle: "Editar asiento contable",
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/contabilidad/asientos-contables/:id/ver",
                    name: "asientos-ver",
                    component: () =>
                        import (
                            "../views/Contabilidad/Asientos_contables/AsientosContablesVer.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Contabilidad"
                            },
                            {
                                title: "Asientos Contables",
                                active: true,
                                url: "/contabilidad/asientos-contables"
                            },
                            {
                                title: "Ver asiento"
                            }
                        ],
                        pageTitle: "Ver asiento contable",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },

    {
        nombre: "Nota de credito",
        value: 11,
        ver: {
            up: [{
                path: "/facturacion/nota-credito",
                name: "listarNotaCred",
                component: () =>
                    import (
                        "../views/Facturacion/Nota_credito/NotaCreditoLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Nota Credito",
                            active: true
                        }
                    ],
                    pageTitle: "Nota Credito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/facturacion/nota-credito/:id/ver",
                    name: "editarNotaCred",
                    component: () =>
                        import (
                            "../views/Facturacion/Nota_credito/NotaCreditoVer.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Nota Credito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Credito",
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/nota-credito/:id/editar",
                    name: "editarNotaCredEditar",
                    component: () =>
                        import ("../views/Facturacion/Nota_credito/NotaCreditoEditar.vue"),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Nota Credito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Credito",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/facturacion/nota-credito/agregar",
                name: "agregarNotaCredito",
                component: () =>
                    import (
                        "../views/Facturacion/Nota_credito/NotaCreditoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Nota Credito",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Nota Credito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Facturas",
        value: 12,
        ver: {
            up: [{
                path: "/facturacion/factura-venta",
                name: "facturasventa",
                component: () =>
                    import (
                        "../views/Facturacion/Factura_venta/FacturasLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Facturas",
                            active: true
                        }
                    ],
                    pageTitle: "Facturas",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/facturacion/factura-venta/:id/ver",
                    name: "verfacturaver",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_venta/FacturasVer.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/facturacion/factura-venta/:id/editar",
                    name: "editarfactura",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_venta/FacturasEditar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                    path: "/facturacion/factura-venta/agregar",
                    name: "factura",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_venta/FacturasAgregar.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Cliente",
                                active: true
                            }
                        ],
                        pageTitle: "Crear factura",
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/factura-venta/duplicar",
                    name: "facturaduplicar",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_venta/FacturaDuplicar.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Facturacion"
                            },
                            {
                                title: "Factura",
                                active: true
                            }
                        ],
                        pageTitle: "Crear factura",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Factura Acumulada",
        value: 70,
        ver: {
            up: [{
                path: "/facturacion/factura_acumulada",
                name: "listarFacturaAcum",
                component: () =>
                    import (
                        "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaLista.vue"
                    ),
                meta: {

                    pageTitle: "Nota de Venta",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/facturacion/factura_acumulada/:id/ver",
                    name: "verFacturaAcumulada",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaVer.vue"
                        ),
                    meta: {

                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/factura_acumulada/:id/editar",
                    name: "editarFacturaAcumulada",
                    component: () =>
                        import ("../views/Facturacion/Factura_Aumulada/FacturaAcumuladaEditar.vue"),
                    meta: {

                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                    path: "/facturacion/factura_acumulada/agregar",
                    name: "agregarFacturaAcumulada",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaAgregar.vue"
                        ),
                    meta: {


                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/factura_acumulada/duplicar",
                    name: "factura_acumuladaduplicar",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaDuplicar.vue"
                        ),
                    meta: {


                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/factura_acumuladaproforma/:id",
                    name: "factura_acumuladaproforma",
                    component: () =>
                        import (
                            "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaProforma.vue"
                        ),
                    meta: {


                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Guia Remision",
        value: 71,
        ver: {
            up: [{
                path: "/facturacion/guia_remision",
                name: "listarGuiaRemision",
                component: () =>
                    import (
                        "../views/Facturacion/Guia_Remision/Guia_RemisionListar.vue"
                    ),
                meta: {

                    pageTitle: "Guia Remision",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/facturacion/guia_remision/:id/editar",
                name: "editarGuiaRemision",
                component: () =>
                    import (
                        "../views/Facturacion/Guia_Remision/Guia_RemisionAgregar.vue"
                    ),
                meta: {

                    rule: "editor",
                    secure: true
                }
            }, ],
            down: []
        },
        // editar: {
        //     up: [
        //         {
        //             path: "/facturacion/factura_acumulada/:id/ver",
        //             name: "verFacturaAcumulada",
        //             component: () =>
        //                 import (
        //                     "../views/Facturacion/Factura_Aumulada/FacturaAcumuladaVer.vue"
        //                 ),
        //             meta: {

        //                 rule: "editor",
        //                 secure: true
        //             }
        //         },
        //         {
        //             path: "/facturacion/factura_acumulada/:id/editar",
        //             name: "editarFacturaAcumulada",
        //             component: () =>
        //                 import ("../views/Facturacion/Factura_Aumulada/FacturaAcumuladaEditar.vue"),
        //             meta: {

        //                 rule: "editor",
        //                 secure: true
        //             }
        //         }
        //     ],
        //     down: []
        // },
        crear: {
            up: [{
                path: "/facturacion/guia_remision/agregar",
                name: "agregarGuiaRemision",
                component: () =>
                    import (
                        "../views/Facturacion/Guia_Remision/Guia_RemisionAgregar.vue"
                    ),
                meta: {


                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Proforma",
        value: 13,
        ver: {
            up: [{
                path: "/facturacion/proforma",
                name: "proforma",
                component: () =>
                    import (
                        "../views/Facturacion/Proforma/ProformaLista.vue"
                    ),
                meta: {
                    breadcrumb: [
                        { title: "Home", url: "/" },
                        { title: "Facturacion" },
                        { title: "Proforma", active: true }
                    ],
                    pageTitle: "Proforma",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/facturacion/proforma/agregar",
                name: "agregarproforma",
                component: () =>
                    import (
                        "../views/Facturacion/Proforma/ProformaAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Proforma",
                            active: true
                        }
                    ],
                    pageTitle: "Proforma",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/facturacion/proforma/:id/editar",
                name: "Editar Proforma",
                component: () =>
                    import (
                        "../views/Facturacion/Proforma/ProformaAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Editar Proforma",
                            active: true
                        }
                    ],
                    pageTitle: "Editar Proforma",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Pago cliente",
        value: 23,
        ver: {
            up: [{
                path: "/facturacion/cuentas-por-cobrar",
                name: "pago",
                component: () =>
                    import (
                        "../views/Facturacion/Pago_cliente/PagoCliente.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Cuentas por Cobrar",
                            active: true
                        }
                    ],
                    pageTitle: "Cuentas por Cobrar",
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Cliente",
        value: 15,
        ver: {
            up: [{
                path: "/facturacion/clientes",
                name: "contacto",
                component: () =>
                    import (
                        "../views/Facturacion/Clientes/ClientesLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Cliente",
                            active: true
                        }
                    ],
                    pageTitle: "Cliente",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/facturacion/cliente/agregar",
                name: "cliente",
                component: () =>
                    import (
                        "../views/Facturacion/Clientes/ClientesAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Cliente",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Cliente",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/facturacion/cliente/:id/editar",
                name: "clienteid",
                component: () =>
                    import (
                        "../views/Facturacion/Clientes/ClientesAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Cliente",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Cliente",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Vendedor",
        value: 16,
        ver: {
            up: [{
                path: "/facturacion/vendedor",
                name: "vendedor",
                component: () =>
                    import (
                        "../views/Facturacion/Vendedor/VendedorLista.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Vendedor",
                            active: true
                        }
                    ],
                    pageTitle: "Vendedor",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/facturacion/vendedor/agregar",
                name: "agregarvendedor",
                component: () =>
                    import (
                        "../views/Facturacion/Vendedor/VendedorAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Vendedor",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Vendedor",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/facturacion/vendedor/:id/editar",
                name: "vendedorid",
                component: () =>
                    import (
                        "../views/Facturacion/Vendedor/VendedorAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Vendedor",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Vendedor",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Reporteria",
        value: 38,
        ver: {
            up: [{
                path: "/facturacion/reporteria",
                name: "Reporteria",
                component: () =>
                    import ("../views/Facturacion/Reporteria/index.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Reporteria",
                            active: true
                        }
                    ],
                    pageTitle: "Reporteria",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Bodega",
        value: 17,
        ver: {
            up: [{
                path: "/inventario/bodega",
                name: "bodega",
                component: () =>
                    import ("../views/Inventario/BodegaLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Bodega",
                            active: true
                        }
                    ],
                    pageTitle: "Bodega",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/inventario/bodega/:id/gestionar",
                name: "gestionbodega",
                component: () =>
                    import ("../views/Inventario/BodegaGestionar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Bodega"
                        },
                        {
                            title: "Gestion Bodega",
                            active: true
                        }
                    ],
                    pageTitle: "Gestion Bodega",
                    rule: "editor",
                    secure: true
                }
            }],
            down: [{
                path: "/inventario/bodega/:id/gestionar",
                name: "gestionbodega",
                component: () =>
                    import ("../views/Inventario/BodegaGestionar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Bodega"
                        },
                        {
                            title: "Gestion Bodega",
                            active: true
                        }
                    ],
                    pageTitle: "Gestion Bodega",
                    rule: "editor",
                    secure: true
                }
            }]
        }
    },
    {
        nombre: "Catalogo",
        value: 18,
        ver: {
            up: [{
                path: "/inventario/catalogo",
                name: "catalogo",
                component: () =>
                    import ("../views/Catalogo/CatalogoLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Catalogo",
                            active: true
                        }
                    ],
                    pageTitle: "Catalogo",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/inventario/catalogo/agregar",
                name: "agregarCatalogo",
                component: () =>
                    import ("../views/Catalogo/CatalogoAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Catalogo",
                            active: true
                        }
                    ],
                    pageTitle: "Agregar Catalogo",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/inventario/catalogo/:id/editar",
                name: "editarCatalogo",
                component: () =>
                    import ("../views/Catalogo/CatalogoAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        },
                        {
                            title: "Catalogo",
                            active: true
                        }
                    ],
                    pageTitle: "Editar Catalogo",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Nomina",
        value: 19,
        ver: {
            up: [{
                path: "/nomina/empleados",
                name: "listar",
                component: () =>
                    import ("../views/Nomina/NominaLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Nomina"
                        },
                        {
                            title: "Listar Empleados",
                            active: true
                        }
                    ],
                    pageTitle: "Empleados",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/empleados/agregar",
                name: "empleado",
                component: () =>
                    import ("../views/Nomina/NominaAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Nomina"
                        },
                        {
                            title: "Empleados",
                            active: true
                        }
                    ],
                    pageTitle: "Empleado",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/nomina/empleados/:id/editar",
                name: "empleadoid",
                component: () =>
                    import ("../views/Nomina/NominaAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Nomina"
                        },
                        {
                            title: "Empleados",
                            active: true
                        }
                    ],
                    pageTitle: "Empleado",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },

    {
        nombre: "Ingresos-Egresos",
        value: 29,
        ver: {
            up: [{
                path: "/nomina/ingreso-egreso",
                name: "listaringreso-egreso",
                component: () =>
                    import (
                        "../views/Nomina/Ingresos-Egresos/Ingresos-egresos.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Ingresos"
                        },
                        {
                            title: "Egresos",
                            active: true
                        },
                        {
                            rule: "editor"
                        }
                    ],
                    pageTitle: "Ingresos-Egresos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/ingreso-egreso/agregar",
                name: "ingresos-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Ingresos-Egresos/IngresosAgregar.vue"
                    ),
                meta: {
                    //pageTitle: "Asignar Ingresos e Egresos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/nomina/ingreso-egreso/:id/editar",
                name: "ingreso-editar",
                component: () =>
                    import (
                        "../views/Nomina/Ingresos-Egresos/IngresosAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Asignar-Ingresos",
        value: 32,
        ver: {
            up: [{
                path: "/nomina/asignar-ingreso",
                name: "listarasignaringreso-egreso",
                component: () =>
                    import (
                        "../views/Nomina/Asignar-Ingreso/Asignar-IngresoLista.vue"
                    ),
                meta: {
                    pageTitle: "Asignar Ingresos e Egresos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/asignar-ingreso/agregar",
                name: "asignar-ingresos-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Asignar-Ingreso/Asignar-IngresoAgregar.vue"
                    ),
                meta: {
                    //pageTitle: "Ingresos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/nomina/asignar-ingreso/:id/editar",
                    name: "asignar-ingreso-editara",
                    component: () =>
                        import (
                            "../views/Nomina/Asignar-Ingreso/Asignar-IngresoEditarA.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }

                },
                {
                    path: "/nomina/asignar-ingreso/:id/editar_nuevo",
                    name: "asignar-ingreso-editarn",
                    component: () =>
                        import (
                            "../views/Nomina/Asignar-Ingreso/AsignarIngresoEditrarN.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Parametrizacion",
        value: 64,
        ver: {
            up: [{
                path: "/nomina/parametrizacion",
                name: "listarparametrizacion",
                component: () =>
                    import ("../views/Nomina/Parametrizacion/ParametrizacionLista.vue"),
                meta: {

                    pageTitle: "Parametrizacion",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/parametrizacion/agregar",
                name: "Parametrizacion-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Parametrizacion/ParametrizacionAgregar.vue"
                    ),
                meta: {

                    pageTitle: "Parametrizacion",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/nomina/parametrizacion/:id/editar",
                name: "parametrizacion-editar",
                component: () =>
                    import (
                        "../views/Nomina/Parametrizacion/ParametrizacionAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Rol Pagos",
        value: 30,
        ver: {
            up: [{
                path: "/nomina/rol-pagos",
                name: "listarrol-pagos",
                component: () =>
                    import ("../views/Nomina/Rol-Pagos/RolPagoLista.vue"),
                meta: {

                    pageTitle: "Rol Pagos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/rol-pagos/agregar",
                name: "Rolpagos-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Rol-Pagos/Rol-pagos-agregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Rol"
                        },
                        {
                            title: "Pagos",
                            active: true
                        }
                    ],
                    pageTitle: "Ingresos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/nomina/rol-pagos/:id/editar",
                    name: "Rolpagos-editar",
                    component: () =>
                        import (
                            "../views/Nomina/Rol-Pagos/Rol-pagos-agregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/nomina/rol-pagos/:id/ver",
                    name: "Rolpagos-ver",
                    component: () =>
                        import (
                            "../views/Nomina/Rol-Pagos/Rol-pago-ver.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
        /*,
                ver: {
                    up: [
                        {
                            path: "/nomina/rol-pagos/:id/ver",
                            name: "Rolpagos-ver",
                            component: () =>
                                import(
                                    "../views/Nomina/Rol-Pagos/Rol-Pago-ver.vue"
                                ),
                            meta: {
                                rule: "editor",
                                secure: true
                            }
                        }
                    ],
                    down: []
                }*/
    },
    {
        nombre: "Rol Provisiones",
        value: 33,
        ver: {
            up: [{
                path: "/nomina/rol-pago-provisiones",
                name: "listarrol-provision",
                component: () =>
                    import (
                        "../views/Nomina/Rol-Provisiones/Rol-provisionesLista.vue"
                    ),
                meta: {

                    pageTitle: "Rol Provisiones",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/rol-pago-provisiones/agregar",
                name: "Rolprovision-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Rol-Provisiones/Rol-provisionesAgregar.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/nomina/rol-pago-provisiones/:id/editar",
                    name: "eRolprovision-editar",
                    component: () =>
                        import (
                            "../views/Nomina/Rol-Provisiones/Rol-provisionesAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/nomina/rol-pago-provisiones/:id/ver",
                    name: "eRolprovision-ver",
                    component: () =>
                        import (
                            "../views/Nomina/Rol-Provisiones/Rol-provisionesVer.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Pago Empleados",
        value: 67,
        ver: {
            up: [{
                path: "/nomina/pago-empleado",
                name: "listarpago-empleado",
                component: () =>
                    import (
                        "../views/Nomina/Pago Empleado/PagoEmpleadoLista.vue"
                    ),
                meta: {

                    pageTitle: "Pago Empleados",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/nomina/pago-empleado/agregar",
                name: "pago-empleado-agregar",
                component: () =>
                    import (
                        "../views/Nomina/Pago Empleado/PagoEmpleadoAgregar.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/nomina/pago-empleado/:id/editar",
                name: "pago-empleado-editar",
                component: () =>
                    import (
                        "../views/Nomina/Pago Empleado/PagoEmpleadoAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }, ],
            down: []
        }
    },
    {
        nombre: "Proveedor",
        value: 20,
        ver: {
            up: [{
                path: "/compras/proveedor",
                name: "listaProveedor",
                component: () =>
                    import ("../views/Compras/Proveedor/ProveedorListar.vue"),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/proveedor/agregar",
                name: "agregarProveedor",
                component: () =>
                    import (
                        "../views/Compras/Proveedor/ProveedorAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/compras/proveedor/:id/editar",
                name: "agregarProveedorid",
                component: () =>
                    import (
                        "../views/Compras/Proveedor/ProveedorAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Facturas compra",
        value: 21,
        ver: {
            up: [{
                path: "/compras/factura-compra",
                name: "facturascompra",
                component: () =>
                    import (
                        "../views/Compras/Facturas-compra/FacturaCompra.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturacion"
                        },
                        {
                            title: "Facturas",
                            active: true
                        }
                    ],
                    pageTitle: "Facturas",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/factura-compra/agregar",
                name: "Agregarcompra",
                component: () =>
                    import (
                        "../views/Compras/Facturas-compra/FacturaCompraAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/compras/factura-compra/:id/editar",
                    name: "editcompra",
                    component: () =>
                        import (
                            "../views/Compras/Facturas-compra/FacturaCompraEditar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/compras/factura-compra/agregar/:id_orden",
                    name: "editcompra_orden",
                    component: () =>
                        import (
                            "../views/Compras/Facturas-compra/FacturaCompraAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/compras/factura-compra/:id/ver",
                    name: "verfactcompra",
                    component: () =>
                        import (
                            "../views/Compras/Facturas-compra/FacturaCompraVer.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
            ],
            down: []
        }
    },
    {
        nombre: "Orden compra",
        value: 28,
        ver: {
            up: [{
                path: "/compras/orden-compra",
                name: "compra",
                component: () =>
                    import (
                        "../views/Compras/Orden_compras/OrdenCompra.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Orden"
                        },
                        {
                            title: "Compra",
                            active: true
                        }
                    ],
                    pageTitle: "Orden compra",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/orden-compra/agregar",
                name: "Ordencompra",
                component: () =>
                    import (
                        "../views/Compras/Orden_compras/OrdenAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/compras/orden-compra/:id/editar",
                name: "editOrdencompra",
                component: () =>
                    import (
                        "../views/Compras/Orden_compras/OrdenAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        }
    },
    {
        nombre: "Compras-Reporteria",
        value: 39,
        ver: {
            up: [{
                path: "/compras/reportes",
                name: "Reporteria compras",
                component: () =>
                    import ("../views/Compras/Reporteria/index.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Reporteria",
                            active: true
                        }
                    ],
                    pageTitle: "Reporteria",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Importacion",
        value: 22,
        ver: {
            up: [{
                    path: "/importacion/registro-importacion",
                    name: "importacion",
                    component: () =>
                        import (
                            "../views/Compras/Importacion/ImportacionLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/importacion/liquidacion",
                    name: "liquidacion",
                    component: () =>
                        import (
                            "../views/Compras/Liquidacion/LiquidacionLista.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/importacion/registro-importacion/agregar",
                name: "AgregarImportacion",
                component: () =>
                    import (
                        "../views/Compras/Importacion/ImportacionAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/importacion/registro-importacion/:id/editar",
                    name: "editImportacion",
                    component: () =>
                        import (
                            "../views/Compras/Importacion/ImportacionAgregar.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                },
                {
                    path: "/importacion/liquidacion/:id/editar",
                    name: "editliquidacion",
                    component: () =>
                        import (
                            "../views/Compras/Liquidacion/LiquidacionVer.vue"
                        ),
                    meta: {
                        rule: "editor"
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Cuentas por pagar",
        value: 14,
        ver: {
            up: [{
                path: "/compras/cuentas-por-pagar",
                name: "CtaxPagar",
                component: () =>
                    import (
                        "../views/Compras/Cuentas_por_pagar/CuentasPorPagar.vue"
                    ),
                meta: {
                    rule: "editor"
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/cuentas-por-pagar/agregar",
                name: "AgregarCtaxPagar",
                component: () =>
                    import (
                        "../views/Compras/Cuentas_por_pagar/CuentasPorPagarAgregar.vue"
                    ),
                meta: {
                    rule: "editor"
                        // secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Formula",
        value: 24,
        ver: {
            up: [{
                path: "/produccion/formula",
                name: "Formula",
                component: () =>
                    import ("../views/Produccion/Formula/Formula.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Fórmula"
                        }
                    ],
                    pageTitle: "Fórmula",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/produccion/formula/agregar",
                name: "Agregar Formula",
                component: () =>
                    import (
                        "../views/Produccion/Formula/FormulaAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Pruducción"
                        },
                        {
                            title: " Fórmula"
                        },
                        {
                            title: "Agregar Fórmula"
                        }
                    ],
                    pageTitle: "Aregar Fórmula",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/produccion/formula/:id/editar",
                name: "editarFormula",
                component: () =>
                    import (
                        "../views/Produccion/Formula/FormulaAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Pruducción"
                        },
                        {
                            title: " Fórmula"
                        },
                        {
                            title: "Editar Fórmula"
                        }
                    ],
                    pageTitle: "Editar Fórmula",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Proceso produccion",
        value: 25,
        ver: {
            up: [{
                path: "/produccion/proceso-produccion",
                name: "Proceso-produccion",
                component: () =>
                    import (
                        "../views/Produccion/Proceso_produccion/ProcesoProduccion.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Proceso de Produccion"
                        }
                    ],
                    pageTitle: "Proceso",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/produccion/proceso-produccion/agregar",
                name: "Anadir-Proceso-Produccion",
                component: () =>
                    import (
                        "../views/Produccion/Proceso_produccion/ProcesoProduccionGestion.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Proceso de Produccion"
                        },
                        {
                            title: "Añadir Proceso de Produccion"
                        }
                    ],
                    pageTitle: "Añadir Proceso de Producción",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/produccion/proceso-produccion/:id/gestion",
                name: "Gestión-Proceso-Produccion",
                component: () =>
                    import (
                        "../views/Produccion/Proceso_produccion/ProcesoProduccionGestion.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Proceso de Produccion"
                        },
                        {
                            title: "Gestión Proceso de Produccion"
                        }
                    ],
                    pageTitle: "Gestión Proceso de Producción",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/produccion/proceso-produccion/:id/ver",
                name: "Ver-Proceso-Produccion",
                component: () =>
                    import (
                        "../views/Produccion/Proceso_produccion/ProcesoProduccionVer.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
        ],
            down: []
        }
    },
    {
        nombre: "Activos fijos",
        value: 26,
        ver: {
            up: [{
                path: "/activos-fijos/registro",
                name: "fijos",
                component: () =>
                    import ("../views/Activos_fijos/ActivoFijo.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Activos"
                        },
                        {
                            title: "Activo Fijo",
                            active: true
                        }
                    ],
                    pageTitle: "Activos Fijos",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/activos-fijos/registro/agregar",
                name: "activo-fijo-agregar",
                component: () =>
                    import (
                        "../views/Activos_fijos/ActivofijoAgregar.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/activos-fijos/registro/:id/editar",
                name: "activo-fijo-editar",
                component: () =>
                    import (
                        "../views/Activos_fijos/ActivofijoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Proceso de Produccion"
                        },
                        {
                            title: "Gestión Proceso de Produccion"
                        }
                    ],
                    pageTitle: "Gestión Proceso de Producción",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Depreciacion",
        value: 66,
        ver: {
            up: [{
                path: "/activos-fijos/depreciacion",
                name: "depreciacion",
                component: () =>
                    import ("../views/Activos_fijos/Depreciacion/Depreciacion.vue"),
                meta: {

                    pageTitle: "Depreciacion",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/activos-fijos/depreciacion/agregar",
                name: "depreciacion-agregar",
                component: () =>
                    import (
                        "../views/Activos_fijos/Depreciacion/DepreciacionAgregar.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/activos-fijos/depreciacion/:id/editar",
                name: "depreciacion-editar",
                component: () =>
                    import (
                        "../views/Activos_fijos/Depreciacion/DepreciacionVer.vue"
                    ),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Seguro",
        value: 72,
        ver: {
            up: [{
                path: "/salud/seguro",
                name: "segurover",
                component: () =>
                    import ("../views/Salud/Seguro/Seguro_Lista.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/salud/plan_seguro/agregar",
                name: "plan_seguro-agregar",
                component: () =>
                    import ("../views/Salud/Seguro/Seguro_Agregar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/salud/plan_seguro/:id/editar",
                name: "plan_seguro-editar",
                component: () =>
                    import ("../views/Salud/Seguro/Seguro_Editar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },

    },




    
    {
        nombre: "SeguimientoCliente",
        value: 73,
        ver: {
            up: [{
                path: "/seguimiento/clientes",
                name: "seguimiento",
                component: () =>
                    import ("../views/Seguimiento/Cliente/ListaClientes.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/seguimiento/proforma",
                name: "proforma",
                component: () =>
                    import ("../views/Seguimiento/Proforma/Proforma.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/seguimiento/obligaciones",
                name: "proforma",
                component: () =>
                    import ("../views/Seguimiento/Obligacion/ListaObligaciones.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/actas",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/ListaActas.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador",
                            active: true
                        }
                    ],
                    pageTitle: "Buscador de Actas de Asignación de Activos",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/activos",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/ListaActivos.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador",
                            active: true
                        }
                    ],
                    pageTitle: "Buscador de Activos",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/inmuebles",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/ListaInmuebles.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador",
                            active: true
                        }
                    ],
                    pageTitle: "Buscador de Inmuebles",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_activo",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarActivo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/vehiculo",
                name: "mobiliario",
                component: () =>
                    import ("../views/Mobiliario/Vehiculo/ListaVehiculo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/maquina",
                name: "mobiliario",
                component: () =>
                    import ("../views/Mobiliario/Maquina/ListaMaquina.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/libro",
                name: "mobiliario",
                component: () =>
                    import ("../views/Mobiliario/Libros/ListaLibro.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/enseres",
                name: "mobiliario",
                component: () =>
                    import ("../views/Mobiliario/Enseres/ListaEnseres.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
		crear: {
            up: [{
                path: "/seguimiento/cliente/agregar",
                name: "seguimiento",
                component: () =>
                    import (
                        "../views/Seguimiento/Cliente/ClientesAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Seguimiento"
                        },
                        {
                            title: "Cliente",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Cliente",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_acta",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarActa.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador Actas Asignación de Activos",
                            url: "/verificacion_activos/actas"
                        },
                        {
                            title: "Agregar",
                            active: true
                        }
                    ],
                    pageTitle: "Acta de Asignación de Activos",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_carga",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarCarga.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador Activos",
                            url: "/verificacion_activos/activos"
                        },
                        {
                            title: "Agregar",
                            active: true
                        }
                    ],
                    pageTitle: "Carga de Activos desde archivo formato EXCEL",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_inmueble",
                name: "activo",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarInmueble.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador Inmuebles",
                            url: "/verificacion_activos/inmuebles"
                        },
                        {
                            title: "Agregar",
                            active: true
                        }
                    ],
                    pageTitle: "Ingreso de Inmuebles",
                    secure: true
                }
            },
            {
                path: "/mobiliario/vehiculo/agregar",
                name: "mobiliario",
                component: () =>
                    import (
                        "../views/Mobiliario/Vehiculo/VehiculoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Vehiculo"
                        },
                        {
                            title: "Vehiculo",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Vehiculo",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/maquina/agregar",
                name: "mobiliario",
                component: () =>
                    import (
                        "../views/Mobiliario/Maquina/MaquinaAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Maquina Equipo"
                        },
                        {
                            title: "Maquina Equipo",
                            active: true
                        }
                    ],
                    pageTitle: "Nueva Maquina Equipo",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/libro/agregar",
                name: "mobiliario",
                component: () =>
                    import (
                        "../views/Mobiliario/Libros/LibroAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Libro"
                        },
                        {
                            title: "Libro",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Libro",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/enseres/agregar",
                name: "mobiliario",
                component: () =>
                    import (
                        "../views/Mobiliario/Enseres/EnseresAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Mobiliario Enseres"
                        },
                        {
                            title: "Mobiliario Enseres",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Mobiliario Enseres",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/mobiliario/vehiculo/:id/editar",
                name: "vehiculo-editar",
                component: () =>
                    import ("../views/Mobiliario/Vehiculo/VehiculoAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Vehiculo"
                        },
                        {
                            title: "Vehiculo",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Vehiculo",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_acta/:id/editar",
                name: "acta-editar",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarActa.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador Actas Asignación de Activos",
                            url: "/verificacion_activos/actas"
                        },
                        {
                            title: "Editar",
                            active: true
                        }
                    ],
                    pageTitle: "Acta de Asignación de Activos",
                    secure: true
                }
            },
            {
                path: "/verificacion_activos/agregar_inmueble/:id/editar",
                name: "inmueble-editar",
                component: () =>
                    import ("../views/VerificacionActivos/AgregarInmueble.vue"),
                meta: {
                    rule: "editor",
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Buscador de Inmuebles",
                            url: "/verificacion_activos/inmuebles"
                        },
                        {
                            title: "Editar",
                            active: true
                        }
                    ],
                    pageTitle: "Editar Inmueble",
                    secure: true
                }
            },
            
            {
                path: "/mobiliario/maquina/:id/editar",
                name: "maquina-editar",
                component: () =>
                    import ("../views/Mobiliario/Maquina/MaquinaAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Maquinaria"
                        },
                        {
                            title: "Maquinaria",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Maquinaria",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/enseres/:id/editar",
                name: "enseres-editar",
                component: () =>
                    import ("../views/Mobiliario/Enseres/EnseresAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Enseres"
                        },
                        {
                            title: "Enseres",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Enseres",
                    rule: "editor",
                    secure: true
                }
            },
            {
                path: "/mobiliario/libro/:id/editar",
                name: "libro-editar",
                component: () =>
                    import ("../views/Mobiliario/Libros/LibroAgregar.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Libro"
                        },
                        {
                            title: "Libro",
                            active: true
                        }
                    ],
                    pageTitle: "Nuevo Libro",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Calendario",
        value: 27,
        ver: {
            up: [{
                path: "/calendario",
                name: "calendario",
                component: () =>
                    import ("../views/Calendario/Calendario.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Consillacion bancaria",
        value: 40,
        ver: {
            up: [{
                path: "/contabilidad/concillacion-bancaria",
                name: "consillacion-bancaria",
                component: () =>
                    import ("../views/Contabilidad/Conciliacion_bancaria/ConciliacionListar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        crear: {
            up: [{
                path: "/contabilidad/concillacion-bancaria/agregar",
                name: "conciliacion-bancaria-agregar",
                component: () =>
                    import ("../views/Contabilidad/Conciliacion_bancaria/ConciliacionAgregar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                path: "/contabilidad/concillacion-bancaria/:id/editar",
                name: "compras-concillacion-bancariaeditar",
                component: () =>
                    import ("../views/Contabilidad/Conciliacion_bancaria/ConciliacionEditar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
    },
    {
        nombre: "Anexo ATS",
        value: 41,
        ver: {
            up: [{
                path: "contabilidad/anexo-sri/anexo-ats",
                name: "anexo-sri-ats",
                component: () =>
                    import ("../views/Contabilidad/Anexo_SRI/Anexo_ATS/Anexo_ATS.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }, ],
            down: []
        }
    },
    {
        nombre: "Anexo ICE",
        value: 41,
        ver: {
            up: [{
                path: "contabilidad/anexo-sri/anexo-ice",
                name: "anexo-sri-ice",
                component: () =>
                    import ("../views/Activos_fijos/ActivoFijo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }, ],
            down: []
        }
    },
    {
        nombre: "Anexo RDEP",
        value: 41,
        ver: {
            up: [{
                path: "contabilidad/anexo-sri/anexo-rdep",
                name: "anexo-sri-rdep",
                component: () =>
                    import ("../views/Activos_fijos/ActivoFijo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }, ],
            down: []
        }
    },
    {
        nombre: "Anexo IBP",
        value: 41,
        ver: {
            up: [{
                path: "contabilidad/anexo-sri/anexo-ibp",
                name: "anexo-sri-ibp",
                component: () =>
                    import ("../views/Activos_fijos/ActivoFijo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }, ],
            down: []
        }
    },
    /*{
        nombre: "Anexo SRI",
        value: 41,
        ver: {
            up: [
                {
                    path: "/contabilidad/anexo-sri",
                    name: "anexo-sri",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "contabilidad/anexo-sri/anexo-ats",
                    name: "anexo-sri-ats",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "contabilidad/anexo-sri/anexo-ice",
                    name: "anexo-sri-ice",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "contabilidad/anexo-sri/anexo-rdep",
                    name: "anexo-sri-rdep",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "contabilidad/anexo-sri/anexo-ibp",
                    name: "anexo-sri-ibp",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
            ],
            down: []
        }
    },*/
    {
        nombre: "Indices financieros",
        value: 42,
        ver: {
            up: [{
                path: "/contabilidad/indices-financieros",
                name: "indices-financieros",
                component: () =>
                    import ("../views/Activos_fijos/ActivoFijo.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Reporteria contabilidad",
        value: 43,
        ver: {
            up: [{
                path: "/contabilidad/reportes",
                name: "contabilidad-reporteria",
                component: () =>
                    import ("../views/Contabilidad/Reportes/index.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Nota debito",
        value: 44,
        ver: {
            up: [{
                path: "/facturacion/nota-debito",
                name: "facturacion-nota-debito",
                component: () =>
                    import ("../views/Facturacion/Nota_debito/NotaDebitoLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Facturación"
                        },
                        {
                            title: "Nota de débito",
                            active: true
                        }
                    ],
                    pageTitle: "Nota de débito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/facturacion/nota-debito/:id/ver",
                    name: "facturacion-nota-debitover",
                    component: () =>
                        import ("../views/Facturacion/Nota_debito/NotaDebitoVer.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/facturacion/nota-debito/:id/editar",
                    name: "facturacion-nota-debitoeditar",
                    component: () =>
                        import ("../views/Facturacion/Nota_debito/NotaDebitoEditar.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/facturacion/nota-debito/agregar",
                name: "facturacion-nota-debitoagregar",
                component: () =>
                    import ("../views/Facturacion/Nota_debito/NotaDebitoAgregar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Liquidacion compra",
        value: 45,
        ver: {
            up: [{
                path: "/compras/liquidacion-compra",
                name: "compras-liquidacion-compra",
                component: () =>
                    import ("../views/Compras/Liquidacion_compra/Liquidacion_compraLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Liquidación de compra",
                            active: true
                        }
                    ],
                    pageTitle: "Liquidación de compra",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/compras/liquidacion-compra/:id/editar",
                    name: "compras-liquidacion-compraeditar",
                    component: () =>
                        import ("../views/Compras/Liquidacion_compra/Liquidacion_compraEditar.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/compras/liquidacion-compra/:id/ver",
                    name: "compras-liquidacion-compraver",
                    component: () =>
                        import ("../views/Compras/Liquidacion_compra/Liquidacion_compraVer.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/liquidacion-compra/agregar",
                name: "compras-liquidacion-compraagregar",
                component: () =>
                    import ("../views/Compras/Liquidacion_compra/Liquidacion_compraAgregar.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Nota de credito",
        value: 46,
        ver: {
            up: [{
                path: "/compras/nota-credito",
                name: "compras-nota-credito",
                component: () =>
                    import ("../views/Compras/Nota_credito/NotaCreditoLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Nota Crédito",
                            active: true
                        }
                    ],
                    pageTitle: "Nota Crédito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/compras/nota-credito/:id/ver",
                    name: "compras-nota-credito-ver",
                    component: () =>
                        import (
                            "../views/Compras/Nota_credito/NotaCreditoVer.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Compras"
                            },
                            {
                                title: "Nota Crédito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Crédito",
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/compras/nota-credito/:id/editar",
                    name: "compras-nota-credito-editar",
                    component: () =>
                        import ("../views/Compras/Nota_credito/NotaCreditoEditar.vue"),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Compras"
                            },
                            {
                                title: "Nota Crédito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Crédito",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/nota-credito/agregar",
                name: "comprasagregarNotaCredito",
                component: () =>
                    import (
                        "../views/Compras/Nota_credito/NotaCreditoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Nota Credito",
                            active: true
                        }
                    ],
                    pageTitle: "Nueva Nota Credito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Nota de debito",
        value: 47,
        ver: {
            up: [{
                path: "/compras/nota-debito",
                name: "compras-nota-debito",
                component: () =>
                    import ("../views/Compras/Nota_debito/NotaDebitoLista.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Nota Débito",
                            active: true
                        }
                    ],
                    pageTitle: "Nota Débito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        },
        editar: {
            up: [{
                    path: "/compras/nota-debito/:id/ver",
                    name: "compras-nota-debito-ver",
                    component: () =>
                        import (
                            "../views/Compras/Nota_debito/NotaDebitoVer.vue"
                        ),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Compras"
                            },
                            {
                                title: "Nota Débito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Débito",
                        rule: "editor",
                        secure: true
                    }
                },
                {
                    path: "/compras/nota-debito/:id/editar",
                    name: "compras-nota-debito-editar",
                    component: () =>
                        import ("../views/Compras/Nota_debito/NotaDebitoEditar.vue"),
                    meta: {
                        breadcrumb: [{
                                title: "Home",
                                url: "/"
                            },
                            {
                                title: "Compras"
                            },
                            {
                                title: "Nota Débito",
                                active: true
                            }
                        ],
                        pageTitle: "Nota Débito",
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        },
        crear: {
            up: [{
                path: "/compras/nota-debito/agregar",
                name: "compras-nota-debito-agregar",
                component: () =>
                    import (
                        "../views/Compras/Nota_debito/NotaDebitoAgregar.vue"
                    ),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Compras"
                        },
                        {
                            title: "Nota Débito",
                            active: true
                        }
                    ],
                    pageTitle: "Nueva Nota Débito",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    {
        nombre: "Reporteria inventario",
        value: 53,
        ver: {
            up: [{
                path: "/inventario/reportes",
                name: "reportes-inventario",
                component: () =>
                    import ("../views/Inventario/Reporteria/InventarioReportes.vue"),
                meta: {
                    breadcrumb: [{
                            title: "Home",
                            url: "/"
                        },
                        {
                            title: "Inventario"
                        }
                    ],
                    pageTitle: "Reportería de Inventario",
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
    /*{
        nombre: "Departamento",
        value: 54,
        ver: {
            up: [
                {
                    path: "/nomina/departamento",
                    name: "departamento",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Area de trabajo",
        value: 55,
        ver: {
            up: [
                {
                    path: "/nomina/area-trabajo",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    /*{
        nombre: "Cargo",
        value: 56,
        ver: {
            up: [
                {
                    path: "/nomina/cargo",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Grupo ocupacional",
        value: 57,
        ver: {
            up: [
                {
                    path: "/nomina/grupo-ocupacional",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Informacion basica",
        value: 58,
        ver: {
            up: [
                {
                    path: "/nomina/informacion-basica",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Detalle del cargo",
        value: 59,
        ver: {
            up: [
                {
                    path: "/nomina/detalle-cargo",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },
    {
        nombre: "Cargas",
        value: 60,
        ver: {
            up: [
                {
                    path: "/nomina/cargas",
                    name: "area-trabajo",
                    component: () =>
                        import("../views/Activos_fijos/ActivoFijo.vue"),
                    meta: {
                        rule: "editor",
                        secure: true
                    }
                }
            ],
            down: []
        }
    },*/
    {
        nombre: "Reporteria nomina",
        value: 61,
        ver: {
            up: [{
                path: "/nomina/reporteria",
                name: "reporteria-nomina",
                component: () =>
                    import ("../views/Nomina/Reporteria/ReporteriaNomina.vue"),
                meta: {
                    rule: "editor",
                    secure: true
                }
            }],
            down: []
        }
    },
];

module.exports = {
    routesAndValue
};