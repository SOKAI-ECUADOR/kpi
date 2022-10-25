<template>
  <div class="vx-row">
    <div class="vx-col w-full ">
      <vx-card >
        <h4>Agregar Empresa</h4>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-select placeholder="Seleccione" class="selectExample w-full" label="Periodo" vs-multiple autocomplete v-model="periodo">
              <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in periodos"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input class="w-full" label="Ruc" v-model="ruc" onkeypress="return enteros(event,this);" @blur="validarruc" maxlength="13"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorruc" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" label="Nombre Comercial" v-model="nombre_comercial" />
            <div v-show="error" v-if="!nombre_comercial">
              <span class="text-danger" v-for="(err,index) in errornombre_comercial" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-2/5 w-full mb-6">
            <vs-input class="w-full" label="Razón social" v-model="razon_social" />
            <div v-show="error" v-if="!razon_social">
              <span class="text-danger" v-for="(err,index) in errorrazon_social" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/5 w-full mb-6">
            <label for="" class="vs-input--label">Teléfonos</label>
            <vs-chips color="rgb(145, 32, 159)" placeholder="Agregue los telefonos" v-model="telefono" icon-pack="feather" remove-icon="icon-trash-2">
                <vs-chip :key="data" @click="remove_chip_correo(data)" v-for="data in telefono" closable icon-pack="feather" close-icon="icon-trash-2">
                    {{ data }}
                </vs-chip>
            </vs-chips>
            <!--<vs-input type="text" v-validate="'numeric'" name="telef" onkeypress="return enteros(event,this);"  class="w-full" label="Telefono" v-model="telefono" maxlength="13"/>-->
            <span class="text-danger text-sm" v-show="errors.has('telef')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-2/5 w-full mb-6">
            <vs-input class="w-full" label="Direccion" v-model="direccion" />
            <div v-show="error" v-if="!direccion">
              <span class="text-danger" v-for="(err,index) in errordireccion" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" >
            <vs-select placeholder="Seleccione Provincia" class="selectExample w-full" label="Provincia" vs-multiple autocomplete v-model="provincia" @change="listarciudades()">
              <vs-select-item v-for="data in provincias" :key="data.id" :value="data.id_provincia" :text="data.nombre"/>
            </vs-select>
            <div v-show="error" v-if="!provincia">
              <span class="text-danger" v-for="(err,index) in errorprovincia" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" >
            <vs-select placeholder="Seleccione Ciudad" class="selectExample w-full" label="Ciudad" vs-multiple autocomplete v-model="ciudad">
              <vs-select-item v-for="data in ciudades" :key="data.id_ciudad" :value="data.id_ciudad" :text="data.nombre"/>
            </vs-select>
            <div v-show="error" v-if="!ciudad">
              <span class="text-danger" v-for="(err,index) in errorciudad" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-validate="'numeric'" name="rucConta"  label="Ruc Contador" v-model="ruc_contador" onkeypress="return enteros(event,this);"  @blur="validarruccontador" maxlength="13"/>
           <div v-show="error" >
              <span class="text-danger" v-for="(err,index) in errorruc_contador" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="text" class="w-full" label="Nombre Contador" v-model="nombre_contador" />
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="text" class="w-full" label="Nombre Representante" v-model="nomb_representante" />
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':tipo_identidicacion}">
            <vs-select placeholder="Tipo" class="selectExample w-full" label="Tipo Identificacion RL" vs-multiple v-model="tipo_identidicacion">
              <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in array_tipo_identificacion"/>
            </vs-select>
            <div v-show="error" v-if="!tipo_identidicacion">
              <span class="text-danger" v-for="(err,index) in error_tipo_identidicacion" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':tipo_identidicacion}" v-if="tipo_identidicacion">
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_representante" v-if="tipo_identidicacion=='Cedula'"  onkeypress="return enteros(event,this);"  @blur="validarrepresentante"  maxlength="10"/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_representante" v-else-if="tipo_identidicacion=='Ruc'"  onkeypress="return enteros(event,this);"  @blur="validarrucrepresentante"  maxlength="13"/>
            <vs-input class="w-full"   label="Identificacion Representante" v-model="identificacion_representante" v-else  maxlength="15"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in erroridentificacion_representante" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':tipo_identidicacion}">
            <label class="vs-input--label">Periodo Inicio</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" placeholder="Fecha de inicio" v-model="periodo_inicio"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':tipo_identidicacion}">
            <label class="vs-input--label">Periodo Fin</label>
            <flat-pickr :config="configFromdateTimePicker" max="2019" min class="w-full" placeholder="Fecha finalización" v-model="periodo_fin"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select placeholder="Seleccione su Moneda" class="selectExample w-full" label="Moneda" vs-multiple autocomplete v-model="moneda">
              <vs-select-item v-for="data in monedas" :key="data.id_moneda" :value="data.id_moneda" :text="data.nomb_moneda"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Fecha de duración</label>
            <flat-pickr class="w-full" placeholder="Fecha de expiración" v-model="fecha_duracion"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Obligado llevar contabilidad</label>
            <vs-checkbox icon-pack="feather" icon="icon-check" class="mt-2" v-model="contabilidad">
              <template v-if="contabilidad">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Obligado
            </vs-checkbox>
        </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <label class="vs-input--label">Fecha de último cierre</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" placeholder="fecha de cierre" v-model="fecha_cierre"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <vs-select  placeholder="Balance consolidado" class="selectExample w-full" label="Balance Consolidado" vs-multiple autocomplete v-model="balance">
              <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in balances"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}" v-if="balance=='Si'">
            <vs-select placeholder="Seleccione Empresa" class="selectExample w-full" label="Empresas Asociadas" vs-multiple autocomplete v-model="empresa_asociada">
              <vs-select-item v-for="data in empresas" :key="data.id_empresa" :value="data.id_empresa" :text="data.nombre_empresa"/>
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" :class="{'sm:w-1/4':balance=='Si'}">
            <label class="vs-input--label">Ultimo Recalculo</label>
            <flat-pickr class="w-full" :config="configdateTimePicker" v-model="recalculo"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" v-validate="'numeric'" name="codEntidad" label="Codigo Entidad" v-model="codigo_entidad" />
            <span class="text-danger text-sm" v-show="errors.has('codEntidad')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select placeholder="Contribuyente especial"  class="selectExample w-full" label="Contribuyente Especial" vs-multiple autocomplete v-model="contribuyente">
              <vs-select-item value=1 text="Si" />
              <vs-select-item value=0 text="No" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input type="text" class="w-full" label="No. Establecimiento" v-model="establecimiento"/>
          </div>
          <div class="vx-col sm:w-full w-full mb-6">
                <label class="vs-input--label">Categorias Producto</label>
                <v-select
                    id="sections"
                    multiple
                    :options="optionscategoria"
                    label="value"
                    v-model="categoria_producto"
                    placeholder="Seleccione los productos"
                ></v-select>
                <div v-show="error">
                  <span class="text-danger" v-for="(err,index) in errorcategoria_producto" :key="index" v-text="err"></span>
                </div>
          </div>
        </div>
        <div class="vx-row chtm" v-if="contribuyente==1">
          <vs-divider border-style="solid" color="dark">Contribuyente especial</vs-divider>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <label class="vs-input--label">Fecha Resolucion</label>
            <flat-pickr :config="configdateTimePicker" class="w-full" v-model="fecha_resolucion"/>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input type="text" v-validate="'numeric'" name="noResolucion" class="w-full" label="No. Resolucion" v-model="numero_resolucion" />
            <span class="text-danger text-sm" v-show="errors.has('noResolucion')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input type="text" class="w-full" label="No. Contribuyente" v-model="numero_contribuyente"/>
          </div>
        </div>

        <vs-divider border-style="solid" color="dark">Datos Opcionales</vs-divider>
        <div class="vx-row">
        <div class="vx-col sm:w-1/2 w-full mb-6">
          <vs-input type="text" class="w-full" label="Primer campo de factura" v-model="primerc"/>
        </div>
        <div class="vx-col sm:w-1/2 w-full mb-6">
          <vs-input type="text" class="w-full" label="Segundo campo de factura" v-model="segundoc"/>
        </div>
        </div>

        <vs-divider border-style="solid" color="dark">Cuentas de la empresa</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Activo</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaActivo1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='1'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Pasivo</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaPasivo1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='2'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Patrimonio</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaPatrimonio1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='3'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Ingreso</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaIngreso1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='4'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Costo</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaCosto1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='5'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Gasto</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaGasto1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='6'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Orden</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaOrden1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button color="primary"  @click="abrircuentas = true, buscar='', valor_cuenta='7'">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Cuenta Resultado</label>
            <vx-input-group class="">
              <vs-input class="w-full" v-model="ctaResultado1" disabled="false"/>
              <template slot="append">
                <div class="append-text btn-addon">
                  <vs-button @click="abrircuentas = true, buscar='', valor_cuenta='8'" color="primary">Buscar</vs-button>
                </div>
              </template>
            </vx-input-group>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Datos adicionales</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <vs-input class="w-full" name="text"  label="Página web" v-model="pagina_web" />
          </div>
          <div class="vx-col sm:w-1/6 w-full mb-6">
            <label class="vs-input--label">Facturación en negativo</label>
            <vs-checkbox class="mt-3" icon-pack="feather" icon="icon-check" v-model="negativo">
              <template v-if="negativo">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | Negativo
            </vs-checkbox>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select placeholder="Selecciona la Leyenda"  class="selectExample w-full" label="Leyenda" vs-multiple autocomplete v-model="leyenda">
              <vs-select-item value="" text="SIN LEYENDA" />
              
              <vs-select-item value="AGENTE DE RETENCIÓN RESOLUCION NAC No DNCRASC20-00000001" text="AGENTE DE RETENCIÓN RESOLUCION NAC No DNCRASC20-00000001" />
              <vs-select-item value="CONTRIBUYENTE REGIMEN MICROEMPRESA" text="CONTRIBUYENTE REGIMEN MICROEMPRESA" />
              <vs-select-item value="2" text="AGENTE DE RETENCIÓN RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN MICROEMPRESA" />
              <vs-select-item value="CONTRIBUYENTE REGIMEN RIMPE" text="CONTRIBUYENTE REGIMEN RIMPE" />
              <vs-select-item value="CONTRIBUYENTE NEGOCIO POPULAR- REGIMEN RIMPE" text="CONTRIBUYENTE NEGOCIO POPULAR- REGIMEN RIMPE" />
              <vs-select-item value="3" text="AGENTE DE RETENCIÓN RESOLUCION NAC No DNCRASC20-00000001 Y REGIMEN RIMPE" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text"  label="Email Facturación" v-model="email_facturacion" />
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Punto de emisión</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text"  label="Secuencial de Factura" v-model="secuencial_factura" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_factura" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" label="Secuencial de Nota de crédito" v-model="secuencial_nota_credito" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_nota_credito" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" label="Secuencial de Nota de débito" v-model="secuencial_nota_debito" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_nota_debito" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text"  label="Secuencial de Guia de remisión" v-model="secuencial_guia_remision" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_guia_remision" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" label="Secuencial de Retención" v-model="secuencial_retencion" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_retencion" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" name="text" label="Secuencial liquidación de compra" v-model="secuencial_liquidacion_compra" />
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorsecuencial_liquidacion_compra" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6 imagencudro">
            <label class="vs-input--label">Estado de punto de emisión:</label>
            <vs-checkbox icon-pack="feather" class="mt-3" icon="icon-check" v-model="estado_punto_emision">
              <template v-if="estado_punto_emision">
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
              </template>
              <template v-else>
                <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
              </template>
              | <span v-if="estado_punto_emision">Activo</span> <span v-else>Inactivo</span>
            </vs-checkbox>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorestado_punto_emision" :key="index" v-text="err"></span>
            </div>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Configuración de facturación electrónica</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" v-validate="'email'" name="email"  label="Email" v-model="email_empresa" />
            <span class="text-danger text-sm" v-show="errors.has('email')">Email no válido</span>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input type="password" class="w-full" label="Contraseña" v-model="password" maxlength="100"/>
            <span class="corto">dejar vacio si no se requiere cambiar</span>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input class="w-full" label="Servidor Correo" v-model="servidor_correo" />
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <vs-input type="text" class="w-full" v-validate="'numeric'" name="puerto" label="Puerto" onkeypress="return enteros(event,this);"  v-model="puerto_correo" maxlength="5"/>
          <span class="text-danger text-sm" v-show="errors.has('puerto')">Este campo solo lleva numeros</span>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6">
            <label class="vs-input--label">Capa de puerto de correo</label>
            <ul class="demo-alignment w-full">
              <li>
                <vs-radio v-model="seguridad_correo" vs-value="ssl">Ssl</vs-radio>
              </li>
              <li>
                <vs-radio v-model="seguridad_correo" vs-value="tls">Tls</vs-radio>
              </li>
              <li>
                <vs-radio v-model="seguridad_correo" vs-value="ninguno">Ninguno</vs-radio>
              </li>
            </ul>
          </div>
          <div class="vx-col sm:w-1/4 w-full mb-6" style="float: right;display: grid;">
            <vs-button color="primary" type="filled" @click="verificarcorreo()">Verificar Correo</vs-button>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select placeholder="Tipo de emisión" class="selectExample w-full" label="Tipo de emisión" vs-multiple autocomplete v-model="tipoemision">
              <vs-select-item value="1" text="Emisor Normal" />
              <vs-select-item value="2" text="Indisponibilidad del SRI" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-select placeholder="Ambiente" class="selectExample w-full" label="Ambiente" vs-multiple autocomplete v-model="ambiente">
              <vs-select-item value="1" text="Pruebas" />
              <vs-select-item value="2" text="Produción" />
            </vs-select>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6" style="text-align: center;">
          <label class="vs-input--label">Opción XML Factura Compra:</label>
          <div class="mt-1" style="text-align: -webkit-center;">
          <vs-switch color="success" v-model="xml_factura_compra" style="text-align: -webkit-center;">
              <span slot="on"><feather-icon icon="CheckIcon" svgClasses="w-3 h-5 hover:text-primary stroke-current cursor-pointer"/></span>
              <span slot="off"><feather-icon icon="XIcon" svgClasses="w-3 h-5 hover:text-primary stroke-current cursor-pointer"/></span>
          </vs-switch>
          </div>
          </div>
        </div>
        <vs-divider border-style="solid" color="dark">Configuracion de Usuario Administrador</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Nombres" v-model="nombreusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errornombreusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Apellidos" v-model="apellidousuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorapellidousuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/3 w-full mb-6">
            <vs-input class="w-full" label="Correo electrónico" v-model="emailusuario"/>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in erroremailusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" type="password" label="Contraseña" v-model="passusuario"/>
            <span class="corto">dejar vacio si no se requiere cambiar</span>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorpassusuario" :key="index" v-text="err"></span>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6">
            <vs-input class="w-full" type="password" label="Confirmar contraseña" v-model="repassusuario"/>
            <span class="corto">dejar vacio si no se requiere cambiar</span>
            <div v-show="error">
              <span class="text-danger" v-for="(err,index) in errorrepassusuario" :key="index" v-text="err"></span>
            </div>
          </div>
        </div>
        <vs-collapse>
          <vs-collapse-item icon-pack="feather" icon-arrow="icon-chevrons-down">
            <div slot="header">
              Clic aqui para otorgar los Permisos a la empresa
            </div>
            <div class="vx-row">
              <vs-divider border-style="solid" color="dark">Permisos de módulos de la empresa</vs-divider>
              <div class="vx-col sm:w-full w-full mb-6">
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Administrar
                      <vs-checkbox class="selectall" v-model="b_administrar" @click="administrar()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==1">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Contabilidad
                      <vs-checkbox class="selectall" v-model="b_contabilidad" @click="contabilidads()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==2">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Facturación
                      <vs-checkbox class="selectall" v-model="b_facturacion" @click="facturacion()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==3">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Compras
                      <vs-checkbox class="selectall" v-model="b_compras" @click="compras()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==4">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Inventario
                      <vs-checkbox class="selectall" v-model="b_inventario" @click="inventario()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==5">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Nómina
                      <vs-checkbox class="selectall" v-model="b_nomina" @click="nomina()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==6">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Importación
                      <vs-checkbox class="selectall" v-model="b_importacion" @click="importacion()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==7">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Producción
                      <vs-checkbox class="selectall" v-model="b_produccion" @click="produccion()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==8">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Activos fijos
                      <vs-checkbox class="selectall" v-model="b_activos" @click="activos()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==9">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
                  <div slot="header" style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;display: inline-flex;">
                      Calendario
                      <vs-checkbox class="selectall" v-model="b_calendario" @click="calendario()"/>
                  </div>
                  <table class="w-full" style="margin: 0 20px;">
                    <tr>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width:40%">Modulo</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Ver</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Editar</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Crear</th>
                      <th class="font-semibold text-base text-left px-3 py-2" style="width: 15%;">Eliminar</th>
                    </tr>
                    <tr v-for="(val, index) in items" :key="index" v-if="val.lugar==10">
                      <td class="px-3 py-2">{{ val.nombre }}</td>
                      <td class="px-3 py-2"><vs-checkbox v-model="val.ver"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.editar"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.crear"/></td>
                      <td class="px-3 py-2"><vs-checkbox v-if="val.value!=1" v-model="val.eliminar"/></td>
                    </tr>
                  </table>
              </div>
            </div>
          </vs-collapse-item>
        </vs-collapse>
        <vs-divider border-style="solid" color="dark">Imagen y Firma P12</vs-divider>
        <div class="vx-row">
          <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto">
            <label class="vs-input--label">Subir logo</label>
            <div class="vx-col md:w-full w-full mb-6">
              <div style="display:none">
                <input type="file" class="filepre seleccionarimagen" accept="image/*" @change="seleccionarimagen"/>
              </div>
              <div class="verimagen" v-if="!imagen">
                <img src="/images/upload.png" @click="agregarimagen" class="imagenpre" style="padding: 20px;"/>
              </div>
              <div class="verimagen" v-else style="position: relative;">
                <template v-if="recuperaimagen">
                  <img :src="'/'+$route.params.id+'/imagen/'+imagen" @click="agregarimagen" class="imagenpre" />
                  <div class="alerta estiloborrar">
                    <div class="text-center">
                      <span class="spanborrar" @click="eliminarimagen">X</span>
                    </div>
                  </div>
                </template>
                <template v-else>
                  <img :src="imagenprevisualizar" @click="agregarimagen" class="imagenpre" />
                  <div class="alerta estiloborrar">
                    <div class="text-center">
                      <span class="spanborrar" @click="eliminarimagen">X</span>
                    </div>
                  </div>
                </template>
              </div>
            </div>
          </div>
          <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto">
            <label class="vs-input--label">Subir archivo p12</label>
            <div class="form-group">
                <label class="btn btn-primary" v-if="recuperafirma">
                    <input type="file" accept=".p12" @change="seleccionarp12" class="seleccionarp12" name="myfile" style="display:none">
                    <vs-button v-if="!p12" color="primary" type="filled" @click="agregarp12">No ha ingresado una firma electrónica !Agregar una!</vs-button>
                    <vs-button v-else color="primary" type="filled" @click="eliminarp12">Firma agregado exitosamente con nombre de: {{p12}} <br/> <b>!Click para eliminar y agregar otro¡</b></vs-button>
                </label>
                <label class="btn btn-primary" v-else>
                    <input type="file" accept=".p12" @change="seleccionarp12" class="seleccionarp12" name="myfile" style="display:none">
                    <vs-button v-if="!p12" color="primary" type="filled" @click="agregarp12">No ha ingresado una firma electrónica !Agregar una!</vs-button>
                    <vs-button v-else color="primary" type="filled" @click="eliminarp12">Firma agregado exitosamente con nombre de: {{p12.name}} <br/> <b>!Click para eliminar y agregar otro¡</b></vs-button>
                </label>
                <div class="vx-col sm:w-full w-full mb-6" v-if="p12">
                  <label class="vs-input--label">Fecha de expiración</label>
                  <flat-pickr class="w-full" placeholder="Fecha de expiración" v-model="fecha_expiracion_firma"/>
                  <vs-input type="password" class="w-full" label="Contraseña Firma"  v-model="pass_firma" maxlength="100"/>
                </div>
            </div>
          </div>
        </div>
        <div class="vx-row">
          <div class="vx-col w-full">
            <div class="vx-col w-full">
            <vs-button color="success" type="filled" @click="guardar()" >GUARDAR</vs-button>
            <vs-button color="danger" type="filled" @click="cancelar()">CANCELAR</vs-button>
            </div>
          </div>
        </div>
      </vx-card>
    </div>
    <vs-popup title='Plan Cuentas' :active.sync="abrircuentas">
      <div class="con-exemple-prompt">
        <vs-input class="mb-4 mr-4 w-full" v-model="buscar" @keyup="listarplancuentas(1,buscar)" v-bind:placeholder="i18nbuscar"/>
        <vs-table stripe v-model="escogervalorcuenta" @selected="escogercuenta" :data="arraycuentas">
          <template slot="thead">
            <vs-th>No.Cuenta</vs-th>
            <vs-th>Tipo Cuenta</vs-th>
          </template>
          <template slot-scope="{data}">
            <vs-tr :data="tr" :key="index" v-for="(tr, index) in data">
              <vs-td>
                {{ tr.codcta  }}
              </vs-td>
              <vs-td>
                {{ tr.nomcta  }}
              </vs-td>
              </vs-tr>
            </template>
        </vs-table>
      </div>
    </vs-popup>
  </div>
