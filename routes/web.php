<?php

use Illuminate\Support\Facades\Route;

include_once getenv("FILE_CONFIG_PHP");

//nueva prueba de import
/*
Route::get('export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');
*/
/*
Route::get('/api/export', 'MyController@export')->name('export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('import', 'MyController@import')->name('import');
*/


Route::post('/get_sesion_user', 'UserController@getusersesion');

Route::get('/api/creacion_factura_venta_pdf/{id}/{tipo}', 'FacturacionController@factura_venta_pdf');
Route::get('/api/creacion_guia_remision_pdf/{id}/{tipo}/{doc}', 'FacturacionController@guiapdf');
Route::get('/api/creacion_retencion_compra_pdf/{id}/{tipo}/{doc}', 'FacturacionController@retencion_compra_pdf');
Route::get('/api/lista/empresas', 'EmpresaController@todosroot');
Route::post('/api/lista/listadoroot', 'EmpresaController@listadoroot');

Route::get('/api/fecha-expiracion-firma-electronica/{id}', 'EmpresaController@firma_expiracion')->name('api.fecha.firma');
Route::post('/api/estado/change_state_empresa', 'EmpresaController@change_state_empresa');

Route::get('/api/export', 'MyController@export');
Route::get('importExportView', 'MyController@importExportView');
Route::post('/api/import', 'MyController@import@import');

Route::get('/api/importExport', 'MyController@importExport');
Route::get('/api/downloadExcel/{type}', 'MyController@downloadExcel');
Route::post('importExcel', 'MyController@importExcel');


//fin nueva prueba de import
Route::get('/api/transaccional', 'FichaTransaccionalController@ficha');
Route::get('/api/anexo_sri/ats/reporte', 'FichaTransaccionalController@generarPDF');


Route::get('/api/todos-roles/{id}', 'RolController@todos');
//Inicio
Route::get('/api/graphic/cliente/{id}', 'InicioGraphicController@graphicClient');
Route::get('/api/graphic/ventasTotalesBar/{id}', 'InicioGraphicController@graphicVentasTotalesBar');
Route::get('/api/graphic/ventasVendedorBar/{id}', 'InicioGraphicController@graphicVentasVendedorBar');
Route::get('/api/graphic/cuentasPagarRadio/{id}', 'InicioGraphicController@graphicCuentasPagar');
Route::get('/api/graphic/cuentasCobrarRadio/{id}', 'InicioGraphicController@graphicCuentasCobrar');
Route::get('/api/graphic/utilidadesLine/{id}', 'InicioGraphicController@graphicUtilidades');
Route::get('/api/graphic/chequesPagar/{id}', 'InicioGraphicController@graphicChequesPagar');
//empresa
Route::post('/api/agregarempresa', 'EmpresaController@store');
Route::post('/api/agregarempresaroles', 'EmpresaController@roles');
Route::post('/api/agregarempresarolesid', 'EmpresaController@rolesid');
Route::get('/api/listarempresas', 'EmpresaController@listarempresas');
Route::get('/api/adicionalesempresa', 'EmpresaController@adicionales');
Route::get('/api/verempresa/{id}', 'EmpresaController@verempresa');
Route::get('/api/listarempresa/{id}', 'EmpresaController@editarempresa');
Route::post('/api/actualizarempresa', 'EmpresaController@actualizarempresa');

Route::get('/api/ciudades', 'EmpresaController@ciudades');
Route::get('/api/empresa/{id}/obtener', 'EmpresaController@obtenerEmpresa');
Route::get('/api/empresa', 'EmpresaController@index');
Route::get('/api/abrirempresa/{id}', 'EmpresaController@abrir');
Route::delete('/api/eliminarempresa/{id}', 'EmpresaController@eliminar');
Route::get('/api/moneda', 'EmpresaController@getMoneda');
Route::get('/api/provincia', 'EmpresaController@getProvincia');
Route::get('/api/ciudad', 'EmpresaController@getCiudad');
Route::get('/api/empresausu', 'EmpresaController@indexUsuario');
Route::get('/api/empresasasoc', 'EmpresaController@getEmpresas');
Route::post('/api/guardarimagen', 'EmpresaController@guardarimagen');
Route::post('/api/guardarimgempresa', 'EmpresaController@guardarimagen');
Route::post('/api/guardarfirmaempresa', 'EmpresaController@guardarfirma');
Route::post('/api/guardarimgempresa1', 'EmpresaController@guardarimagen1');
Route::post('/api/guardarfirmaempresa1', 'EmpresaController@guardarfirma1');
Route::delete('/api/eliminarfirma/{id}', 'EmpresaController@eliminarfirma');

//producto
Route::get('/api/productos/{id}', 'ProductoController@index');
Route::get('/api/productos/categorias/{id}', 'ProductoController@listcategorias');
Route::post('/api/guardarproductos', 'ProductoController@guardar');
Route::get('/api/abrirproductos/{id}', 'ProductoController@abrir');
Route::post('/api/actualizarproductos', 'ProductoController@actualizar');
Route::delete('/api/eliminarproductos/{id}', 'ProductoController@eliminar');
Route::get('/api/camposadicionales', 'ProductoController@camposadicionales');
Route::post('/api/guardarimgproducto', 'ProductoController@guardarimagen');
Route::get('/api/productoempresa/{id}', 'ProductoController@ProductoEmpresa');
Route::get('/api/reporte/solo_productos', 'ProductoController@ProductoReporte');
Route::get('/api/pdf/solo_productos', 'ProductoController@generarReporte');


//productos-lineaproducto
Route::get('/api/lineaproductos/{id}', 'LineaproductoController@index');
Route::post('/api/guardarlinea', 'LineaproductoController@store');
Route::post('/api/editarlinea', 'LineaproductoController@editar');
Route::delete('/api/eliminarlinea/{id}', 'LineaproductoController@eliminar');
Route::get('/api/lineaproductosall/{id}', 'LineaproductoController@todo');

//productos-tipoaproducto
Route::get('/api/tipoproductos/{id}', 'TipoproductoController@index');
Route::post('/api/guardartipo', 'TipoproductoController@store');
Route::post('/api/editartipo', 'TipoproductoController@editar');
Route::delete('/api/eliminartipo/{id}', 'TipoproductoController@eliminar');
Route::get('/api/tipoproductosall', 'TipoproductoController@todo');
Route::get('/api/tipoproductosallr/{id}', 'TipoproductoController@todor');

//marcas
Route::get('/api/marca/{id}', 'MarcaController@index');
Route::post('/api/guardarmarca', 'MarcaController@store');
Route::post('/api/editarmarca', 'MarcaController@editar');
Route::delete('/api/eliminarmarca/{id}', 'MarcaController@eliminar');
Route::get('/api/marcaall/{id}', 'MarcaController@todo');
Route::get('/api/marcaallpdf/{id}', 'MarcaController@todoPdf');

//modelos
Route::get('/api/modelo/{id}', 'ModeloController@index');
Route::post('/api/guardarmodelo', 'ModeloController@store');
Route::post('/api/editarmodelo', 'ModeloController@editar');
Route::delete('/api/eliminarmodelo/{id}', 'ModeloController@eliminar');
Route::get('/api/modeloall/{id}', 'ModeloController@todo');

//Bodega
Route::get('/api/codbodega/{id}', 'BodegaController@codigo');
Route::get('/api/bodega/{id}/{ide}', 'BodegaController@index');
Route::post('/api/guardarbodega', 'BodegaController@store');
Route::post('/api/editarbodega', 'BodegaController@editar');
Route::put('/api/abrirbodega', 'ProductoController@abrir');
Route::delete('/api/eliminarbodega/{id}', 'BodegaController@eliminar');
Route::get('/api/bodegaall/{id}', 'BodegaController@todo');


Route::get('/api/reporte/inventario_bodega', 'BodegaController@generarReporte');

//Producto_Bodega
Route::get('/api/abrirbodegagestion/{id}', 'ProductoBodegaController@bodega');
Route::get('/api/abrirproductosbodega/{id}', 'ProductoBodegaController@productos');
Route::get('/api/pdf/productosbodega', 'ProductoBodegaController@generarPdf');
//Bodega_Ingreso
Route::get('/api/ingresoBodega/{idb}/{ide}', 'BodegaIngresoController@index');
Route::get('/api/codingres/{id}', 'BodegaIngresoController@codingres');
Route::get('/api/productoingreso/{id}', 'BodegaIngresoController@productoingreso');
Route::post('/api/guardarbodegaingreso', 'BodegaIngresoController@store');
Route::get('/api/getingresobodega/{id}', 'BodegaIngresoController@getingresobodega');

Route::get('/api/pdf/ingresobodega', 'BodegaIngresoController@generarPdf');

Route::get('/api/ver_asiento/bodega_ingreso/{id}', 'BodegaIngresoController@verAsiento');
Route::post('/api/agregar/bodega_ingreso', 'BodegaIngresoController@agregarAsiento_Ingreso');
Route::post('/api/agregar/detalle/bodega_ingreso', 'BodegaIngresoController@agregarAsientoDetalle_Ingreso');

//Bodega_Egreso
Route::get('/api/egresoBodega/{idb}/{ide}', 'BodegaEgresoController@index');
Route::get('/api/codegres/{id}', 'BodegaEgresoController@codegres');
Route::get('/api/abrirstockbodegaegreso/{id}', 'BodegaEgresoController@productosstockegreso');
Route::post('/api/guardarbodegaegreso', 'BodegaEgresoController@store');
Route::get('/api/getegresobodega/{id}', 'BodegaEgresoController@getegresobodega');

Route::get('/api/ver_asiento/bodega_egreso_fact/{id}', 'BodegaEgresoController@verAsientoBodegaFactura');
Route::post('/api/agregar/bodega_egreso_fact', 'BodegaEgresoController@agregarAsiento_EgFact');
Route::post('/api/agregar/detalle/bodega_egreso_fact', 'BodegaEgresoController@agregarAsientoDetalle_EgFact');

Route::get('/api/ver_asiento/bodega_egreso/{id}', 'BodegaEgresoController@verAsientoBodega');
Route::post('/api/agregar/bodega_egreso', 'BodegaEgresoController@agregarAsiento_Egreso');
Route::post('/api/agregar/detalle/bodega_egreso', 'BodegaEgresoController@agregarAsientoDetalle_Egreso');

Route::get('/api/pdf/egresobodega', 'BodegaEgresoController@generarPdf');

//Bodega_Transferencia Envio
Route::get('/api/transeBodega/{idb}/{ide}', 'BodegaTransferenciaController@indexenvio');
Route::get('/api/codtrans/{id}', 'BodegaTransferenciaController@codtrans');
Route::get('/api/bodegasTranse/{idb}/{ide}', 'BodegaTransferenciaController@indexbodegastranse');
Route::get('/api/abrirstockbodegatranse/{id}', 'BodegaTransferenciaController@productosstocktranse');
Route::post('/api/guardarbodegatranse', 'BodegaTransferenciaController@storetranse');
Route::get('/api/gettransebodega/{id}', 'BodegaTransferenciaController@gettransebodega');

Route::get('/api/ver_asiento/bodega_transf/{id}', 'BodegaTransferenciaController@verAsiento');
Route::get('/api/pdf/bodega_transf', 'BodegaTransferenciaController@generarPdf');

//Bodega_Transferencia Recepcion
Route::get('/api/transrBodega/{idb}/{ide}', 'BodegaTransferenciaController@indexrecepcion');
Route::get('/api/gettransrbodega/{id}', 'BodegaTransferenciaController@gettransrbodega');
Route::post('/api/guardarbodegatransr', 'BodegaTransferenciaController@storetransr');


Route::get('/api/ver_asiento/bodega_transf_recep/{id}', 'BodegaTransferenciaController@verAsiento_Receptor');
Route::post('/api/agregar/bodega_trans', 'BodegaTransferenciaController@agregarAsiento_Trans');
Route::post('/api/agregar/detalle/bodega_trans', 'BodegaTransferenciaController@agregarAsientoDetalle_Trans');

//Reporteria Inventarios
Route::get('/api/list-product/{id}', 'KardexController@index');
Route::get('/api/product-filter', 'KardexController@ProductFilter');
Route::get('/api/bodega-filter', 'KardexController@BodegaFilter');
Route::get('/api/reportes/kardex', 'KardexController@generarReporte');

//ubicacion fisica
Route::get('/api/ubicacion_fisica', 'Ubicacion_fisicaController@index');
Route::post('/api/guardarubicacion_fisica', 'Ubicacion_fisicaController@store');
Route::post('/api/editarubicacion_fisica', 'Ubicacion_fisicaController@editar');
Route::delete('/api/eliminarubicacion_fisica/{id}', 'Ubicacion_fisicaController@eliminar');
Route::get('/api/ubicacion_fisicaall', 'Ubicacion_fisicaController@todo');
Route::get('/api/ubicacion_fisicaallr', 'Ubicacion_fisicaController@todor');

//presentacion
Route::get('/api/presentacion/{id}', 'PresentacionController@index');
Route::post('/api/guardarpresentacion', 'PresentacionController@store');
Route::post('/api/editarpresentacion', 'PresentacionController@editar');
Route::delete('/api/eliminarpresentacion/{id}', 'PresentacionController@eliminar');
Route::get('/api/presentacionall/{id}', 'PresentacionController@todo');

//tipo_medida
Route::get('/api/tipomedida', 'Tipo_medidaController@todo');

//unidad_medida
Route::get('/api/unidadmedida', 'Unidad_medidaController@todo');
Route::get('/api/unidadmedidar', 'Unidad_medidaController@todor');

//iva
Route::get('/api/iva', 'IvaController@todo');

