<template>
	<div class="vx-row">
		<!-- MULTIPLE COLUMNS-->
		<div class="vx-col w-full mb-base">
			<vx-card title="Agregar Empresa" code-toggler>
				<div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Codigo" v-model="input25" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Periodo" v-model="input26" />
					</div>
				</div>
				<div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Nombre" v-model="input27" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Ruc" v-model="input28" />
					</div>
				</div>
				<div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Direccion" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Telefono" v-model="input30" />
					</div>
				</div>
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Provincia" v-model="input27" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Ciudad" v-model="input28" />
					</div>
				</div>
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Ruc Contador" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Nombre Contador" v-model="input30" />
					</div>
				</div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-2">
						<vs-select placeholder="Tipo" class="selectExample" label="Tipo Identificacion" label-placeholder="vs-Multiple" vs-multiple v-model="select1">
				        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in options1" />
			            </vs-select>
					</div>
					<div class="vx-col sm:w-1/3 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Ruc Representante" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/3 w-full mb-2">
						<vs-input type="email" class="w-full" label-placeholder="Nombre Representante" v-model="input30" />
					</div>
				</div>
                
                <div class="vx-row">
					<div class="vx-col sm:w-1/3 w-full mb-2">
						<vs-input label="Periodo Inicio" placeholder="0000/00/00"  v-model="value1"/>
					</div>
					<div class="vx-col sm:w-1/3 w-full mb-6">
						<vs-input label="Periodo Fin" placeholder="0000/00/00"  v-model="value2"/>
					</div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Duracion Clave" v-model="input30" />
				</div>
				</div>
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<!--<vs-input class="w-full" label-placeholder="Moneda" v-model="input29" />-->
                        <vs-select placeholder="Sleccione su Moneda" class="selectExample" label="Moneda" label-placeholder="Autocomplete" vs-multiple autocomplete v-model="select2">
				        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in options2" />
			        </vs-select>
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input label="Mascara Contable" placeholder="X.XX.XX.XX.XX.XX"  v-model="value3"/>
					</div>
				</div>
                <div class="vx-row">
					<div class="vx-col sm:w-1/4 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Cuenta Activo" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Pasivo" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Patrimonio" v-model="input30" />
				    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Ingreso" v-model="input30" />
				    </div>
				</div>
                <div class="vx-row">
					
					<div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Costo" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/4 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Cuenta Gasto" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Orden" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Resultado" v-model="input30" />
				    </div>
				</div>
                <!--
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Cuenta Activo" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Pasivo" v-model="input30" />
					</div>
				</div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Patrimonio" v-model="input30" />
				</div>
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Cuenta Gasto" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Cuenta Orden" v-model="input30" />
					</div>
				</div>-->
                <div class="vx-row">
					<div class="vx-col sm:w-1/4 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Fecha Ultimo Cierre" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/4 w-full mb-6">
						<!--<vs-input type="email" class="w-full" label-placeholder="Balance" v-model="input30" />-->
                        <vs-select placeholder="Search and select" class="selectExample" label="Balance Consolidado" label-placeholder="Autocomplete" vs-multiple autocomplete v-model="select3">
				            <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in options3" />
			            </vs-select>
					</div>
                    <!--<div class="vx-col sm:w-1/4 w-full mb-2">
						<vs-select placeholder="Search and select" class="selectExample" label="Tipo Operativo" label-placeholder="Autocomplete" vs-multiple autocomplete v-model="select4">
				            <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in options4" />
			            </vs-select>
					</div>-->
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input label="Ultimo Recalculo" placeholder="0000/00/00"  v-model="value4"/>
					</div>
				</div>
                <!--
                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-select placeholder="Search and select" class="selectExample" label="Tipo Operativo" label-placeholder="Autocomplete" vs-multiple autocomplete v-model="select4">
				            <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in options4" />
			            </vs-select>
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input label="Ultimo Recalculo" placeholder="0000/00/00"  v-model="value4"/>
					</div>
				</div>-->
                <div class="vx-row">
					<div class="vx-col sm:w-1/6 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Codigo Entidad" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/6 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Contribuyente especial" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="No. Establecimiento" v-model="input30" />
				</div>
				</div>
                

                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Email" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Contraseña" v-model="input30" />
					</div>
				</div>

                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Email" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Contraseña" v-model="input30" />
					</div>
				</div>

                <div class="vx-row">
					<div class="vx-col sm:w-1/4 w-full mb-2">
						<vs-input class="w-full" label-placeholder="Servidor Correo" v-model="input29" />
					</div>
					<div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Correo Remitente" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Puerto" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
						<ul class="demo-alignment">
                            <li>
                                <vs-radio v-model="radios1" vs-value="ssl">Ssl</vs-radio>
                            </li>
                            <li>
                                <vs-radio v-model="radios1" vs-value="tls">Tls</vs-radio>
                            </li>
						</ul>
                    </div>
				</div>
                <div class="excel-import">
                    <input type="file" ref="fileInput" class="hidden" accept=".xlsx, .xls" @change="handleClick">
                        <div
                            @click="$refs.fileInput.click()"
                            @drop="handleDrop"
                            @dragover="handleDragover"
                            @dragenter="handleDragover"
                            class="px-8 py-16 cursor-pointer text-center border-2 border-dashed d-theme-border-grey-light d-theme-dark-bg text-xl">
                            <feather-icon icon="UploadCloudIcon" svgClasses="h-16 w-16 stroke-current text-grey" class="block" />
                            <span>Subir Logo</span>
                            <span class="font-medium text-primary" @click.stop="$refs.fileInput.click()">Browse</span>
                            <!-- <vs-button type="border" @click.stop="$refs.fileInput.click()">Browse</vs-button> -->
                        </div>
                        <div class="excel-import">
                    <input type="file" ref="fileInput" class="hidden" accept=".xlsx, .xls" @change="handleClick">
                        <div
                            @click="$refs.fileInput.click()"
                            @drop="handleDrop"
                            @dragover="handleDragover"
                            @dragenter="handleDragover"
                            class="px-8 py-16 cursor-pointer text-center border-2 border-dashed d-theme-border-grey-light d-theme-dark-bg text-xl">
                            <feather-icon icon="UploadCloudIcon" svgClasses="h-16 w-16 stroke-current text-grey" class="block" />
                            <span>Subir Firma</span>
                            <span class="font-medium text-primary" @click.stop="$refs.fileInput.click()">Browse</span>
                            <!-- <vs-button type="border" @click.stop="$refs.fileInput.click()">Browse</vs-button> -->
                        </div>
                    </div>
                </div>

                <div class="vx-row">
					<div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Codigo Firma" v-model="input30" />
					</div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
						<vs-input type="email" class="w-full" label-placeholder="Ruta" v-model="input30" />
					</div>
				</div>
				
				<div class="vx-row">
					<div class="vx-col w-full">
						<vs-button class="mr-3 mb-2">Submit</vs-button>
						<vs-button color="warning" type="border" class="mb-2" @click="input25 = input26 = input27 = input28 = input29 = input30 = ''; check7 = false;">Reset</vs-button>
					</div>
				</div>
        <template slot="codeContainer">
