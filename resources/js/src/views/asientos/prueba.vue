 
 {
        nombre: "Vendedor",
        value: 16,
        ver: {
            up: [{
                path: "/facturacion/vendedor",
                name: "vendedor",
                component: () =>
                    import ("../views/facturacion/Vendedor.vue"),
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
                path: "/plan/asientos",
                name: "agregarasiento",
                component: () =>
                    import ("../views/asientos/Asientos.vue"),
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
        editar: {
            up: [{
                path: "/plan/asientos/:id",
                name: "asientosid",
                component: () =>
                    import ("../views/asientos/Asientos.vue"),
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
        }
    },