//ice
Route::get('/api/listice/{id}', 'IceController@index');
Route::post('/api/guardarice', 'IceController@store');
Route::post('/api/editarice', 'IceController@editar');
Route::delete('/api/eliminarice/{id}', 'IceController@eliminar');
Route::get('/api/selectice/{id}', 'IceController@select');
//antiguo select
Route::get('/api/ice', 'IceController@todo');
//ice formula
Route::get('/api/codformice/{id}', 'IceFormulaController@codigo');
Route::get('/api/listiceformula/{id}', 'IceFormulaController@index');
Route::post('/api/guardariceformula', 'IceFormulaController@store');
Route::post('/api/editariceformula', 'IceFormulaController@editar');
Route::delete('/api/eliminariceformula/{id}', 'IceFormulaController@eliminar');
Route::get('/api/iceformula/{id}', 'IceFormulaController@todo');

//cliente
Route::get('/api/clientes/{id}', 'ClienteController@index');
Route::post('/api/cliente/guardar', 'ClienteController@store');
Route::get('/api/cliente/vercliente/{id}', 'ClienteController@vercliente');
Route::put('/api/cliente/editar', 'ClienteController@update');
Route::get('/api/ciudad', 'ClienteController@getCiudad');
Route::get('/api/provincia', 'ClienteController@getProvincia');
Route::get('/api/parroquia', 'ClienteController@getParroquia');
Route::delete('/api/eliminarCliente/{id}', 'ClienteController@destroy');
Route::get('/api/verificarcliente/{id}', 'ClienteController@verificarcliente');
Route::get('/api/grupo_cliente', 'ClienteController@getGrupoClientes');
Route::get('/api/select_client', 'ClienteController@selectclient');
Route::get('/api/traercliente/{id}', 'ClienteController@traercliente');

//grupo cliente
Route::get('/api/grupocliente/{id}', 'GrupoclienteController@index');
Route::post('/api/guardargrupo', 'GrupoclienteController@store');
Route::post('/api/editargrupo', 'GrupoclienteController@editar');
Route::delete('/api/eliminargrupo/{id}', 'GrupoclienteController@eliminar');
Route::get('/api/grupoclienteall/{id}', 'GrupoclienteController@todo');
Route::get('/api/grupo_cliente/{id}', 'ClienteController@getGrupoClientes');

///tipo cliente
Route::delete('/api/eliminartipocliente/{id}', 'TipoclienteController@eliminar');
Route::post('/api/guardartipocliente', 'TipoclienteController@store');
Route::post('/api/editartipocliente', 'TipoclienteController@editar');
Route::get('/api/listartipocliente/{id}', 'TipoclienteController@index');
Route::get('/api/grupotipocliente/{id}', 'ClienteController@getTipoCliente');

///vendedor en cliente
Route::delete('/api/eliminarvendedorcliente/{id}', 'VendedorclienteController@eliminar');
Route::post('/api/guardarvendedorcliente', 'VendedorclienteController@store');
Route::put('/api/editarvendedorcliente', 'VendedorclienteController@editar');
Route::get('/api/listarvendedorcliente/{id}', 'VendedorclienteController@index');
Route::get('/api/grupo_vendedor/{id}', 'ClienteController@getGrupoVendedor');
Route::put('/api/vendedor/vercliente', 'VendedorclienteController@vervendedor');
Route::get('/api/codigovend', 'VendedorclienteController@codigo');
Route::get('/api/grupo_user/{id}', 'VendedorclienteController@getGrupoUser');

//formas de pagos
Route::get('/api/listarFormaDePagos/{id}', 'ClienteController@getFormaPagos');
//***asientos contables */
Route::get('/api/asientos-contables/{id}', 'AsientosController@ObtenerAsientoContable');
Route::post('/api/asientos-contables/manuales/buscar', 'AsientosController@buscarCoincidencia');
Route::get('/api/asientos-contables/manuales/listar/{id}', 'AsientosController@listarAsientosContables');
Route::post('/api/asientos-contables/manuales/guardar', 'AsientosController@guardarAsientosContablesManuales');
Route::post('/api/asientos-contables/manuales/editar', 'AsientosController@editarAsientosContablesManuales');
Route::post('/api/asientos-contables/manuales/eliminar', 'AsientosController@eliminarAsientocontable');
Route::get('/api/asientos-contables/manuales/ultimo-numero/{id_comprobante}', 'AsientosController@obtenerUltimoNumeroDeAsientoContableManual');
Route::get('/api/asientos-contables/manuales/comprobantes/{automaticos?}', 'AsientosController@obtenerListaDeComprobantes');
Route::get('/api/asientos-contables/reporte/diario_general', 'AsientosController@generarReporte');
Route::get('/api/listarplan_cuenta', 'AsientosController@getPlanCuenta');
// Route::get('/api/listarimpresora', 'AsientosController@obtenerImpresora');
Route::post('/api/validarcheque', 'AsientosController@validarCheque');
Route::post('/api/validarcheque/editar', 'AsientosController@validarChequeEditar');

Route::get('/api/arreglo/costo_venta/{id}', 'AsientosController@costo_venta');
Route::get('/api/arreglo_contabilidad/costo_venta/{id}', 'AsientosController@costo_venta_asiento');

//pdf-asientos
Route::get('/api/pdf/asientos', 'AsientosController@generarPdf');

//cheques-asientos
Route::get('/api/cheques/asientos', 'AsientosController@generarCheque');


//asientos tabla proyecto
Route::get('/api/listarproyecto/{id}', 'ProyectoController@index');
Route::get('/api/listarproyecto/asientos/{id}', 'ProyectoController@getProyecto');
Route::get('/api/getproyect/{id}', 'ProyectoController@listproy');
Route::delete('/api/eliminarproyecto/{id}', 'ProyectoController@eliminar');
Route::post('/api/guardarproyecto', 'ProyectoController@store');
Route::post('/api/editarproyecto', 'ProyectoController@editar');
Route::put('/api/verproyecto/{id}', 'ProyectoController@abrir');


//asientos detalle tabla proyecto
Route::get('/api/listarasientodetalle/{id}', 'AsientoDetalleController@index');

//cierre contable
Route::get('/api/ejercicio_contable/listar', 'CierreContableController@index');
Route::get('/api/ejercicio_contable', 'CierreContableController@listarCierrePeriodo');
Route::get('/api/ejercicio_contable/cta_resultado', 'CierreContableController@listarCierrePeriodoCtaResultado');
Route::get('/api/ejercicio_contable/listar_cierre/{id}', 'CierreContableController@getCierreEstado');
Route::post('/api/ejercicio_contable/guardar/asiento', 'CierreContableController@agregarAsiento');
Route::post('/api/ejercicio_contable/guardar/detalle', 'CierreContableController@agregarAsientoDetalle');
Route::get('/api/ejercicio_contable/eliminar/{id}', 'CierreContableController@eliminar');

//balance inicial
Route::get('/api/balance_inicial/listar', 'BalanceInicialController@index');
Route::get('/api/balance_inicial', 'BalanceInicialController@listarBalanceInicial');
Route::get('/api/balance_inicial/listar_cierre/{id}', 'BalanceInicialController@getCierreEstado');
Route::get('/api/balance_inicial/eliminar/{id}', 'BalanceInicialController@eliminar');
Route::post('/api/balance_inicial/guardar/asiento', 'BalanceInicialController@agregarAsiento');
Route::post('/api/balance_inicial/guardar/detalle', 'BalanceInicialController@agregarAsientoDetalle');

//cierre mes
Route::get('/api/cierre_mes/listar', 'CierreContableController@indexCierreMes');
Route::get('/api/cierre_mes/abrir/{id}', 'CierreContableController@getFecha');
Route::post('/api/cierre_mes/guardar/asiento', 'CierreContableController@agregarAsientoCierreMes');
Route::put('/api/actualizar/cierre_mes', 'CierreContableController@editarCierre');

//import clientes
Route::post('/api/importarexcel', 'ImportController@import');

//Export excel export
Route::get('/api/exportarexcel', 'ImportController@export');

//importar plan cuentas
Route::post('/api/importarplancuentaexcel', 'ImportController@importPlanCuentas');

//importar vendedores
Route::post('/api/ImportarVendedoresExcel', 'ImportController@ImportVendedores');

//importar proveedor
Route::post('/api/importarproveedorexcel', 'ImportController@importProveedor');

//importar producto
Route::post('/api/importarproductosexcel', 'ImportController@importProductos');

//impor lineas de productos
Route::post('/api/importarlineaproductosexcel', 'ImportController@importLineasProducto');

//importar tipos de productos
Route::post('/api/importartipoproductosexcel', 'ImportController@importTipoProducto');

//impor marca de productos
Route::post('/api/importarmarcaproductosexcel', 'ImportController@importMarcaProducto');

//impor Modelos de productos
Route::post('/api/importarmodelosproductosexcel', 'ImportController@importModelosProducto');

//impor Presentacion de productos
Route::post('/api/importarpresentacionproductosexcel', 'ImportController@importPresentacionProducto');

//importar cuentas por cobrar
Route::post('/api/importarcuentascobrar', 'CuentaporcobrarController@importar');

//importar cuentas por pagar
Route::post('/api/importarcuentaspagar', 'CuentaporpagarController@importar');

//importar Bodega
Route::post('/api/ImportarBodegaExcel', 'ImportController@importBodega');
//importar Proyecto
Route::post('/api/ImportarProyectosExcel', 'ImportController@importProyectos');
//importar Usuarios
Route::post('/api/ImportarUsuariosexcel', 'ImportController@importUsuarios');
//importar establecimiento
Route::post('/api/ImportarEstablecimientosExcel', 'ImportController@ImportEstablecimientos');
//importar Registro importacion
Route::post('/api/ImportarRegistroImportacionExcel', 'ImportController@ImportRegistroImportacion');
//importar Codigo Impuesto
Route::post('/api/ImportarImpuestoExcel', 'ImportController@importCodigoImpuesto');
//importar Tipo Comprobante
Route::post('/api/ImportarTipoComprobanteExcel', 'ImportController@importTipoComprobante');
//importar Tipo Comprobante
Route::post('/api/ImportarTipoSustentoExcel', 'ImportController@importTipoSustento');
//importar Formas de pago sri
Route::post('/api/ImportarFormasPagoSriExcel', 'ImportController@importFormasPagoSri');
//importar Formas de pago sri
Route::post('/api/ImportarRetencionesExcel', 'ImportController@importRetenciones');
//importar puntos de emision
Route::post('/api/ImportarPuntosEmisionExcel', 'ImportController@importPuntosEmision');
//punto de emision
Route::get('/api/ptoemision/{id}', 'PtoemisionController@index');
Route::post('/api/ptoemisiong', 'PtoemisionController@store');
Route::delete('/api/eliminarpt/{id}', 'PtoemisionController@eliminar');
Route::put('/api/abrirpt', 'PtoemisionController@abrir');
Route::put('/api/actualizarpt', 'PtoemisionController@actualizar');
Route::get('/api/abrirestablecimiento/{id}', 'PtoemisionController@abrire');
Route::get('/api/pttodo/{id}', 'PtoemisionController@todo');

//Establecimiento
Route::get('/api/establecimiento/{id}', 'EstableciemtoController@index');
Route::post('/api/establecimientog', 'EstableciemtoController@store');
Route::delete('/api/establecimientoeliminar/{id}', 'EstableciemtoController@destroy');
Route::get('/api/listaremisor', 'EstableciemtoController@abriremisor');
Route::put('/api/establecimientoact', 'EstableciemtoController@update');
Route::get('/api/esttodo/{id}', 'EstableciemtoController@todo');

//listas
Route::get('/api/listarcuentas/{id}', 'CuentacontableController@cuentas');

//punto de emision
Route::get('/api/ptoemision', 'PtoemisionController@index');
Route::post('/api/ptoemisiong', 'PtoemisionController@store');
Route::delete('/api/eliminarpt/{id}', 'PtoemisionController@eliminar');
Route::put('/api/abrirpt', 'PtoemisionController@abrir');
Route::put('/api/actualizarpt', 'PtoemisionController@actualizar');

//listas codigo pais
Route::get('/api/listarcodigopais', 'ClienteController@CodigoPais');

//Establecimiento
Route::get('/api/establecimiento', 'EstableciemtoController@index');
Route::post('/api/establecimientog', 'EstableciemtoController@store');
Route::delete('/api/establecimientoeliminar/{id}', 'EstableciemtoController@destroy');
Route::put('/api/establecimientoabrir', 'EstableciemtoController@abrir');
Route::put('/api/listaremisor', 'EstableciemtoController@abriremisor');
Route::put('/api/establecimientoact', 'EstableciemtoController@update');

//cuentas por cobrar
Route::get('/api/pago/{id}', 'CuentaporcobrarController@index');
Route::get('/api/pagocliente', 'CuentaporcobrarController@indexcliente');
Route::post('/api/agregarpago', 'CuentaporcobrarController@store');
Route::post('/api/agregarpagos', 'CuentaporcobrarController@agregarpagos');
Route::put('/api/abrirpago/{id}', 'CuentaporcobrarController@abrir');
Route::put('/api/actualizarpago', 'CuentaporcobrarController@update');
Route::delete('/api/eliminarpago/{id}', 'CuentaporcobrarController@eliminar');
Route::get('/api/traercliente', 'CuentaporcobrarController@getCliente');
Route::post('/api/guardarpagos', 'CuentaporcobrarController@guardarpagar');
Route::get('/api/anticipototal', 'CuentaporcobrarController@anticipo');
Route::post('/api/guardar_edicion_pago', 'CuentaporcobrarController@guardar_edicion_pago');

Route::get('/api/listar_anticipos/cliente/{id}', 'CuentaporcobrarController@listar_anticipos');

Route::get('/api/listarcuentaslista', 'CuentaporcobrarController@listarcuentaslista');
Route::delete('/api/eliminarcxc/{id}', 'CuentaporcobrarController@eliminarcxc');

Route::get('/api/recibo_cuenta_cobrar/{id}/{id_empresa}/{tipo}', 'CuentaporcobrarController@recibo_cobro');
Route::get('/api/recibo_cuenta_cobrar_anticipo/{id}/{id_empresa}/{tipo}', 'CuentaporcobrarController@recibo_cobro_anticipo');

