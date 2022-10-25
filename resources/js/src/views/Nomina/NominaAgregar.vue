<template>
    <vx-card title="Agregar Empleado">
        <vs-tabs alignment="fixed" v-model="tabIndex">
            
            <vs-tab
                title="BasicInfo"
                label="Información Básica"
                icon-pack="feather"
                icon="icon-user"
                name="Jobs"
            >
                <div class="vx-row">
                    <vs-divider>Datos del Empleado</vs-divider>
                    <div class="vx-col md:w-2/3 w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-select
                                    placeholder="--Seleccione--"
                                    class="selectExample w-full"
                                    label="Tipo de Identificación:"
                                    vs-multiple
                                    autocomplete
                                    v-model="tipo_dni"
                                >
                                    <vs-select-item
                                        :key="index"
                                        :value="item.text"
                                        :text="item.text"
                                        v-for="(item,
                                        index) in tipo_ident_array"
                                    />
                                </vs-select>
                                
                                <div v-show="error" v-if="!tipo_dni">
                                    <span
                                        class="text-danger"
                                        v-for="err in errortipo_dni"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>

                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input 
                                    class="w-full"
                                    label="No. Identificación:"
                                    v-model="dni"
                                    v-if="tipo_dni=='Cédula'"
                                    @keyup="validarcedula"
                                    @keypress="solonumeros($event)"
                                    maxlength="10"
                                />
                                <vs-input 
                                    class="w-full"
                                    label="No. Identificación:"
                                    v-model="dni"
                                    v-else
                                    @keypress="solonumeros($event)"
                                    maxlength="15"
                                />
                                
                                <div v-show="errorcedula" v-if="tipo_dni=='Cédula'">
                                    <span class="text-danger" v-for="err in errordni" :key="err" v-text="err"></span>
                                </div>
                                <div v-show="error" v-else>
                                    <span class="text-danger" v-for="err in errordni" :key="err" v-text="err"></span>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Primer Nombre:"
                                    @keypress="sololetras($event)"
                                    v-model="primer_nombre"
                                />
                                <div v-show="error" v-if="!primer_nombre">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorprimer_nombre"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Segundo Nombre:"
                                    @keypress="sololetras($event)"
                                    v-model="segundo_nombre"
                                />
                                <div v-show="error" v-if="!segundo_nombre">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorsegundo_nombre"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Apellido Paterno:"
                                    @keypress="sololetras($event)"
                                    v-model="apellido_paterno"
                                />
                                <div v-show="error" v-if="!apellido_paterno">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorapellido_paterno"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Apellido Materno:"
                                    @keypress="sololetras($event)"
                                    v-model="apellido_materno"
                                />
                                <div v-show="error" v-if="!apellido_materno">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorapellido_materno"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>

                            <div class="vx-col w-1/2 mb-6">
                                <label class="vs-input--label"
                                    >Fecha Nacimiento:</label
                                >
                                <flat-pickr
                                    :config="configdateTimePicker"
                                    class="w-full"
                                    placeholder="--Seleccione--"
                                    v-model="fecha_nacimiento"
                                   
                                />
                                <!-- @change="calcularEdad(fecha_nacimiento,edad)"-->
                                {{calcularEdad}}
                                <div v-show="error" v-if="!fecha_nacimiento">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorfecha_nacimiento"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Edad:"
                                    v-model="edad"
                                    @keypress="solonumeros($event)"
                                    maxlength="2"
                                    disabled
                                />
                            </div>
                        </div>
                    </div>
                    <!--Import Img inicio-->
                    <div class="vx-col md:w-1/3 w-full mb-3">
                <div style="display:none">
                    <input
                        type="file"
                        class="filepre seleccionarimagen"
                        accept="image/*"
                        @change="seleccionarimagen"
                    />
                </div>
                <div class="verimagen" v-if="!imagen">
                    <img
                        src="/images/upload.png"
                        @click="agregarimagen"
                        class="imagenpre"
                        style="padding: 20px;"
                    />
                </div>
                <div class="verimagen" v-else style="position: relative;">
                    <template v-if="recuperaimagen">
                        <img
                            :src="'/'+usuario.id_empresa+'/empleados/'+$route.params.id+'/imagenes/'+imagen"
                            @click="agregarimagen"
                            class="imagenpre"
                        />
                        <div class="alerta estiloborrar">
                            <div class="text-center">
                                <span class="spanborrar" @click="eliminarimagen"
                                    >X</span
                                >
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <img
                            :src="imagenprevisualizar"
                            @click="agregarimagen"
                            class="imagenpre"
                        />
                        <div class="alerta estiloborrar">
                            <div class="text-center">
                                <span class="spanborrar" @click="eliminarimagen"
                                    >X</span
                                >
                            </div>
                        </div>
                    </template>
                </div>
            </div>  
            
                    <!--Import Img fin-->
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Provincia</label>
                        <vs-select
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="provincia"
                            @change="
                                getCiudades(); 
                            "
                        >
                        <!--canton = '';
                                parroquia = '';-->
                            <vs-select-item
                                v-for="data in provincias2"
                                :key="data.id_provincia"
                                :value="data.id_provincia"
                                :text="data.nombre"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!provincia">
                            <span
                                class="text-danger"
                                v-for="err in errorprovincia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Cantón</label>
                        <vs-select
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="canton"
                            @change="getParroquias"
                        >
                            <vs-select-item
                                v-for="data in ciudades2"
                                :key="data.id_ciudad"
                                :value="data.id_ciudad"
                                :text="data.nombre"
                            />
                        </vs-select>
                        
                        <div v-show="error" v-if="!canton">
                            <span
                                class="text-danger"
                                v-for="err in errorcanton"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Parroquia</label>
                        <vs-select
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="lugar_nacimiento"
                            @change="getParroquias()"
                        >
                            <vs-select-item
                                v-for="data in parroquias2"
                                :key="data.id_parroquia"
                                :value="data.id_parroquia"
                                :text="data.nombre_parroquia"
                            />
                        </vs-select>
                        
                        <!--
            <div v-show="error">
              <span class="text-danger" v-for="err in errorparroquia" :key="err" v-text="err"></span>
            </div>
            -->
                    </div>

                    <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                         <vs-input
                                    class="w-full"
                                    label="Lugar Nacimiento:"
                                    v-model="lugar_nacimiento"
                                    disabled
                                />
                        <!--
            <div v-show="error">
              <span
                class="text-danger"
                v-for="err in errorlugar_nacimiento"
                :key="err"
                v-text="err"
              ></span>
            </div>
            -->
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Nacionalidad:"
                            vs-multiple
                            autocomplete
                            v-model="nacionalidad"
                        >
                            <vs-select-item
                                v-for="data in nacionalidad2"
                                :key="data.id_nacionalidad"
                                :value="data.id_nacionalidad"
                                :text="data.GENTILICIO_NAC"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!nacionalidad">
                            <span
                                class="text-danger"
                                v-for="err in errornacionalidad"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Lugar Residencia:"
                            v-model="lugar_residencia"
                        />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Estado Civil:"
                            vs-multiple
                            autocomplete
                            v-model="estado_civil"
                        >
                            <vs-select-item
                                v-for="(item, index) in estado_civil_array"
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                            />
                        </vs-select>
                        <div v-if="!estado_civil">
                            <span
                                class="text-danger"
                                v-for="err in errorestado_civil"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Sexo:"
                            vs-multiple
                            autocomplete
                            v-model="sexo"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in sexo_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!sexo">
                            <span
                                class="text-danger"
                                v-for="err in errorsexo"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Dirección Residencia:"
                            v-model="direccion_residencia"
                        />
                        <div v-show="error" v-if="!direccion_residencia">
                            <span
                                class="text-danger"
                                v-for="err in errordireccion_residencia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Teléfono:"
                            v-model="telefono"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Celular:"
                            v-model="celular"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                        <div v-show="error" v-if="!celular">
                            <span
                                class="text-danger"
                                v-for="err in errorcelular"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Email:"
                            v-model="email"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in erroremail"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Tipo de Sangre:"
                            vs-multiple
                            autocomplete
                            v-model="tipo_sangre"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in tipo_sangre_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tipo_sangre">
                            <span
                                class="text-danger"
                                v-for="err in errortipo_sangre"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Profesion:"
                            v-model="profesion"
                        />
                        <div v-show="error" v-if="!profesion">
                            <span
                                class="text-danger"
                                v-for="err in errorprofesion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <!--prueba-->
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Discapacidad:"
                            vs-multiple
                            autocomplete
                            v-model="discapacidad"
                            @change="cambio(discapacidad)"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in discapacidad_array"
                            />
                        </vs-select>
                        
                        <div v-show="error" v-if="!discapacidad">
                            <span
                                class="text-danger"
                                v-for="err in errordiscapacidad"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div v-if="tipodiscap && discapacidad=='Otro'" class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Describa la Discapacidad:"
                            v-model="otra_discap"
                            maxlength="300"
                        />           
                    </div>
                    <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="% Discapacidad:"
                            v-model="discap_porcentaje"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        
                        <div v-show="error" v-if="!discap_porcentaje">
                        <span
                            class="text-danger"
                            v-for="err in errordiscap_porcentaje"
                            :key="err"
                            v-text="err"
                        ></span>
                        </div>
            
                    </div>
                    <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            class="selectExample w-full"
                            label="Documento de Discapacidad:"
                            placeholder="--Seleccione--"
                            vs-multiple
                            autocomplete
                            v-model="tipo_iden_discap"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in ident_discap_array"
                            />
                        </vs-select>
                        
            <div v-show="error" v-if="!tipo_iden_discap">
              <span
                class="text-danger"
                v-for="err in errortipo_iden_discap"
                :key="err"
                v-text="err"
              ></span>
            </div>
            
                    </div>
                    <div v-if="tipodiscap" class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="No. Identificación Discap.:"
                            v-model="num_iden_discap"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                        
            <div v-show="error" v-if="!num_iden_discap">
              <span class="text-danger" v-for="err in errornum_iden_discap" :key="err" v-text="err"></span>
            </div>
            
                    </div>
                    <!--<div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="No. IESS:"
                            v-model="num_iess"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                    </div>-->
                    <!--<div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="No. Libreta Militar:"
                            v-model="num_libreta_militar"
                            @keypress="solonumeros($event)"
                            maxlength="10"
                        />
                    </div>-->
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Banco:"
                            vs-multiple
                            autocomplete
                            v-model="banco"
                        >
                            <vs-select-item
                                v-for="data in banco2"
                                :key="data.id_banco"
                                :value="data.id_banco"
                                :text="data.nombre_banco"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!banco">
                            <span
                                class="text-danger"
                                v-for="err in errorbanco"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Tipo de Cuenta:"
                            vs-multiple
                            autocomplete
                            v-model="tipo_cuenta"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in tipo_cuenta_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tipo_cuenta">
                            <span
                                class="text-danger"
                                v-for="err in errortipo_cuenta"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="No. Cuenta:"
                            v-model="num_cuenta"
                            @keypress="solonumeros($event)"
                            maxlength="25"
                        />
                        <div v-show="error" v-if="!num_cuenta">
                            <span
                                class="text-danger"
                                v-for="err in errornum_cuenta"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    
                    <div  class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Cargas:"
       
                            v-model="cargas"
                        >
                            <vs-select-item value=1 text="Si"/>
                            <vs-select-item value=0 text="No"/>
                        </vs-select>
                        <div v-show="error" v-if="!cargas">
                            <span
                                class="text-danger"
                                v-for="err in errorcargas"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="cargas==1">
                        <vs-input
                            class="w-full"
                            label="No. Cargas:"
                            v-model="num_cargas"
                            @change="numcargas()"
                            @keypress="solonumeros($event)"
                            maxlength="1"
                        />
                        <div v-show="error" v-if="!num_cargas">
                            <span
                                class="text-danger"
                                v-for="err in errornum_cargas"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="this.$route.params.id">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Estado:"
                            vs-multiple
                            autocomplete
                            v-model="estado"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in estado_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!estado">
                            <span
                                class="text-danger"
                                v-for="err in errorestado"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <vs-divider>Contacto de Emergencia</vs-divider>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre:"
                            v-model="contacto_nombre"
                            maxlength="300"
                        />
                        <div v-show="error" v-if="!contacto_nombre">
                            <span
                                class="text-danger"
                                v-for="err in errorcontacto_nombre"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Parentezco:"
                            v-model="contacto_parentezco"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in car_parentezco_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!contacto_parentezco">
                            <div
                                v-for="err in errorcontacto_parentezco"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                        
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Telefono:"
                            v-model="contacto_telefono"
                            maxlength="300"
                        />
                        <div v-show="error" v-if="!contacto_telefono">
                            <span
                                class="text-danger"
                                v-for="err in errorcontacto_telefono"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <vs-divider></vs-divider>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div>
                            <label class="vs-input--label"
                                >Observaciones:</label
                            >
                            <vs-textarea v-model="observaciones" rows="3" />
                        </div>
                    </div>

                    <!--BOTONES

          <div class="vx-col w-full">
            <vs-button
              color="success"
              type="filled"
              @click="editarEmpleado()"
              v-if="$route.params.id"
            >Siguitente</vs-button>
            <vs-button color="success" type="filled" @click="guardarEmpleado()" v-else>Siguitente</vs-button>
          </div>
          -->
                    
                </div>
            </vs-tab>
            <!--
      -->

            <vs-tab
                title="JobInfo"
                label="Detalles del Cargo"
                icon-pack="feather"
                icon="icon-briefcase"
                v-model="tab1e"
                
            >
                <div class="vx-col w-full mb-base">
                    <div class="vx-row">
                        <!--Segunda tabla-->

                        <vs-divider>Datos del Cargo</vs-divider>

                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label">Departamento</label>
                            <vs-select
                                placeholder="--Seleccione--"
                                autocomplete
                                class="selectExample w-full"
                                v-model="departamento"
                                @change="
                                    getArea();
                                    
                                "
                            >
                            <!--area_trabajo = '';
                                    cargo = '';-->
                                <vs-select-item
                                    v-for="data in departamento2"
                                    :key="data.id_departamento"
                                    :value="data.id_departamento"
                                    :text="data.dep_nombre"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!departamento">
                                <span
                                    class="text-danger"
                                    v-for="err in errordepartamento"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>

                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label"
                                >Área de Trabajo</label
                            >
                            <vs-select
                                placeholder="--Seleccione--"
                                autocomplete
                                class="selectExample w-full"
                                v-model="area_trabajo"
                                @change="getCargo()"
                            >
                            <!--@change="getCargo"-->
                                <vs-select-item
                                    v-for="data in area2"
                                    :key="data.id_area"
                                    :value="data.id_area"
                                    :text="data.are_nombre"
                                />
                            </vs-select>
                            <!--{{area_trabajo}}-->
                            <div v-show="error" v-if="!area_trabajo">
                                <span
                                    class="text-danger"
                                    v-for="err in errorarea_trabajo"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label">Cargo</label>
                            <vs-select
                                placeholder="--Seleccione--"
                                autocomplete
                                class="selectExample w-full"
                                v-model="idcargo"
                                @change="getCargo(),getSueldoCargo(idcargo)"
                            >
                                <vs-select-item
                                    v-for="data in cargo2"
                                    :key="data.id_cargo"
                                    :value="data.id_cargo"
                                    :text="data.car_nombre"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!idcargo">
                                <span
                                    class="text-danger"
                                    v-for="err in errorcargo"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                            
                        </div>
                        <!--
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input class="w-full" label="Sueldo:" disabled v-model="sueldo" />
              <div v-show="error">
                <span class="text-danger" v-for="err in errorsueldo" :key="err" v-text="err"></span>
              </div>
            </div>
            -->
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label"
                                >Fecha Ingreso:</label
                            >
                            <flat-pickr
                                :config="configdateTimePicker"
                                class="w-full"
                                placeholder="--Seleccione--"
                                v-model="fecha_ingreso"
                            />
                            <div v-show="error" v-if="!fecha_ingreso">
                                <span
                                    class="text-danger"
                                    v-for="err in errorfecha_ingreso"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label">Fecha Salida:</label>
                            <flat-pickr
                                :config="configdateTimePicker"
                                class="w-full"
                                placeholder="--Seleccione--"
                                v-model="fecha_salida"
                            />
                            <div v-show="error" v-if="!fecha_salida">
                                <span
                                    class="text-danger"
                                    v-for="err in errorfecha_salida"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Grupo Ocupacional:"
                                vs-multiple
                                autocomplete
                                v-model="idgrupo"
                            >
                                <vs-select-item
                                    v-for="data in grupo_ocupacional2"
                                    :key="data.id_grupo"
                                    :value="data.id_grupo"
                                    :text="data.grup_nombre"
                                />
                            </vs-select>
                            
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Tipo Horario:"
                                vs-multiple
                                autocomplete
                                v-model="tipo_horario"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in tipo_horario_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!tipo_horario">
                                <span
                                    class="text-danger"
                                    v-for="err in errortipo_horario"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Tipo Contrato:"
                                vs-multiple
                                autocomplete
                                v-model="tipo_contrato"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in tipo_contrato_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!tipo_contrato">
                                <span
                                    class="text-danger"
                                    v-for="err in errortipo_contrato"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Sueldo:"
                                @keypress="solonumeros($event)"
                                v-model="bonos"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Aporte IESS:"
                                vs-multiple
                                autocomplete
                                v-model="aporte_iess"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in aporte_iess_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!aporte_iess">
                                <span
                                    class="text-danger"
                                    v-for="err in erroraporte_iess"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Fondo Reserva Mensual:"
                                vs-multiple
                                autocomplete
                                v-model="fondo_reserva"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in fondo_reserva_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!fondo_reserva">
                                <span
                                    class="text-danger"
                                    v-for="err in errorfondo_reserva"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Décimo Tercero Mensual:"
                                vs-multiple
                                autocomplete
                                v-model="decimo_tercero"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item,
                                    index) in decimo_tercero_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!decimo_tercero">
                                <span
                                    class="text-danger"
                                    v-for="err in errordecimo_tercero"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Décimo Cuarto Mensual:"
                                vs-multiple
                                autocomplete
                                v-model="decimo_cuarto"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in decimo_cuarto_array"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!decimo_cuarto">
                                <span
                                    class="text-danger"
                                    v-for="err in errordecimo_cuarto"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <!--CUENTA CONTABLE======1-->
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            
                             <!--<div class="row mb-2">
                            <div
                                class="col-xl-6 col-lg-12 justify-content-end clicker"
                                style="position: relative;"
                            >
                                <div
                                    class="input-group mb-3"
                                    style="width: 100%;"
                                >
                                    <vs-input
                                        label="Cuenta Contable"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Contable"
                                        v-model="ctacontable"
                                        @keyup="
                                            listarcuenta(ctacontable),
                                                abrirlista()
                                        "
                                    />
                                </div>
                                <div
                                    class="menuescoger"
                                    style="display:none"
                                    id="menuescoger"
                                    v-if="ctacontable"
                                >
                                    <template v-if="contenidocuenta.length">
                                        <ul
                                            v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelected(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicion">
                                                    <template v-if="tr.codcta"
                                                        ><span
                                                            >Codigo:
                                                            {{ tr.codcta }}
                                                        </span>
                                                    </template>
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <ul
                                            style="padding: 7px;text-align: center;"
                                        >
                                            <li>
                                                Cuenta Contable no existe
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>-->
                            <label class="vs-input--label">Cuenta Contable</label>
                                <vx-input-group class>
                                <vs-input
                                    class="w-full"
                                    v-model="ctacontable"
                                    :value="idContable"
                                    disabled
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        
                                        <vs-button
                                            color="primary"
                                            @click="activePrompt3 = true"
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                            
                        </div>
                        
                        
                    </div>
                    <!--FIN CUENTA CONTABLE=====1-->
                    <vs-popup
                        title="Plan Cuentas"
                        class="peque"
                        :active.sync="activePrompt3"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar"
                                @keyup="listar(1, buscar)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3"
                                @selected="handleSelected3"
                                :data="contenido"
                            >
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td :data="data[indextr].codcta">{{
                                            data[indextr].codcta
                                        }}</vs-td>
                                        <vs-td :data="data[indextr].nomcta">{{
                                            data[indextr].nomcta
                                        }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>
                    <!--
            <div class="vx-col sm:w-1/3 w-full mb-6">
              <vs-input class="w-full" label="ID:" v-model="id_empleado"/>
            </div>-->
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div>
                            <label class="vs-input--label"
                                >Observaciones:</label
                            >
                            <vs-textarea v-model="observaciones_dos" rows="3" />
                        </div>
                        
                    </div>
                </div>
            </vs-tab>

            <!--
      -->
            <vs-tab
                title="cargas"
                label="Cargas"
                icon-pack="feather"
                icon="icon-users"
            >
                <vs-divider>
                    <!--<vs-button color="warning" type="filled" @click="agregarCarga()">Agregar</vs-button>-->
                </vs-divider>
                
                <div class="vx-row" v-for="(tr,index) in valorcargas" :key="index">
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                    <!--v-model="car_dni"-->
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Tipo Identificación:"
                            v-model="tr.tipo_car_dni"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in car_tipo_dni_array"
                            />
                        </vs-select>
                        
                        <div v-show="error" v-if="!tr.tipo_car_dni">
                        <div
                            v-for="err in tr.errortipo_car_dni"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6" :hidden="!tr.tipo_car_dni">
                    <!--v-model="car_dni"-->
                        <vs-input
                            class="w-full"
                            label="No. Identificación:"
                            v-model="tr.car_dni"
                            v-if="tr.tipo_car_dni==1"
                            @keypress="solonumeros($event)"
                            @keyup="validarcedula_carga()"
                            maxlength="10"
                        />
                        <vs-input
                            class="w-full"
                            label="No. Identificación:"
                            v-model="tr.car_dni"
                            v-else
                            @keypress="solonumeros($event)"
                            maxlength="20"
                        />
                        <div v-show="errorcedula_cargo">
                        <div
                            v-for="err in tr.errorcar_dni"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre:"
                            v-model="tr.car_nombre"
                            @keypress="sololetras($event)"
                            maxlength="199"
                        />
                        <div v-show="error" v-if="!tr.car_nombre">
                        <div
                            v-for="err in tr.errorcar_nombre"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    <!---->
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                    
                        <label class="vs-input--label">Fecha Nacimiento:</label>
                        <flat-pickr
                            :config="configdateTimePicker"
                            class="w-full"
                            placeholder="--Seleccione--"
                            v-model="tr.car_fecha_nacimiento"
                            
                        />
                        <div v-show="error" v-if="!tr.car_fecha_nacimiento">
                        <div
                            v-for="err in tr.errorcar_fecha_nacimiento"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    {{calcularEdadCarga(index)}}
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Edad:"
                            v-model="tr.car_edad"
                            @keypress="solonumeros($event)"
                            maxlength="2"
                            disabled
                        />
                        <!--<div v-show="error" v-if="!car_edad">
                            <span
                                class="text-danger"
                                v-for="err in errorcar_edad"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>-->
                    </div>
                    
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Ocupación:"
                            v-model="tr.car_ocupacion"
                            @keypress="sololetras($event)"
                            maxlength="10"
                        />
                        <!--<div v-show="error" v-if="!car_ocupacion">
                            <span
                                class="text-danger"
                                v-for="err in errorcar_ocupacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>-->
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Parentezco:"
                            v-model="tr.car_parentezco"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in car_parentezco_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tr.car_parentezco">
                        <div
                            v-for="err in tr.errorcar_parentezco"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                        
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Discapacidad:"
                            vs-multiple
                            autocomplete
                            v-model="tr.car_discapacidad"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in car_discapacidad_array"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tr.car_discapacidad">
                        <div
                            v-for="err in tr.errorcar_discapacidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    <div v-if="tr.car_discapacidad=='Otro'" class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Descrip Discapacidad:"
                            v-model="tr.car_tipo_discapacidad"
                            maxlength="300"
                        />
                        
                    </div>
                    <div v-if="tr.car_discapacidad!='Ninguna'" class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="% Discapacidad:"
                            v-model="tr.car_discap_porcentaje"
                            @keypress="solonumeros($event)"
                            maxlength="9"
                        />
                        
                    </div>
                    <div  class="vx-col sm:w-1/3 w-full mb-6">
                        <label>Documento de Veracidad</label>
                        <div class="form-group">
                <label class="btn btn-primary">
                    <input type="file" @change="seleccionardocmento" :class="'seleccionardocmento'+index" :id="index" name="myfile" style="display:none">
                    <vs-button v-if="!tr.car_documento_validez" color="primary" type="filled" @click="agregardocmento(index)">No ha ingresado un archivo</vs-button>
                </label>
                <div class="vx-col sm:w-full w-full mb-6" v-if="tr.car_documento_validez">
                    <vx-input-group class="mb-base">
                            <vs-input
                                class="w-full"
                                v-model="tr.car_documento_validez"
                                disabled
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <input type="file" @change="seleccionardocmento" :class="'seleccionardocmento'+index" :id="index" name="myfile" style="display:none">
                                    <vs-button color="primary" @click="eliminardocmento(index)"
                                        >Eliminar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                </div>
            </div>
                    </div>

                    <vs-divider/>
                </div>
            </vs-tab>

            <vs-tab
                title="documentacion"
                label="Documentación"
                icon-pack="feather"
                icon="icon-file-text"
                
            >
            <!--@click="llamar()"-->
                <vs-divider>
                    <vs-button color="warning" type="filled" @click="agregarDocumento()">Agregar</vs-button>
                </vs-divider>
                
                <div class="vx-row" v-for="(tr,index) in valordocumentos" :key="index">
                    
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Documento:"
                            vs-multiple
                            autocomplete
                            v-model="tr.id_documento"
                        >
                            <vs-select-item
                                v-for="data in doc_descripcion2"
                                :key="data.id_documento"
                                :value="data.id_documento"
                                :text="data.doc_descripcion"
                            />
                        </vs-select>
                        
                        <div v-show="error" v-if="!tr.id_documento">
                        <div
                            v-for="err in tr.errorid_documento"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                        </div>
                    </div>
                    {{documento_estado}}
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-select
                            placeholder="--Seleccione--"
                            class="selectExample w-full"
                            label="Estado:"
                            vs-multiple
                            autocomplete
                            v-model="tr.doc_estado"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.text"
                                :text="item.text"
                                v-for="(item, index) in doc_estado_array"
                            />
                        </vs-select>
                        
                        <div v-show="error" v-if="!tr.doc_estado">
                            <div
                                v-for="err in tr.errordoc_estado"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Subir Archivo</label>
                        
            <div class="form-group">
                <label class="btn btn-primary">
                    <input type="file" @change="seleccionarp12" :class="'seleccionarp12'+index" :id="index" name="myfile" style="display:none">
                    <vs-button v-if="!tr.doc_url" color="primary" type="filled" @click="agregarp12(index)">No ha ingresado un archivo</vs-button>
                </label>
                <div class="vx-col sm:w-full w-full mb-6" v-if="tr.doc_url">
                    <vx-input-group class="mb-base">
                            <vs-input
                                class="w-full"
                                v-model="tr.doc_url"
                                disabled
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <input type="file" @change="seleccionarp12" :class="'seleccionarp12'+index" :id="index" name="myfile" style="display:none">
                                    <vs-button color="primary" @click="eliminarp12(index)"
                                        >Eliminar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                </div>
            </div>
                        <!--<vx-input-group class="mb-base">
                            <vs-input
                                class="w-full"
                                v-model="tr.doc_url"
                                @keypress="solonumeros($event)"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button color="primary" @click="Elegir()"
                                        >Elegir</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>-->
                        
                        <div v-show="error" v-if="!tr.doc_url">
                            <div
                                v-for="err in tr.errordoc_url"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <feather-icon
                    icon="TrashIcon"
                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                    class="ml-1 cursor-pointer"
                    @click="borrarDocumento(index)"
                  />
                  
                    </div>
                    
                    
                    <vs-divider/>
                </div>
                
                <!--BOTONES-->
                <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="editarEmpleado()"
                            v-if="$route.params.id"
                            >Guardar</vs-button
                        >
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardarEmpleado()"
                            v-else
                            >Guardar</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            @click="cancelar()"
                            >Cancelar</vs-button
                        >
                    </div>
            </vs-tab>
        </vs-tabs>
    </vx-card>
</template>

<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
import VueUploadMultipleImage from "vue-upload-multiple-image";
const axios = require("axios");
const $ = require("jquery");
//import { FormFilePlugin } from 'bootstrap-vue';
import moment from "moment";
moment.locale('es');  

export default {
    data() {
        return {
            Tabnav: false,
            tab1e: "",
            infobasica: "",

            activePrompt3: false,
            cuentaarray3: [],
            ctacontable: "",
            idContable: "",
            contenidocuenta:[],
            buscar: "",
            contenido: [],
            i18nbuscar: this.$t("i18nbuscar"),
            agregar_campo: "",
            //tabla empleado
            tipo_dni: "",
            dni: "",
            primer_nombre: "",
            segundo_nombre: "",
            apellido_paterno: "",
            apellido_materno: "",
            fecha_nacimiento: "",
            edad: "",
            event_at: new Date(),
            foto: "",
            lugar_nacimiento: "",
            lugar_residencia:"",
            nacionalidad: "",
            estado_civil: "",
            sexo: "",
            direccion_residencia: "",
            telefono: "",
            celular: "",
            email: "",
            tipo_sangre: "",
            profesion: "",
            discapacidad: "",
            discap_porcentaje: "",
            otra_discap: "",
            tipo_iden_discap: "",
            num_iden_discap: "",
            num_iess: "",
            num_libreta_militar: "",
            banco: "",
            tipo_cuenta: "",
            num_cuenta: "",
            cargas:"",
            num_cargas: "",
            estado: 0,
            observaciones: "",
            nombre_global: "",
            apellido_global: "",
            contacto_nombre:"",
            contacto_parentezco:"",
            contacto_telefono:"",
            //campos segunda tabla cargo-empleado
            id_emp_cargo: "",
            fecha_ingreso: "",
            fecha_salida: "",
            tipo_horario: "",
            tipo_contrato: "",
            bonos: "",
            aporte_iess: "",
            fondo_reserva: "",
            decimo_tercero: "",
            decimo_cuarto: "",
            cuenta_contable: "",
            observaciones_dos: "",
            idcargo: "",
            idgrupo: "",

            //campos tabla cargas-empleado
            car_dni: "",
            car_nombre: "",
            car_fecha_nacimiento: "",
            car_edad: "",
            car_ocupacion: "",
            car_parentezco: "",
            car_discapacidad: "",
            car_discap_porcentaje: "",
            idempleado: "",
            valorcargas:[/*{
                id_carga:"",
                car_dni: "",
                car_nombre: "",
                car_fecha_nacimiento: "",
                car_edad: "",
                car_ocupacion: "",
                car_parentezco: "",
                car_discapacidad: "",
                car_discap_porcentaje: "",
                car_documento_validez: "",
                documeto_carga:""
            }*/],

            //campos tabla documento-empleado
            id_docu_emp: "",
            doc_url: "",
            doc_estado: "",
            iddocu: "",
            idemple: "",
            valordocumentos:[{
                id_docu_emp:null,
                doc_url: "",
                documento:[],
                doc_estado: "",
                id_documento: "",
            }],

            //campos tabla calendario-empleado
            id_calen_emp: "",
            fecha_inicio: "",
            fecha_fin: "",
            razon: "",
            idemplead: "",

            //campos desde base
            departamento: "",
            area_trabajo: "",
            cargo: "",
            sueldo: "",
            doc_descripcion: "",
            provincia: "",
            canton: "",
            parroquia: "",
            id_canton:"",

            //object
            imagen: "",
            imagenprevisualizar: [],
            recuperaimagen: 0,
            imagenrecupera: [],
            prueba_imagen:"",
            //object file

            archivoprevisualizar: [],
            recuperaarchivo: 0,
            archivorecupera: [],

            //otras variables
            tipodiscap: 0,

            //arrays

            provincias2: [],
            ciudades2: [],
            parroquias2: [],
            banco2: [],
            nacionalidad2: [],
            grupo_ocupacional2: [],
            departamento2: [],
            area2: [],
            cargo2: [],
            anio:new Date(),
            doc_descripcion2: [],
            configdateTimePicker: {
                locale: SpanishLocale,
                //minDate:new Date('1950-12-31'),
                //maxDate: new Date('2008-12-31')
                
            },

            ident_discap_array: [
                { text: "Carnet CONADIS", value: 1 },
                { text: "Otro", value: 2 }
            ],
            tipo_ident_array: [
                { text: "Cédula", value: 1 },
                { text: "Pasaporte", value: 2 }
            ],

            discapacidad_array: [
                { text: "Ninguna", value: 1 },
                { text: "Visual", value: 2 },
                { text: "Física", value: 3 },
                { text: "Auditiva", value: 4 },
                { text: "Mental", value: 5 },
                { text: "Verbal", value: 6 },
                { text: "Otro", value: 7 }
            ],

            tipo_sangre_array: [
                { text: "A+", value: 1 },
                { text: "A-", value: 2 },
                { text: "B+", value: 3 },
                { text: "B-", value: 4 },
                { text: "O+", value: 5 },
                { text: "O-", value: 6 },
                { text: "AB+", value: 7 },
                { text: "AB-", value: 8 }
            ],

            estado_civil_array: [
                { text: "Soltero", value: 1 },
                { text: "Casado", value: 2 },
                { text: "Viudo", value: 3 },
                { text: "Divorciado", value: 4 },
                { text: "Unión Libre", value: 5 },
                { text: "Unión de Hecho", value: 6 }
            ],
            decision: [
                { text: "No", value: 1 },
                { text: "Sì", value: 2 }
            ],
            sexo_array: [
                { text: "Masculino", value: 1 },
                { text: "Femenino", value: 2 }
            ],
            estado_array: [
                { text: "Activo", value: 1 },
                { text: "Inactivo", value: 2 }
            ],
            estado_dos_array: [
                { text: "Activo", value: 1 },
                { text: "Inactivo", value: 2 }
            ],
            parentezco_array: [
                { text: "Hermano/a", value: 1 },
                { text: "Tío/a", value: 2 },
                { text: "Sobrino/a", value: 3 },
                { text: "Hijo/a", value: 4 },
                { text: "Abuelo/a", value: 5 },
                { text: "Primo/a", value: 6 },
                { text: "Esposo/a", value: 7 }
            ],
            tipo_cuenta_array: [
                { text: "AHORROS", value: 1 },
                { text: "CORRIENTE", value: 2 }
            ],
            carga_array: [
                { text: "Si", value: 1 },
                { text: "No", value: 0 }
            ],
            porcentaje_array: [
                { text: "10%", value: 1 },
                { text: "50%", value: 2 }
            ],

            //arrays segunda tabla
            tipo_horario_array: [
                { text: "Jornada Completa", value: 1 },
                { text: "Diurno", value: 2 },
                { text: "Vespertino", value: 3 },
                { text: "Nocturno", value: 4 }
            ],
            tipo_contrato_array: [
                { text: "Indefinido", value: 1 },
                { text: "Por horas", value: 2 },
                { text: "Definido", value: 3 },
                { text: "Obra Cierta", value: 4 }
            ],
            aporte_iess_array: [
                { text: "No", value: 1 },
                { text: "Sì", value: 2 }
            ],
            fondo_reserva_array: [
                { text: "No", value: 1 },
                { text: "Sì", value: 2 }
            ],
            decimo_tercero_array: [
                { text: "No", value: 1 },
                { text: "Sì", value: 2 }
            ],
            decimo_cuarto_array: [
                { text: "No", value: 1 },
                { text: "Sì", value: 2 }
            ],

            //
            car_parentezco_array: [
                { text: "Hermano/a", value: 1 },
                { text: "Tío/a", value: 2 },
                { text: "Sobrino/a", value: 3 },
                { text: "Hijo/a", value: 4 },
                { text: "Abuelo/a", value: 5 },
                { text: "Primo/a", value: 6 },
                { text: "Conjugue", value: 7 },
                { text: "Padre", value: 8 },
                { text: "Madre", value: 9 },
            ],
            car_tipo_dni_array:[
                { text: "Cedula", value: 1 },
                { text: "Pasaporte", value: 2 }
            ],
            car_discapacidad_array: [
                { text: "Ninguna", value: 1 },
                { text: "Visual", value: 2 },
                { text: "Física", value: 3 },
                { text: "Auditiva", value: 4 },
                { text: "Mental", value: 5 },
                { text: "Verbal", value: 6 },
                { text: "Otro", value: 7 },
            ],

            //arrays para base
            departamento_array: [
                { text: "Sistemas", value: 1 },
                { text: "Financiero", value: 2 }
            ],
            area_trabajo_array: [
                { text: "Infraestructura", value: 1 },
                { text: "Contabilidad", value: 2 }
            ],
            cargo_array: [
                { text: "Técnico Mantenimeinto", value: 1 },
                { text: "Técnico Hardware", value: 2 }
            ],
            doc_descripcion_array: [
                { text: "Acta de Matrimonio", value: 1 },
                { text: "Certificado Médico", value: 2 }
            ],
            doc_estado_array: [
                { text: "Entregado", value: 1 },
                { text: "No Entregado", value: 2 }
            ],

            //errores
            error: 0,
            errorcedula:0,
            errorruc:0,
            errortipo_dni: [],

            errordni: [],
            errordni2:false,
            errordni3:false,
            errorprimer_nombre: [],
            errorsegundo_nombre: [],
            errorapellido_paterno: [],
            errorapellido_materno: [],
            errorfecha_nacimiento: [],
            erroredad: [],
            errorlugar_nacimiento: [],
            errornacionalidad: [],
            errorestado_civil: [],
            errorsexo: [],
            errordireccion_residencia: [],
            errorcelular: [],
            erroremail: [],
            errortipo_sangre: [],
            errorprofesion: [],
            errordiscapacidad: [],
            errordiscap_porcentaje: [],
            errortipo_iden_discap: [],
            errornum_iden_discap: [],
            errorbanco: [],
            errortipo_cuenta: [],
            errornum_cuenta: [],
            errorcargas:[],
            errornum_cargas: [],
            errorestado: [],
            errorprovincia: [],
            errorcanton: [],
            errorparroquia: [],
            errorcontacto_nombre:[],
            errorcontacto_parentezco:[],
            errorcontacto_telefono:[],
            //tabla empleado - cargo
            errorfecha_ingreso: [],
            errorfecha_salida: [],
            errortipo_horario: [],
            errortipo_contrato: [],
            erroraporte_iess: [],
            errorfondo_reserva: [],
            errordecimo_tercero: [],
            errordecimo_cuarto: [],
            errorcuenta_contable: [],
            errordepartamento: [],
            errorarea_trabajo: [],
            errorcargo: [],

            //tabla empleado - cargas
            errorcedula_cargo:0,
            errorcar_dni: [],
            errorcar_nombre: [],
            errorcar_fecha_nacimiento: [],
            //errorcar_edad: "",
            //errorcar_ocupacion: "",
            errorcar_parentezco: [],
            errorcar_discapacidad: [],
            errorcar_discap_porcentaje: "",
            
            //campos tabla calendario-empleado
            errorfecha_inicio: "",
            errorfecha_fin: "",
            errorrazon: "",
            //errores detalles de cargo
            //error para campos desde base
            errordepartamentos: "",
            errorarea_trabajos: "",
            errorcargos: "",
            errorsueldos: "",
            errordoc_descripcion: "",
            //errordoc_estado: "",
            errordoc_url:[],
            errordoc_estado:[],
            errorid_documento:[],
            pagination4: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            cantidadp4: 100000,

            contenidocargas: [],
            tabIndex: 0
        };
    },
    //importa calendario español
    components: {
        flatPickr,
        FormWizard,
        TabContent,
        Datepicker
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        documento_estado(){
            this.valordocumentos.forEach(el=>{
                if(el.doc_url){
                    el.doc_estado="Entregado";
                }else{
                    el.doc_estado="No Entregado";
                }
            })
        },
        caledad(){
            var fecha=new Date();
            var edad=0;
            var anio=0;
            var fecha1 = moment(fecha, 'DD-MM-YYYY HH:mm');//fecha1.format('YYYY-MM-DD')-
            fecha1.subtract(1, 'year');
            anio=fecha1.format('YYYY-MM-DD');
            if(!this.fecha_nacimiento){
                edad=0
            }else{
                edad=anio-this.fecha_nacimiento;
            }
            
            return edad;
        },
        calcularEdad() {
            if (
                typeof this.fecha_nacimiento != "string" &&
                this.fecha_nacimiento &&
                esNumero(this.fecha_nacimiento.getTime())
            ) {
                this.fecha_nacimiento = formatDate(this.fecha_nacimiento, "yyyy-MM-dd");
            }

            var values = this.fecha_nacimiento.split("-");
            var dia = values[2];
            var mes = values[1];
            var ano = values[0];

            // cogemos los valores actuales
            var fecha_hoy = new Date();
            var ahora_ano = fecha_hoy.getYear();
            var ahora_mes = fecha_hoy.getMonth() + 1;
            var ahora_dia = fecha_hoy.getDate();

            // realizamos el calculo
            var edad = ahora_ano + 1900 - ano;
            var anio_hoy=ahora_ano + 1900;
            if (ahora_mes < mes) {
                edad--;
            }
            if (mes == ahora_mes && ahora_dia < dia) {
                edad--;
            }
            if (edad > 1900) {
                edad -= 1900;
            }

            // calculamos los meses
            var meses = 0;

            if (ahora_mes > mes && dia > ahora_dia) meses = ahora_mes - mes - 1;
            else if (ahora_mes > mes) meses = ahora_mes - mes;
            if (ahora_mes < mes && dia < ahora_dia)
                meses = 12 - (mes - ahora_mes);
            else if (ahora_mes < mes) meses = 12 - (mes - ahora_mes + 1);
            if (ahora_mes == mes && dia > ahora_dia) meses = 11;

            // calculamos los dias
            var dias = 0;
            if (ahora_dia > dia) dias = ahora_dia - dia;
            if (ahora_dia < dia) {
                var ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
                dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
            }

            var edad_string =
                edad + " años, " + meses + " meses y " + dias + " días";
                if(!this.fecha_nacimiento){
                    this.edad=0;
                }else{
                    if(ano>=anio_hoy){
                        this.$vs.notify({
                            title: "El Año Escogido es Incorrecto",
                            text: "No puede escoger un fecha igual o mayor a la actual",
                            color: "danger"
                        });
                    }else{
                        this.edad=edad;
                    }
                    
                }
           
            //return edad_string;
        },
        
    },
    methods: {
        numcargas(){
            for(var a = 0; a < this.num_cargas; a++){
                    this.valorcargas.push({
                        id_carga:null,
                        tipo_car_dni:"",
                        car_dni: "",
                        car_nombre: "",
                        car_fecha_nacimiento: "",
                        car_edad: "",
                        car_ocupacion: "",
                        car_parentezco: "",
                        car_discapacidad: "",
                        car_discap_porcentaje: "",
                        documento_carga:[],
                        car_documento_validez:"",
                        car_tipo_discapacidad:"",
                    });
            };
        },
        agregarimagen() {
            this.recuperaimagen = 0;
            $(".seleccionarimagen").click();
        },
        seleccionarimagen(event) {
            this.recuperaimagen = 0;
            var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.webp)$/i;
            if (!allowedExtensions.exec($(".seleccionarimagen").val())) {
                this.imagen = "";
                $(".seleccionarimagen").val("");
                this.$vs.notify({
                    time: 8000,
                    color: "danger",
                    title: "Formato inválido ",
                    text: "Solo se acepta archivos imagen"
                });
            } else {
                var tam = parseInt(event.target.files[0].size / 1024);
                if (tam > 1024) {
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
        calcularEdadCarga(index){
            if (
                typeof this.valorcargas[index].car_fecha_nacimiento != "string" &&
                this.valorcargas[index].car_fecha_nacimiento &&
                esNumero(this.valorcargas[index].car_fecha_nacimiento.getTime())
            ) {
                this.valorcargas[index].car_fecha_nacimiento = formatDate(this.valorcargas[index].car_fecha_nacimiento, "yyyy-MM-dd");
            }

            var values = this.valorcargas[index].car_fecha_nacimiento.split("-");
            var dia = values[2];
            var mes = values[1];
            var ano = values[0];

            // cogemos los valores actuales
            var fecha_hoy = new Date();
            var ahora_ano = fecha_hoy.getYear();
            var ahora_mes = fecha_hoy.getMonth() + 1;
            var ahora_dia = fecha_hoy.getDate();

            // realizamos el calculo
            var edad = ahora_ano + 1900 - ano;
            var anio_hoy=ahora_ano + 1900;
            if (ahora_mes < mes) {
                edad--;
            }
            if (mes == ahora_mes && ahora_dia < dia) {
                edad--;
            }
            if (edad > 1900) {
                edad -= 1900;
            }

            // calculamos los meses
            var meses = 0;

            if (ahora_mes > mes && dia > ahora_dia) meses = ahora_mes - mes - 1;
            else if (ahora_mes > mes) meses = ahora_mes - mes;
            if (ahora_mes < mes && dia < ahora_dia)
                meses = 12 - (mes - ahora_mes);
            else if (ahora_mes < mes) meses = 12 - (mes - ahora_mes + 1);
            if (ahora_mes == mes && dia > ahora_dia) meses = 11;

            // calculamos los dias
            var dias = 0;
            if (ahora_dia > dia) dias = ahora_dia - dia;
            if (ahora_dia < dia) {
                var ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
                dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
            }

            var edad_string =
                edad + " años, " + meses + " meses y " + dias + " días";
                if(!this.valorcargas[index].car_fecha_nacimiento){
                    this.valorcargas[index].car_edad=0;
                }else{
                    if(ano>anio_hoy){
                        this.$vs.notify({
                            title: "El Año Escogido es Incorrecto",
                            text: "No puede escoger un fecha  mayor a la actual",
                            color: "danger"
                        });
                    }else{
                        this.valorcargas[index].car_edad=edad;
                    }
                    
                }
            /*var regex = /(\d+)/g;
            var anios=0;
            this.valorcargas.forEach(el =>{
                if(el.car_fecha_nacimiento){
                    //el.car_edad = (moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex);
                    //anios= (moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex);
                    //el.car_edad = (el.car_edad).toString();
                    if(((moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex))!=null && ((moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex))!=19)
                    {
                      el.car_edad = ((moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex)).toString();  
                    }else{
                        if((moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex)===19){
                            el.car_edad = "1";
                        }else{
                            el.car_edad = ((moment(el.car_fecha_nacimiento, "YYYYMMDD").fromNow()).match(regex)).toString();
                        }
                    }
                    console.log(+"fecha");

                }
            })*/
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
        agregardocmento(index){
            $(".seleccionardocmento"+index).click();
        },
        seleccionardocmento (event) {
            var allowedExtensions = /(.*)$/i;
            if(!allowedExtensions.exec($(".seleccionardocmento").val())){
            $(".seleccionardocmento").val(""); 
            this.$vs.notify({
                time: 8000,
                color: "danger",
                title: "Formato inválido ",
                text: "Solo se acepta archivos p12"
            });
            }else{
                
                this.valorcargas[event.srcElement.id].documento_carga = event.target.files[0];
                //console.log("hola"+this.valorcargas[event.srcElement.id].documento_carga);
                this.valorcargas[event.srcElement.id].car_documento_validez = event.target.files[0].name;
                //console.log(event.srcElement.id);
                console.log(this.valorcargas);
            }
        },
        eliminardocmento (index) {
            this.valorcargas[index].documento_carga ="";
            this.valorcargas[index].car_documento_validez ="";
        },
        agregarp12(index){
        $(".seleccionarp12"+index).click();
        },
        seleccionarp12 (event) {
            var allowedExtensions = /(.*)$/i;
            if(!allowedExtensions.exec($(".seleccionarp12").val())){
            $(".seleccionarp12").val(""); 
            this.$vs.notify({
                time: 8000,
                color: "danger",
                title: "Formato inválido ",
                text: "Solo se acepta archivos p12"
            });
            }else{
                //console.log(event.srcElement.id);
                //console.log(this.valordocumentos);
                this.valordocumentos[event.srcElement.id].documento = event.target.files[0];
                //console.log("hola"+this.valordocumentos[event.srcElement.id].documento);
                this.valordocumentos[event.srcElement.id].doc_url = event.target.files[0].name;
                console.log(this.valordocumentos);
            }
        },
        eliminarp12 (index) {
            this.valordocumentos[index].documento="";
            this.valordocumentos[index].doc_url="";
        },
        borrarDocumento(index){
            //console.log(index+"hola borr");
            this.valordocumentos.splice(index, 1);
        },
        llamar() {
            if (this.validarE()) {
                this.tabIndex = this.tabIndex - 3;
                this.$vs.notify({
                    title: "Faltan Campos",
                    text: "Revise los campos que le faltan",
                    color: "danger"
                });
                return;
            } else {
                this.tabIndex = 1;
                alert("bien");
            }
        },
        llamar2() {
            if (!this.observaciones_dos) {
                this.$vs.notify({
                    title: "Faltan Campos",
                    text: "Revise los campos que le faltan",
                    color: "danger"
                });
                this.tabIndex = this.tabIndex - 2;
            } else {
                this.tabIndex = 1;
                alert("bien");
            }
        },
        subir() {
            alert("sdfsdf");
        },
        /*abrirfile() {
            $(".filepre").click();
        },*/
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable"
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page +
                // "&buscar=" +
                // buscar
                ;
            axios
                .get(url,{
                    params:{
                        empresa:this.usuario.id_empresa,
                        buscar:buscar
                    }
                })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        handleSelected3(tr) {
            (this.ctacontable = `${tr.nomcta}`),
                (this.idContable = `${tr.id_plan_cuentas}`),
                (this.activePrompt3 = false);
        },
        agregarCarga(){
            if(this.valorcargas.length<4){
                this.valorcargas.push(
                    {
                        id_carga:null,
                        car_dni: "",
                        car_nombre: "",
                        car_fecha_nacimiento: "",
                        car_edad: "",
                        car_ocupacion: "",
                        car_parentezco: "",
                        car_discapacidad: "",
                        car_discap_porcentaje: "",
                        car_tipo_discapacidad:""
                    },
                );
           }
        },
        agregarDocumento(){
            this.valordocumentos.push(
                    {
                        id_docu_emp:null,
                        doc_url: "",
                        documento:"",
                        doc_estado: "",
                        id_documento: "",
                    },
                );
        },
        cancelar() {
            this.$router.push("/nomina/empleados").catch(() => {});
            //this.$router.push(JobInfo).catch(() => {})
        },
        successUpload() {
            this.$vs.notify({
                color: "success",
                title: "Upload Success",
                text: "Lorem ipsum dolor sit amet, consectetur"
            });
        },
        guardarEmpleado() {
            if (this.validarE()) {
                return;
            }
            let formData = new FormData();
            //tabla empleado
            if (this.tipo_dni != null)
                formData.append("tipo_dni", this.tipo_dni);
            if (this.dni != null) formData.append("dni", this.dni);
            if (this.primer_nombre != null)
                formData.append("primer_nombre", this.primer_nombre);
            if (this.segundo_nombre != null)
                formData.append("segundo_nombre", this.segundo_nombre);
            if (this.apellido_paterno != null)
                formData.append("apellido_paterno", this.apellido_paterno);
            if (this.apellido_materno != null)
                formData.append("apellido_materno", this.apellido_materno);
            if (this.fecha_nacimiento != null)
                formData.append("fecha_nacimiento", this.fecha_nacimiento);
            if (this.edad != null)
                formData.append("edad", this.edad);
            if (this.provincia != null)
                formData.append("id_provincia", this.provincia);
            if (this.canton != null)
                formData.append("id_canton", this.canton);
            if (this.lugar_nacimiento != null)
                formData.append("lugar_nacimiento", this.lugar_nacimiento);
            if(this.lugar_residencia){
                formData.append("lugar_residencia", this.lugar_residencia);
            }


            if (this.contacto_nombre != null)
                formData.append("contacto_nombre", this.contacto_nombre);
            if (this.contacto_parentezco != null)
                formData.append("contacto_parentezco", this.contacto_parentezco);
            if(this.contacto_telefono){
                formData.append("contacto_telefono", this.contacto_telefono);
            }
            // contacto_nombre:"",
            // contacto_parentezco:"",
            // contacto_telefono:"",    
            //tabla empleado - cargo
            if (this.nacionalidad != null)
                formData.append("nacionalidad", this.nacionalidad);
            if (this.estado_civil != null)
                formData.append("estado_civil", this.estado_civil);
            if (this.sexo != null) formData.append("sexo", this.sexo);
            if (this.direccion_residencia != null)
                formData.append(
                    "direccion_residencia",
                    this.direccion_residencia
                );
            if (this.telefono != null)
                formData.append("telefono", this.telefono);
            if (this.celular != null) formData.append("celular", this.celular);
            if (this.email != null) formData.append("email", this.email);
            if (this.tipo_sangre != null)
                formData.append("tipo_sangre", this.tipo_sangre);
            if (this.profesion != null)
                formData.append("profesion", this.profesion);
            if (this.discapacidad != null)
                formData.append("discapacidad", this.discapacidad);
            if (this.otra_discap != null)
                formData.append("otra_discap", this.otra_discap);
            if (this.discap_porcentaje != null)
                formData.append("discap_porcentaje", this.discap_porcentaje);
            if (this.tipo_iden_discap != null)
                formData.append("tipo_iden_discap", this.tipo_iden_discap);
            if (this.num_iden_discap != null)
                formData.append("num_iden_discap", this.num_iden_discap);
            //if (this.num_iess != null)
              
              //formData.append("num_iess", this.num_iess);
            /*if (this.num_libreta_militar != null)
                formData.append(
                    "num_libreta_militar",
                    this.num_libreta_militar
                );*/
            if (this.banco != null) formData.append("banco", this.banco);
            if (this.tipo_cuenta != null)
                formData.append("tipo_cuenta", this.tipo_cuenta);
            if (this.num_cuenta != null)
                formData.append("num_cuenta", this.num_cuenta);
            if (this.cargas != null)
                formData.append("carga", this.cargas);
            if (this.num_cargas != null)
                formData.append("num_cargas", this.num_cargas);
            if (this.estado != null) formData.append("estado", this.estado);
            if (this.observaciones != null)
                formData.append("observaciones_empl", this.observaciones);
            //
            if (this.fecha_ingreso != null)
                formData.append("fecha_ingreso", this.fecha_ingreso);
            if (this.fecha_salida != null)
                formData.append("fecha_salida", this.fecha_salida);
            if (this.tipo_horario != null)
                formData.append("tipo_horario", this.tipo_horario);
            if (this.tipo_contrato != null)
                formData.append("tipo_contrato", this.tipo_contrato);
            if (this.bonos != null) formData.append("bonos", this.bonos);
            if (this.aporte_iess != null)
                formData.append("aporte_iess", this.aporte_iess);
            if (this.fondo_reserva != null)
                formData.append("fondo_reserva", this.fondo_reserva);
            if (this.decimo_tercero != null)
                formData.append("decimo_tercero", this.decimo_tercero);
            if (this.decimo_cuarto != null)
                formData.append("decimo_cuarto", this.decimo_cuarto);
            if (this.idContable != null)
                formData.append("cuenta_contable", this.idContable);
            if (this.observaciones_dos != null)
                formData.append("observaciones_dos", this.observaciones_dos);
            if (this.idcargo != null) formData.append("id_cargo", this.idcargo);
            if (this.idgrupo != null) formData.append("id_grupo", this.idgrupo);
            if (this.area_trabajo != null) formData.append("id_area", this.area_trabajo);
                formData.append("id_empresa", this.usuario.id_empresa);
                formData.append("ucrea",this.usuario.id);
            if (this.departamento != null)
                formData.append("departamento", this.departamento);
            if (this.imagen != null)
                formData.append("file_imagen", this.imagen);
            axios
                .post("/api/empleado/agregar",formData)
                .then(res => {
                    if(res.data=="existe dni"){
                        this.$vs.notify({
                            text: "Ya existe este Empleado",
                            color: "danger"
                        });
                        return;
                    }
                    this.guardarCargas(res.data);
                    this.guardarDocumentos(res.data);
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito.",
                        color: "success"
                    });
                    this.$router.push("/nomina/empleados");
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Registro no Realizado",
                        text: "Registro no se guardó, revise los campos.",
                        color: "danger"
                    });
                });
        },
        guardarCargas(id){
                axios.post("/api/empleadocarga/agregar",{
                    id_empleado:id,
                    provds:this.valorcargas
                }).then(res=>{
                    
                    this.solodocumento_carga(res.data);
                    console.log("guardado cargas");
                }).catch(err =>{
                    console.log("ERROR guardado cargas");
                })

        
        },
        solodocumento_carga(id){
            let formData = new FormData();
            this.valorcargas.forEach((el,index) => {
                formData.append(`documento_carga${index}`, el.documento_carga);
            });
            formData.append("cantidad", this.valorcargas.length);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("id", id);
            axios.post("/api/empleadocarga/agregararchivos",formData)
            .then(res=>{
                  console.log("guardado archivos carga");  
            }).catch(err =>{
                    console.log("ERROR guardado archivo cargas");
                })
            
        },
        guardarDocumentos(id){
            axios.post("/api/empleadodocumento/agregar",{
                    id_empleado:id,
                    provds:this.valordocumentos
                }).then(res=>{
                    this.solodocumentos(res.data);
                }).catch(err =>{
                    console.log("ERROR guardado archivo cargas");
                })
        },
        solodocumentos(id){
            let formData = new FormData();
            this.valordocumentos.forEach((el,index) => {
                formData.append(`documento${index}`, el.documento);
            });
            formData.append("cantidad", this.valordocumentos.length);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("id", id);
            axios.post("/api/empleadodocumento/agregararchivos",formData)
            .then(res=>{
                  console.log("guardado archivos");  
            }).catch(err =>{
                console.log("Error guardado archivos"); 
            });
            
        },
        listarEmpleado(id) {
            axios
                .put("/api/empleado/verempleado", {
                    id: id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    let data = res.data[0];
                    this.tipo_dni = data.tipo_dni;
                    this.dni = data.dni;
                    this.primer_nombre = data.primer_nombre;
                    this.segundo_nombre = data.segundo_nombre;
                    this.apellido_paterno = data.apellido_paterno;
                    this.apellido_materno = data.apellido_materno;
                    this.fecha_nacimiento = data.fecha_nacimiento;
                    this.edad = data.edad;
                    this.imagen = data.foto;
                    this.prueba_imagen= data.foto;
                    this.provincia=data.id_provincia;
                    this.canton=data.id_ciudad;
                    this.lugar_nacimiento = data.id_parroquia;
                    this.nacionalidad = data.id_nacionalidad;
                    this.lugar_residencia=data.lugar_residencia;
                    this.estado_civil = data.estado_civil;
                    this.sexo = data.sexo;
                    this.direccion_residencia = data.direccion_residencia;
                    this.telefono = data.telefono;
                    this.celular = data.celular;
                    this.email = data.email;
                    this.tipo_sangre = data.tipo_sangre;
                    this.profesion = data.profesion;
                    this.discapacidad = data.discapacidad;
                    this.discap_porcentaje = data.discap_porcentaje;
                    this.tipo_iden_discap = data.tipo_iden_discap;
                    this.num_iden_discap = data.num_iden_discap;
                    //this.num_iess = data.num_iess;
                    //this.num_libreta_militar = data.num_libreta_militar;
                    this.banco = data.id_banco;
                    this.tipo_cuenta = data.tipo_cuenta;
                    this.num_cuenta = data.num_cuenta;
                    this.cargas=data.carga;
                    this.num_cargas = data.num_cargas;
                    this.estado = data.estado;
                    this.observaciones = data.observaciones;
                    this.contacto_nombre = data.contacto_nombre;
                    this.contacto_parentezco = data.contacto_parentezco;
                    this.contacto_telefono = data.contacto_telefono;
                    //tabla empleado - cargo
                    this.fecha_ingreso = data.fecha_ingreso;
                    this.fecha_salida = data.fecha_salida;
                    this.tipo_horario = data.tipo_horario;
                    this.tipo_contrato = data.tipo_contrato;
                    this.bonos = data.sueldo;
                    this.aporte_iess = data.aporte_iess;
                    this.fondo_reserva = data.fondo_reserva;
                    this.decimo_tercero = data.decimo_tercero;
                    this.decimo_cuarto = data.decimo_cuarto;
                    this.idContable = data.id_plan_cuentas;
                    this.ctacontable=data.cuenta_resultado;
                    this.observaciones_dos = data.observacion_cargo;
                    this.departamento = data.id_departamento;
                    this.idcargo = data.id_cargo;
                    this.idgrupo = data.id_grupo;
                    this.area_trabajo=data.id_area_trabajo;
                    //tabla empleado - cargas
                    /*this.id_provincia=data.id_carga;
                    this.car_dni = data.car_dni;
                    this.car_nombre = data.car_nombre;
                    this.car_fecha_nacimiento = data.car_fecha_nacimiento;
                    this.car_edad = data.car_edad;
                    this.car_ocupacion = data.car_ocupacion;
                    this.car_parentezco = data.car_parentezco;
                    this.car_discapacidad = data.car_discapacidad;
                    this.car_discap_porcentaje = data.car_discap_porcentaje;
                    //tabla empleado - documento
                    this.doc_url = data.doc_url;
                    this.doc_estado = data.doc_estado;
                    */
                    if (this.imagen) {
                            this.recuperaimagen = 1;
                        }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        listarCargas(){
            if(this.$route.params.id){
                var idrecupera = this.$route.params.id;
            }else{
                var idrecupera = 0;
            }
      
            var url = "/api/obtenercargas/" +idrecupera;
            axios
                .get(url)
                .then(res => {
                this.valorcargas = res.data;
                //console.log(this.contenidopr);
                })
                .catch(err => {
                //console.log(err);
                });
        },
        listarDocumentos(){
            if(this.$route.params.id){
                var idrecupera = this.$route.params.id;
            }else{
                var idrecupera = 0;
            }
      
      var url = "/api/obtenerdocumentos/" +idrecupera;
      axios
        .get(url)
        .then(res => {
          this.valordocumentos = res.data;
          //console.log(this.contenidopr);
        })
        .catch(err => {
          //console.log(err);
        });
        },
        editarEmpleado() {
            if (this.validarE()) {
                return;
            }
            let formData = new FormData();
            //tabla empleado
            formData.append("id", this.$route.params.id);
            if (this.tipo_dni != null)
                formData.append("tipo_dni", this.tipo_dni);
            if (this.dni != null) formData.append("dni", this.dni);
            if (this.primer_nombre != null)
                formData.append("primer_nombre", this.primer_nombre);
            if (this.segundo_nombre != null)
                formData.append("segundo_nombre", this.segundo_nombre);
            if (this.apellido_paterno != null)
                formData.append("apellido_paterno", this.apellido_paterno);
            if (this.apellido_materno != null)
                formData.append("apellido_materno", this.apellido_materno);
            if (this.fecha_nacimiento != null)
                formData.append("fecha_nacimiento", this.fecha_nacimiento);
            if (this.edad != null)
                formData.append("edad", this.edad);
            if (this.recuperaimagen != null)
                formData.append("recuperaimagen", this.recuperaimagen);
            if (this.provincia != null)
                formData.append("id_provincia", this.provincia);
            if (this.canton != null)
                formData.append("id_canton", this.canton);
            if (this.lugar_nacimiento != null)
                formData.append("lugar_nacimiento", this.lugar_nacimiento);
            if (this.contacto_nombre != null)
                formData.append("contacto_nombre", this.contacto_nombre);
            if (this.contacto_parentezco != null)
                formData.append("contacto_parentezco", this.contacto_parentezco);
            if(this.contacto_telefono){
                formData.append("contacto_telefono", this.contacto_telefono);
            }
            //tabla empleado - cargo
            if (this.nacionalidad != null)
                formData.append("nacionalidad", this.nacionalidad);
            if(this.lugar_residencia){
                formData.append("lugar_residencia", this.lugar_residencia);
            }
            if (this.estado_civil != null)
                formData.append("estado_civil", this.estado_civil);
            if (this.sexo != null) formData.append("sexo", this.sexo);
            if (this.direccion_residencia != null)
                formData.append(
                    "direccion_residencia",
                    this.direccion_residencia
                );
            if (this.telefono != null)
                formData.append("telefono", this.telefono);
            if (this.celular != null) formData.append("celular", this.celular);
            if (this.email != null) formData.append("email", this.email);
            if (this.tipo_sangre != null)
                formData.append("tipo_sangre", this.tipo_sangre);
            if (this.profesion != null)
                formData.append("profesion", this.profesion);
            if (this.discapacidad != null)
                formData.append("discapacidad", this.discapacidad);
            if (this.otra_discap != null)
                formData.append("otra_discap", this.otra_discap);
            if (this.discap_porcentaje != null)
                formData.append("discap_porcentaje", this.discap_porcentaje);
            if (this.tipo_iden_discap != null)
                formData.append("tipo_iden_discap", this.tipo_iden_discap);
            if (this.num_iden_discap != null)
                formData.append("num_iden_discap", this.num_iden_discap);
            /*if (this.num_iess != null)
                formData.append("num_iess", this.num_iess);
            if (this.num_libreta_militar != null)
                formData.append(
                    "num_libreta_militar",
                    this.num_libreta_militar
                );*/
            if (this.banco != null) formData.append("banco", this.banco);
            if (this.tipo_cuenta != null)
                formData.append("tipo_cuenta", this.tipo_cuenta);
            if (this.num_cuenta != null)
                formData.append("num_cuenta", this.num_cuenta);
            if (this.cargas != null)
                formData.append("carga", this.cargas);
            if (this.num_cargas != null)
                formData.append("num_cargas", this.num_cargas);
            if (this.estado != null) formData.append("estado", this.estado);
            if (this.observaciones != null)
                formData.append("observaciones_empl", this.observaciones);
            //
            if (this.fecha_ingreso != null)
                formData.append("fecha_ingreso", this.fecha_ingreso);
            if (this.fecha_salida != null)
                formData.append("fecha_salida", this.fecha_salida);
            if (this.tipo_horario != null)
                formData.append("tipo_horario", this.tipo_horario);
            if (this.tipo_contrato != null)
                formData.append("tipo_contrato", this.tipo_contrato);
            if (this.bonos != null) formData.append("bonos", this.bonos);
            if (this.aporte_iess != null)
                formData.append("aporte_iess", this.aporte_iess);
            if (this.fondo_reserva != null)
                formData.append("fondo_reserva", this.fondo_reserva);
            if (this.decimo_tercero != null)
                formData.append("decimo_tercero", this.decimo_tercero);
            if (this.decimo_cuarto != null)
                formData.append("decimo_cuarto", this.decimo_cuarto);
            if (this.idContable != null)
                formData.append("cuenta_contable", this.idContable);
            if (this.observaciones_dos != null)
                formData.append("observaciones_dos", this.observaciones_dos);
            if (this.idcargo != null) formData.append("id_cargo", this.idcargo);
            if (this.idgrupo != null) formData.append("id_grupo", this.idgrupo);
            if (this.area_trabajo != null) formData.append("id_area", this.area_trabajo);
            if (this.usuario.id_empresa != null)
                formData.append("id_empresa", this.usuario.id_empresa);
            if (this.departamento != null)
                formData.append("departamento", this.departamento);
                formData.append("umodifica",this.usuario.id);
            if (this.imagen != null)
                formData.append("file_imagen", this.imagen);
            axios
                .post("/api/empleado/editar/", formData)
                .then(res => {
                    if(res.data=="existe dni"){
                        this.$vs.notify({
                            text: "Ya existe este Empleado",
                            color: "danger"
                        });
                        return;
                    }
                    this.editarCargas(res.data);
                    this.editarDocumentos(res.data);
                    this.$vs.notify({
                        text: "Registro actualizado exitosamente",
                        color: "primary"
                    });
                    this.$router.push("/nomina/empleados");
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editarCargas(id){
            axios.put("/api/empleadocargo/editar",{
                id_empleado:id,
                provds: this.valorcargas
            }).then(res=>{
                this.solodocumento_carga(res.data);
                console.log("Actualizado documento cargas"+res);
            }).catch(err => {
                console.log("Error al actualizar carga"+err);
            });
        },
        editarDocumentos(id){
            axios.put("/api/empleadodocumento/editar",{
                    id_empleado:id,
                    provds:this.valordocumentos
                }).then(res=>{
                    this.solodocumentos(res.data);
                    console.log("Actualizado dicumentos"+res);
                }).catch(err => {
                console.log("Error al actualizar documentos"+err);
            });
        },
        //metodos para segunda tabla
        
        /*
    listarCargo(id) {
      axios.put("/api/cargo/abrir", { id: id }).then(res => {
        let data = res.data[0];
        this.id_empleado = data.id_empleado;
        this.apellido_paterno = data.apellido_paterno;
      });
    },
    */
        editarCargo() {
            /*if (this.validarC()) {
        return;
      }*/
            axios
                .put("/api/cargo/editar", {
                    id: this.$route.params.id,
                    //campos segunda tabla - empleado-cargo
                    fecha_ingreso: this.fecha_ingreso,
                    fecha_salida: this.fecha_salida,
                    tipo_horario: this.tipo_horario,
                    tipo_contrato: this.tipo_contrato,
                    bonos: this.bonos,
                    aporte_iess: this.aporte_iess,
                    fondo_reserva: this.fondo_reserva,
                    decimo_tercero: this.decimo_tercero,
                    decimo_cuarto: this.decimo_cuarto,
                    cuenta_contable: this.cuenta_contable,
                    observaciones: this.observaciones_dos,
                    id_empleado: this.id_empleado,
                    idcargo: this.idcargo,
                    idgrupo: this.idgrupo
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Actualizado",
                        text: "Registro actualizado con éxito",
                        color: "success"
                    });
                    this.$router.push("/nomina/empleados");
                })
                .catch(err => {
                    console.log(err);
                });
        },
        //metodos para tercera tabla
        
        listarCarga(id) {
            axios.put("/api/carga/abrir", { id: id }).then(res => {
                let data = res.data[0];
                this.id_empleado = data.id_empleado;
            });
        },
        /*listarCargas(page4, buscar4) {
      let me = this;
      var url =
        "/api/carga?page=" +
        page4 +
        "&buscar=" +
        buscar4 +
        "&cantidadp=" +
        cantidadp4;
      axios
        .get(url)
        .then(function(response) {
          var respuesta = response.data;
          me.contenidocargas = respuesta.recupera.data;
          me.pagination4 = respuesta.pagination;
          if (cantidadp4 > me.pagination4.total) {
            cantidadp4 = me.pagination4.total;
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },*/
        editarCarga() {
            /*if (this.validar()) {
        return;
      }*/
            axios
                .put("/api/carga/editar", {
                    id: this.$route.params.id,
                    //campos segunda tabla - empleado-cargo
                    car_dni: this.car_dni,
                    car_nombre: this.car_nombre,
                    car_fecha_nacimiento: this.car_fecha_nacimiento,
                    car_edad: this.car_edad,
                    car_ocupacion: this.car_ocupacion,
                    car_discapacidad: this.car_discapacidad,
                    car_discap_porcentaje: this.car_discap_porcentaje,
                    idempleado: this.id_empleado
                })
                .then(res => {
                    this.$vs.notify({
                        text: "Registro actualizado exitosamente",
                        color: "primary"
                    });
                    this.$router.push("/nomina/empleados");
                })
                .catch(err => {
                    console.log(err);
                });
        },
        eliminarCarga(id) {
            axios
                //Envia id
                .delete("/api/empleado/eliminar/" + id);
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: "¿Desea Elimnar este registro?",
                accept: this.acceptAlert
            });
        },
        //metodos para cuarta tabla - documentacion
        /*listarDocumento(id) {
            axios.put("/api/documento/abrir", { id: id }).then(res => {
                let data = res.data[0];
                this.id_empleado = data.id_empleado;
                this.apellido_paterno = data.apellido_paterno;
            });
        },*/
        
        //validaciones
        validarE() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.errortipo_dni = [];
            //this.errordni = [];
            this.errorprimer_nombre = [];
            this.errorsegundo_nombre = [];
            this.errorapellido_paterno = [];
            this.errorapellido_materno = [];
            this.errorfecha_nacimiento = [];
            this.erroredad = [];
            this.errorlugar_nacimiento = [];
            this.errornacionalidad = [];
            this.errorestado_civil = [];
            this.errorsexo = [];
            this.errordireccion_residencia = [];
            this.errorcelular = [];
            this.erroremail = [];
            this.errortipo_sangre = [];
            this.errorprofesion = [];
            this.errordiscapacidad = [];
            this.errordiscap_porcentaje = [];
            this.errortipo_iden_discap = [];
            this.errorbanco = [];
            this.errortipo_cuenta = [];
            this.errornum_cuenta = [];
            this.errorcargas=[];
            this.errornum_cargas = [];
            this.errorestado = [];
            this.errorprovincia = [];
            this.errorcanton = [];
            this.errorparroquia = [];
            //error detalles cargo

            this.errortipo_horario = [];
            this.errortipo_contrato = [];
            this.erroraporte_iess = [];
            this.errorfondo_reserva = [];
            this.errordecimo_tercero = [];
            this.errordecimo_cuarto = [];
            this.errorcuenta_contable = [];
            this.errordepartamento = [];
            this.errorarea_trabajo = [];
            this.errorcargo = [];
            //errores cargas
            this.errorcedula=0
            this.errorcar_dni= [];
            this.errorcar_nombre= [];
            this.errorcar_fecha_nacimiento= [];
            this.errorcar_parentezco= [];
            this.errorcar_discapacidad= [];
            //errores documentacion
            this.errordoc_url=[];
            this.errordoc_estado=[];
            this.errorid_documento=[];

            if (!this.tipo_dni) {
                this.errortipo_dni.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 1");
            }else{
                if(this.tipo_dni=="Cédula"){
                    this.validarcedula();
                    if(this.errordni2==true){
                        this.validarcedula();
                        this.error = 1;
                        console.log("Campo Obli 2");
                    }  
                }else{
                    if(!this.dni){
                        this.errordni.push("Campo obligatorio");
                    this.error=1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 3");
                    }

                }
            }
            
            if (!this.primer_nombre) {
                this.errorprimer_nombre.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 4");
            }

            // if (!this.segundo_nombre) {
            //     this.errorsegundo_nombre.push("Campo obligatorio");
            //     this.error = 1;
            //     this.tabIndex = this.tabIndex - 3;
            //     console.log("Campo Obli 5");
            // }

            if (!this.apellido_paterno) {
                this.errorapellido_paterno.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 6");
            }

            // if (!this.apellido_materno) {
            //     this.errorapellido_materno.push("Campo obligatorio");
            //     this.error = 1;
            //     this.tabIndex = this.tabIndex - 3;
            //     console.log("Campo Obli 7");
            // }

            if (!this.fecha_nacimiento) {
                this.errorfecha_nacimiento.push("Campo obligatorio");
                this.error = 1;
               this.tabIndex = this.tabIndex - 3;
               console.log("Campo Obli 8");
            }

            if (!this.edad) {
                this.erroredad.push("Campo obligatorio");
                this.error = 1;
               this.tabIndex = this.tabIndex - 3;
               console.log("Campo Obli 9");
            }

            if (!this.lugar_nacimiento) {
                this.errorlugar_nacimiento.push("Campo obligatorio");
                this.error = 1;
               this.tabIndex = this.tabIndex - 3;
               console.log("Campo Obli 10");
            }

            if (!this.nacionalidad) {
                this.errornacionalidad.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 11");
            }

            if (!this.estado_civil) {
                this.errorestado_civil.push("Campo obligatorio");
                this.error = 1;
               this.tabIndex = this.tabIndex - 3;
               console.log("Campo Obli 12");
            }

            if (!this.sexo) {
                this.errorsexo.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 13");
            }

            if (!this.direccion_residencia) {
                this.errordireccion_residencia.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 14");
            }

            if (!this.celular) {
                this.errorcelular.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 15");
            }
            if(!this.email){
                this.erroremail.push("Campo Obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 16.1");
            }else{
                if (!this.validaremail(this.email)) {
                    this.erroremail.push("Email no valido");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 16.2");
                }
            }

            

            if (!this.tipo_sangre) {
                this.errortipo_sangre.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 17");
            }

            if (!this.profesion) {
                this.errorprofesion.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 18");
            }

            if (!this.discapacidad) {
                this.errordiscapacidad.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 19");
            }else{
                if(this.discapacidad!="Ninguna"){
                    if (!this.discap_porcentaje) {
                    this.errordiscap_porcentaje.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 20");
                    }
                    if (!this.tipo_iden_discap) {
                        this.errortipo_iden_discap.push("Campo obligatorio");
                        this.error = 1;
                        this.tabIndex = this.tabIndex - 3;
                        console.log("Campo Obli 21");
                    }
                }
                  
            }

            

            if (!this.banco) {
                this.errorbanco.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 22");
            }

            if (!this.tipo_cuenta) {
                this.errortipo_cuenta.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 23");
            }

            if (!this.num_cuenta) {
                this.errornum_cuenta.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 24");
            }
            if(this.cargas===""){
               this.errorcargas.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 25");
            }else{
                if(this.cargas==1){
                    if (!this.num_cargas) {
                        this.errornum_cargas.push("Campo obligatorio");
                        this.error = 1;
                        this.tabIndex = this.tabIndex - 3;
                        console.log("Campo Obli 26");
                    }else{
                        for (var i = 0; i < this.valorcargas.length; i++) {
                            this.valorcargas[i].errorcar_dni = [];
                            this.valorcargas[i].errorcar_nombre = [];
                            this.valorcargas[i].errorcar_fecha_nacimiento = [];
                            this.valorcargas[i].errorcar_parentezco = [];
                            this.valorcargas[i].errorcar_discapacidad = [];
                            if (!this.valorcargas[i].car_dni) {
                                this.valorcargas[i].errorcar_dni.push("Campo obligatorio");
                                this.error = 1;
                                this.tabIndex = this.tabIndex - 3;
                                console.log("Campo Obli 41");
                            }
                            if(!this.valorcargas[i].car_nombre){
                                this.valorcargas[i].errorcar_nombre.push("Campo obligatorio");
                                this.error = 1;
                                this.tabIndex = this.tabIndex - 3;
                                console.log("Campo Obli 42");
                            }
                            if(!this.valorcargas[i].car_fecha_nacimiento){
                                this.valorcargas[i].errorcar_fecha_nacimiento.push("Campo obligatorio");
                                this.error = 1;
                                this.tabIndex = this.tabIndex - 3;
                                console.log("Campo Obli 43");
                            }
                            if(!this.valorcargas[i].car_parentezco){
                                this.valorcargas[i].errorcar_parentezco.push("Campo obligatorio");
                                this.error = 1;
                                this.tabIndex = this.tabIndex - 3;
                                console.log("Campo Obli 44");
                            }
                            if(!this.valorcargas[i].car_discapacidad){
                                this.valorcargas[i].errorcar_discapacidad.push("Campo obligatorio");
                                this.error = 1;
                                this.tabIndex = this.tabIndex - 3;
                                console.log("Campo Obli 45");
                            }
                        }
                    }
                }
            }

            if(this.$route.params.id){
                if (!this.estado) {
                    this.errorestado.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 27");
                }else{
                    if (this.estado==="Inactivo") {
                        if(!this.fecha_salida){
                            this.errorfecha_salida.push("Campo obligatorio");
                            this.error = 1;
                            this.tabIndex = this.tabIndex - 3;
                            console.log("Campo Obli 27.2");
                        }
                    }
                }
            }
            

            if (!this.provincia) {
                this.errorprovincia.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 28");
            }

            if (!this.canton) {
                this.errorcanton.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 29");
            }

            if (!this.lugar_nacimiento) {
                this.errorparroquia.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 30");
            }
            /*
            if (
                this.errortipo_dni.length ||
                this.errordni.length ||
                this.errorprimer_nombre.length ||
                this.errorsegundo_nombre.length ||
                this.errorapellido_paterno.length ||
                this.errorapellido_materno.length ||
                this.errorfecha_nacimiento.length ||
                this.erroredad.length ||
                this.errorlugar_nacimiento.length ||
                this.errornacionalidad.length ||
                this.errorestado_civil.length ||
                this.errorsexo.length ||
                this.errordireccion_residencia.length ||
                this.errorcelular.length ||
                this.erroremail.length ||
                this.errortipo_sangre.length ||
                this.errorprofesion.length ||
                this.errordiscapacidad.length ||
                this.errordiscap_porcentaje.length ||
                this.errortipo_iden_discap.length ||
                this.errorbanco.length ||
                this.errortipo_cuenta.length ||
                this.errornum_cuenta.length ||
                this.errornum_cargas.length ||
                this.errorestado.length ||
                this.errorprovincia.length ||
                this.errorcanton.length ||
                this.errorparroquia.length
            )*/
                if (!this.departamento) {
                    //excepciones detalles cargo
                    this.errordepartamento.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 31");
                }
            

            if (!this.tipo_horario) {
                this.errortipo_horario.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 32");
            }

            if (!this.tipo_contrato) {
                this.errortipo_contrato.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 33");
            }

            if (!this.aporte_iess) {
                this.erroraporte_iess.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 34");
            }

            if (!this.fondo_reserva) {
                this.errorfondo_reserva.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 35");
            }

            if (!this.decimo_tercero) {
                this.errordecimo_tercero.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 36");
            }

            if (!this.decimo_cuarto) {
                this.errordecimo_cuarto.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 37");
            }

            /*if (!this.idContable) {
                this.errorcuenta_contable.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 38");
            }*/
            if (!this.area_trabajo) {
                this.errorarea_trabajo.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 39");
            }
            if (!this.idcargo) {
                this.errorcargo.push("Campo obligatorio");
                this.error = 1;
                this.tabIndex = this.tabIndex - 3;
                console.log("Campo Obli 40");
            }
            
            for (var i = 0; i < this.valordocumentos.length; i++) {
                this.valordocumentos[i].errordoc_url = [];
                this.valordocumentos[i].errordoc_estado = [];
                this.valordocumentos[i].errorid_documento = [];

                if (!this.valordocumentos[i].doc_url) {
                    this.valordocumentos[i].errordoc_url.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 46");
                }
                if(!this.valordocumentos[i].doc_estado){
                    this.valordocumentos[i].errordoc_estado.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 47");
                }
                if(!this.valordocumentos[i].id_documento){
                    this.valordocumentos[i].errorid_documento.push("Campo obligatorio");
                    this.error = 1;
                    this.tabIndex = this.tabIndex - 3;
                    console.log("Campo Obli 48");
                }
                
            }  
            return this.error;
        },
        
        validaremail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
           return re.test(email);
        },
        successUpload() {
            this.$vs.notify({
                color: "success",
                title: "Upload Success",
                text: "Lorem ipsum dolor sit amet, consectetur"
            });
        },
        /*listarcuenta(buscar1){
             axios
                .get(
                    "/api/selcuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscar1
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        abrirlista() {
            $(".menuescoger").show();
        },
        handleSelected(tr) {
            this.idContable = `${tr.id_plan_cuentas}`;
            this.ctacontable = `${tr.codcta}`;
        },*/
        validarcedula($event) {
            this.errorcedula = 0;
            //this.error = 0;
            this.errordni2=false;
            this.errordni = [];
            if(!this.dni){
                this.errordni.push("Campo Obligatorio");
                this.errorcedula = 1;
                this.errordni2=true;
                this.tabIndex = this.tabIndex - 3;
            }else{
                if (this.dni.length < 10) {
                this.errordni.push("Cedula invalida");
                this.errorcedula = 1;
                this.errordni2=true;
                this.tabIndex = this.tabIndex - 3;
            }
            }
            
            if (
                typeof this.dni == "string" &&
                this.dni.length == 10 &&
                /^\d+$/.test(this.dni)
            ) {
                var digitos = this.dni.split("").map(Number);
                var codigo_provincia = digitos[0] * 10 + digitos[1];

                //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

                if (
                    codigo_provincia >= 1 &&
                    (codigo_provincia <= 24 || codigo_provincia == 30)
                ) {
                    var digito_verificador = digitos.pop();

                    var digito_calculado =
                        digitos.reduce(function(
                            valorPrevio,
                            valorActual,
                            indice
                        ) {
                            return (
                                valorPrevio -
                                ((valorActual * (2 - (indice % 2))) % 9) -
                                (valorActual == 9) * 9
                            );
                        },
                        1000) % 10;
                    //return digito_calculado === digito_verificador;
                    if (digito_calculado === digito_verificador) {
                        this.errordni = [];
                    } else {
                        this.errordni.push("Cédula inválida");
                        this.errorcedula = 1;
                        this.tabIndex = this.tabIndex - 3;
                        this.errordni2=true;
                    }
                } else {
                    this.errordni.push("Cédula inválida");
                    this.errorcedula = 1;
                    this.tabIndex = this.tabIndex - 3;
                    this.errordni2=true;
                }
            }
            return this.errorcedula;
        },
        validarcedula_carga(){
            this.errorcedula_cargo = 0;
            for (var i = 0; i < this.valorcargas.length; i++) {
                
                this.valorcargas[i].errorcar_dni = [];
                 this.valorcargas[i].errorcedula2 = false;       
                if(!this.valorcargas[i].car_dni){
                    this.valorcargas[i].errorcar_dni.push("Campo Obligatorio");
                    this.errorcedula_cargo = 1;
                    this.valorcargas[i].errorcedula2 =true;
                    //this.tabIndex = this.tabIndex - 3;
                    console.log("CEdula oblioga");
                }else{
                    if (this.valorcargas[i].car_dni.length < 10) {
                    this.valorcargas[i].errorcar_dni.push("Cedula invalida");
                    this.errorcedula_cargo = 1;
                    this.valorcargas[i].errorcedula2 =true;
                    //this.tabIndex = this.tabIndex - 3;
                    console.log("Cedula invalida - 10");
                }
                }
                
                if (
                    typeof this.valorcargas[i].car_dni == "string" &&
                    this.valorcargas[i].car_dni.length == 10 &&
                    /^\d+$/.test(this.valorcargas[i].car_dni)
                ) {
                    var digitos = this.valorcargas[i].car_dni.split("").map(Number);
                    var codigo_provincia = digitos[0] * 10 + digitos[1];

                    //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

                    if (
                        codigo_provincia >= 1 &&
                        (codigo_provincia <= 24 || codigo_provincia == 30)
                    ) {
                        var digito_verificador = digitos.pop();

                        var digito_calculado =
                            digitos.reduce(function(
                                valorPrevio,
                                valorActual,
                                indice
                            ) {
                                return (
                                    valorPrevio -
                                    ((valorActual * (2 - (indice % 2))) % 9) -
                                    (valorActual == 9) * 9
                                );
                            },
                            1000) % 10;
                        //return digito_calculado === digito_verificador;
                        if (digito_calculado === digito_verificador) {
                            this.valorcargas[i].errorcar_dni = [];
                        } else {
                            this.valorcargas[i].errorcar_dni.push("Cedula invalida");
                            this.errorcedula_cargo = 1;
                            this.valorcargas[i].errorcedula2 =true;
                            //this.tabIndex = this.tabIndex - 3;
                            console.log("Cedula invalida codigo verificador");
                        }
                    } else {
                        this.valorcargas[i].errorcar_dni.push("Cedula invalida");
                        this.errorcedula_cargo = 1;
                        this.valorcargas[i].errorcedula2 =true;
                        //this.tabIndex = this.tabIndex - 3;
                        console.log("Cedula invalida codigo provincia");
                    }
                }
                

            }
            return this.errorcedula_cargo;
            
        },
        //validaciones para cargo
       
        /**
         * solonumeros pertime solo en ingreso de nuemeros en el formulario.
         * @  funcion solonumeros
         */
        solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        sololetras: function($event) {
            var letra = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                !letra.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        
        /*valida_fecha: function($event) {
            var patron = new RegExp(
                "^(19|20)+([0-9]{2})([-])([0-9]{1,2})([-])([0-9]{1,2})$"
            );

            if (fecha.search(patron) == 0) {
                var values = fecha.split("-");
                if (isValidDate(values[2], values[1], values[0])) {
                    return true;
                }
            }
            return false;
        },*/
        cambio(c) {
            this.tipodiscap = 0;

            if (c == "Visual") {
                this.tipodiscap = 1;
            } else if (c == "Física") {
                this.tipodiscap = 1;
            } else if (c == "Auditiva") {
                this.tipodiscap = 1;
            } else if (c == "Mental") {
                this.tipodiscap = 1;
            } else if (c == "Verbal") {
                this.tipodiscap = 1;
            }else if(c == "Otro"){
                this.tipodiscap = 1;
            } else if (c == "Ninguna") {
                this.discap_porcentaje = null;
                this.tipo_iden_discap = null;
                this.num_iden_discap = null;
                this.otra_discap=null;
            }
        },
        /*octl() {
            if (this.ocult == true) {
                this.ocult = false;
            } else {
                this.ocult = true;
            }
        },*/
        getGrupoOcu: function() {
            axios
                .get("/api/grupo_ocupacional/" + this.usuario.id_empresa)
                .then(res => {
                    this.grupo_ocupacional2 = res.data;
                });
        },
        getBanco: function() {
            axios.get("/api/banco").then(
                function(response) {
                    this.banco2 = response.data;
                }.bind(this)
            );
        },
        getNacionalidad: function() {
            axios.get("/api/nacionalidad").then(
                function(response) {
                    this.nacionalidad2 = response.data;
                }.bind(this)
            );
        },
        getProvincias: function() {
            axios.get("/api/provincia").then(
                function(response) {
                    this.provincias2 = response.data;
                }.bind(this)
            );
        },
        getCiudades: function() {
            //this.canton = "";
            axios
                .get("/api/ciudad", {
                    params: {
                        id_provincia: this.provincia
                    }
                })
                .then(
                    function(response) {
                        this.ciudades2 = response.data;
                    }.bind(this)
                );
        },
        getParroquias: function() {
            axios
                .get("/api/parroquia", {
                    params: {
                        id_ciudad: this.canton
                    }
                })
                .then(
                    function(response) {
                        this.parroquias2 = response.data;
                        

                    }.bind(this)
                );
        },
        getDocumento: function() {
            axios.get("/api/documento/" + this.usuario.id_empresa).then(res => {
                this.doc_descripcion2 = res.data;
            });
        },
        /*obtenerimagen(e) {
            let file = e.target.files[0];
            var allowedExtensions = /(.jpg|.jpeg|.png)$/i;
            if (!allowedExtensions.exec(file.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .jpg, .jpeg, .png",
                    color: "danger"
                });
                return;
            }
            this.imagen.obtener = file;
            this.imagenrecupera = file;
            this.cargarimagen(file);
        },*/
        /*cargarimagen(file) {
            let reader = new FileReader();

            reader.onload = e => {
                this.imagen.visualizar = e.target.result;
            };

            reader.readAsDataURL(file);
        },*/

        /*guardarimagen(id) {
            let formData = new FormData();
            console.log("el id  es:" + id);
            formData.append("id", id);
            formData.append("file", this.imagenrecupera);
            axios
                .post("/api/guardarimgempleado", formData)
                .then(resp => {
                    console.log("RESPUESTA", resp);
                })
                .catch(err => {
                    console.log("ERROR", err);
                });
            for (const f of formData) {
                console.log("In formData", f);
            }
            // console.log(formData)
        },*/
        getDepartamento: function() {
            axios
                .get("/api/departamento/" + this.usuario.id_empresa)
                .then(res => {
                    this.departamento2 = res.data;
                });
        },
        getArea: function() {
            //this.canton = ""; coment
            axios.get("/api/area/" + this.usuario.id_empresa).then(res => {
                this.area2 = res.data;
            });
        },
        getCargo: function() {
            if(!this.$route.params.id){
                axios
                .get("/api/cargo", {
                    params: {
                        id_area: this.area_trabajo
                    }
                })
                .then(
                    response =>{
                        this.cargo2 = response.data;
                        
                    }
                );
            }else{
                axios
                .get("/api/cargoempleado")
                .then(
                    response =>{
                        this.cargo2 = response.data;
                    }
                );
            }
            
        },
        getSueldoCargo(id){
            if(id){
                 var id_cargo=id
            }else{
                id_cargo=0
            }
            axios
                .get("/api/sueldocargoempleado/"+id_cargo)
                .then(
                    response =>{
                        this.bonos = response.data[0].car_sueldo;
                    }
                );
        },
        importarexcel() {
            $(".inputexcel").click();
        }
    },
    mounted() {
        this.listar(1, this.buscar);
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.listarEmpleado(id);
        }
        this.listarCargas();
        this.listarDocumentos() ;
        this.getProvincias();
        this.getCiudades();
        this.getParroquias();
        this.getDepartamento();
        this.getArea();
        this.getCargo();
        this.getBanco();
        this.getNacionalidad();
        this.getGrupoOcu();
        this.getDocumento();
    }
};
</script>
<style scoped>
.btn-upload-all {
    display: none;
}
.btn-upload-file {
    display: none;
}
.image-container[data-v-10e59822] {
    width: 100%;
    height: 270px;
    border: 1px dashed #d6d6d6;
    border-radius: 4px;
    background-color: #fff;
}

.centered[data-v-10e59822] {
    width: 100%;
    height: 100%;
}

.show-img[data-v-10e59822] {
    max-width: 100%;
    max-height: 185px;
    width: 100%;
}

.image-overlay[data-v-10e59822] {
    height: 170px;
}

.image-overlay-details[data-v-10e59822] {
    top: 62%;
}

.image-list-container .image-list-item .show-img[data-v-10e59822] {
    max-width: 30px;
    max-height: 33px;
    height: 30px;
}

.imagenpre {
    height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 284px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}
.verarchivo {
    overflow: hidden;
    padding: 0px;
    height: 45px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 1px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
}

.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estiloborrar {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estiloborrar:hover {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    box-shadow: 0px -20px 20px 20px red;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen:hover .spanborrar {
    margin-bottom: 0px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.spanborrar {
    margin-bottom: -999px;
    position: absolute;
    bottom: 29px;
    color: red;
    font-size: 44px;
    font-weight: bold;
    margin-left: -10px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.btn-up {
    width: 200px;
    height: 100px;
    border: 2px solid;
    margin-top: 20px;
    color: transparent;
}
.menuescoger {
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul {
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 14px arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
}
.posicion {
    bottom: 5px;
    position: absolute;
    left: 15px;
}
.posicion span {
    font-size: 10px;
}
</style>
