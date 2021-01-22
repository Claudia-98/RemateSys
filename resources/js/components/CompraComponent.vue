<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Sistema</li>
                <li class="breadcrumb-item">Compras</li>
                <li class="breadcrumb-item active">Registro compra</li>
            </ol>
            
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Registrar compra

                    </div>

            <div class="card-body">
               <div class="form-group row border"> 


                   <!-- fecha -->

                    <div class="col-md-9">
                        <div class="form-group row m-2">
                          <label class="col-md-3 form-control-label" for="text-input">Fecha(*)</label>
                            <div class="col-md-9">
                                <datepicker :disabled-dates ="state.disabledDates" 
                                            :format="customFormatter" 
                                            :language="es" placeholder="Seleccione fecha"  
                                            v-model="fechavalid"
                                
                                ></datepicker>
                            </div>
                         </div>
                    </div>

                   <!-- fin fecha -->

                   
 
                   <!-- proveedor  -->
                   <div class="col-md-9">
                          <div class="form-group row m-2">
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor(*)</label>
                                     <div class="col-md-4">
                                        <div class="form-inline">
                                            <v-select style="width: 75%"
                                                ref="buscadorProveedor"
                                                v-model="ProveedorEXIST"
                                                @search="selectProveedor"
                                                label="nombre"
                                                :options="arrayProveedor"
                                                placeholder="Buscar proveedor..."
                                                @input="getDatosProveedor">
                                                <span slot="no-options">No hay coincidencias con los registros.</span>
                                            </v-select>
                                            <div class="form-inline col m-1"> 
                                            <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                                             <i class="icon-plus"></i>&nbsp;
                                            </button>
                                                
                                            </div>
                                        
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                                
                   </div>
                   <!-- fin proveedor -->
                   <!-- observaciones -->

                    <div class="col-md-9">
                         <div class="form-group row m-2">
                                    <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                <div class="col-md-9">
                                    <input type="text-area" class="form-control" v-model="observaciones">
                                </div>
                         </div>
                    </div>

                   <!-- fin observaciones -->
                <div class="col-md-9">
                 <div v-show="errorCompra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

                                        </div>
                                    </div>
                     </div>
                </div>
                <button type="button"  class="btn btn-primary" @click="registrarCompra()">Guardar</button>
                


               </div>   

           <!--  Inicio detalle compra -->
             <div class="form-group row border"> 

                  <div class="col-md-4 m-2">
                                <div class="form-group">
                                    <label>Producto <span style="color:red;" v-show="idproducto==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="codigo" @keyup.enter="buscarProducto()" placeholder="Ingrese producto">
                                        <button @click="abrirModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="producto"> 
                                    </div> 

                                                    
                    </div>
                 </div>

               
                 <div class="col-md-2 m-2">
                                <div class="form-group">
                                    <label>Precio Compra <span style="color:red;"   >(*Ingrese)</span></label>
                                    <input  type="number"  value="0" step="any" class="form-control" v-model="precio_compra">
                                </div>
                  </div>
                
                <div class="col-md-2 m-2">
                                <div class="form-group">
                                    <label>Precio Venta <span style="color:red;" >(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio_venta">
                                </div>
                 </div>

                <div class="col-md-2 m-2">
                                <div class="form-group">
                                    <label>Precio Mayorista <span style="color:red;" >(*Ingrese)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="precio_mayorista">
                                </div>
                 </div>
                       
                 <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>
                        
             </div>
             <!-- Fin detalle compra -->
       
            </div>
                </div>

           

            </div>

         <!--Inicio del modal agregar proveedor-->
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
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Proveedor">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Dirección</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="direccion" class="form-control" placeholder="Dirección proveedor">
                                        
                                    </div>
                                </div>

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Teléfono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="telefono" class="form-control" placeholder="Teléfono proveedor">
                                        
                                    </div>
                                </div>
                                
                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Correo electrónico</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="email" class="form-control" placeholder="Correo electrónico proveedor">
                                        
                                    </div>
                                </div>

                                
                                <div v-show="errorPersona" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
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
    import Datepicker from 'vuejs-datepicker';
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import moment from "moment";
    import {en, es} from 'vuejs-datepicker/dist/locale';

    var state = {
        disabledDates: {
            from: new Date() // Desabilita fechas futuras
        }
    }
    export default {
        data (){
            return {
                state:state,
                compra_id: 0,
                fecha: '',
                bandera_editar:false,
                fechavalid : '',
                total: 0,
                observaciones:'',
                personaid:0,
                nombre:'',
                direccion:'',
                telefono:'',
                idproducto:0,
                arrayProducto:[],
                producto:'',
                precio_venta:'',
                precio_compra:'',
                precio_mayorista:'',
                email:'',
                modal : 0,
                tituloModal : '',
                errorPersona : 0,
                errorMostrarMsjPersona : [],
                arrayCompra : [],
                arrayPersona:[],
                arrayProveedor:[],
                ProveedorEXIST:'',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCompra : 0,
                idproveedor:0,
                errorMostrarMsjCompra : [],
                en: en,
                es: es,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'fecha',
                buscar : '',
                codigo: '',
                arrayProducto: [],
            }
        },
        components:{

            Datepicker,
            vSelect
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

            }
        },
        methods : {


            customFormatter(date) {
                this.fecha = moment(date).format('YYYY-MM-DD, h:mm:ss');
                return moment(date).format('DD MMM yy');
            },

            listarCompra (page,buscar,criterio){
                let me=this;
                var url= '/compra?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCompra = respuesta.compra.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarCompra(page,buscar,criterio);
            },
            registrarCompra(){
                if (this.validarCompra()){
                    return;
                }
                
                let me = this;

                axios.post('/compra/registrar',{
                    'fecha': this.fecha,
                    'total': this.total,
                    'observaciones': this.observaciones,
                    'idpersona': this.personaid,
                   
                }).then(function (response) {
                   /*  me.cerrarModal();
                    me.listarCompra(1,'','fecha'); */
                    me.fecha = '';
                    me.fechavalid = '';
                    me.ProveedorEXIST = '';
                    me.total = 0;
                    me.observaciones = '';
                    me.idpersona = 0;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarCompra(){
               if (this.validarCompra()){
                    return;
                }
                
                let me = this;

                axios.put('/compra/actualizar',{
                    
                    'fecha': this.fecha,
                    'total': this.total,
                    'observaciones': this.observaciones,
                    'personaid': this.personaid,
                    'id': this.compra_id,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCompra(1,'','fecha');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarCompra(id){
               Swal.fire({
                title: 'Desactivar compra',
                text: "¿Esta seguro de desactivar esta compra?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Desactivarla'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/compra/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCompra(1,'','fecha');
                        Swal.fire(
                        'Desactivado!',
                        'La compra ha sido desactivada con éxito.',
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
            activarCompra(id){
               Swal.fire({
                title: 'Activar compra',
                text: "¿Esta seguro de activar esta compra?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/compra/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCompra(1,'','fecha');
                        Swal.fire(
                        'Activado!',
                        'La compra ha sido activada con éxito.',
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
            validarCompra(){
                this.errorCompra=0;
                this.errorMostrarMsjCompra =[];

                
                
                if (!this.fecha) this.errorMostrarMsjCompra.push("La fecha no puede estar vacía.");
                if (!this.personaid) this.errorMostrarMsjCompra.push("El proveedor no puede estar vacío.");
                if (this.errorMostrarMsjCompra.length) this.errorCompra = 1;

                return this.errorCompra;
            },
            selectProveedor(search, loading){
                let me = this;
                loading(true);
                let url = '/compra/obtenerPersona?nombre=' + search;
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    a: search
                    me.arrayProveedor = respuesta.persona;
                    // console.log(me.arrayCliente);
                    loading(false);
                })
                .catch(function (error) {
                    // console.log({error});
                });
            },
            getDatosProveedor(val1){
                let me = this;
                me.loading = true;
                me.personaid = val1.id;
                
            },
            buscarProducto (){
                let me=this;
                var url= '/compra/buscarProducto?codigo=' + me.codigo;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos;

                    
                    if (me.arrayProducto.length>0){
                        me.producto=me.arrayProducto[0]['nombre'];
                        me.idproducto=me.arrayProducto[0]['id'];
                        me.precio_venta =me.arrayProducto[0]['precio_venta'];
                        me.precio_compra = me.arrayProducto[0]['precio_compra'];
                        me.precio_mayorista = me.arrayProducto[0]['precio_mayorista'];
                        
                    }
                    else{
                        me.producto='No existe artículo';
                        me.idproducto=0;
                    }

                   


                })
                .catch(function (error) {
                    console.log(error);
                });
            },
              registrarPersona(){
                if (this.validarPersona()){
                    return;
                }
                
                let me = this;

                axios.post('/compra/registrarPersona',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'telefono': this.telefono,
                    'email': this.email,
                   
                }).then(function (response) {
                    
                    me.cerrarModal();
                    me.cargarProveedor();
                    
                }).catch(function (error) {
                    console.log(error);
                });
            },
             validarPersona(){
                this.errorPersona=0;
                this.errorMostrarMsjPersona =[];

                if (!this.nombre) this.errorMostrarMsjPersona.push("El nombre del proveedor no puede estar vacío.");
                if (!this.direccion) this.errorMostrarMsjPersona.push("La dirección del proveedor no puede estar vacío.");
                if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

                return this.errorPersona;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre= '';
                this.direccion = '';
                this.telefono= '';
                this.email= '';
                
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Proveedor';
                                this.nombre= '';
                                this.direccion = '';
                                this.telefono= '';
                                this.email= '';
                                this.tipoAccion = 1;
                                break;
                            }
                        }
                    }
                }
            },
            cargarProveedor(){
                let me = this;
                let url = '/compra/obtenerUltimaPersona';
               
                axios.get(url).then(function (response) {
                    let respuesta = response.data;
                    me.ProveedorEXIST = respuesta.persona; 
                    me.personaid = respuesta.persona.id;
                    console.log(respuesta.persona);
                    
                })
                .catch(function (error) {
                    // console.log({error});
                });
               

            }

        },
        mounted() {
            this.listarCompra(1,this.buscar,this.criterio);
        },
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
</style>