Route::get('/api/cuentacobrarvercontabilidad/{id}', 'CuentaporcobrarController@verAsiento');
Route::get('/api/cuentacobrar_anticipo_vercontabilidad/{id}', 'CuentaporcobrarController@verAsientoAnticipo');

Route::post('/api/cuenta_cobrar/agregar/asiento', 'CuentaporcobrarController@agregarAsiento');

Route::post('/api/cuenta_cobrar/agregar/asiento_detalle', 'CuentaporcobrarController@agregarAsientoDetalle');

Route::get('/api/pdf/ctaxcobrar', 'CuentaporcobrarController@generarPdf');

Route::post('/api/llamartablavalores', 'CuentaporcobrarController@llamartablavalores');

Route::get('/api/listarsecuencia_recibo/{id}', 'CuentaporcobrarController@listarsecuencia');

//cuentas por pagar
Route::get('/api/pagoproveedor', 'CuentaporpagarController@indexproveedor');
Route::get('/api/cobro/{id}', 'CuentaporpagarController@indexdetalle');
Route::get('/api/ajustarpagos/{id}', 'CuentaporpagarController@ajustarpagos');
Route::post('/api/agregarcobros', 'CuentaporpagarController@agregarcobros');
Route::get('/api/reporte/cuenta_pagar', 'CuentaporpagarController@reporte');
Route::get('/api/form_pago/cuenta_pagar/{id}', 'CuentaporpagarController@getFormaPago');
Route::get('/api/user/cuenta_pagar/admin/{id}', 'CuentaporpagarController@getUserAdmin');
Route::get('/api/user/cuenta_pagar/{id}', 'CuentaporpagarController@getUser');
Route::post('/api/guardar_edicion_pago_compra', 'CuentaporpagarController@guardar_edicion_pago_compra');
//lista los anticipos de los proveedores a pagar
Route::get('/api/listar_anticipos/proveedor/{id}', 'CuentaporpagarController@listar_anticipos');

Route::get('/api/listarcuentasplista', 'CuentaporpagarController@listarcuentaslista');
Route::delete('/api/eliminarcxp/{id}', 'CuentaporpagarController@eliminarcxc');
Route::post('/api/guardarpagoscompra', 'CuentaporpagarController@guardarpagar');
Route::get('/api/anticipototalcompra', 'CuentaporpagarController@anticipo');
Route::get('/api/cuentapagarvercontabilidad/{id}', 'CuentaporpagarController@verAsiento');

Route::get('/api/cuentapagar_anticipo_vercontabilidad/{id}', 'CuentaporpagarController@verAsientoAnticipo');
Route::get('/api/cuentapagar_pago_anticipo_vercontabilidad/{id}', 'CuentaporpagarController@verAsientoPagoAnticipo');


Route::post('/api/cuenta_pagar/agregar/asiento', 'CuentaporpagarController@agregarAsiento');
Route::post('/api/cuenta_pagar/agregar/asiento_detalle', 'CuentaporpagarController@agregarAsientoDetalle');

Route::post('/api/cuenta_pagar/agregar_anticipos_pago/asiento', 'CuentaporpagarController@agregarAsientoPagoAnticipo');
Route::post('/api/cuenta_pagar/agregar_pago_asiento/asiento_detalle', 'CuentaporpagarController@agregarAsientoDetallePagoAnticipo');

Route::get('/api/pdf/ctaxpagar', 'CuentaporpagarController@generarPdf');
Route::get('/api/pdf/cheque/ctaxpagar', 'CuentaporpagarController@generarCheque');

Route::post('/api/llamartablavaloresd', 'CuentaporpagarController@llamartablavalores');

//abonos cuentas por pagar
Route::delete('/api/eliminar/abonos/{id}', 'CuentaporcobrarController@eliminarabonos');
Route::post('/api/editar/abonos', 'CuentaporcobrarController@guardarabonos');

//plan cuentas
Route::get('/api/cuentas/{id}', 'PlancuentasController@index');
Route::get('/api/cuentas/movimiento/{id}', 'PlancuentasController@movimiento');
Route::get('/api/traerempresa', 'PlancuentasController@getEmpresa');
Route::get('/api/traermoneda', 'PlancuentasController@getMoneda');
Route::post('/api/agregarcuentas', 'PlancuentasController@store');
Route::put('/api/abrircta/{id}', 'PlancuentasController@abrir');
Route::put('/api/actualizarcta', 'PlancuentasController@update');
Route::delete('/api/eliminarcta/{id}', 'PlancuentasController@eliminar');
Route::get('/api/traergrupos', 'GrupoController@getgrupos');
Route::get('/api/getcodplan/{id}', 'PlancuentasController@getcodplancuentas');
Route::get('/api/selcuentas/{id}', 'PlancuentasController@select');
Route::get('/api/select_plan_cuentas/{id}', 'PlancuentasController@selectplan_cuentas');

//caja chica
Route::get('/api/caja/{id}', 'CajaController@index');
Route::get('/api/selcuentascaja/{id}', 'CajaController@select');
Route::post('/api/agregarcaja', 'CajaController@store');
Route::put('/api/abrircaja/{id}', 'CajaController@abrir');
Route::put('/api/actualizarcaja', 'CajaController@update');
Route::delete('/api/eliminarcaja/{id}', 'CajaController@eliminar');

//conciliacion-bancaria
Route::get('/api/traer/conciliacion/{id}', 'ConciliacionBancariaController@index');
Route::get('/api/planctas/conciliacion/{id}', 'ConciliacionBancariaController@getPlancuentas');
Route::get('/api/conciliacion/{id}', 'ConciliacionBancariaController@getConciliacion');
Route::get('/api/conciliacion/banco/{id}', 'ConciliacionBancariaController@getBanco');
Route::get('/api/abrir/conciliacion/{id}', 'ConciliacionBancariaController@traerConciliacion');
Route::put('/api/update/asiento/detalle', 'ConciliacionBancariaController@updateAsientoDetalle');
Route::put('/api/update/asiento/detalle/conc', 'ConciliacionBancariaController@updateAsientoDetalleEditCon');
Route::post('/api/agregar/conciliacion', 'ConciliacionBancariaController@store');
Route::put('/api/actualizar/conciliacion', 'ConciliacionBancariaController@update');
Route::delete('/api/eliminar/conciliacion/{id}', 'ConciliacionBancariaController@eliminar');
Route::delete('/api/eliminar/conciliacion/libro/{id}', 'ConciliacionBancariaController@eliminarlibro');

Route::get('/api/actualizar/registro/{id}', 'ConciliacionBancariaController@actualizarRegistro');

Route::get('/api/pdf/conciliacion', 'ConciliacionBancariaController@generarPdf');
Route::post('/api/email/conciliacion', 'ConciliacionBancariaController@generarEmail');

//proveedor
Route::get('/api/proveedor/{id}', 'ProveedorController@index');
Route::put('/api/abrirproveedor', 'ProveedorController@abrir');
Route::put('/api/actualizarproveedor', 'ProveedorController@update');
Route::post('/api/agregarproveedor', 'ProveedorController@store');
Route::delete('/api/eliminarproveedor/{id}', 'ProveedorController@eliminar');
Route::get('/api/traerprovinciaprov', 'ProveedorController@getProvincia');
Route::get('/api/traerciudadprov', 'ProveedorController@getCiudad');
Route::get('/api/traerbancoprov', 'ProveedorController@getBanco');
Route::get('/api/traergruprov/{id}', 'ProveedorController@getGrupo');
Route::get('/api/traerimpfuente/{id}', 'ProveedorController@getImpFuente');
Route::get('/api/traerimpiva/{id}', 'ProveedorController@getImpIva');
Route::get('/api/traertipcomprob/{id}', 'ProveedorController@getTipComprob');
Route::get('/api/traerretfuente/{id}', 'ProveedorController@getRetencionFuente');
Route::get('/api/traerretiva/{id}', 'ProveedorController@getRetencionIva');
Route::get('/api/codigo', 'ProveedorController@codigo');
Route::get('/api/verificarproveedor/{id}', 'ProveedorController@verificarproveedor');
Route::get('/api/selcuentasgrupprov/{id}', 'ProveedorController@select');
Route::get('/api/reporte/proveedor', 'ProveedorController@generarPDF');
Route::get('/api/proveedor_ident/{identificacion}/{id}', 'ProveedorController@getprov_ident');
Route::post('/api/proveedor/buscarfactura', 'ProveedorController@ProveedorFactura');
Route::get('/api/buscarprovciudad', 'ProveedorController@buscarprovciudad');

//grupo-proveedor
Route::get('/api/grupoprov/{id}', 'GrupoProveedorController@index');
Route::post('/api/agregargrupoprov', 'GrupoProveedorController@store');
Route::put('/api/abrirgrupoprov/{id}', 'GrupoProveedorController@abrir');
Route::put('/api/actualizargrupoprov', 'GrupoProveedorController@update');
Route::delete('/api/eliminargrupoproveedor/{id}', 'GrupoProveedorController@eliminar');

//ctaxpagar
/*Route::get('/api/ctaxpagar', 'CuentaporpagarController@index');
Route::post('/api/agregargrupoprov', 'CuentaporpagarController@store');
Route::put('/api/abrirgrupoprov/{id}', 'CuentaporpagarController@abrir');
Route::put('/api/actualizargrupoprov', 'CuentaporpagarController@update');
Route::delete('/api/eliminargrupoproveedor/{id}', 'CuentaporpagarController@eliminar');
Route::get('/api/traerprovctaxpagar', 'CuentaporpagarController@getProveedor');*/

//tipo-comprobante
Route::get('/api/tipcomprob', 'TipocomprobanteController@index');
Route::post('/api/agregartipcomprob', 'TipocomprobanteController@store');
Route::put('/api/abrirtipcomprob/{id}', 'TipocomprobanteController@abrir');
Route::put('/api/actualizartipcomprob', 'TipocomprobanteController@update');
Route::delete('/api/eliminartipcomprob/{id}', 'TipocomprobanteController@eliminar');

//impuestos
Route::get('/api/impuesto', 'ImpuestoController@index');
Route::post('/api/agregarimpuesto', 'ImpuestoController@store');
Route::put('/api/abririmpuesto/{id}', 'ImpuestoController@abrir');
Route::put('/api/actualizarimpuesto', 'ImpuestoController@update');
Route::delete('/api/eliminarimpuesto/{id}', 'ImpuestoController@eliminar');
//Route::get('/api/impuestoexport','ImpuestoController@exportExcel');

//retenciones
Route::get('/api/retencion', 'RetencionController@index');
Route::post('/api/agregarretencion', 'RetencionController@store');
Route::put('/api/abrirretencion/{id}', 'RetencionController@abrir');
Route::put('/api/actualizarretencion', 'RetencionController@update');
Route::delete('/api/eliminarretencion/{id}', 'RetencionController@eliminar');
Route::get('/api/traermonedaret', 'RetencionController@getMoneda');
Route::get('/api/traerimpret', 'RetencionController@getImpuesto');
Route::get('/api/listarclaveretencion/{id}', 'RetencionController@clave');

//tipo-sustento
Route::get('/api/tiposustento/{id}', 'TiposustentoController@index');
Route::post('/api/agregartiposustento', 'TiposustentoController@store');
Route::put('/api/abrirtiposustento/{id}', 'TiposustentoController@abrir');
Route::put('/api/actualizartiposustento', 'TiposustentoController@update');
Route::delete('/api/eliminartiposustento/{id}', 'TiposustentoController@eliminar');

//facturas varias
Route::post('/api/crearfactura', 'FacturaController@store');
Route::post('/api/facturas', 'FacturaController@index');
Route::get('/api/establecimientos', 'EstablecimientoController@index');
Route::get('/api/ptoemision', 'PtoemisionController@index');
Route::get('/api/impuestos', 'ImpuestoController@index');
Route::post('/empresa/agregar', 'EmpresaController@store');
Route::get('/api/listarclave/{id}', 'FacturaController@clave');
Route::post('/api/eliminarfactura', 'FacturaController@eliminar');
Route::get('/api/productos_reporte/{id}', 'FacturaController@productos_reporte');

Route::get('/api/imprimir/ticket/{id}/{tipo}', 'FacturaController@imprimirTicket');
Route::get('/api/imprimir/ejemplo_ticket/{id}', 'FacturaController@ejemplo_ticket');
Route::get('/api/factura/fisica', 'FacturaController@factura_fisica');
Route::get('/api/imprimir/ticket/nota_venta/{id}/{tipo}', 'FacturaController@imprimirTicketNotaVenta');



Route::post('/api/factura_venta/agregar/asiento', 'FacturaController@agregarAsiento');
Route::post('/api/factura_venta/agregar/asiento_detalle', 'FacturaController@agregarAsientoDetalle');
//Guia de remisión
Route::get('/api/listarclave_guia/{id}', 'FacturaController@clave_guia');

//remision-compra
Route::post('/api/factura/xml_r_factura', 'XMLControler@r_factura');

//usuarios
Route::get('/api/usuarios/{id}', 'UserController@index');

//pagos
Route::post('/api/pagoagregar', 'CuentaporcobrarController@pagar');
Route::get('/api/recuperapagoabono/{id}', 'CuentaporcobrarController@recuperabono');
Route::post('/api/editarabono', 'CuentaporcobrarController@editarabono');
Route::post('/api/agregarpagoletra', 'CuentaporcobrarController@pagarletra');

//Proforma
Route::get('/api/proforma/{id}', 'FacturaController@indexp');
Route::get('/api/proforma-vendedor/{id}', 'FacturaController@get_vendedor_profroma');
Route::get('/api/abrirproforma/{id}', 'FacturaController@abrirprof');
Route::put('/api/editarproforma', 'FacturaController@editarprof');
Route::delete('/api/eliminarproforma/{id}', 'FacturaController@eliminarprof');
Route::post('/api/crearproforma', 'FacturaController@storep');
Route::post('/api/proforma/enviarcorreo', 'FacturaController@correoproforma');
Route::post('/api/proforma/listar_productos', 'FacturaController@listar_productos_prof');