&lt;template&gt;
  &lt;div class=&quot;vx-row&quot;&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-2&quot;&gt;
      &lt;vs-input class=&quot;w-full&quot; label-placeholder=&quot;First Name&quot; v-model=&quot;input25&quot; /&gt;
    &lt;/div&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-2&quot;&gt;
      &lt;vs-input class=&quot;w-full&quot; label-placeholder=&quot;Last Name&quot; v-model=&quot;input26&quot; /&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;div class=&quot;vx-row&quot;&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-2&quot;&gt;
      &lt;vs-input class=&quot;w-full&quot; label-placeholder=&quot;City&quot; v-model=&quot;input27&quot; /&gt;
    &lt;/div&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-2&quot;&gt;
      &lt;vs-input class=&quot;w-full&quot; label-placeholder=&quot;Country&quot; v-model=&quot;input28&quot; /&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;div class=&quot;vx-row&quot;&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-2&quot;&gt;
      &lt;vs-input class=&quot;w-full&quot; label-placeholder=&quot;Company&quot; v-model=&quot;input29&quot; /&gt;
    &lt;/div&gt;
    &lt;div class=&quot;vx-col sm:w-1/2 w-full mb-6&quot;&gt;
      &lt;vs-input type=&quot;email&quot; class=&quot;w-full&quot; label-placeholder=&quot;Email&quot; v-model=&quot;input30&quot; /&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;div class=&quot;vx-row&quot;&gt;
    &lt;div class=&quot;vx-col w-full mb-6&quot;&gt;
      &lt;vs-checkbox class=&quot;inline-flex&quot; v-model=&quot;check7&quot;&gt;Remember Me&lt;/vs-checkbox&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;div class=&quot;vx-row&quot;&gt;
    &lt;div class=&quot;vx-col w-full&quot;&gt;
      &lt;vs-button class=&quot;mr-3 mb-2&quot;&gt;Submit&lt;/vs-button&gt;
      &lt;vs-button color=&quot;warning&quot; type=&quot;border&quot; class=&quot;mb-2&quot; @click=&quot;input25 = input26 = input27 = input28 = input29 = input30 = ''; check7 = false;&quot;&gt;Reset&lt;/vs-button&gt;
    &lt;/div&gt;
  &lt;/div&gt;