</template>
<script>
import Datepicker from 'vuejs-datepicker';
import flatPickr from 'vue-flatpickr-component'
import 'flatpickr/dist/flatpickr.min.css';
import {Spanish as SpanishLocale} from 'flatpickr/dist/l10n/es.js';
import { AgGridVue } from "ag-grid-vue";

const $ = require('jquery');
const axios = require("axios");
export default {
  components: {
    AgGridVue,
    Datepicker,
    flatPickr
  },
  computed: {
    usuario() {
      return this.$store.state.AppActiveUser;
    },
    token() {
      return this.$store.state.Token;
    }
  },
  data() {
    return {
      listar: [],
      //configuraciones
      configdateTimePicker: {
        locale: SpanishLocale
      },
      configdateTimePickerPeriodo: {
        locale: SpanishLocale,
        dateFormat: "Y",
      },
      configFromdateTimePicker: {
        minDate: new Date(),
        maxDate: null,
        locale: SpanishLocale
      },
      //datos generales
      periodo:null,
      ruc:"",
      nombre_comercial:"",
      razon_social:"",
      telefono:[],
      direccion:"",
      provincia:"",
      ciudad:"",
      ruc_contador:"",
      nombre_contador:"",
      nomb_representante:"",
      tipo_identidicacion:"",
      identificacion_representante:"",
      moneda:"",
      periodo_inicio:"",
      periodo_fin:"",
      fecha_duracion:"",
      contabilidad:false,
      fecha_cierre:"",
      balance:"",
      recalculo:"",
      empresa_asociada:"",
      codigo_entidad:"",
      establecimiento:null,
      contribuyente:false,
      fecha_resolucion:"",
      numero_resolucion:"",
      numero_contribuyente:"",
      //variables  categoria producto
      optionscategoria: [
                { text: "General", value: "General" },
                { text: "Seguridad Industrial", value: "Seguridad Industrial" },
                { text: "Vehículos", value: "Vehículos" },
                { text: "Electrodomésticos", value: "Electrodomésticos" },
                { text: "Licores", value: "Licores" },
                { text: "Medicamentos", value: "Medicamentos" },
                { text: "Cortinas", value: "Cortinas" }
                // { text: "Tecnología", value: "Tecnología" },
                // { text: "Alimentos", value: "Alimentos" },
                // { text: "Insumos Médicos", value: "Insumos Médicos" }
      ],
      categoria_producto:[],
      //datos opcionales
      primerc:'',
      segundoc:'',
      //datos adicinales
      pagina_web:"",
      negativo:true,
      //puntos de emisión
      secuencial_factura:1,
      secuencial_nota_credito:1,
      secuencial_nota_debito:1,
      secuencial_guia_remision:1,
      secuencial_retencion:1,
      secuencial_liquidacion_compra:1,
      estado_punto_emision:true,
      //Configuración de facturación electrónica
      email_empresa:"",
      password:"",
      servidor_correo:"",
      puerto_correo:"",
      seguridad_correo:"ssl",
      tipoemision:null,
      ambiente:null,
      xml_factura_compra:false,
      //Configuracion de Usuario Administrador
      nombreusuario:"",
      apellidousuario:"",
      emailusuario:"",
      passusuario:"",
      repassusuario:"",
      //Errores listar
      error:0,
      errorruc: [],
      errornombre_comercial: [],
      errorrazon_social: [],
      errorprovincia: [],
      errorciudad: [],
      errorruc_contador: [],
      errortipo_identidicacion: [],
      errordireccion: [],
      error_tipo_identidicacion: [],
      erroridentificacion_representante: [],
      errorsecuencial_factura:[],
      errorsecuencial_nota_credito:[],
      errorsecuencial_nota_debito:[],
      errorsecuencial_guia_remision:[],
      errorsecuencial_retencion:[],
      errorsecuencial_liquidacion_compra:[],
      errorestado_punto_emision:[],
      errornombreusuario: [],
      errorapellidousuario: [],
      erroremailusuario: [],
      errorpassusuario: [],
      errorrepassusuario: [],
      //error  categoria
      errorcategoria_producto:[],
      //recupera rovincias
      provincias: [],
      //recupera ciudades
      ciudades: [],
      //recupera monedas
      monedas: [],
      //recupera empresas
      empresas: [],
      //periodos
      periodos: [{ text: "2022", value: "2022" },{ text: "2021", value: "2021" },{ text: "2020", value: "2020" },{ text: "2019", value: "2019" }, { text: "2018", value: "2018" },{ text: "2017", value: "2017" }, { text: "2016", value: "2018" },{ text: "2015", value: "2015" }, { text: "2014", value: "2014" },{ text: "2013", value: "2013" }, { text: "2012", value: "2012" },{ text: "2011", value: "2011" }, { text: "2010", value: "2010" },{ text: "2009", value: "2009" }, { text: "2008", value: "2008" },{ text: "2007", value: "2007" }, { text: "2006", value: "2006" },{ text: "2005", value: "2005" }, { text: "2004", value: "2004" },{ text: "2003", value: "2003" }, { text: "2002", value: "2002" },{ text: "2001", value: "2001" }, { text: "2000", value: "2000" },{ text: "1999", value: "1999" }, { text: "1998", value: "1998" }],
      //lista tipos de identificaciones
      array_tipo_identificacion: [{ text: "Cedula", value: "Cedula" },{ text: "Ruc", value: "Ruc" },{ text: "Pasaporte", value: "Pasaporte" }],
      //llamada de todos los balances
      balances:[ {value:'Si', text:'Si'}, {value:"No",text:"No"}],
      fecha_expiracion_firma:"",
      pass_firma:"",
      //valor que no se envian u obtienen files
      imagen:"",
      imagenprevisualizar:[],
      p12:"",
      recuperaimagen:0,
      recuperafirma:0,
      id_empresa:null,
      id_user:null,
      id_punto_emision:null,
      id_establecimiento:null,
      //plan de cuentas
      ctaActivo: "",
      ctaPasivo: "",
      ctaPatrimonio: "",
      ctaIngreso: "",
      ctaCosto: "",
      ctaGasto: "",
      ctaOrden: "",
      ctaResultado: "",

      ctaActivo1: "",
      ctaPasivo1: "",
      ctaPatrimonio1: "",
      ctaIngreso1: "",
      ctaCosto1: "",
      ctaGasto1: "",
      ctaOrden1: "",
      ctaResultado1: "",

      abrircuentas: false,
      arraycuentas:[],
      escogervalorcuenta:[],
      valor_cuenta:"",
      //busqueda cuentas
      pagina: 1,
      cantidadp:50,
      offset: 3,
      buscar: "",
      criterio: "codcta",
      gridApi: null,
      contenido: [],
      i18nbuscar:this.$t("i18nbuscar"),
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count:0,
      },

      //usuario roles
      items: [],

      selected: [],
      selectAll: false,
      selected1: [],
      selectAll1: false,
      selected2: [],
      selectAll2: false,
      selected3: [],
      selectAll3: false,
      selected4: [],
      selectAll4: false,
      selected5: [],
      selectAll5: false,
      selected6: [],
      selectAll6: false,
      empresa:"",

      b_administrar: 0,
      b_contabilidad: 0,
      b_facturacion: 0,
      b_compras: 0,
      b_inventario: 0,
      b_nomina: 0,
      b_importacion: 0,
      b_produccion: 0,
      b_activos: 0,
      b_calendario: 0,
      leyenda:"",
      email_facturacion:"",
    };
  },
  mounted() {
    this.llamadas();
    this.listarempresas();
    this.listarempresa();
    this.listarplancuentas(1,this.buscar);
    this.listarroles();
  },
  methods: {
    listarempresa(){
      axios.get("/api/listarempresa/" + this.$route.params.id).then( res => {
        console.log(res);
        this.id_empresa = res.data.id_empresa;
        this.id_user = res.data.id;
        this.id_punto_emision = res.data.id_punto_emision;
        this.id_establecimiento = res.data.id_establecimiento;
        this.periodo = res.data.periodo_empresa;
        this.ruc = res.data.ruc_empresa;
        this.nombre_comercial = res.data.nombre_empresa;
        this.razon_social = res.data.razon_social;
        this.telefono = res.data.telefono_empresa;
        this.direccion = res.data.direccion_empresa;
        this.provincia = res.data.id_provincia;
        this.ciudad = res.data.id_ciudad;
        this.ruc_contador = res.data.ruc_contador;
        this.nombre_contador = res.data.nombre_contador;
        this.nomb_representante = res.data.nomb_representante;
        this.tipo_identidicacion = res.data.tipo_identidicacion_empresa;
        this.identificacion_representante = res.data.identificaion_rep;
        this.moneda = res.data.id_moneda;
        this.periodo_inicio = res.data.periodo_inicio;
        this.periodo_fin = res.data.periodo_fin;
        this.fecha_duracion = res.data.clave_duracion;
        if(res.data.obligado_contabilidad==1){
          this.contabilidad = true;
        }else{
          this.contabilidad = false;
        }

        this.fecha_cierre = res.data.fcierre;
        this.balance = res.data.balance;
        this.recalculo = res.data.recalculo;
        this.empresa_asociada = res.data.empresa_asociada;
        this.codigo_entidad = res.data.codigo_entidad;
        this.establecimiento = res.data.codigo_establecimiento;
        this.contribuyente = res.data.contribuyente;
        this.leyenda = res.data.leyenda;
        this.email_facturacion = res.data.email_facturacion;
        if(res.data.categoria_producto.length>0){
          for(var i=0;i<res.data.categoria_producto.length;i++){
            if(res.data.categoria_producto[i].length>0){
              this.categoria_producto.push({
                text:res.data.categoria_producto[i],
                value:res.data.categoria_producto[i]
              });
            }
          }  
        }else{
          this.categoria_producto=[];
        }
        console.log("CATEGORIA PRODUCTO");
        //console.log(res.data.categoria_producto[0]);

        setTimeout(() => {
          this.fecha_resolucion = res.data.fresolucion;
          this.numero_resolucion = res.data.noresolucion;
          this.numero_contribuyente = res.data.nocontribuyente;
        }, 100);
        this.fecha_resolucion = res.data.fecha_resolucion;
        this.numero_resolucion = res.data.numero_resolucion;
        this.numero_contribuyente = res.data.numero_contribuyente;
        // = res.data.//; adicinales
        this.pagina_web = res.data.urlweb;
        this.negativo = res.data.negativo;
        // = res.data.//; de emisión
        this.secuencial_factura = res.data.secuencial_factura;
        this.secuencial_nota_credito = res.data.secuencial_nota_credito;
        this.secuencial_nota_debito = res.data.secuencial_nota_debito;
        this.secuencial_guia_remision = res.data.secuencial_guia_remision;
        this.secuencial_retencion = res.data.secuencial_retencion;
        this.secuencial_liquidacion_compra = res.data.secuencial_liquidacion_compra;
        this.estado_punto_emision = res.data.activo;
        // = res.data.//; de facturación electrónica
        this.email_empresa = res.data.email_empresa;
        this.password = "**********";
        this.servidor_correo = res.data.servidor_correo;
        this.puerto_correo = res.data.puerto_correo;
        this.seguridad_correo = res.data.seguridad_correo;
        this.tipoemision = res.data.tipo_emision;
        this.ambiente = res.data.ambiente;
        this.xml_factura_compra = res.data.xml_factura_compra;
        // = res.data.//; de Usuario Administrador
        this.nombreusuario = res.data.nombres;
        this.apellidousuario = res.data.apellidos;
        this.emailusuario = res.data.email;
        this.passusuario = "**********";
        this.repassusuario = "**********";
        this.fecha_expiracion_firma = res.data.fecha_expiracion_firma;
        this.pass_firma = res.data.pass_firma;
        this.imagen = res.data.logo;

        this.ctaActivo = res.data.id_plan_cuentas_activo;
        this.ctaPasivo = res.data.id_plan_cuentas_pasivo;
        this.ctaPatrimonio = res.data.id_plan_cuentas_patrimonio;
        this.ctaIngreso = res.data.id_plan_cuentas_ingreso;
        this.ctaCosto = res.data.id_plan_cuentas_costo;
        this.ctaGasto = res.data.id_plan_cuentas_gasto;
        this.ctaOrden = res.data.id_plan_cuentas_orden;
        this.ctaResultado = res.data.id_plan_cuentas_resultado;

        this.ctaActivo1 = res.data.plan_cuentas_activo;
        this.ctaPasivo1 = res.data.plan_cuentas_pasivo;
        this.ctaPatrimonio1 = res.data.plan_cuentas_patrimonio;
        this.ctaIngreso1 = res.data.plan_cuentas_ingreso;
        this.ctaCosto1 = res.data.plan_cuentas_costo;
        this.ctaGasto1 = res.data.plan_cuentas_gasto;
        this.ctaOrden1 = res.data.plan_cuentas_orden;
        this.ctaResultado1 = res.data.plan_cuentas_resultado;

        if(this.imagen){
          this.recuperaimagen = 1;
        }
        this.p12 = res.data.firma;
        if(this.p12){
          this.recuperafirma=1;
        }
      }).catch( err => {
        console.log(err);
      });
    },
    //Llama los datos adicionales necesarios para crear la empresa
    llamadas(){
      axios.get('/api/adicionalesempresa').then( res => {
        this.monedas = res.data.monedas;
        this.provincias = res.data.provincias;
      });
    },
    //lista las ciudades dependiendo de la provincia seleccioanda
    listarciudades(){
      setTimeout(() => {
        axios.get('/api/ciudades?id='+this.provincia).then( res => {
          this.ciudades = res.data;
        });
      }, 200);
    },
    //guarda la empresa
    guardar(){
      if (this.validar()) {
        return;
      }
      if(this.negativo){
        this.negativo = 1;
      }else{
        this.negativo = 0;
      }
      let formData = new FormData();
      formData.append("recuperaimagen", this.recuperaimagen);
      formData.append("recuperafirma", this.recuperafirma);

      formData.append("id_empresa", this.id_empresa);
      formData.append("id_user", this.id_user);
      formData.append("id_punto_emision", this.id_punto_emision);
      formData.append("id_establecimiento", this.id_establecimiento);
      formData.append("compra", this.primerc);
      formData.append("migo", this.segundoc);
      formData.append("periodo", this.periodo);
      formData.append("ruc", this.ruc);
      formData.append("nombre_comercial", this.nombre_comercial);
      formData.append("razon_social", this.razon_social);
      formData.append("telefono", this.telefono);
      formData.append("direccion", this.direccion);
      formData.append("provincia", this.provincia);
      formData.append("ciudad", this.ciudad);
      formData.append("ruc_contador", this.ruc_contador);
      formData.append("nombre_contador", this.nombre_contador);
      formData.append("nomb_representante", this.nomb_representante);
      formData.append("tipo_identidicacion", this.tipo_identidicacion);
      formData.append("identificacion_representante", this.identificacion_representante);
      formData.append("moneda", this.moneda);
      formData.append("periodo_inicio", this.periodo_inicio);
      formData.append("periodo_fin", this.periodo_fin);
      formData.append("fecha_duracion", this.fecha_duracion);
      if(this.contabilidad){
        formData.append("contabilidad", 1);
      }else{
        formData.append("contabilidad", 0);
      }
      formData.append("fecha_cierre", this.fecha_cierre);
      formData.append("balance", this.balance);
      formData.append("recalculo", this.recalculo);
      formData.append("empresa_asociada", this.empresa_asociada);
      formData.append("codigo_entidad", this.codigo_entidad);
      formData.append("establecimiento", this.establecimiento);
      formData.append("contribuyente", this.contribuyente);
      formData.append("fecha_resolucion", this.fecha_resolucion);
      formData.append("numero_resolucion", this.numero_resolucion);
      formData.append("numero_contribuyente", this.numero_contribuyente);
      formData.append("pagina_web", this.pagina_web);
      formData.append("negativo", this.negativo);
      if(this.categoria_producto.length>0){
        formData.append("categoria_producto", JSON.stringify(this.categoria_producto));
      }
      formData.append("secuencial_factura", this.secuencial_factura);
      formData.append("secuencial_nota_credito", this.secuencial_nota_credito);
      formData.append("secuencial_nota_debito", this.secuencial_nota_debito);
      formData.append("secuencial_guia_remision", this.secuencial_guia_remision);
      formData.append("secuencial_retencion", this.secuencial_retencion);
      formData.append("secuencial_liquidacion_compra", this.secuencial_liquidacion_compra);
      formData.append("estado_punto_emision", this.estado_punto_emision);
      formData.append("email_empresa", this.email_empresa);
      if(this.password=='**********'){
        formData.append("password", '');
      }else{
        formData.append("password", this.password);
      }
      formData.append("servidor_correo", this.servidor_correo);
      formData.append("puerto_correo", this.puerto_correo);
      formData.append("seguridad_correo", this.seguridad_correo);
      formData.append("tipoemision", this.tipoemision);
      formData.append("ambiente", this.ambiente);
      formData.append("xml_factura_compra", this.xml_factura_compra);
      formData.append("nombreusuario", this.nombreusuario);
      formData.append("apellidousuario", this.apellidousuario);
      formData.append("emailusuario", this.emailusuario);
      if(this.passusuario=='**********'){
        formData.append("passusuario", '');
      }else{
        formData.append("passusuario", this.passusuario);
      }

      formData.append("fecha_expiracion_firma", this.fecha_expiracion_firma);
      formData.append("pass_firma", this.pass_firma);
      formData.append("file_imagen", this.imagen);
      formData.append("file_p12", this.p12);

      formData.append("ctaActivo", this.ctaActivo);
      formData.append("ctaPasivo", this.ctaPasivo);
      formData.append("ctaPatrimonio", this.ctaPatrimonio);
      formData.append("ctaIngreso", this.ctaIngreso);
      formData.append("ctaCosto", this.ctaCosto);
      formData.append("ctaGasto", this.ctaGasto);
      formData.append("ctaOrden", this.ctaOrden);
      formData.append("ctaResultado", this.ctaResultado);
      formData.append("leyenda", this.leyenda);
      formData.append("email_facturacion", this.email_facturacion);
      axios.post("/api/actualizarempresa",formData).then( res => {
        axios.post("/api/agregarempresarolesid",{
          roles: this.items,
          id: this.$route.params.id,
        }).then( () => {
          this.$vs.notify({
            title: "Empresa actualizada",
            text: "La empresa se actualizao exitosamente",
            color: "success"
          });
          this.$router.push('/administrar/empresa');
        });
      });
    },
    //valida los datos obligatorios
    validar(){
      this.error = 0;
      this.errorruc =  [];
      this.errornombre_comercial =  [];
      this.errorrazon_social =  [];
      this.errorprovincia =  [];
      this.errorciudad =  [];
      this.errorruc_contador =  [];
      this.errortipo_identidicacion =  [];
      this.errordireccion =  [];
      this.error_tipo_identidicacion =  [];
      this.erroridentificacion_representante =  [];
      this.errorsecuencial_factura = [];
      this.errorsecuencial_nota_credito = [];
      this.errorsecuencial_nota_debito = [];
      this.errorsecuencial_guia_remision = [];
      this.errorsecuencial_retencion = [];
      this.errorsecuencial_liquidacion_compra = [];
      this.errorestado_punto_emision = [];
      this.errornombreusuario =  [];
      this.errorapellidousuario =  [];
      this.erroremailusuario =  [];
      this.errorpassusuario =  [];
      this.errorrepassusuario =  [];
      this.errorcategoria_producto=[];
      if(!this.ruc){
        this.errorruc.push("Ruc obligatorio");
        this.error = 1;
        console.log("Ruc obligatorio");
      }
      if(!this.nombre_comercial){
        this.errornombre_comercial.push("Nombre obliatorio");
        this.error = 1;
        console.log("Nombre obligatorio");
      }
      if(!this.razon_social){
        this.errorrazon_social.push("Razón social obligatorio");
        this.error = 1;
        console.log("Razón social obligatorio");
      }
      if(!this.provincia){
        this.errorprovincia.push("Provincia obliatorio");
        this.error = 1;
        console.log("Provincia obligatorio");
      }
      if(!this.ciudad){
        this.errorciudad.push("Ciudad obligatorio");
        this.error = 1;
        console.log("Ciudad obligatorio");
      }
      // if(!this.tipo_identidicacion){
      //   this.errortipo_identidicacion.push("Tipo obligatorio");
      //   this.error = 1;
      //   console.log("Tipo obligatorio");
      // }
      if(!this.secuencial_factura){
        this.errorsecuencial_factura.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio fact");
      }
      if(!this.secuencial_nota_credito){
        this.errorsecuencial_nota_credito.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio nta credito");
      }
      if(!this.secuencial_nota_debito){
        this.errorsecuencial_nota_debito.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio nta debito");
      }
      if(!this.secuencial_guia_remision){
        this.errorsecuencial_guia_remision.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio guia remision");
      }
      if(!this.secuencial_retencion){
        this.errorsecuencial_retencion.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio retencion");
      }
      if(!this.secuencial_liquidacion_compra){
        this.errorsecuencial_liquidacion_compra.push("Secuencial obligatorio");
        this.error = 1;
        console.log("Secuencial obligatorio liquid");
      }
      if(!this.nombreusuario){
        this.errornombreusuario.push("Nombre obligatorio");
        this.error = 1;
        console.log("Nombre obligatorio usu");
      }
      if(!this.apellidousuario){
        this.errorapellidousuario.push("Apellido obligatorio");
        this.error = 1;
        console.log("Apellido obligatorio usu");
      }
      if(!this.emailusuario){
        this.erroremailusuario.push("Email obligatorio");
        this.error = 1;
        console.log("Email obligatorio usu");
      }
      if(this.repassusuario != this.passusuario){
        this.errorrepassusuario.push("Las contraseñas no coinciden");
        this.error = 1;
        console.log("Las contraseñas no coinciden");
      }
      if(this.categoria_producto.length<=0){
        this.errorcategoria_producto.push("Campo Obligatorio");
        this.error = 1;
        console.log("19");
      }
      if(this.error){
        setTimeout(() => {
          var valor = $(".text-danger:first-child").offset().top - 300;
          $("html, body").animate({
              scrollTop: valor,
          }, 500);
        }, 50);
        console.log("hubo un error");
      }
      return this.error;

    },
    //devuelve a la lista de empresas
    cancelar(){
      this.$router.push("/administrar/empresa");
    },
    //validaciones de ruc
    validarruc($event){
      this.errorruc = [];
      var numero = this.ruc;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;
      var ok=1;
      var d1 = numero.substr(0,1);
      var d2 = numero.substr(1,1);
      var d3 = numero.substr(2,1);
      var d4 = numero.substr(3,1);
      var d5 = numero.substr(4,1);
      var d6 = numero.substr(5,1);
      var d7 = numero.substr(6,1);
      var d8 = numero.substr(7,1);
      var d9 = numero.substr(8,1);
      var d10 = numero.substr(9,1);
      if (d3==7 || d3==8){
        this.errorruc.push("El tercer dígito ingresado es inválido");
        this.error=1;
        return false;
      }
      if (d3 < 6){
        nat = true;
        p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
        modulo = 10;
      }
      else if(d3 == 6){
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
      }
      else if(d3 == 9) {
        var pri = true;
        var p1 = d1 * 4;
        var p2 = d2 * 3;
        var p3 = d3 * 2;
        var p4 = d4 * 7;
        var p5 = d5 * 6;
        var p6 = d6 * 5;
        var p7 = d7 * 4;
        var p8 = d8 * 3;
        var p9 = d9 * 2;
      }
      var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
      var residuo = suma % modulo;
      var digitoVerificador = residuo==0 ? 0: modulo - residuo;
      if (pub==true){
        if (digitoVerificador != d9){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return false;
        }
        if ( numero.substr(9,4) != '0001' ){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return false;
        }
      }else if(pri == true){
        if (digitoVerificador != d10){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return false;
        }
        if ( numero.substr(10,3) != '001' ){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return false;
        }
      }else if(nat == true){
        if (digitoVerificador != d10){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return;
        }
        if (numero.length <14 && numero.substr(10,12) != '001' ){
          this.errorruc.push("Ruc invalido");
          this.error=1;
          return false;
        }
      }
      return true;
    },
    validarrucrepresentante($event){
      if(this.tipo_identidicacion=="Ruc"){
        this.erroridentificacion_representante = [];
        var numero = this.identificacion_representante;
        var suma = 0;
        var residuo = 0;
        var pri = false;
        var pub = false;
        var nat = false;
        var numeroProvincias = 22;
        var modulo = 11;
        var ok=1;
        var d1 = numero.substr(0,1);
        var d2 = numero.substr(1,1);
        var d3 = numero.substr(2,1);
        var d4 = numero.substr(3,1);
        var d5 = numero.substr(4,1);
        var d6 = numero.substr(5,1);
        var d7 = numero.substr(6,1);
        var d8 = numero.substr(7,1);
        var d9 = numero.substr(8,1);
        var d10 = numero.substr(9,1);
        if (d3==7 || d3==8){
        this.erroridentificacion_representante.push("El tercer dígito ingresado es inválido");
        this.error=1;
        return false;
        }
        if (d3 < 6){
        nat = true;
        p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
        p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
        p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
        p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
        p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
        p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
        p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
        p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
        p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
        modulo = 10;
        }
        else if(d3 == 6){
        pub = true;
        p1 = d1 * 3;
        p2 = d2 * 2;
        p3 = d3 * 7;
        p4 = d4 * 6;
        p5 = d5 * 5;
        p6 = d6 * 4;
        p7 = d7 * 3;
        p8 = d8 * 2;
        p9 = 0;
        }
        else if(d3 == 9) {
        var pri = true;
        var p1 = d1 * 4;
        var p2 = d2 * 3;
        var p3 = d3 * 2;
        var p4 = d4 * 7;
        var p5 = d5 * 6;
        var p6 = d6 * 5;
        var p7 = d7 * 4;
        var p8 = d8 * 3;
        var p9 = d9 * 2;
        }
        var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
        var residuo = suma % modulo;
        var digitoVerificador = residuo==0 ? 0: modulo - residuo;
        if (pub==true){
        if (digitoVerificador != d9){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        if ( numero.substr(9,4) != '0001' ){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        }
        else if(pri == true){
        if (digitoVerificador != d10){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        if ( numero.substr(10,3) != '001' ){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        }
        else if(nat == true){
        if (digitoVerificador != d10){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        if (numero.length <14 && numero.substr(10,12) != '001' ){
        this.erroridentificacion_representante.push("Ruc invalido");
        this.error=1;
        return false;
        }
        }
        return true;
      }
    },
    validarruccontador($event){
      this.errorruc_contador = [];
      var numero = this.ruc_contador;
      var suma = 0;
      var residuo = 0;
      var pri = false;
      var pub = false;
      var nat = false;
      var numeroProvincias = 22;
      var modulo = 11;
      var ok=1;
      var d1 = numero.substr(0,1);
      var d2 = numero.substr(1,1);
      var d3 = numero.substr(2,1);
      var d4 = numero.substr(3,1);
      var d5 = numero.substr(4,1);
      var d6 = numero.substr(5,1);
      var d7 = numero.substr(6,1);
      var d8 = numero.substr(7,1);
      var d9 = numero.substr(8,1);
      var d10 = numero.substr(9,1);
      if (d3==7 || d3==8){
      this.errorruc_contador.push("El tercer dígito ingresado es inválido");
      this.error=1;
      return false;
      }
      if (d3 < 6){
      nat = true;
      p1 = d1 * 2; if (p1 >= 10) p1 -= 9;
      p2 = d2 * 1; if (p2 >= 10) p2 -= 9;
      p3 = d3 * 2; if (p3 >= 10) p3 -= 9;
      p4 = d4 * 1; if (p4 >= 10) p4 -= 9;
      p5 = d5 * 2; if (p5 >= 10) p5 -= 9;
      p6 = d6 * 1; if (p6 >= 10) p6 -= 9;
      p7 = d7 * 2; if (p7 >= 10) p7 -= 9;
      p8 = d8 * 1; if (p8 >= 10) p8 -= 9;
      p9 = d9 * 2; if (p9 >= 10) p9 -= 9;
      modulo = 10;
      }
      else if(d3 == 6){
      pub = true;
      p1 = d1 * 3;
      p2 = d2 * 2;
      p3 = d3 * 7;
      p4 = d4 * 6;
      p5 = d5 * 5;
      p6 = d6 * 4;
      p7 = d7 * 3;
      p8 = d8 * 2;
      p9 = 0;
      }
      else if(d3 == 9) {
      var pri = true;
      var p1 = d1 * 4;
      var p2 = d2 * 3;
      var p3 = d3 * 2;
      var p4 = d4 * 7;
      var p5 = d5 * 6;
      var p6 = d6 * 5;
      var p7 = d7 * 4;
      var p8 = d8 * 3;
      var p9 = d9 * 2;
      }
      var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
      var residuo = suma % modulo;
      var digitoVerificador = residuo==0 ? 0: modulo - residuo;
      if (pub==true){
      if (digitoVerificador != d9){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(9,4) != '0001' ){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(pri == true){
      if (digitoVerificador != d10){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if ( numero.substr(10,3) != '001' ){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      else if(nat == true){
      if (digitoVerificador != d10){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      if (numero.length <14 && numero.substr(10,12) != '001' ){
      this.errorruc_contador.push("Ruc invalido");
      this.error=1;
      return false;
      }
      }
      return true;
    },
    validarrepresentante($event){
      if(this.tipo_identidicacion=="Cedula"){
        this.errorrucRepre = [];
        var cad = this.identificacion_repr;
        var total = 0;
        var longitud = cad.length;
        var longcheck = longitud - 1;
        for(var i = 0; i < longcheck; i++){
          if (i%2 === 0) {
            var aux = cad.charAt(i) * 2;
            if (aux > 9) aux -= 9;
            total += aux;
          } else {
            total += parseInt(cad.charAt(i)); // parseInt o concatenará en lugar de sumar
          }
        }
        total = total % 10 ? 10 - total % 10 : 0;

        if (cad.substring(0,10).charAt(longitud-1) == total) {
          this.errorrucRepre=[];
        }else{
          this.errorrucRepre.push("Cédula inválida");
          this.error = 1;
          return;
        }
      }
    },
    listarempresas(){
      axios.get("/api/listarempresas").then( res => {
        this.empresas = res.data;
      });
    },
    //Archivo P12
    agregarp12(){
      this.recuperafirma = 0;
      $(".seleccionarp12").click();
    },
    seleccionarp12 (event) {
      this.recuperafirma = 0;
      var allowedExtensions = /(.p12)$/i;
      if(!allowedExtensions.exec($(".seleccionarp12").val())){
        this.p12 = "";
        $(".seleccionarp12").val("");
        this.$vs.notify({
          time: 8000,
          color: "danger",
          title: "Formato inválido ",
          text: "Solo se acepta archivos p12"
        });
      }else{
        this.p12 = event.target.files[0];
      }
    },
    eliminarp12 (event) {
        this.p12 = "";
        this.recuperafirma = 0;
        $(".seleccionarp12").val("");
        this.fecha_expiracion_firma = [];
        this.pass_firma = [];
      },
    //Archivo de imagen de empresa
    agregarimagen(){
      this.recuperaimagen = 0;
      $(".seleccionarimagen").click();
    },
    seleccionarimagen(event) {
      this.recuperaimagen = 0;
      var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.webp)$/i;
      if(!allowedExtensions.exec($(".seleccionarimagen").val())){
        this.imagen = "";
        $(".seleccionarimagen").val("");
        this.$vs.notify({
          time: 8000,
          color: "danger",
          title: "Formato inválido ",
          text: "Solo se acepta archivos imagen"
        });
      }else{
        var tam = parseInt((event.target.files[0].size / 1024));
        if(tam > 1024){
          this.imagen = "";
          $(".seleccionarimagen").val("");
          this.$vs.notify({
            time: 8000,
            color: "danger",
            title: "Formato inválido",
            text: "El tamaño de la imagen no puede ser mayor a 1 MB"
          });
          return;
        }
        this.imagen = event.target.files[0];
        this.previsualizarimagen(this.imagen);
      }
    },
    eliminarimagen(event) {
      this.recuperaimagen = 0;
      this.imagen = "";
      $(".seleccionarimagen").val("");
    },
    previsualizarimagen(file) {
        this.recuperaimagen = 0;
        let reader = new FileReader();
        reader.onload = e => {
          this.imagenprevisualizar = e.target.result;
        };
        reader.readAsDataURL(file);
      },
    listarplancuentas(page, buscar) {
      var url = "/api/cuentas/" + this.$route.params.id + "?page=" +page +"&buscar=" +buscar;
      axios.get(url).then( res => {
        this.arraycuentas = res.data;
      });
    },
    escogercuenta(tr) {
        /*var punto = tr.codcta.charAt(tr.codcta.length-1)
        if(punto=='.'){
            this.$vs.notify({
                title: "Cuenta contable erroneo",
                text: "Esta cuenta contable no es válida",
                color: "danger"
            });
            return;
        }*/

      if(this.valor_cuenta == "1"){
        this.ctaActivo = tr.id_plan_cuentas;
        this.ctaActivo1 = tr.codcta;
      }else if(this.valor_cuenta == "2"){
        this.ctaPasivo = tr.id_plan_cuentas;
        this.ctaPasivo1 = tr.codcta;
      }else if(this.valor_cuenta == "3"){
        this.ctaPatrimonio = tr.id_plan_cuentas;
        this.ctaPatrimonio1 = tr.codcta;
      }else if(this.valor_cuenta == "4"){
        this.ctaIngreso = tr.id_plan_cuentas;
        this.ctaIngreso1 = tr.codcta;
      }else if(this.valor_cuenta == "5"){
        this.ctaCosto = tr.id_plan_cuentas;
        this.ctaCosto1 = tr.codcta;
      }else if(this.valor_cuenta == "6"){
        this.ctaGasto = tr.id_plan_cuentas;
        this.ctaGasto1 = tr.codcta;
      }else if(this.valor_cuenta == "7"){
        this.ctaOrden = tr.id_plan_cuentas;
        this.ctaOrden1 = tr.codcta;
      }else{
        this.ctaResultado = tr.id_plan_cuentas;
        this.ctaResultado1 = tr.codcta;
      }
      this.abrircuentas=false
    },
    administrar(){
        if(!this.b_administrar==1){
          this.items.forEach(el => {
            if(el.lugar==1){
              el.ver = 1;
              el.editar = 1;
              el.crear = 1;
              el.eliminar = 1;
            }
          });
        }else{
          this.items.forEach(el => {
            if(el.lugar==1){
              el.ver = 0;
              el.editar = 0;
              el.crear = 0;
              el.eliminar = 0;
            }
          });
        }
    },
    listarroles() {
      axios.get('/api/empresaroles/' + this.$route.params.id).then( ({data}) => {
        this.items = data;
        this.verificaritems();
      });
    },
    verificaritems(){
      this.items.forEach(el => {
            if(el.lugar==1){
              if(el.ver==1) {
                this.b_administrar = 1;
              }
            }else if(el.lugar==2){
              if(el.ver==1) {
                this.b_contabilidad = 1;
              }
            }else if(el.lugar==3){
              if(el.ver==1) {
                this.b_facturacion = 1;
              }
            }else if(el.lugar==4){
              if(el.ver==1) {
                this.b_compras = 1;
              }
            }else if(el.lugar==5){
              if(el.ver==1) {
                this.b_inventario = 1;
              }
            }else if(el.lugar==6){
              if(el.ver==1) {
                this.b_nomina = 1;
              }
            }else if(el.lugar==7){
              if(el.ver==1) {
                this.b_importacion = 1;
              }
            }else if(el.lugar==8){
              if(el.ver==1) {
                this.b_produccion = 1;
              }
            }else if(el.lugar==9){
              if(el.ver==1) {
                this.b_activos = 1;
              }
            }else{
              if(el.ver==1) {
                this.b_calendario = 1;
              }
            }
          });
    },
    contabilidads(){
      if(!this.b_contabilidad==1){
        this.items.forEach(el => {
          if(el.lugar==2){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==2){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    facturacion(){
      if(!this.b_facturacion==1){
        this.items.forEach(el => {
          if(el.lugar==3){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==3){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    compras(){
      if(!this.b_compras==1){
        this.items.forEach(el => {
          if(el.lugar==4){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==4){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    inventario(){
      if(!this.b_inventario==1){
        this.items.forEach(el => {
          if(el.lugar==5){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==5){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    nomina(){
      if(!this.b_nomina==1){
        this.items.forEach(el => {
          if(el.lugar==6){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==6){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    importacion(){
      if(!this.b_importacion==1){
        this.items.forEach(el => {
          if(el.lugar==7){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==7){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    produccion(){
      if(!this.b_produccion==1){
        this.items.forEach(el => {
          if(el.lugar==8){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==8){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    activos(){
      if(!this.b_activos==1){
        this.items.forEach(el => {
          if(el.lugar==9){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==9){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    calendario(){
      if(!this.b_calendario==1){
        this.items.forEach(el => {
          if(el.lugar==10){
            el.ver = 1;
            el.editar = 1;
            el.crear = 1;
            el.eliminar = 1;
          }
        });
      }else{
        this.items.forEach(el => {
          if(el.lugar==10){
            el.ver = 0;
            el.editar = 0;
            el.crear = 0;
            el.eliminar = 0;
          }
        });
      }
    },
    verificarcorreo(){
      axios.post("/api/pruebacorreodata",{
        email_empresa: this.email_empresa,
        password: this.password,
        servidor_correo: this.servidor_correo,
        puerto_correo: this.puerto_correo,
        seguridad_correo: this.seguridad_correo,
      }).then( ({data}) => {
        if(data == 'Enviado'){
          this.$vs.notify({
            title: "Datos de correo correctos",
            text: "La prueba de correo fue exitosa",
            color: "success"
          });
        }else{
          this.$vs.notify({
            title: "Datos de correo incorrectos",
            text: "Verifique los datos de envio de correo e intente nuevamente",
            color: "danger"
          });
        }
      }).catch(error => {
        this.$vs.notify({
          title: "Datos de correo incorrectos",
          text: "Verifique los datos de envio de correo e intente nuevamente",
          color: "danger"
        });
      });
    },
    remove_chip_correo (item) {
        this.telefono.splice(this.telefono.indexOf(item), 1)
    }
  }
};
</script>
<style>
  input[type=”file”]#nuestroinput {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
  }
  label[for=" nuestroinput"] {
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  background-color: #106BA0;
  display: inline-block;
  transition: all .5s;
  cursor: pointer;
  padding: 15px 40px !important;
  text-transform: uppercase;
  width: fit-content;
  text-align: center;
  }
  .imagenpre{
    max-height: 100%;
    cursor:pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
    max-width: 100%;
  }
  .centimg{
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius:20px;
    background: rgba(255,255,255,.8)!important;
    -webkit-transition: all .3s ease;
  -moz-transition: all .3s ease;
  -ms-transition: all .3s ease;
  -o-transition: all .3s ease;
  transition: all .3s ease;
  }

  .verimagen{
      overflow: hidden;
      padding: 0px;
      height: 300px;
      height: 300px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      border-radius: 20px;
      background: rgba(255,255,255,.8)!important;
      -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
    border: 5px solid rgba(0,0,0,.3);
  }

  .imagenpre:hover{
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
  }

  .centimg:hover{
    background: rgba(255,255,255,.6)!important;
    cursor: pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }

  .centimg img{
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .demo-alignment > * {
      margin-right: 1.5rem;
      margin-top: 0.8rem;
  }
  .text-danger{
    font-size:13px;
  }
  .vs-dialog{
    max-width: 900px!important;
  }
  .estiloborrar{
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .estiloborrar:hover{
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    box-shadow: 0px -20px 20px 20px red;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .verimagen:hover .spanborrar{
    margin-bottom: 0px;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .spanborrar{
    margin-bottom: -999px;
    position: absolute;
    bottom: 29px;
    color: red;
    font-size: 44px;
    font-weight: bold;
    margin-left: -10px;
    -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
  }
  .corto{
    font-size: 9px;
    position: absolute;
  }
</style>