//facturas-compra
Route::get('/api/factcompra/{id}', 'FacturacompraController@index');
Route::put('/api/abrirfactcompra', 'FacturacompraController@abrir');
Route::put('/api/abrircredfactcompra/{id}', 'FacturacompraController@abrirCredito');
Route::put('/api/actualfactcompra', 'FacturacompraController@update');
Route::put('/api/actprodfactcompra', 'FacturacompraController@actProducto');
Route::get('/api/abrirprodfactcompra/{id}', 'FacturacompraController@listarProduct');
Route::get('/api/abrirpagfactcompra/{id}', 'FacturacompraController@listPagos');
Route::post('/api/guardarfactura', 'FacturacompraController@store');
Route::post('/api/guardarprodfactcom', 'FacturacompraController@guardarProducto');
Route::post('/api/guardarpagfactcom', 'FacturacompraController@guardarPago');
Route::post('/api/guardarretffactcom', 'FacturacompraController@guardarRetencion');
Route::post('/api/eliminarfactcomp', 'FacturacompraController@eliminar');
Route::get('/api/traersustento', 'FacturacompraController@getSustento');
Route::get('/api/traermonedafact', 'FacturacompraController@getMoneda');
Route::get('/api/traerimport', 'FacturacompraController@getImportacion');
Route::get('/api/traerimport_editar', 'FacturacompraController@getImportacionEditar');
Route::get('/api/traerprovinciafactcomp', 'FacturacompraController@getProvincia');
Route::get('/api/traerptoemfactcomp', 'FacturacompraController@getPtoemision');
Route::get('/api/traerretffactcomp', 'FacturacompraController@getRetencionFuente');
Route::get('/api/traerretivafactcomp', 'FacturacompraController@getRetencionIva');
Route::get('/api/abrirporcretfactcompra', 'FacturacompraController@getPorcentaje');
Route::get('/api/abrirporcivafactcompra', 'FacturacompraController@getPorcentajeIva');
Route::get('/api/traercajafactcomp', 'FacturacompraController@getCaja');
Route::get('/api/traerbancofactcomp', 'FacturacompraController@getBanco');
Route::get('/api/traerclientfactcomp', 'FacturacompraController@getCliente');
Route::get('/api/facturacompra_envioretencion/{id}', 'FacturacompraController@llamado_retencion');
Route::get('/api/ajustarfecharetenciones', 'FacturacompraController@ajustarfecharetenciones');
Route::get('/api/ajustarfechafacturas', 'FacturaController@ajustarfechafacturas');
Route::get('/api/ajustarfechafacturas2', 'FacturaController@ajustarfechafacturas2');
Route::get('/api/ajustarfechanotasdeventas', 'FacturaController@ajustarfechanotasdeventas');
Route::get('/api/ajustarfechabodegaegreso', 'FacturaController@ajustarfechabodegaegreso');
Route::get('/api/ajustarfechabodegaegreso2', 'FacturaController@ajustarfechabodegaegreso2');

Route::get('/api/factura_compra/ctaxpagar/verficacion', 'FacturacompraController@verpagoproveedor');

Route::get('/api/retencion/pdf', 'FacturacompraController@generarPdf');

//orden compra
Route::get('/api/ordencompra/{id}', 'OrdencompraController@indexorden');
Route::post('/api/guardarorden', 'OrdencompraController@store');
Route::post('/api/guardarprodord', 'OrdencompraController@guardarProducto');
Route::put('/api/abrirorden/{id}', 'OrdencompraController@abrir');
Route::put('/api/actualizarorden', 'OrdencompraController@update');
Route::put('/api/actualizarprodorden', 'OrdencompraController@actProducto');
Route::delete('/api/eliminarorden/{id}', 'OrdencompraController@eliminar');
Route::get('/api/abrirprovorden/{id}', 'OrdencompraController@traerProveedores');
Route::get('/api/abrirgrupprovorden', 'OrdencompraController@traergrupoProvd');
Route::get('/api/abrirprodorden/{id}', 'OrdencompraController@traerProductos');
Route::get('/api/abrirformapago', 'OrdencompraController@traerFormaPago');
Route::get('/api/reporte/orden_compra', 'OrdencompraController@generarReporte');

Route::get('/api/pdf/orden_compra', 'OrdencompraController@generarPDF');
Route::post('/api/orden_compra/enviarcorreo', 'OrdencompraController@correorden');





//importacion
Route::get('/api/importacion/{id}', 'ImportacionController@index');
Route::post('/api/agregarimportacion', 'ImportacionController@store');
Route::post('/api/agregarprodimportacion', 'ImportacionController@guardarProd');
Route::post('/api/agregarprovimportacion', 'ImportacionController@guardarProv');
Route::put('/api/abririmportacion/{id}', 'ImportacionController@abrir');
Route::put('/api/actualizarimportacion', 'ImportacionController@update');
Route::put('/api/actualizarprodimportacion', 'ImportacionController@actProducto');
Route::get('/api/actualizarprovimportacion/{id}', 'ImportacionController@traerProvedor');
Route::delete('/api/eliminarimportacion/{id}', 'ImportacionController@eliminar');
Route::get('/api/traerproveedorimport/{id}', 'ImportacionController@getProveedor');
Route::get('/api/traerproductoimport/{id}', 'ImportacionController@abrirProducto');
Route::get('/api/traerproductoliquid/{id}', 'ImportacionController@abrirProductoLiquid');
Route::get('/api/abrirproveedorimport/{id}', 'ImportacionController@abrirProvedor');
Route::get('/api/traerorden/{id}', 'ImportacionController@getOrden');

Route::get('/api/pdf/importacion', 'ImportacionController@generarPDF');

//cuenta_importacion
Route::get('/api/cuenta_importacion/{id}', 'CuentaImportacionController@index');
Route::post('/api/agregarcuenta_importacion', 'CuentaImportacionController@store');
Route::get('/api/abrircuenta_importacion/{id}', 'CuentaImportacionController@abrir');
Route::put('/api/actualizarcuenta_importacion', 'CuentaImportacionController@update');
Route::delete('/api/eliminarcuenta_importacion/{id}', 'CuentaImportacionController@eliminar');

//tipo-activo-fijo
Route::get('/api/tipo_activo/{id}', 'TipoActivoController@index');
Route::get('/api/abrir/tipo_activo/{id}', 'TipoActivoController@abrir');
Route::post('/api/guardar/tipo_activo', 'TipoActivoController@store');
Route::put('/api/actualizar/tipo_activo', 'TipoActivoController@update');
Route::delete('/api/eliminar/tipo_activo/{id}', 'TipoActivoController@eliminar');

//grupo-activo-fijo
Route::get('/api/grupo_activo/{id}', 'GrupoActivoController@index');
Route::get('/api/abrir/grupo_activo/{id}', 'GrupoActivoController@abrir');
Route::post('/api/guardar/grupo_activo', 'GrupoActivoController@store');
Route::put('/api/actualizar/grupo_activo', 'GrupoActivoController@update');
Route::delete('/api/eliminar/grupo_activo/{id}', 'GrupoActivoController@eliminar');

//activos-fijos
Route::get('/api/activo_fijo/{id}', 'ActivoFijoController@index');
Route::post('/api/guardar/activo_fijo', 'ActivoFijoController@store');
Route::get('/api/abrir/activo_fijo/{id}', 'ActivoFijoController@abrir');
Route::put('/api/actualizar/activo_fijo', 'ActivoFijoController@update');
Route::get('/api/abrir/valores/grupo_activo/{id}', 'ActivoFijoController@abrirGrupo');
Route::delete('/api/eliminar/activo_fijo/{id}', 'ActivoFijoController@eliminar');


//depreciacion
Route::get('/api/depreciacion/{id}', 'DepreciacionController@index');
Route::get('/api/depreciacion/activos-fijos/todos/{id}', 'DepreciacionController@getActivoTodos');
Route::get('/api/depreciacion/activos-fijos/{id}', 'DepreciacionController@getActivoEspecial');
Route::get('/api/depreciacion/abrir/{id}', 'DepreciacionController@abrirDepreciacion');
Route::post('/api/depreciacion/activos-fijos/individual', 'DepreciacionController@getActivoIndividual');
Route::post('/api/guardar/depreciacion', 'DepreciacionController@cabecera');
Route::post('/api/guardar/depreciacion/detalle', 'DepreciacionController@detalle');
Route::delete('/api/eliminar/depreciacion/{id}', 'DepreciacionController@eliminar');

Route::get('/api/depreciacion/fecha_maximo/{id}', 'DepreciacionController@fecha_depreciacion');

Route::get('/api/depreciacion/vercontabilidad/{id}', 'DepreciacionController@verAsiento');
Route::post('/api/depreciacion/agregar/asiento', 'DepreciacionController@agregarAsiento');
Route::post('/api/depreciacion/agregar/asiento_detalle', 'DepreciacionController@agregarAsientoDetalle');

Route::get('/api/pdf/depreciaccion', 'DepreciacionController@generarPDF');


//area-activo-fijo
Route::get('/api/area_activo/{id}', 'AreaActivoController@index');
Route::get('/api/abrir/area_activo/{id}', 'AreaActivoController@abrir');
Route::post('/api/guardar/area_activo', 'AreaActivoController@store');
Route::put('/api/actualizar/area_activo', 'AreaActivoController@update');
Route::delete('/api/eliminar/area_activo/{id}', 'AreaActivoController@eliminar');

//cuentas_bodega
Route::get('/api/cuenta_ingreso_bodega/{id}', 'CuentasBodegaController@indexCta_Ingreso');
Route::get('/api/cuenta_egreso_bodega/{id}', 'CuentasBodegaController@indexCta_Egreso');
Route::get('/api/cuenta_transf_bodega/{id}', 'CuentasBodegaController@indexCta_Transf');
Route::post('/api/agregarcuenta_ingreso_bodega', 'CuentasBodegaController@storeCta_Ingreso');
Route::post('/api/agregarcuenta_egreso_bodega', 'CuentasBodegaController@storeCta_Egreso');
Route::post('/api/agregarcuenta_transf_bodega', 'CuentasBodegaController@storeCta_Transf');
Route::get('/api/abrircuenta_ingreso_bodega/{id}', 'CuentasBodegaController@abrirCta_Ingreso');
Route::get('/api/abrircuenta_egreso_bodega/{id}', 'CuentasBodegaController@abrirCta_Egreso');
Route::get('/api/abrircuenta_transf_bodega/{id}', 'CuentasBodegaController@abrirCta_Transf');
Route::put('/api/actualizarcuenta_ingreso_bodega', 'CuentasBodegaController@updateCta_Ingreso');
Route::put('/api/actualizarcuenta_egreso_bodega', 'CuentasBodegaController@updateCta_Egreso');
Route::put('/api/actualizarcuenta_transf_bodega', 'CuentasBodegaController@updateCta_Transf');
Route::delete('/api/eliminarcuenta_ingreso_bodega/{id}', 'CuentasBodegaController@eliminarCta_Ingreso');
Route::delete('/api/eliminarcuenta_egreso_bodega/{id}', 'CuentasBodegaController@eliminarCta_Egreso');
Route::delete('/api/eliminarcuenta_transf_bodega/{id}', 'CuentasBodegaController@eliminarCta_Transf');

Route::get('/api/cuenta_produccion/{id}', 'CuentaProduccionController@index');
Route::post('/api/agregarcuenta_produccion', 'CuentaProduccionController@store');
Route::put('/api/editarcuenta_produccion', 'CuentaProduccionController@update');
Route::post('/api/eliminarcuenta_produccion', 'CuentaProduccionController@delete');
Route::get('/api/pdf_produccion/{id}', 'CuentaProduccionController@pdf_produccion');
//Route::get('/api/traerproveedor/{id}','ImportacionController@getProveedor');
//Route::get('/api/traerproducto/{id}','ImportacionController@getProducto');
//liquidacion
Route::get('/api/liquid/{id}', 'LiquidacionController@index');
Route::put('/api/verliquid', 'LiquidacionController@abrir');
Route::put('/api/liquidar', 'LiquidacionController@liquidar');
Route::get('/api/traerfactliquid/{id}', 'LiquidacionController@abrirFactura');
Route::get('/api/traerbodliquid/{id}', 'LiquidacionController@abrirBodega');
Route::post('/api/liquidarbodega', 'LiquidacionController@guardarBodegaIngreso');
Route::get('/api/liquidvercontabilidad/{id}', 'LiquidacionController@verAsiento');

Route::post('/api/liquid/agregar/asiento', 'LiquidacionController@agregarAsiento');
Route::post('/api/liquid/agregar/asiento_detalle', 'LiquidacionController@agregarAsientoDetalle');


Route::get('/api/creacion_liquidacion_import_pdf/{id}', 'LiquidacionController@PdfLiquidacion');


//Produccion

//formula de Produccion
Route::get('/api/formula/{id}', 'FormulaController@index');

//codigo produccion
Route::get('/api/codfomr/{id}', 'FormulaController@codform');
Route::post('/api/agregarformula', 'FormulaController@store');
Route::put('/api/editarformula', 'FormulaController@update');
Route::delete('/api/eliminarformula/{id}', 'FormulaController@delete');
Route::get('/api/traerformula/{id}', 'FormulaController@getform');
Route::get('/api/traerformprod/{id}', 'FormulaController@getformprod');
Route::get('/api/traerformingred/{id}', 'FormulaController@getformingred');
Route::get('/api/traerformulaproduct/{id}', 'FormulaController@getproductlistformula');
Route::get('/api/traerformulaingred/{id}', 'FormulaController@getingredlistformula');

//proceso produccion
Route::get('/api/traerprocesprod/{id}/{ide}', 'ProcesoProduccionController@index');
Route::get('/api/produccionvercontabilidad/{id}', 'ProcesoProduccionController@verAsiento');
Route::post('/api/produccion/agregar/asiento', 'ProcesoProduccionController@agregarAsiento');
Route::post('/api/produccion/agregar/asiento_detalle', 'ProcesoProduccionController@agregarAsientoDetalle');
Route::post('/api/eliminarproceso_produccion', 'ProcesoProduccionController@eliminarproceso_produccion');