&lt;/template&gt;
        </template>
			</vx-card>
		</div>
	</div>
</template>
<script>
export default {
    props: {
    onSuccess: {
      type: Function,
      required: true
    }
  },
	data() {
		return {

			select1: [],
            select2: [],
            select3:[],
            select4:[],
            value1:'',
            value2:'',
            value3:'',
            value4:'',
            radios1: 'ssl',
			options1: [
                { text: 'Cedula', value: 0 },
                { text: 'Ruc', value: 1 },
				{ text: 'Pasaporte', value: 2 },
				
			],
			options2: [
				{ text: 'Dolar', value: 1 },
				{ text: 'Extranjera', value: 2 },

            ],
            options3:[
                { text: 'No', value: 1 },
				{ text: 'Si', value: 2 },
            ],
            options4:[
                { text: 'Iva', value: 1 },
            ],
            excelData: {
                header: null,
                results: null,
                meta: null,
            }
		}
    },
    methods: {
    generateData({ header, results, meta }) {
      this.excelData.header = header
      this.excelData.results = results
      this.excelData.meta = meta
      this.onSuccess && this.onSuccess(this.excelData)
    },
    getHeaderRow(sheet) {
      const headers = []
      const range = XLSX.utils.decode_range(sheet['!ref'])
      let C
      const R = range.s.r
      /* start in the first row */
      for (C = range.s.c; C <= range.e.c; ++C) { /* walk every column in the range */
        const cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })]
        /* find the cell in the first row */
        let hdr = 'UNKNOWN ' + C // <-- replace with your desired default
        if (cell && cell.t) hdr = XLSX.utils.format_cell(cell)
        headers.push(hdr)
      }
      return headers
    },
    handleDragover(e) {
      e.stopPropagation()
      e.preventDefault()
      e.dataTransfer.dropEffect = 'copy'
    },
    handleDrop(e) {
      e.stopPropagation()
      e.preventDefault()
      const files = e.dataTransfer.files
      if (files.length !== 1) {
        this.$vs.notify({
            title: 'Login Attempt',
            text: 'Only support uploading one file!',
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'warning'
        })
        return
      }
      const rawFile = files[0] // only use files[0]
      if (!this.isExcel(rawFile)) {
        this.$vs.notify({
            title: 'Login Attempt',
            text: 'Only supports upload .xlsx, .xls, .csv suffix files',
            iconPack: 'feather',
            icon: 'icon-alert-circle',
            color: 'warning'
        })
        return false
      }
      this.uploadFile(rawFile)
    },
    readerData(rawFile) {
      return new Promise((resolve) => {
        const reader = new FileReader()
        reader.onload = e => {
          const data = e.target.result
          const workbook = XLSX.read(data, { type: 'array' })
          const firstSheetName = workbook.SheetNames[0]
          const worksheet = workbook.Sheets[firstSheetName]
          const header = this.getHeaderRow(worksheet)
          const results = XLSX.utils.sheet_to_json(worksheet)
          const meta = { sheetName: firstSheetName }
          this.generateData({ header, results, meta })
          resolve()
        }
        reader.readAsArrayBuffer(rawFile)
      })
    },
    handleClick(e) {
      const files = e.target.files
      const rawFile = files[0]
      if (!rawFile) return
      this.uploadFile(rawFile)
    },
    isExcel(file) {
      return /\.(xlsx|xls|csv)$/.test(file.name)
    },
    uploadFile(file) {
      this.$refs['fileInput'].value = null // fix can't select the same excel
      this.readerData(file)
    }
    }
}
</script> 