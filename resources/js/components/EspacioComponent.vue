<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Sistema</li>
                <li class="breadcrumb-item">Tiendas-Espacios</li>
                <!-- <li class="breadcrumb-item active">Medidas</li> -->
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <template v-if="cara==0">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Tiendas - Espacios
                            <button type="button" @click="abrirModal('espacio','registrar')" class="btn btn-secondary">
                                <i class="icon-plus"></i>&nbsp;
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="direccion">Dirección</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="telefono">Teléfono</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarEspacio(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarEspacio(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Nombre</th>
                                        <th>Dirección</th>
                                        <th>No. Teléfono</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="categoria in arrayEspacio" :key="categoria.id">
                                        <td>
                                            <button type="button" @click="abrirModal('espacio','actualizar',categoria)" class="btn btn-warning btn-sm">
                                            <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="agregarStock(categoria.id,categoria.nombre,categoria.direccion)" class="btn btn-primary btn-sm">
                                            <i class="icon-plus"></i>
                                            </button> &nbsp;
                                            <button type="button" @click="listarProductos(categoria.id,1,buscar,criterio)" class="btn btn-warning btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="categoria.estado">
                                                <button type="button" class="btn btn-danger btn-sm" @click="desactivarMedida(categoria.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                            <template v-else>
                                                <button type="button" class="btn btn-info btn-sm" @click="activarMedida(categoria.id)">
                                                    <i class="icon-check"></i>
                                                </button>
                                            </template>
                                        </td>
                                        <td v-text="categoria.nombre"></td>
                                        <td v-text="categoria.direccion"></td>
                                        <td v-text="categoria.telefono"></td>
                                        <td>
                                            <div v-if="categoria.estado">
                                                <span class="badge badge-success">Activo</span>
                                            </div>
                                            <div v-else>
                                                <span class="badge badge-danger">Desactivado</span>
                                            </div>
                                            
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
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
                    </div>
                </template>
                <template v-if="cara==1">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> <strong>Stock en:</strong> {{nombre}}
                            
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="alias">Alias</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarProductos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarProductos(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Stock</th>
                                        <th>Precio compra</th>
                                        <th>Precio venta</th>
                                        <th>Precio mayorista</th>
                                        <!-- <th>Estado</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="categoria in arrayProductos" :key="categoria.id">
                                        
                                        <td>
                                            <img class="imgProduct" v-bind:src="'/uploads/'+categoria.producto.foto" />
                                        </td>
                                        <td v-text="categoria.producto.nombre"></td>
                                        <td v-text="categoria.stock +' ('+ categoria.producto.medida.medida +')'"></td>
                                        <td v-text="formatQuetzales(categoria.producto.precio_compra)"></td>
                                        <td v-text="formatQuetzales(categoria.producto.precio_venta)"></td>
                                        <td v-text="formatQuetzales(categoria.producto.precio_mayorista)"></td>
                                        
                                    </tr>                                
                                </tbody>
                            </table>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="paginationM.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaM(paginationM.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumberM" :key="page" :class="[page == isActivedM ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaM(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="paginationM.current_page < paginationM.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaM(paginationM.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                                <button type="button" class="btn btn-secondary" @click="cerrarProductos()">Cerrar</button>
                            </nav>
                        </div>
                    </div>
                </template>
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
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre de la tienda ó espacio">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección de la tienda ó espacio">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Número de teléfono de la tienda ó espacio">
                                        
                                    </div>
                                </div>
                                
                                <div v-show="errorEspacio" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEspacio" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarMedida()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarMedida()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            <!--Inicio del modal agregar stock-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalP}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="'Agregar stock en '+nombre"></h4>
                            <button type="button" class="close" @click="cerrarModalP()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-7 form-control-label" for="text-input"><strong>Dirección del espacio / tienda: </strong> {{direccion}}</label>
                                    
                                </div>
                                <template v-if="bandera==0">
                                    <div>
                                        <span style="color:red;" v-show="ClienteEXIST == ''">Busque un producto</span>
                                        <div class="form-inline">
                                            <v-select style="width: 75%"
                                                ref="buscadorCliente"
                                                v-model="ClienteEXIST"
                                                @search="selectCliente"
                                                label="nombre"
                                                :options="arrayProducto"
                                                placeholder="Buscar artículos..."
                                                @input="getDatosCliente">
                                                <span slot="no-options">No hay coincidencias con los registros.</span>
                                            </v-select>
                                        </div>
                                    </div>
                                    <div v-if="ClienteEXIST!=''">
                                        <img class="imgPreV" v-bind:src="'/uploads/'+ClienteEXIST.foto" />
                                    </div>
                                </template>
                                <template v-if="bandera==1">
                                    <div class="form-group row" v-if="band==0">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Stock actual:</strong> ({{medida}}) {{stock}}</label>
                                    </div>
                                    <div class="form-group row" v-if="band==1">
                                        <label class="col-md-3 form-control-label" for="text-input"><strong>Primer stock</strong></label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Nuevo stock: ({{medida}})</label>
                                        
                                        <div class="col-md-3">
                                            <input type="number" min="0" v-model="stockN" class="form-control" placeholder="Existencia del producto en la tienda ó espacio">
                                        </div>
                                    </div>


                                </template>
                                
                                
                                <div v-show="errorEspacioP" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjEspacioP" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" v-if="bandera==0" class="btn btn-secondary" @click="cerrarModalP()">Cerrar</button>
                            <button type="button" v-if="bandera==1" class="btn btn-secondary" @click="anterior()">Atras</button>
                            <button type="button" v-if="bandera==0" class="btn btn-primary" @click="siguiente(ClienteEXIST.id)">Siguiente</button>
                            <button type="button" v-if="bandera==1" class="btn btn-primary" @click="registrarStock()">Agregar</button>
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
    import 'vue-select/dist/vue-select.css';
    import createNumberMask from 'text-mask-addons/dist/createNumberMask';
    import MaskedInput from 'vue-text-mask';

    export default {
        data (){
            return {
                espacio_id: 0,
                espaciop_id: 0,
                producto_id: 0,
                nombre : '',
                cont:0,
                cara:0,
                direccion : '',
                telefono : '',
                arrayEspacio : [],
                arrayEspacioP : [],
                arrayProducto:[],
                arrayProductos:[],
                modal : 0,
                band:0,
                modalP : 0,
                medida:'',
                stock:0,
                stockN:0,
                tituloModal : '',
                ClienteEXIST:'',
                bandera:0,
                idcliente:0,
                tipoAccion : 0,
                loading:0,
                errorEspacio : 0,
                errorMostrarMsjEspacio : [],
                errorEspacioP : 0,
                errorMostrarMsjEspacioP : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationM : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'direccion',
                buscar : ''
            }
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedM: function(){
                return this.paginationM.current_page;
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
            pagesNumberM: function() {
                if(!this.paginationM.to) {
                    return [];
                }
                
                var from = this.paginationM.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.paginationM.last_page){
                    to = this.paginationM.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;             

            }
        },
        components: {
            vSelect
        },
        methods : {
            listarEspacio (page,buscar,criterio){
                let me=this;
                var url= '/espacio?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayEspacio = respuesta.espacios.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listarProductos (id,page,buscar,criterio){
                let me=this;
                if(me.cont==0){me.espaciop_id=id;me.cont++;}
                me.cara=1;
                var url= '/espacio/productos?id='+ me.espaciop_id +'&page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    // console.log(respuesta);
                    me.arrayProductos = respuesta.producto.data;
                    me.paginationM= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            formatQuetzales (amount) {
                var num = parseFloat(amount), formatted
                formatted =  num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,')
                return amount = 'Q ' + formatted
            },
            converMaskToNumberxd (number) {
                var myNumber = ''
                for (var i = 0; i < number.length; i += 1) {
                if (number.charAt(i) === 'Q' || number.charAt(i) === ',' || number.charAt(i) === ' ') {
                    continue
                } else {
                    myNumber += number.charAt(i)
                }
                }
                return parseFloat(myNumber).toFixed(2)
            },
            cerrarProductos(){
                var me = this;
                me.cara=0;
                me.cont=0;
                me.arrayProductos=[];
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEspacio(page,buscar,criterio);
            },
            cambiarPaginaM(id,page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.paginationM.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarProductos(id,page,buscar,criterio);
            },
            registrarMedida(){
                if (this.validarEspacio()){
                    return;
                }
                
                let me = this;

                axios.post('/espacio/registrar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'telefono': this.telefono
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarEspacio(1,'','direccion');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarMedida(){
               if (this.validarEspacio()){
                    return;
                }
                
                let me = this;
                
                    axios.put('/espacio/actualizar',{
                        'nombre': this.nombre,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'id': this.espacio_id
                    }).then(function (response) {
                        me.cerrarModal();
                        me.listarEspacio(1,'','direccion');
                    }).catch(function (error) {
                        console.log(error);
                    });
                
            },
            agregarStock(id,nom,dir){
                var me = this;
                me.espacio_id=id; me.nombre=nom; me.direccion=dir; me.stock=0;
                me.modalP=1;
            },
            registrarStock(){
                var me = this;
                if(me.validarStock()){
                    return;
                }
                if(me.band==0){
                    axios.put('/espacio/actualizarP',{
                        'idespacio': this.espacio_id,
                        'idproducto': this.producto_id,
                        'stock': this.stockN,
                        'id': this.espaciop_id
                    }).then(function (response) {
                        me.cerrarModalP();
                        me.listarEspacio(1,'','direccion');
                    }).catch(function (error) {
                        console.log(error);
                    }); 
                } else if (me.band==1){
                    axios.post('/espacio/registrarP',{
                        'idespacio': this.espacio_id,
                        'idproducto': this.producto_id,
                        'stock': this.stockN
                    }).then(function (response) {
                        me.cerrarModalP();
                        me.listarEspacio(1,'','direccion');
                    }).catch(function (error) {
                        console.log(error);
                    }); 
                }

            },
            async siguiente(id){
                var me = this;
                if(me.ClienteEXIST==''||me.ClienteEXIST==null){
                    return;
                }
                me.producto_id=id;
                let url = '/espacio/obtenerStock?idproducto=' + me.producto_id + '&idespacio='+me.espacio_id;
                await axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    var array = respuesta.espacio;
                    me.arrayEspacioP = array[0];
                })
                .catch(function (error) {
                    // console.log({error});
                });
                if(me.arrayEspacioP==[]||me.arrayEspacioP==''||me.arrayEspacioP==null){
                    me.band=1;
                    me.bandera=1;
                }
                else{
                    me.band=0;
                    me.espaciop_id=me.arrayEspacioP['id'];
                    me.medida=me.arrayEspacioP['producto']['medida']['medida'];
                    me.stock=me.arrayEspacioP['stock'];
                    me.stockN=me.arrayEspacioP['stock'];
                    me.bandera=1;
                }
                
            },
            anterior(){
                var me = this;
                me.bandera=0;
                me.band=0;
                me.ClienteEXIST='';
                me.stock=0;
                me.stockN=0;
                me.arrayEspacioP=[];
            },
            desactivarMedida(id){
               Swal.fire({
                title: 'Desactivar espacio',
                text: "¿Esta seguro de desactivar este espacio?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Desactivarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/espacio/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEspacio(1,'','direccion');
                        Swal.fire(
                        'Desactivado!',
                        'El espacio ha sido desactivado con éxito.',
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
            selectCliente(search, loading){
                let me = this;
                loading(true);
                let url = '/espacio/obtenerProducto?nombre=' + search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    a: search
                    me.arrayProducto = respuesta.producto;
                    // console.log(me.arrayCliente);
                    loading(false);
                })
                .catch(function (error) {
                    // console.log({error});
                });
            },
            getDatosCliente(val1){
                let me = this;
                me.loading = true;
                if (val1 == null || val1 == ''){
                    me.ClienteEXIST = '';
                    me.idcliente = -1;
                    
                    
                }
                else {
                    me.idcliente = val1.id;
                }
            },
            activarMedida(id){
               Swal.fire({
                title: 'Desactivar espacio',
                text: "¿Esta seguro de activar este espacio?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/espacio/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarEspacio(1,'','direccion');
                        Swal.fire(
                        'Activado!',
                        'El espacio ha sido activado con éxito.',
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
            validarStock(){
                var me =this;
                me.errorEspacioP=0;
                me.errorMostrarMsjEspacioP=[];
                if(!me.stockN||me.stockN<=0) this.errorMostrarMsjEspacioP.push("El stock del producto no puede ser 0 o estar vacío.");

                if(me.errorMostrarMsjEspacioP.length) this.errorEspacioP=1;
                return this.errorEspacioP;
            },
            validarEspacio(){
                this.errorEspacio=0;
                this.errorMostrarMsjEspacio =[];

                if (!this.nombre) this.errorMostrarMsjEspacio.push("El nombre del espacio no puede estar vacío.");
                if (!this.direccion) this.errorMostrarMsjEspacio.push("La dirección del espacio no puede estar vacío.");
                if (!this.telefono) this.errorMostrarMsjEspacio.push("El número telefónico del espacio no puede estar vacío.");

                if (this.errorMostrarMsjEspacio.length) this.errorEspacio = 1;

                return this.errorEspacio;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.direccion='';
                this.telefono='';
            },
            cerrarModalP(){
                this.modalP=0;
                this.ClienteEXIST='';
                this.bandera=0;
                this.band=0;
                this.espaciop_id=0;
                this.producto_id=0;
                this.stockN=0;
                this.stock=0;
                this.arrayProducto=[];
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "espacio":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Espacio';
                                this.nombre= '';
                                this.direccion= '';
                                this.telefono= '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Espacio';
                                this.tipoAccion=2;
                                this.espacio_id=data['id'];
                                this.nombre = data['nombre'];
                                this.direccion = data['direccion'];
                                this.telefono = data['telefono'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarEspacio(1,this.buscar,this.criterio);
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
    .imgProduct {
        width: 80px !important;
        height: 80px;
        /* border-radius: 150px; */
        border: 10px rgb(4, 1, 74);
    }
    .imgPreV {
        width: 150px !important;
        height: 150px;
        /* border-radius: 150px; */
        border: 10px rgb(4, 1, 74);
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