//proceso produccion orden
Route::get('/api/traercodproducion/{id}', 'ProcesoOrdenController@codproduc');
Route::get('/api/traerprocesingred', 'ProcesoOrdenController@getingred');
Route::get('/api/producformu/{id}', 'ProcesoOrdenController@indexform');
Route::get('/api/ordenstockbodegaingred', 'ProcesoOrdenController@stockbodegaingrediente');
Route::post('/api/agregarprocesoorden', 'ProcesoOrdenController@store');
Route::get('/api/traerordenprod/{id}', 'ProcesoOrdenController@getorden');
//proceso produccion proceso
Route::post('/api/agregarprocesoproces', 'ProcesoProcesoController@store');
Route::get('/api/getnewingred/{id}/{ide}', 'ProcesoProcesoController@getnewingred');
//proceso produccion liquidacion
Route::get('/api/traerliquidprodprod/{id}', 'ProcesoLiquidacionController@getordenprod');
Route::get('/api/traerliquidproingred', 'ProcesoLiquidacionController@getingred');
Route::post('/api/agregarliquidproces', 'ProcesoLiquidacionController@store');
///cristian
//Empleados
Route::get('/api/nomina/{id}', 'EmpleadoController@index');
Route::post('/api/empleado/agregar', 'EmpleadoController@store');
Route::post('/api/empleadocarga/agregar', 'EmpleadoController@guardarCarga');
Route::post('/api/empleadodocumento/agregar', 'EmpleadoController@guardarDocumentos');
Route::post('/api/empleadodocumento/agregararchivos', 'EmpleadoController@GuardarArchivos');
Route::post('/api/empleadocarga/agregararchivos', 'EmpleadoController@GuardarDocumetoCarga');
Route::delete('/api/empleado/eliminar/{id}', 'EmpleadoController@eliminar');
Route::put('/api/empleado/verempleado', 'EmpleadoController@verEmpleado');
Route::put('/api/empleadocargo/editar', 'EmpleadoController@actCargas');
Route::put('/api/empleadodocumento/editar', 'EmpleadoController@actDocumentos');
Route::post('/api/empleado/editar', 'EmpleadoController@update');
Route::get('/api/nacionalidad', 'EmpleadoController@getNacionalidad');
Route::get('/api/banco', 'EmpleadoController@getBanco');
Route::get('/api/ciudad', 'EmpleadoController@getCiudad');
Route::get('/api/provincia', 'EmpleadoController@getProvincia');
Route::get('/api/parroquia', 'EmpleadoController@getParroquia');
Route::get('/api/obtenerid', 'EmpleadoController@buscarId');
Route::get('/api/obtenercargas/{id}', 'EmpleadoController@listarCargas');
Route::get('/api/obtenerdocumentos/{id}', 'EmpleadoController@listarDocumentos');
Route::get('/api/cargo', 'EmpleadoController@getCargoEmpleadoArea');
Route::get('/api/cargoempleado', 'EmpleadoController@getCargoEmpleadoId');
Route::get('/api/sueldocargoempleado/{id}', 'EmpleadoController@getSueldoCargoEmpleado');
Route::get('/api/reporteempleado', 'EmpleadoController@generarReporte');

Route::get('/api/empleados/departamento', 'EmpleadoController@empleadosDepartamento');


//Subir Imagen
Route::post('/api/guardarimgempleado', 'EmpleadoController@guardarimagen');

//Cargo de Empleado
Route::post('/api/cargo/agregar', 'EmpleadoCargoController@store');
Route::delete('/api/cargo/eliminar/{id}', 'EmpleadoCargoController@eliminar');
Route::put('/api/cargo/abrir', 'EmpleadoCargoController@abrir');
Route::put('/api/cargo/editar', 'EmpleadoCargoController@update');
Route::get('/api/grupo_ocupacional/{id}', 'EmpleadoCargoController@getGrupoOcu');
Route::get('/api/area/{id}', 'EmpleadoCargoController@getArea');
Route::get('/api/departamento/{id}', 'EmpleadoCargoController@getDepartamento');
Route::get('/api/cargo', 'EmpleadoCargoController@getCargo');

//Cargas de Empleado
Route::get('/api/cargas/listar', 'EmpleadoCargasController@index');
Route::post('/api/carga/agregar', 'EmpleadoCargasController@store');
Route::delete('/api/carga/eliminar/{id}', 'EmpleadoCargasController@eliminar');
Route::put('/api/carga/abrir', 'EmpleadoCargasController@abrir');
Route::put('/api/carga/editar', 'EmpleadoCargasController@update');

//Documentacion de Empleado
Route::post('/api/documentacion/agregar', 'EmpleadoDocumentoController@store');
Route::delete('/api/documentacion/eliminar/{id}', 'EmpleadoDocumentoController@eliminar');
Route::put('/api/documentacion/abrir', 'EmpleadoDocumentoController@abrir');
Route::put('/api/documentacion/editar', 'EmpleadoDocumentoController@update');
Route::get('/api/documento/{id}', 'EmpleadoDocumentoController@getDocumento');

//Calendario de Empleado
Route::get('/api/calendario/listar/{id}', 'EmpleadoCalendarioController@index');
Route::post('/api/calendario/agregar', 'EmpleadoCalendarioController@store');
Route::delete('/api/calendario/eliminar/{id}', 'EmpleadoCalendarioController@eliminar');
Route::put('/api/calendario/abrir', 'EmpleadoCalendarioController@abrir');
Route::put('/api/calendario/editar', 'EmpleadoCalendarioController@update');

//Departamento
Route::get('/api/departamento/listar/{id}', 'DepartamentoController@index');
Route::post('/api/departamento/agregar', 'DepartamentoController@store');
Route::delete('/api/departamento/eliminar/{id}', 'DepartamentoController@eliminar');
Route::put('/api/departamento/abrir', 'DepartamentoController@abrir');
Route::put('/api/departamento/editar', 'DepartamentoController@update');
Route::get('/api/departamento/rol/{id}', 'DepartamentoController@derpemp');

//Asignar Ingresos Egresos
Route::get('/api/asignarinregos/listar/{id}', 'AsignaringresosController@listar');
Route::get('/api/asignarinregos/empleados/{id}', 'AsignaringresosController@listar');
Route::get('/api/asignarinregos/ver/{id}', 'AsignaringresosController@edit');
Route::get('/api/asignarinregos/ver_nuevo/{id}', 'AsignaringresosController@edit_nuevo');
//Route::get('/api/asignarinregos/listar/{id}', 'AsignaringresosController@listar');
Route::post('/api/asignaringresos/agregar/', 'AsignaringresosController@store');
Route::get('/api/asignaringresos/listarempleado', 'AsignaringresosController@getEmpleado');
Route::put('/api/asignaringresos/editar/', 'AsignaringresosController@update');
Route::put('/api/asignaringresos/editar_nuevo/', 'AsignaringresosController@update_nuevo');
Route::delete('/api/asignaringresos/eliminar/{id}', 'AsignaringresosController@destroy');
Route::delete('/api/asignaringresos/eliminar_nuevo/{id}', 'AsignaringresosController@destroy_nuevo');

//Ingresos-egresos
Route::get('/api/ingresoegreso/listar/{id}', 'ingregresoController@listar');
Route::post('/api/ingresoegreso/agregar', 'ingregresoController@store');
Route::put('/api/ingresoegreso/editar', 'ingregresoController@update');
Route::get('/api/ingresoegreso/listarin/{id}', 'ingregresoController@listarin');
Route::get('/api/veringresoegreso/listar/{id}', 'ingregresoController@veringresos');
Route::delete('/api/ingresoegreso/eliminaruno/{id}', 'ingregresoController@destroy');
Route::delete('/api/ingresoegreso/eliminartodo/{id}', 'ingregresoController@eliminartodo');

//Area de Trabajo
Route::get('/api/area_trabajo/listar/{id}', 'AreaTrabajoController@index');
Route::post('/api/area_trabajo/agregar', 'AreaTrabajoController@store');
Route::delete('/api/area_trabajo/eliminar/{id}', 'AreaTrabajoController@eliminar');
Route::put('/api/area_trabajo/abrir', 'AreaTrabajoController@abrir');
Route::put('/api/area_trabajo/editar', 'AreaTrabajoController@update');

//Cargo
Route::get('/api/cargo/listar/{id}', 'CargoController@index');
Route::post('/api/cargo/agregar', 'CargoController@store');
Route::delete('/api/cargo/eliminar/{id}', 'CargoController@eliminar');
Route::put('/api/cargo/abrir', 'CargoController@abrir');
Route::put('/api/cargo/editar', 'CargoController@update');

//Grupo Ocupacional
Route::get('/api/grupo_ocupacional/listar/{id}', 'GrupoOcupacionalController@index');
Route::post('/api/grupo_ocupacional/agregar', 'GrupoOcupacionalController@store');
Route::delete('/api/grupo_ocupacional/eliminar/{id}', 'GrupoOcupacionalController@eliminar');
Route::put('/api/grupo_ocupacional/abrir', 'GrupoOcupacionalController@abrir');
Route::put('/api/grupo_ocupacional/editar', 'GrupoOcupacionalController@update');

//Documento
Route::get('/api/documento/listar/{id}', 'DocumentacionController@index');
Route::post('/api/documento/agregar', 'DocumentacionController@store');
Route::delete('/api/documento/eliminar/{id}', 'DocumentacionController@eliminar');
Route::put('/api/documento/abrir', 'DocumentacionController@abrir');
Route::put('/api/documento/editar', 'DocumentacionController@update');

//Roles
Route::get('/api/rol/{id}', 'RolPagoController@index');
Route::get('/api/cargorol/{id}', 'RolPagoController@abrirArea');
Route::get('/api/empleadoRoles', 'RolPagoController@getEmpleados');
Route::get('/api/ingresoRoles/{id}', 'RolPagoController@getIngreso');
Route::get('/api/ingresoRoles/ver/{id}', 'RolPagoController@getVerIngreso');
Route::get('/api/ingresovalorRoles/{id}', 'RolPagoController@getValoresIngreso');
Route::get('/api/egresoRoles/{id}', 'RolPagoController@getEgreso');
Route::get('/api/egresoRoles/ver/{id}', 'RolPagoController@getVerEgreso');
Route::get('/api/egresovalorRoles/{id}', 'RolPagoController@getValoresEgreso');
Route::get('/api/abriringresoRoles/{id}', 'RolPagoController@abrirIngresos');
Route::get('/api/abriringresoRolesEditar/{id}', 'RolPagoController@abrirIngresosEditar');
Route::get('/api/abrirdepartamento/{id}', 'RolPagoController@abrirDepartamento');
Route::delete('/api/rolpagoeliminar/{id}', 'RolPagoController@destroy');
//Route::post('/api/guardarrol', 'RolPagoController@store');
Route::post('/api/guardaregrerol', 'RolPagoController@store');
Route::put('/api/editarrolpago', 'RolPagoController@update');
Route::get('/api/abrirrolpdf', 'RolPagoController@RolPagoGeneral');
Route::get('/api/generarrolpdf', 'RolPagoController@generarReporte');
Route::get('/api/traerempleadorol', 'RolPagoController@getEmpleadosRolPago');
Route::get('/api/traeremailempleadorol/{id}', 'RolPagoController@getEmpleadosEmail');
Route::post('/api/rolpago/correo', 'RolPagoController@EnviarCorreo');
Route::post('/api/rolpago/correo/rol_general', 'RolPagoController@EnviarRolGeneralCorreo');
Route::get('/api/rolpago/papeletas', 'RolPagoController@Papeletas');
Route::get('/api/rolpago/rol_general', 'RolPagoController@PDFRolGeneral');
Route::get('/api/rolpago/papeleta/individual', 'RolPagoController@PapeletaIndividual');
Route::get('/api/rolpago/traerproyecto/{id}', 'RolPagoController@getProyecto');
Route::get('/api/rolpago/empleado/{id}', 'RolPagoController@getEmpleadoAsiento');
Route::get('/api/rolpago/proyecto/{id}', 'RolPagoController@getProyectoAsiento');
Route::get('/api/rolpago/detalle/{id}', 'RolPagoController@getDetalleAsiento');
Route::post('/api/rolpago/agregar/asiento', 'RolPagoController@agregarAsiento');
Route::post('/api/rolpago/agregar/asiento_detalle', 'RolPagoController@agregarAsientoDetalle');

Route::get('/api/rolpago/empleados', 'RolPagoController@RolPagoEmpleado');


//Rol-Provisiones
Route::post('/api/guardarrolprov', 'RolProvicionController@store');
Route::put('/api/editarolprov', 'RolProvicionController@update');
Route::get('/api/rolprov/{id}', 'RolProvicionController@index');
Route::get('/api/abrirrolprov/{id}', 'RolProvicionController@abrir');
Route::get('/api/abrirrolpagoprov/{id}', 'RolProvicionController@abrirIngresos');
Route::get('/api/generarrolpagoprovpdf', 'RolProvicionController@generarReporte');
Route::delete('/api/rolproveliminar/{id}', 'RolProvicionController@destroy');
Route::get('/api/rolprovicion/empleado/{id}', 'RolProvicionController@getEmpleadoAsiento');
Route::get('/api/rolprovicion/proyecto/{id}', 'RolProvicionController@getProyectoAsiento');
Route::get('/api/rolprovicion/detalle/{id}', 'RolProvicionController@getDetalleAsiento');
Route::post('/api/rolprovicion/agregar/asiento', 'RolProvicionController@agregarAsiento');
Route::post('/api/rolprovicion/agregar/asiento_detalle', 'RolProvicionController@agregarAsientoDetalle');

