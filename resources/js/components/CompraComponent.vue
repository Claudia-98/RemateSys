<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Sistema</li>
                <li class="breadcrumb-item">Compras</li>
                <li class="breadcrumb-item active">Registro compra</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Registro compra
                        <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;
                        </button>
                    </div>
                    <div class="card-body">
                           <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Fecha</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="fecha" class="form-control" placeholder="Fecha">
                                        
                                    </div>
                                </div>

                                 
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                    <div class="col-md-9">
                                        <select id="cargoo" class="form-control" v-model="personaid" >
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="persona in arrayPersona" :key="persona.id" :value="persona.id" v-text="persona.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                

                                  <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Observaciones</label>
                                    <div class="col-md-9">
                                        <input type="textarea" v-model="observaciones" class="form-control" placeholder="Observaciones">
                                        
                                    </div>
                                </div>

                                
                                <div v-show="errorCompra" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                    </div>
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
                         
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCompra()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCompra()">Actualizar</button>
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
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCompra : 0,
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
                    me.cerrarModal();
                    me.listarCompra(1,'','fecha');
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
              obtenerPersona(){
                let me = this;

                axios.get('compra/obtenerPersona').then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersona = respuesta.persona;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.fecha = '';
                this.total = 0;
                this.observaciones = '';
                this.personaid =0;
                    
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
                                this.fecha = '',
                                this.total = 0,
                                this.observaciones = ''
                                this.personaid =0
                                this.tipoAccion = 1;
                                this.obtenerPersona();
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Proveedor';
                                this.tipoAccion=2;
                                this.compra_id=data['id'];
                                this.fecha = data['fecha'];
                                this.total = data['total'];
                                this.observaciones = data['observaciones'];
                                this.personaid =data['idpersona'];
                             

                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarCompra(1,this.buscar,this.criterio);
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
</style>
