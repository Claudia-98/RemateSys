<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Sistema</li>
                <li class="breadcrumb-item">Mover producto</li>
                <!-- <li class="breadcrumb-item active">Artículos</li> -->
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Movimientos
                        <button v-if="bandera==1" type="button" @click="mostrarDetalle()" class="btn btn-danger">
                            <i class="icon-plus"></i>&nbsp;
                        </button>
                    </div>
                    <!-- Listado-->
                    <template v-if="bandera==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="no_recibo">No. Recibo</option>
                                      <option value="user">Realizado por..</option>
                                      <option value="fecha">Fecha</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarMovimiento(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarMovimiento(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>No. Recibo</th>
                                        <th>Fecha</th>
                                        <th>Realizado por:</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="mov in arrayMovimiento" :key="mov.id">
                                        <td>
                                            <button type="button" @click="verMovimiento(mov.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="mov.estado=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarMovimiento(mov.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="mov.no_recibo"></td>
                                        <td v-text="mov.fecha"></td>
                                        <td v-text="mov.user"></td>
                                        <td>
                                            <div v-if="mov.estado">
                                                <span class="badge badge-danger">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-success">Cancelado</span>
                                            </div>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </template>
                    <!--Fin Listado-->
                    <!-- Detalle-->
                    <template v-else-if="bandera==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Espacio(Emisor)</label>
                                    <v-select style="width: 75%"
                                        ref="buscadorEspacio"
                                        v-model="EspacioAEXIST"
                                        @search="selectEspacioA"
                                        label="nombre"
                                        :options="arrayEspacioA"
                                        placeholder="Buscar espacios..."
                                        @input="getDatosEspacioA">
                                        <span slot="no-options">No hay coincidencias con los registros.</span>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Espacio(Receptor)</label>
                                    <v-select style="width: 75%"
                                        ref="buscadorEspacio"
                                        v-model="EspacioBEXIST"
                                        @search="selectEspacioB"
                                        label="nombre"
                                        :options="arrayEspacioB"
                                        placeholder="Buscar espacios..."
                                        @input="getDatosEspacioB">
                                        <span slot="no-options">No hay coincidencias con los registros.</span>
                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Recibo</label>
                                    <input type="text" disabled="disabled" class="form-control" v-model="no_recibo">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha</label>
                                    <datepicker :bootstrap-styling="true"  placeholder="Seleccione fecha" :language="es" :format="customFormatter" v-model="fecha"></datepicker>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <input type="text" class="form-control" v-model="observa">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Artículo <span style="color:red;" v-show="idproducto==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="busc" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="articulo">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <!-- disabled="disabled" -->
                                <div class="form-group">
                                    <label>Cantidad <span style="color:red;" v-show="cantidad==0">(*Ingrese)</span></label>
                                    <input type="number" value="0" min="0" class="form-control"  v-model="cantidad">
                                </div>
                            </div>
                             <div class="col-md-2">
                                <div >
                                    <label>Stock</label>
                                    <input type="number" disabled="disabled" class="form-control"  v-model="stock">
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div >
                                    <label>Precio de venta</label>
                                    <input type="number" disabled="disabled" class="form-control"  v-model="(precio_venta)">
                                    
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div v-show="errorArticulo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Stock</th>
                                            <th>Código</th>
                                            <th>Precio Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" min="0" class="form-control">
                                            </td>
                                        <td v-text="detalle.stock">
                                            </td>
                                        <td v-text="detalle.codigo">
                                            </td>
                                        <td v-text="formatQuetzales(detalle.precio_venta)">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                Sin productos
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarMovimiento()">Realizar recibo</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- Fin Detalle-->
                    <!-- Ver ingreso -->
                    <template v-else-if="bandera==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="">Impuesto</label>
                                <p v-text="impuesto"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="tipo_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_comprobante"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_comprobante"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row border">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo">
                                            </td>
                                            <td v-text="detalle.precio">
                                            </td>
                                            <td v-text="detalle.cantidad">
                                            </td>
                                            <td v-text="detalle.descuento">
                                            </td>
                                            <td>
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ {{total}}</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                NO hay artículos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                
                            </div>
                        </div>
                    </div>
                    </template>
                    <!-- fin ver ingreso -->
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterioA">
                                        <option value="nombre">Nombre</option>
                                        <option value="descripcion">Descripción</option>
                                        <option value="codigo">Código</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listarArticulo(buscarA,criterioA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarArticulo(buscarA,criterioA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Código</th>
                                            <th>Alias</th>
                                            <th>Nombre</th>
                                            <th>Stock</th>
                                            <th>Precio Venta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                            <td>
                                                <button type="button" @click="agregarDetalleModal(articulo)" class="btn btn-success btn-sm">
                                                <i class="icon-check"></i>
                                                </button>
                                            </td>
                                            <td v-text="articulo.producto.codigo"></td>
                                            <td v-text="articulo.producto.alias"></td>
                                            <td v-text="articulo.producto.nombre"></td>
                                            <td v-text="articulo.stock"></td>
                                            <td v-text="formatQuetzales(articulo.producto.precio_venta)"></td>
                                        </tr>                                
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    import vSelect from 'vue-select';
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment';
    import {en, es} from 'vuejs-datepicker/dist/locale';
    import createNumberMask from 'text-mask-addons/dist/createNumberMask';
    import MaskedInput from 'vue-text-mask';

    export default {
        data (){
            return {
                venta_id: 0,
                mask_quetzal: createNumberMask({
                    prefix: 'Q ',
                    suffix: '',
                    includeThousandsSeparator: true,
                    thousandsSeparatorSymbol: ',',
                    allowDecimal: true,
                    decimalSymbol: '.',
                    decimalLimit: 2, // how many digits allowed after the decimal
                    integerLimit: 10, // limit length of integer numbers
                    allowNegative: false,
                    allowLeadingZeroes: false
                }),
                es: es,
                nombre:'',
                alias:'',
                cantidad:0,
                stock:0,
                codigo:0,
                idcliente:0,
                idespacioA:0,
                observa:'',
                idespacioB:0,
                fecha:'',
                no_recibo:0,
                disparador:0,
                cliente:'',
                tipo_comprobante : 'BOLETA',
                serie_comprobante : '',
                num_comprobante : '',
                impuesto: 0.18,
                total:0.0,
                totalImpuesto: 0.0,
                totalParcial: 0.0,
                precio_venta:0,
                busc:'',
                idEP:0,
                precio_costo:0,
                precio_mayorista:0,
                stock:0,
                EspacioAEXIST:'',
                EspacioBEXIST:'',
                arrayMovimiento : [],
                arrayCliente: [],
                arrayEspacioA: [],
                arrayEspacioB: [],
                arrayDetalle : [],
                bandera:1,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorVenta : 0,
                errorMostrarMsjVenta : [],
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'num_comprobante',
                buscar : '',
                criterioA:'nombre',
                buscarA: '',
                arrayArticulo: [],
                idproducto: 0,
                codigo: '',
                articulo: '',
                precio: 0,
                cantidad:0,
                descuento: 0,
                stock:0
            }
        },
        components: {
            Datepicker,
            vSelect,
            MaskedInput,
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad-this.arrayDetalle[i].descuento)
                }
                return resultado;
            }
        },
        methods : {
            listarMovimiento (page,buscar,criterio){
                let me=this;
                var url= '/movimiento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayMovimiento = respuesta.movimientos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerNoRecibo(){
                let me = this;
                let url = '/movimientos/noRecibo';
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    console.log(respuesta);
                    if(respuesta.noventa == null || respuesta.noventa == ''){
                        me.no_recibo = 1;
                    }
                    else {
                        me.no_recibo = respuesta.noventa.no_recibo + 1;
                    }
                })
                .catch(function (error) {
                    console.log({error});
                });
            },
            formatQuetzales (amount) {
                var num = parseFloat(amount), formatted
                formatted =  num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                return amount = 'Q ' + formatted
            },
            selectEspacioA(search, loading){
                let me = this;
                loading(true);
                let url = '/movimiento/obtenerEspacio?nombre=' + search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    a: search
                    me.arrayEspacioA = respuesta.espacio;
                    // console.log(me.arrayCliente);
                    loading(false);
                })
                .catch(function (error) {
                    // console.log({error});
                });
            },
            selectEspacioB(search, loading){
                let me = this;
                loading(true);
                let url = '/movimiento/obtenerEspacio?nombre=' + search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    a: search
                    me.arrayEspacioB = respuesta.espacio;
                    // console.log(me.arrayCliente);
                    loading(false);
                })
                .catch(function (error) {
                    // console.log({error});
                });
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
            getDatosEspacioA(val1){
                let me = this;
                me.loading = true;
                me.idespacioA = val1.id;
            },
            getDatosEspacioB(val1){
                let me = this;
                me.loading = true;
                me.idespacioB = val1.id;
            },
            obtenerStock(id){

            },
            buscarArticulo(){
                let me=this;
                
                if(me.EspacioAEXIST!=0){ //Comprueba si ya se escogio algun espacio de donde se moveran los productos
                    var url= '/producto/obtenerProducto?nombre=' + me.busc ;
                    axios.get(url).then(function (response) {
                        var respuesta= response.data;
                        me.arrayArticulo = respuesta.producto;
                        
                        if (me.arrayArticulo.length>0){
                            for(let i = 0; i < me.arrayArticulo.length; i++){
                                if(me.arrayArticulo[i]['idespacio']==me.idespacioA){

                                    me.articulo=me.arrayArticulo[i]['producto']['nombre'];
                                    me.codigo=me.arrayArticulo[i]['producto']['codigo'];
                                    me.idproducto=me.arrayArticulo[i]['producto']['id'];
                                    me.idEP=me.arrayArticulo[i]['id'];
                                    me.precio_venta=me.arrayArticulo[i]['producto']['precio_venta'];
                                    me.stock=me.arrayArticulo[i]['stock'];
                                    break;
                                }
                            }
                        }
                        else{
                            me.articulo='No existe el producto';
                            me.cantidad=0;
                            me.stock=0;
                            me.idproducto=0;
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }else{
                    me.articulo='No existe el producto';
                    me.cantidad=0;
                            me.stock=0;
                    me.idproducto=0;
                    return;
                }
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarMovimiento(page,buscar,criterio);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idproducto==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalle(){
                let me=this;
                if(me.validarArticulo()){
                    return;
                }
                if(me.encuentra(me.idproducto)){
                    me.errorMostrarMsjArticulo.push("Busque un protucto.");
                    me.errorArticulo=1;
                    return;
                }
                me.arrayDetalle.push({
                    idproducto: me.idproducto,
                    codigo: me.codigo,
                    articulo: me.articulo,
                    cantidad: me.cantidad,
                    stock:me.stock,
                    precio_venta: me.precio_venta,
                    idEP: me.idEP
                });
                me.codigo="";
                me.stock=0;
                me.busc='';
                me.idproducto=0;
                me.articulo="";
                me.cantidad=0;
                me.precio_venta=0;
                me.idEP=0;

            },
            agregarDetalleModal(data =[]){
                let me=this;
                if(me.encuentra(data['producto']['id'])){
                        // swal({
                        //     type: 'error',
                        //     title: 'Error...',
                        //     text: 'Ese artículo ya se encuentra agregado!',
                        //     })
                        return;
                    }
                    else{
                       me.arrayDetalle.push({
                            idproducto: data['producto']['id'],
                            articulo: data['producto']['nombre'],
                            cantidad: 1,
                            codigo: data['producto']['codigo'],
                            precio_venta: data['producto']['precio_venta'],
                            idEP:data['id'],
                            stock:data['stock']
                        }); 
                    }
            },
            listarArticulo (buscar,criterio){
                let me=this;
                var url= '/producto/listarProducto?buscar='+ buscar + '&criterio='+ criterio+'&id='+me.idespacioA;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticulo = respuesta.producto.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarMovimiento(){
                if (this.validarMovimiento()){
                    return;
                }
                
                let me = this;

                axios.post('/movimiento/registrar',{
                    'idEspacioA': this.idespacioA,
                    'idEspacioB': this.idespacioB,
                    'no_recibo' : this.no_recibo,
                    'fecha': this.fecha,
                    'observaciones': this.observaciones,
                    'data': this.arrayDetalle

                }).then(function (response) {
                    me.bandera=1;
                    me.listarMovimiento(1,'','no_movimiento');
                    me.idespacioA=0;
                    me.idespacioB=0;
                    me.no_recibo=0;
                    me.fecha='';
                    me.observaciones='';
                    me.arrayDetalle=[];

                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarMovimiento(){
                let me=this;
                me.errorVenta=0;
                me.errorMostrarMsjVenta =[];

                if (me.arrayDetalle.length<=0) me.errorMostrarMsjVenta.push("Ingrese productos a mover.");
                if(me.fecha=='') me.errorMostrarMsjVenta.push("Ingrese una fecha.");
                if (!me.idespacioA) me.errorMostrarMsjVenta.push("Ingrese un espacio emisor.")
                if (!me.idespacioB) me.errorMostrarMsjVenta.push("Ingrese un espacio receptor.")

                if (me.errorMostrarMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            validarArticulo(){
                let me = this;
                me.errorArticulo=0;
                me.errorMostrarMsjArticulo=[];

                if (!me.idproducto) me.errorMostrarMsjArticulo.push("Ingrese un producto");
                if (parseFloat(me.cantidad) > parseFloat(me.stock) || parseFloat(me.cantidad) <=0) me.errorMostrarMsjArticulo.push("Ingrese un stock válido.")

                if(me.errorMostrarMsjArticulo.length) me.errorArticulo = 1;
                return me.errorArticulo;
            },
            mostrarDetalle(){
                let me=this;
                me.bandera=0;
                me.obtenerNoRecibo();
                me.idproveedor=0;
                me.tipo_comprobante='BOLETA';
                me.serie_comprobante='';
                me.num_comprobante='';
                me.impuesto=0.18;
                me.total=0.0;
                me.idproducto=0;
                me.articulo='';
                me.cantidad=0;
                me.precio=0;
                me.arrayDetalle=[];
            },
            ocultarDetalle(){
                this.bandera=1;
            },
            verMovimiento(id){
                let me=this;
                me.bandera=2;
                
                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;

                    me.cliente = arrayVentaT[0]['nombre'];
                    me.tipo_comprobante=arrayVentaT[0]['tipo_comprobante'];
                    me.serie_comprobante=arrayVentaT[0]['serie_comprobante'];
                    me.num_comprobante=arrayVentaT[0]['num_comprobante'];
                    me.impuesto=arrayVentaT[0]['impuesto'];
                    me.total=arrayVentaT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
                var urld= '/venta/obtenerDetalles?id=' + id;
                
                axios.get(urld).then(function (response) {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });               
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
            }, 
            abrirModal(){               
                this.arrayArticulo=[];
                this.listarArticulo(this.buscarA,this.criterioA);
                this.modal = 1;
                this.tituloModal = 'Seleccione uno o varios artículos';
            },
            desactivarMovimiento(id){
               swal({
                title: 'Esta seguro de anular esta venta?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarMovimiento(1,'','num_comprobante');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulada con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
        },
        mounted() {
            this.listarMovimiento(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    @media (min-width: 600px) {
        .btnagregar {
            margin-top: 2rem;
        }
    }

</style>