//Parametrizacion
Route::get('/api/parametrizacion/listar/{id}', 'ParametrizacionController@listar');
Route::post('/api/parametrizacion/agregar', 'ParametrizacionController@store');
Route::put('/api/parametrizacion/editar', 'ParametrizacionController@update');
Route::get('/api/parametrizacion/listarin/{id}', 'ParametrizacionController@listarin');
Route::get('/api/parametrizacion/listarcuentas/{id}', 'ParametrizacionController@ObtenerPlanCuentas');
Route::get('/api/verparametrizacion/listar/{id}', 'ParametrizacionController@veringresos');
Route::delete('/api/parametrizacion/eliminaruno/{id}', 'ParametrizacionController@destroy');
Route::delete('/api/parametrizacion/eliminartodo/{id}', 'ParametrizacionController@eliminartodo');

//Usuario
Route::post('/api/regusuario', 'UserController@registro');
Route::post('/api/editarregusuario', 'UserController@editar');
Route::get('/api/recregusuario/{id}', 'UserController@listar');
Route::delete('/api/eliminarusuario/{id}', 'UserController@delete');
Route::get('/api/versesionuser/{id}', 'UserController@versesion');

//modulos
Route::get('/api/listarmodulo/{id}', 'ModulosController@ver');
Route::post('/api/guardarmodulo', 'ModulosController@store');

//formas de pago
Route::get('/api/administrar/forma_pagos/listar', 'Forma_pagosController@listar');
Route::post('/api/administrar/forma_pagos/guardar', 'Forma_pagosController@guardar');
Route::put('/api/administrar/forma_pagos/editar', 'Forma_pagosController@editar');
Route::delete('/api/administrar/forma_pagos/eliminar/{id}', 'Forma_pagosController@eliminar');
Route::get('/api/administrar/forma_pagos/cuenta_contable', 'Forma_pagosController@cuenta_contable');
Route::get('/api/administrar/forma_pagos/listar/asientos', 'Forma_pagosController@listarFormasPagosAsientos');
Route::get('/api/administrar/forma_pagos/listar/asientos/index', 'Forma_pagosController@listarFormasPagosAsientosIndex');

//formas de pago sri
Route::get('/api/forma_pagos_sri/listar', 'Forma_pagos_sriController@listar');
Route::post('/api/forma_pagos_sri/guardar', 'Forma_pagos_sriController@guardar');
Route::put('/api/forma_pagos_sri/editar', 'Forma_pagos_sriController@editar');
Route::delete('/api/forma_pagos_sri/eliminar/{id}', 'Forma_pagos_sriController@eliminar');

//---------------------------COMPROBANTES-------------------------------//

//Nueva Estructura de Comprobantes

//envio al sri las facturas
Route::post('/api/leerFacturaphp', 'FacturacionController@leerFactura');
Route::post('/api/firmaphp', 'FacturacionController@firmaphp');
Route::get('/api/hexToInt/{id}', 'FacturacionController@hexToInt');
Route::post('/api/validarComprobantephp', 'FacturacionController@validarComprobantephp');
Route::post('/api/autorizacionComprobantephp', 'FacturacionController@autorizacionComprobantephp');
Route::post('/api/validarFechaCertificadophp', 'FacturacionController@validarFechaCertificadophp');
Route::post('/api/respfactura', 'FacturacionController@respfactura');

//generar XML de comprobantes
Route::post('/api/factura/xml_factura', 'XMLControler@efactura');
Route::post('/api/factura/xml_guia', 'XMLControler@e_guia');
Route::post('/api/factura/xml_nota_credito', 'XMLControler@enotacredito');
Route::post('/api/factura/xml_nota_debito', 'XMLControler@enotadebito');
Route::post('/api/factura/xml_compro_retenc', 'XMLControler@e_comproretenc');
Route::post('/api/liquidacion_compra/xml_factura', 'XMLControler@e_liquidacioncompra');

//facturas
Route::post('/api/factura_venta/guardar_factura', 'FacturaController@guardar_factura');
Route::put('/api/factura_venta/editar_factura', 'FacturaController@editar_factura');
Route::get('/api/listarretenciones', 'FacturaController@listarretenciones');
Route::get('/api/factproductos', 'FacturaController@verproductos');
Route::get('/api/abrirretencionp/{id}', 'FacturaController@listarpretenciones');
Route::get('/api/abrircreditosp/{id}', 'FacturaController@abrircreditosp');
Route::get('/api/abrirpagosp/{id}', 'FacturaController@abrirpagosp');
Route::get('/api/traerclientefactura/{id}', 'FacturaController@traercliente');
Route::get('/api/facturaformapagos/{id}', 'FacturaController@facturaformapagos');
Route::post('/api/factura_venta/verificaproducto', 'FacturaController@verificaproducto');
Route::post('/api/factura_venta/guardar_factura_clave', 'FacturaController@guardar_factura_clave');

Route::post('/api/factura_venta/listar_productos', 'FacturaController@listar_productos');

Route::get('/api/factura/recuperar/{id}', 'FacturaController@recuperar');
Route::get('/api/facturaver/{id}', 'FacturaController@facturaver');
Route::get('/api/facturavercontabilidad/{id}', 'FacturaController@facturaContabilizar');
Route::get('/api/factura/vervendedor', 'FacturaController@vendedores');
Route::get('/api/factura/empresavervendedor', 'FacturaController@vendedoresEmpresa');
Route::get('/api/factura/duplicar', 'FacturaController@duplicar');


Route::get('/api/listarproductos/factura/anterior', 'FacturaController@productos_anterior');


//facturas acumuladas
Route::post('/api/factura_acumulada/guardar_factura', 'FacturaController@store_factura_acumulada');
Route::post('/api/facturas_acumulada', 'FacturaController@index_acumulada');
Route::get('/api/listarclave_nota_venta/{id}', 'FacturaController@clave_nota_venta');
Route::get('/api/nota_venta/recuperar/{id}', 'FacturaController@recuperar_nota_venta');
Route::put('/api/nota_venta/editar_nota_venta', 'FacturaController@editar_nota_venta');
Route::post('/api/eliminarnota_venta', 'FacturaController@eliminarnota_venta');
Route::get('/api/nota_ventaver/{id}', 'FacturaController@nota_ventaver');
Route::get('/api/creacion_nota_venta_pdf/{id}/{tipo}', 'FacturacionController@nota_venta_pdf');
Route::post('/api/factura_acumulada/guardar_notaventa_proforma', 'FacturaController@notaventa_proforma');

Route::post('/api/nota_venta/guardar_nota_venta_clave', 'FacturaController@guardar_nota_venta_clave');

Route::get('/api/listarclave_guia_nota_venta/{id}', 'FacturaController@clave_guia');

Route::get('/api/notaVentavercontabilidad/{id}', 'FacturaController@notaVentaContabilizar');

Route::post('/api/nota_venta/agregar/asiento', 'FacturaController@agregarAsientoNotaVenta');
Route::post('/api/nota_venta/agregar/asiento_detalle', 'FacturaController@agregarAsientoDetalleNotaVenta');

Route::get('/api/listarproductos/nota_venta/anterior', 'FacturaController@productos_anterior_nota_venta');

Route::get('/api/reportes/nota_venta', 'FacturaController@reportestotales_nota_venta');

Route::get('/api/nota_venta/duplicar', 'FacturaController@duplicar_nota_venta');


//facturacion masiva
Route::get('/api/factura_masiva/list/{id}', 'FacturaMasivaController@index');
//lista precio
Route::get('/api/reportes/listaprecio', 'ListaPrecioController@generarReporte');




Route::post('/api/factura_venta/guardar_guia_clave', 'FacturaController@guardar_guia_clave');
Route::post('/api/factura/recuperar_guia/{id}', 'FacturaController@listar_guia_clave');

//facturas_compra
Route::post('/api/factura_compra/guardar_factura', 'FacturacompraController@guardar_factura');
Route::put('/api/factura_compra/editar_factura', 'FacturacompraController@editar_factura');
Route::put('/api/factura_compra/validar', 'FacturacompraController@validar_clave_retencion');
Route::get('/api/factura_compra/listar_proveedor', 'FacturacompraController@listar_proveedor');
Route::get('/api/factura_compra/recuperar/{id}', 'FacturacompraController@recuperar');
Route::get('/api/factura_compra/traerbodegas', 'FacturacompraController@traerbodegas');
Route::get('/api/factura_compravercontabilidad/{id}', 'FacturacompraController@facturaCompraContabilizar');
Route::post('/api/factura_compra/agregar/asiento', 'FacturacompraController@agregarAsiento');
Route::post('/api/factura_compra/agregar/asiento_detalle', 'FacturacompraController@agregarAsientoDetalle');
Route::post('/api/factura_compra/guardar_factura_clave', 'FacturacompraController@guardar_factura_clave');
Route::post('/api/factura_compra/listar_productoxml', 'FacturacompraController@listar_productoxml');
Route::get('/api/creacion_factura_compra_pdf/{id}/{tipo}', 'FacturacompraController@factura_compra_pdf');
Route::post('/api/savefilesfactcompra', 'FacturacompraController@savefilesfactcompra');
Route::get('/api/dowloadxmlfactcompra', 'FacturacompraController@downloadxmlfactcompra');
Route::get('/api/dowloadpdffactcompra', 'FacturacompraController@downloadpdffactcompra');

//agregar nota de credito
Route::post('/api/nota_credito', 'NotacreditoController@index');
Route::get('/api/listarclavecredito/{id}', 'NotacreditoController@clave');
Route::get('/api/notacredito/listar_cliente', 'NotacreditoController@listar_cliente');
Route::post('/api/notacredito/listar_productos', 'NotacreditoController@listar_productos');
Route::post('/api/notacredito/listar_productos1', 'NotacreditoController@listar_productos1');
Route::get('/api/notacredito/listar_creacion_cliente/{id}', 'NotacreditoController@listar_creacion_cliente');
Route::get('/api/notacredito/listar_canton/{id}', 'NotacreditoController@listar_canton');
Route::get('/api/notacredito/listar_parroquia/{id}', 'NotacreditoController@listar_parroquia');
Route::get('/api/notacredito/listar_cuenta_contable', 'NotacreditoController@listar_cuenta_contable');
Route::get('/api/notacredito/verificarcliente/{id}', 'NotacreditoController@verificarcliente');
Route::get('/api/vernotacredito/{id}', 'NotacreditoController@ver');
Route::get('/api/notacredito/recuperar/{id}', 'NotacreditoController@recuperar');
Route::post('/api/notacredito/guardar_cliente', 'NotacreditoController@guardar_cliente');
Route::post('/api/notacredito/verificarcliente', 'NotacreditoController@verificarcliente');

Route::get('/api/reportes/nota_credito', 'NotacreditoController@generarReport');


Route::post('/api/notacredito/guardar_factura', 'NotacreditoController@guardar_factura');
Route::put('/api/notacredito/editar_factura', 'NotacreditoController@editar_factura');
Route::delete('/api/eliminarfactcre/{id}/{documento}', 'NotacreditoController@eliminar');
Route::post('/api/notacredito/buscarfactura', 'NotacreditoController@buscarfactura');
Route::post('/api/notacredito/listar_facturas', 'NotacreditoController@listar_facturas');

Route::get('/api/notacredito/verasiento/{id}', 'NotacreditoController@verAsiento');

Route::post('/api/nota_credito_fact/agregar/asiento', 'NotacreditoController@agregarAsiento');
Route::post('/api/nota_credito_fact/agregar/asiento_detalle', 'NotacreditoController@agregarAsientoDetalle');

Route::get('/api/nota_credito_fact/pdf', 'NotacreditoController@generarPDF');

//agregar nota de credito Compra
Route::post('/api/nota_credito_compra', 'NotacreditoCompraController@index');
Route::post('/api/notacreditocompra/buscarfactura', 'NotacreditoCompraController@buscarfactura');
Route::post('/api/notacreditocompra/guardar_factura', 'NotacreditoCompraController@guardar_factura');
Route::post('/api/notacreditocompra/editar_factura', 'NotacreditoCompraController@editar_factura');
Route::delete('/api/eliminarnota_credito_compra/{id}/{documento}/{empresa}', 'NotacreditoCompraController@eliminar');
Route::get('/api/rectificarctaspagar/{empresa}', 'NotacreditoCompraController@rectificar');
Route::get('/api/notacreditocompra/recuperar/{id}/{ide}', 'NotacreditoCompraController@recuperar');
Route::get('/api/reportes/nota_credito_compra', 'NotacreditoCompraController@reportes');
Route::get('/api/notacredito_compra/verasiento/{id}', 'NotacreditoCompraController@verAsiento');
Route::get('/api/nota_credito_compra/pdf', 'NotacreditoCompraController@generarPDF');
Route::post('/api/nota_credito_comp/agregar/asiento', 'NotacreditoCompraController@agregarAsiento');
Route::post('/api/nota_credito_comp/agregar/asiento_detalle', 'NotacreditoCompraController@agregarAsientoDetalle');
Route::post('/api/notacredito_compra/listar_servicios', 'NotacreditoCompraController@listar_servicios');

//agregar nota de debito
Route::post('/api/nota_debito', 'NotadebitoController@index');
Route::delete('/api/eliminardebito/{id}', 'NotadebitoController@eliminar');
Route::get('/api/listarclavedebito/{id}', 'NotadebitoController@clave');
Route::post('/api/notadebito/guardar_factura', 'NotadebitoController@guardar_factura');
Route::put('/api/notadebito/editar_factura', 'NotadebitoController@editar_factura');
Route::get('/api/notadebito/recuperar/{id}', 'NotadebitoController@recuperar');
Route::get('/api/vernotadebito/{id}', 'NotadebitoController@ver');


Route::get('/api/notadebito/verasiento/{id}', 'NotadebitoController@verAsiento');
Route::post('/api/nota_debito_fact/agregar/asiento', 'NotadebitoController@agregarAsiento');
Route::post('/api/nota_debito_fact/agregar/asiento_detalle', 'NotadebitoController@agregarAsientoDetalle');

