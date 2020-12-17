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
                          <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                            <div class="col-md-9">
                                <datepicker :format="customFormatter" placeholder="Select Date" ></datepicker>
                            </div>
                         </div>
                    </div>

                   <!-- fin fecha -->

                   

                   <!-- proveedor  -->
                   <div class="col-md-9">
                          <div class="form-group row m-2">
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                     <div class="col-md-9">
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
                <button type="button"  class="btn btn-primary" @click="registrarCompra()">Guardar</button>
                
               </div>   

                
             <div class="form-group row border"> 

                 <h1>jj</h1>
             </div>
       
            </div>
                </div>

           

            </div>

         
           
        </main>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import vSelect from 'vue-select';
    import 'vue-select/dist/vue-select.css';
    import moment from "moment";
    export default {
        data (){
            return {
                compra_id: 0,
                fecha: '',
                total: 0,
                observaciones:'',
                personaid:0,
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
                buscar : ''
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
                return this.fecha;
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