//agregar nota de debito compra
Route::post('/api/nota_debito_compra', 'NotadebitoCompraController@index');
Route::delete('/api/eliminardebitocompra/{id}', 'NotadebitoCompraController@eliminar');
Route::post('/api/notadebitocompra/guardar_factura', 'NotadebitoCompraController@guardar_factura');
Route::put('/api/notadebitocompra/editar_factura', 'NotadebitoCompraController@editar_factura');
Route::get('/api/notadebitocompra/recuperar/{id}', 'NotadebitoCompraController@recuperar');
Route::get('/api/vernotadebitocompra/{id}', 'NotadebitoCompraController@ver');
Route::post('/api/notadebitocompra/guardar_factura', 'NotadebitoCompraController@guardar_factura');

Route::get('/api/notadebito_compra/verasiento/{id}', 'NotadebitoCompraController@verAsiento');

Route::post('/api/nota_debito_comp/agregar/asiento', 'NotadebitoCompraController@agregarAsiento');
Route::post('/api/nota_debito_comp/agregar/asiento_detalle', 'NotadebitoCompraController@agregarAsientoDetalle');

//agregar Liquidación compra
Route::post('/api/liquidacion_compra', 'LiquidacionCompraController@index');
Route::delete('/api/eliminarliquidacion_compra/{id}', 'LiquidacionCompraController@eliminar');
Route::get('/api/listarclaveliquidacioncompra/{id}', 'LiquidacionCompraController@clave');
Route::post('/api/liquidacion_compra/guardar_liquidacion_clave', 'LiquidacionCompraController@guardar_liquidacion_clave');
Route::post('/api/liquidacion_compra/guardar_liquidacion', 'LiquidacionCompraController@guardar_liquidacion');
Route::get('/api/liquidacion_compra/recuperar/{id}', 'LiquidacionCompraController@recuperar');
Route::get('/api/liquidacion_compra/ctaxpagar/verficacion', 'LiquidacionCompraController@verpagoproveedor');
Route::put('/api/liquidacion_compra/editar_liquidacion', 'LiquidacionCompraController@editar_liquidacion_compra');
Route::post('/api/eliminarliquidacion_compra', 'LiquidacionCompraController@eliminar_liquidacion_compra');
Route::get('/api/liquidacioncompra_envioretencion/{id}', 'LiquidacionCompraController@llamado_retencion');
Route::get('/api/liquidacion_compravercontabilidad/{id}', 'LiquidacionCompraController@liquidacionCompraContabilizar');
Route::post('/api/liquidacion_compra/agregar/asiento', 'LiquidacionCompraController@agregarAsiento');
Route::post('/api/liquidacion_compra/agregar/asiento_detalle', 'LiquidacionCompraController@agregarAsientoDetalle');
Route::get('/api/liquidcomp/tipcomprob', 'LiquidacionCompraController@vertipcomprob');
Route::get('/api/creacion_liquidacion_compra_pdf/{id}/{tipo}', 'LiquidacionCompraController@liquidacion_compra_pdf');
Route::get('/api/reportes/liquidacion_compra', 'LiquidacionCompraController@liquidacion_comprastotales');

//ejemplos
Route::get('/ejemplos/{id}', 'FacturaController@ejemplos');
Route::get('/cambio_cod_prov/{id}', 'FacturaController@cambio_cod_prov');
Route::get('/usuario_cobros/{id}', 'FacturaController@cambio_user_cobro');
Route::get('/ejemplos_producto/{id}', 'FacturaController@ejemplos_producto');
Route::get('/cambio_sustento/{id}', 'FacturacompraController@sustento_compra');
Route::get('/cambio_fecha_ctas_pagar/{id}', 'FacturacompraController@fecha_cta_pagar');
Route::get('/cambio_posicion_ctas_cobrar/{id}', 'FacturaController@cambio_posicion_ctasxcobrar');
Route::get('/cambio_posicion_ctas_pagar/{id}', 'FacturaController@cambio_posicion_ctasxpagar');
Route::get('/devolucion_nota_credito_compra/{id}', 'FacturaController@devolucion_nota_credito_compra');
Route::get('/devolucion_nota_credito_venta/{id}', 'FacturaController@devolucion_nota_credito_venta');


Route::get('/data_cliente', 'FacturaController@dataClient');

//planes seguro
Route::post('/api/planes_seguro/{id}', 'PlanSeguroController@index');
Route::get('/api/productos_planes/{id}', 'PlanSeguroController@productos');
Route::post('/api/guardar_planes_seguro', 'PlanSeguroController@guardar');
Route::get('/api/pla_seguro/recuperar/{id}', 'PlanSeguroController@recuperar');
Route::get('/api/productos_planes_editar/{id}', 'PlanSeguroController@productos_editar');
Route::post('/api/editar_planes_seguro', 'PlanSeguroController@editar');
Route::get('/api/eliminar_plan_seguro/{id}', 'PlanSeguroController@delete');
Route::get('/api/list_plan_seguro/{id}', 'PlanSeguroController@list_plan_seguro');

//seguro
Route::post('/api/seguros/{id}', 'SeguroController@index');
Route::post('/api/guardar_seguro', 'SeguroController@store');
Route::put('/api/editar_seguro', 'SeguroController@update');
Route::get('/api/eliminar_seguro/{id}', 'SeguroController@delete');

//roles generales
Route::get('/api/empresaroles', 'EmpresaController@empresaroles');
Route::get('/api/empresaroles/{id}', 'EmpresaController@empresarolesid');

Route::get('/{empresa}/imagen/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . "$empresa/imagen/$filename";
  //return File::get($filePath);
  $file = File::get($filePath);
  $type = File::mimeType($filePath);
  $response = Response::make($file, 200);
  $response->header("Content-Type", $type);
  return $response;
})->name('image.displayImage');

Route::get('/image/{id}/{imagen}', 'EmpresaController@logoempresa');

Route::get(constant("DATA_EMPRESA") . '{empresa}/firma/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/firma/' . $filename;
  return File::get($filePath);
});
Route::get('{empresa}/productos/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/productos/' . $filename;
  return File::get($filePath);
});

/*Route::get(constant("DATA_EMPRESA").'{empresa}/empleados/{idempleados}/imagenes/{filename}', function ($empresa, $filename,$idempleados) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/empleados/' . $idempleados . '/imagenes/' . $filename;
  return File::get($filePath);
});*/

Route::get('{empresa}/empleados/{idempleados}/imagenes/{filename}', function ($empresa, $filename, $idempleados) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/empleados/' . $filename . '/imagenes/' . $idempleados;
  return File::get($filePath);
});

Route::get('{empresa}/empleados/{idempleados}/documentos/{filename}', function ($empresa, $filename, $idempleados) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/empleados/' . $filename . '/documentos/' . $idempleados;
  return File::get($filePath);
});

Route::get('{empresa}/papeletas/{idempleados}/{departamento}/{filename}', function ($empresa, $filename, $departamento, $idempleados) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/papeletas/' . $filename . '/' . $departamento . '/' . $idempleados;
  return File::get($filePath);
});

Route::get('{empresa}/rol_general/{idempleados}/{departamento}/{filename}', function ($empresa, $filename, $departamento, $idempleados) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/rol_general/' . $filename . '/' . $departamento . '/' . $idempleados;
  return File::get($filePath);
});

//---------------------------PDF NUEVOS-------------------------------//
Route::get('/api/reportes/factura_venta/pdf', 'PdfController@factura_venta');
Route::get('/api/reportes/dairio/pdf', 'PdfController@diario');

//-------------------------VISTA DE PDF------------------------------//
Route::get('/{empresa}/vistapdf/factura_venta/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/nota_credito/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/nota_debito/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notadebito/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/liquidacion_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/liquidacioncompra/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/retencion_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/retencion_' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/nota_credito_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacreditocompra/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/nota_debito_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacreditocompra/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdf/guia/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/' . $filename . '.pdf';
  return Response::download($filePath);
});
//----------------------Recibo Cobro---------------------------------//
Route::get('/{empresa}/vistapdfrecibo_cobro/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/recibo_cobro/recibo_cobro_' . $filename . '.pdf';
  return Response::file($filePath);
});
//--------------------------------------------------------------------//

//-------------------------VISTA DE XML------------------------------//
Route::get('/{empresa}/vistaxml/factura_venta/respuesta_sri/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/respuestaSRI/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/factura_venta/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/nota_credito/respuesta_sri/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/respuestaSRI/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/nota_credito/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/nota_debito/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notadebito/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/liquidacion_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/liquidacion_compra/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/retencion_compra/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/guia/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/guia/respuesta_sri/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/respuestaSRI/' . $filename . '.xml';
  return Response::download($filePath);
});
Route::get('/{empresa}/factura/ticket/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.pdf';
  return Response::download($filePath);
});
Route::get('/{empresa}/vistaxml/errores_ventas/{filename}', function ($empresa, $filename) {
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/errores/' . $filename . '.txt';
  return Response::download($filePath);
});
Route::get('/{id}/anexto_transaccional/decarga/{anio}/{mes}', function ($id, $anio, $mes) {
  $mess = str_pad($mes, 2, "0", STR_PAD_LEFT);
  $filePath = constant("DATA_EMPRESA") . $id . "/comprobantes/factura/anexo_transaccional_" . $id . "_" . $anio . "-" . $mess . ".xml";
  $empresa = DB::select("SELECT * FROM empresa WHERE id_empresa = $id");
  $nombre = $empresa[0]->nombre_empresa;
  return Response::download($filePath, "ATS_" . $nombre . "_" . $mess . "_" . $anio . ".xml");
});
//----------------------VISTA DE PDF Y XML---------------------------//
Route::get('/{empresa}/vistapdfyxml/factura_venta/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.xml';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/factura/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdfyxml/nota_credito/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/' . $filename . '.xml';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notacredito/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdfyxml/nota_debito/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notadebito/' . $filename . '.xml';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notadebito/' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/notadebito/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdfyxml/liquidacion_compra/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/liquidacioncompra/' . $filename . '.xml';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/liquidacioncompra/' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/liquidacioncompra/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdfyxml/retencion_compra/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/' . $filename . '.xml';
  //$file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/' . $filename . '.pdf';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/retencion_' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/retencioncompra/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
Route::get('/{empresa}/vistapdfyxml/guia/{filename}', function ($empresa, $filename) {
  $file = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/' . $filename . '.xml';
  $file1 = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/' . $filename . '.pdf';
  $filePath = constant("DATA_EMPRESA") . $empresa . '/comprobantes/guia/' . $filename . '.zip';
  Madzipper::make($filePath)->add($file)->add($file1)->close();
  return Response::download($filePath);
});
//------------------------RUTAS CORREO-----------------------------//
Route::post('/api/factura_venta/enviarcorreo', 'CorreoController@correofacturaventa');
Route::post('/api/factura_venta/enviarcorreo_masivo', 'CorreoController@correofacturaventa_masivo');
Route::post('/api/nota_credito/enviarcorreo', 'CorreoController@correonotacredito');
Route::post('/api/nota_debito/enviarcorreo', 'CorreoController@correonotadebito');
Route::post('/api/liquidacion_compra/enviarcorreo', 'CorreoController@correoliquidacioncompra');
Route::post('/api/retencion_compra/enviarcorreo', 'CorreoController@correoretencioncompra');
Route::post('/api/retencion_compra/solopdfenviarcorreo', 'CorreoController@correoretencioncompraSolo');
Route::post('/api/factura_compra/enviarcorreo', 'CorreoController@correofacturacompra');
Route::post('/api/nota_credito_compra/enviarcorreo', 'CorreoController@correonotacreditocompra');
Route::post('/api/nota_debito_compra/enviarcorreo', 'CorreoController@correonotadebitocompra');
Route::post('/api/guia/enviarcorreo', 'CorreoController@correoguia');
Route::post('/api/guia/enviarcorreo_masivo', 'CorreoController@correoguia_masivo');
Route::post('/api/nota_venta/enviarcorreo', 'CorreoController@correonotaventa');
//---------------------------REPORTES-------------------------------//

//reporte liquidación individual
//Route::get('/liquidacion/{id}', 'LiquidacionController');
//reporte venta facturas
Route::get('/api/reportes/factura', 'FacturaController@reportestotales');
Route::get('/api/reportes/cierre_caja', 'FacturaController@reportesCierreCaja');
Route::get('/api/reportes/productovscostos', 'FacturaController@reportesproductovscostos');
Route::get('/api/reportes/facturavscostos', 'FacturaController@reportesfacturavscostos');
Route::get('/api/reportes/check_list', 'FacturaController@reportesCheckList');
Route::get('/api/excel/exportar/factura', 'ImportController@exportFactura');

Route::get('/api/reportes/compra', 'FacturacompraController@comprastotales');
Route::get('/api/reportes/comprasporproducto', 'FacturacompraController@reportecomprasporproducto');
Route::get('/api/reportes/balance-comprobacion', 'PlancuentasController@balanceComprobacion');
Route::get('/api/reportes/proforma', 'FacturaController@generaProforma');
Route::get('/api/reportes/vendedor', 'FacturaController@reportesvendedor');
Route::get('/api/vendedores/{id}', 'FacturaController@getVendedores');
Route::get('/api/traer/vendedores/admin/{id}', 'FacturaController@getVendedoresVendAdmin');
Route::get('/api/traer/vendedores/{id}', 'FacturaController@getVendedoresVend');
Route::get('/api/factura/ver/clientes/{id}', 'FacturaController@getClientes');
Route::get('/api/proveedores', 'FacturacompraController@getProveedores');
Route::get('/api/rucs', 'FacturacompraController@getRucProveedores');
Route::get('/api/reportes/cuentas-por-cobrar', 'CuentaporcobrarController@reporteCuentasCobrar');
Route::get('/api/reportes/cuentas-por-pagar', 'CuentaporpagarController@reporteCuentasPagar');

Route::get('/api/pruebacorreo/{id}', 'CorreoController@pruebacorreo');
Route::post('/api/pruebacorreodata', 'CorreoController@pruebacorreodata');

//Guia Remision
Route::post('/api/guia_remision/guardar_guia', 'GuiaController@guardar_guia');
Route::post('/api/guia_remision/listar', 'GuiaController@listar');
Route::post('/api/guia_remision/listar_documentos', 'GuiaController@listar_documentos');
Route::post('/api/guia_remision/buscardocumento', 'GuiaController@buscardocumento');
Route::get('/api/guia_remision/recuperar/{id}', 'GuiaController@recuperar');
Route::put('/api/guia_remision/editar_guia', 'GuiaController@editar_guia');
Route::get('/api/reportes/guia_remision', 'GuiaController@generar_reporte');
Route::post('/api/guia_remision/guia_remision_clave', 'GuiaController@guardar_guia_clave');
Route::post('/api/guia_remision/eliminar', 'GuiaController@eliminar');

//Tabla Interes
Route::get('/api/tabla_interes/{id}', 'TablaInteresController@index');
Route::post('/api/tabla_interes/guardar', 'TablaInteresController@store');
Route::put('/api/tabla_interes/editar', 'TablaInteresController@update');
Route::get('/api/tabla_interes/eliminar/{id}/{user}', 'TablaInteresController@delete');
//Tabla Interes Anual
Route::get('/api/tabla_interes_anual/{id}', 'TablaInteresController@index_anual');
Route::post('/api/tabla_interes_anual/guardar', 'TablaInteresController@store_anual');
Route::put('/api/tabla_interes_anual/editar', 'TablaInteresController@update_anual');
Route::get('/api/tabla_interes_anual/eliminar/{id}/{user}', 'TablaInteresController@delete_anual');

//Convenio
Route::get('/api/convenio/{id}', 'ConvenioController@index');
Route::get('/api/permisoconvenio/{id}', 'ConvenioController@permiso');
Route::post('/api/convenio/guardar', 'ConvenioController@store');
Route::put('/api/convenio/editar', 'ConvenioController@update');
Route::put('/api/convenio/activacion', 'ConvenioController@activacion');
Route::get('/api/convenio/eliminar/{id}', 'ConvenioController@delete');

//tipo activo
Route::get('/api/tipoactivomobiliario/{id}/{buscar?}', 'MobiliarioController@indextipoactivo');
Route::post('/api/guardartipoactivomobiliario', 'MobiliarioController@storetipoactivo');
Route::post('/api/editartipoactivomobiliario', 'MobiliarioController@updatetipoactivo');
Route::delete('/api/eliminartipoactivomobiliario/{id}', 'MobiliarioController@deletetipoactivo');
//color
Route::get('/api/colormobiliario/{id}/{buscar?}', 'MobiliarioController@indexcolor');
Route::post('/api/guardarcolormobiliario', 'MobiliarioController@storecolor');
Route::post('/api/editarcolormobiliario', 'MobiliarioController@updatecolor');
Route::delete('/api/eliminarcolormobiliario/{id}', 'MobiliarioController@deletecolor');
//conservacion
Route::get('/api/conservacionmobiliario/{id}/{buscar?}', 'MobiliarioController@indexconservacion');
Route::post('/api/guardarconservacionmobiliario', 'MobiliarioController@storeconservacion');
Route::post('/api/editarconservacionmobiliario', 'MobiliarioController@updateconservacion');
Route::delete('/api/eliminarconservacionmobiliario/{id}', 'MobiliarioController@deleteconservacion');
//custodio
Route::get('/api/custodiomobiliario/{id}/{buscar?}', 'MobiliarioController@indexcustodio');
Route::post('/api/guardarcustodiomobiliario', 'MobiliarioController@storecustodio');
Route::post('/api/editarcustodiomobiliario', 'MobiliarioController@updatecustodio');
Route::delete('/api/eliminarcustodiomobiliario/{id}', 'MobiliarioController@deletecustodio');
//dimension
Route::get('/api/dimensionmobiliario/{id}/{buscar?}', 'MobiliarioController@indexdimension');
Route::post('/api/guardardimensionmobiliario', 'MobiliarioController@storedimension');
Route::post('/api/editardimensionmobiliario', 'MobiliarioController@updatedimension');
Route::delete('/api/eliminardimensionmobiliario/{id}', 'MobiliarioController@deletedimension');
//mantenimiento
Route::get('/api/mantenimientomobiliario/{id}/{buscar?}', 'MobiliarioController@indexmantenimiento');
Route::post('/api/guardarmantenimientomobiliario', 'MobiliarioController@storemantenimiento');
Route::post('/api/editarmantenimientomobiliario', 'MobiliarioController@updatemantenimiento');
Route::delete('/api/eliminarmantenimientomobiliario/{id}', 'MobiliarioController@deletemantenimiento');
//marca
Route::get('/api/marcamobiliario/{id}/{buscar?}', 'MobiliarioController@indexmarca');
Route::post('/api/guardarmarcamobiliario', 'MobiliarioController@storemarca');
Route::post('/api/editarmarcamobiliario', 'MobiliarioController@updatemarca');
Route::delete('/api/eliminarmarcamobiliario/{id}', 'MobiliarioController@deletemarca');
//material
Route::get('/api/materialmobiliario/{id}/{buscar?}', 'MobiliarioController@indexmaterial');
Route::post('/api/guardarmaterialmobiliario', 'MobiliarioController@storematerial');
Route::post('/api/editarmaterialmobiliario', 'MobiliarioController@updatematerial');
Route::delete('/api/eliminarmaterialmobiliario/{id}', 'MobiliarioController@deletematerial');
//modelo
Route::get('/api/modelomobiliario/{id}/{buscar?}', 'MobiliarioController@indexmodelo');
Route::post('/api/guardarmodelomobiliario', 'MobiliarioController@storemodelo');
Route::post('/api/editarmodelomobiliario', 'MobiliarioController@updatemodelo');
Route::delete('/api/eliminarmodelomobiliario/{id}', 'MobiliarioController@deletemodelo');
//ubicacion general
Route::get('/api/ubicaciongeneralmobiliario/{id}/{buscar?}', 'MobiliarioController@indexubicaciongeneral');
Route::post('/api/guardarubicaciongeneralmobiliario', 'MobiliarioController@storeubicaciongeneral');
Route::post('/api/editarubicaciongeneralmobiliario', 'MobiliarioController@updateubicaciongeneral');
Route::delete('/api/eliminarubicaciongeneralmobiliario/{id}', 'MobiliarioController@deleteubicaciongeneral');
//ubicacion especifica
Route::get('/api/ubicacionespecificamobiliario/{id}/{buscar?}', 'MobiliarioController@indexubicacionespecifica');
Route::get('/api/listarubicacionespecificamobiliario/{id}/{buscar?}/{ubicaciongeneral?}', 'MobiliarioController@listarubicacionespecifica');
Route::post('/api/guardarubicacionespecificamobiliario', 'MobiliarioController@storeubicacionespecifica');
Route::post('/api/editarubicacionespecificamobiliario', 'MobiliarioController@updateubicacionespecifica');
Route::delete('/api/eliminarubicacionespecificamobiliario/{id}', 'MobiliarioController@deleteubicacionespecifica');
//identificador
Route::get('/api/identificadormobiliario/{id}/{buscar?}', 'MobiliarioController@indexidentificador');
Route::post('/api/guardaridentificadormobiliario', 'MobiliarioController@storeidentificador');
Route::post('/api/editaridentificadormobiliario', 'MobiliarioController@updateidentificador');
Route::delete('/api/eliminaridentificadormobiliario/{id}', 'MobiliarioController@deleteidentificador');
//tipo
Route::get('/api/tipomobiliario/{id}/{buscar?}', 'MobiliarioController@indextipo');
Route::post('/api/guardartipomobiliario', 'MobiliarioController@storetipo');
Route::post('/api/editartipomobiliario', 'MobiliarioController@updatetipo');
Route::delete('/api/eliminartipomobiliario/{id}', 'MobiliarioController@deletetipo');

// Inicio Modulo Actas de Asignacion de Activos

Route::get('/api/listaractascargaarchivo/{id}', 'ActaCargaArchivoController@indexactascargaarchivo');
Route::get('/api/listaractasasignacionactivos/{id}', 'ActaAsignacionActivoController@indexactasasignacionactivos');

Route::post('/api/guardaractaasignacionactivo', 'ActaAsignacionActivoController@storeactaasignacionactivos');



// Inmuebles
Route::post('/api/guardarinmueble', 'ActaInmuebleController@guardarinmueble');
Route::post('/api/buscaractainmuebles', 'ActaInmuebleController@buscaractainmuebles');
Route::get('/api/buscaractainmueble/{id}', 'ActaInmuebleController@buscaractainmueble');
Route::post('/api/editaractainmueble', 'ActaInmuebleController@editaractainmueble');
Route::get('/api/buscaractainmuebleimagenes/{inmueble_id}', 'ActaInmuebleController@buscaractainmuebleimagenes');
Route::post('/api/agregaractainmuebleimagen', 'ActaInmuebleController@agregaractainmuebleimagen');
Route::delete('/api/eliminaractainmuebleimagen/{id}', 'ActaInmuebleController@eliminaractainmuebleimagen');
Route::get('/api/buscaractainmuebleimagen/{id}', 'ActaInmuebleController@buscaractainmuebleimagen');
Route::post('/api/editaractainmuebleimagen', 'ActaInmuebleController@editaractainmuebleimagen');
Route::get('/api/buscaractaprovincias', 'ActaProvinciaController@buscaractaprovincias');
Route::get('/api/buscaractacantonesxprovincia/{provincia_id}', 'ActaCantonController@buscaractacantonesxprovincia');
Route::get('/api/buscaractacantones', 'ActaCantonController@buscaractacantones');
Route::get('/api/buscaractaciudadesxprovincia/{provincia_id}', 'ActaCiudadController@buscaractaciudadesxprovincia');
Route::get('/api/buscaractaparroquiaxcanton/{canton_id}', 'ActaParroquiaController@buscaractaparroquiaxcanton');
Route::get('/api/reporte_inmueble_pdf/{acta_inmueble_id}', 'ActaInmuebleController@reporte_inmueble_pdf');
Route::get('/api/buscaractaimagenestipos', 'ActaInmuebleController@buscaractaimagenestipos');


Route::get('/api/listaractasasignacionactivos/{id}', 'ActaAsignacionActivoController@indexactasasignacionactivos');
Route::get('/api/actaresponsables/{id}/{buscar?}', 'ActaAsignacionActivoController@indexuser');
Route::get('/api/buscaractaasignacionactivo/{id}', 'ActaAsignacionActivoController@buscaractaasignacionactivo');

Route::get('/api/actaagencia/{id}/{buscar?}', 'ActaAgenciaController@indexactaagencia');
Route::get('/api/actaactivotipo/{id}/{buscar?}', 'ActaActivoTipoController@indexactaactivotipo');
Route::get('/api/actaestado/{origen}/{buscar?}', 'ActaEstadoController@indexactaestado');
Route::get('/api/actaactivounidad', 'ActaActivoUnidadController@indexactaactivounidad');
Route::get('/api/actaactivonombrenivel2/{acta_tipo_id}/{buscar?}', 'ActaActivoNombreNivel2Controller@indexactaactivonombrenivel2');
Route::get('/api/actaactivonombrenivel3/{acta_activo_nombre_nivel_2_id}/{buscar?}', 'ActaActivoNombreNivel3Controller@indexactaactivonombrenivel3');
Route::get('/api/actaactivonombrenivel4/{acta_activo_nombre_nivel_3_id}/{buscar?}', 'ActaActivoNombreNivel4Controller@indexactaactivonombrenivel4');

// Fin Modulo Actas de Asignacion de Activos

//mobiliario vehiculo
Route::get('/api/vehiculomobiliario/{id}/{buscar?}', 'MobiliarioController@indexvehiculo');
Route::get('/api/buscarvehiculomobiliario/{id}', 'MobiliarioController@buscarvehiculo');
Route::post('/api/guardarvehiculomobiliario', 'MobiliarioController@storevehiculo');
Route::post('/api/editarvehiculomobiliario', 'MobiliarioController@updatevehiculo');
Route::delete('/api/eliminarvehiculomobiliario/{id}', 'MobiliarioController@deletevehiculo');

//mobiliario maquina
Route::get('/api/maquinamobiliario/{id}/{buscar?}', 'MobiliarioController@indexmaquina');
Route::get('/api/buscarmaquinamobiliario/{id}', 'MobiliarioController@buscarmaquina');
Route::post('/api/guardarmaquinamobiliario', 'MobiliarioController@storemaquina');
Route::post('/api/editarmaquinamobiliario', 'MobiliarioController@updatemaquina');
Route::delete('/api/eliminarmaquinamobiliario/{id}', 'MobiliarioController@deletemaquina');

//mobiliario enseres
Route::get('/api/enseresmobiliario/{id}/{buscar?}', 'MobiliarioController@indexenseres');
Route::get('/api/buscarenseresmobiliario/{id}', 'MobiliarioController@buscarenseres');
Route::post('/api/guardarenseresmobiliario', 'MobiliarioController@storeenseres');
Route::post('/api/editarenseresmobiliario', 'MobiliarioController@updateenseres');
Route::delete('/api/eliminarenseresmobiliario/{id}', 'MobiliarioController@deleteenseres');

//mobiliario libro
Route::get('/api/libromobiliario/{id}/{buscar?}', 'MobiliarioController@indexlibro');
Route::get('/api/buscarlibromobiliario/{id}', 'MobiliarioController@buscarlibro');
Route::post('/api/guardarlibromobiliario', 'MobiliarioController@storelibro');
Route::post('/api/editarlibromobiliario', 'MobiliarioController@updatelibro');
Route::delete('/api/eliminarlibromobiliario/{id}', 'MobiliarioController@deletelibro');

Route::get('/auth_ruc_sri/{ruc}', 'AuthSRIController@llamar_datos_sri');
//Todas las rutas relativas de vuejs
Route::get('/{any}', 'ApplicationController')->where('any', '.*');
