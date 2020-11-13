<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item">Tiendas-Espacios</li>
                <!-- <li class="breadcrumb-item active">Medidas</li> -->
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
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
        </main>
</template>

<script>
    export default {
        data (){
            return {
                espacio_id: 0,
                nombre : '',
                direccion : '',
                telefono : '',
                arrayEspacio : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorEspacio : 0,
                errorMostrarMsjEspacio : [],
                pagination : {
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
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarEspacio(page,buscar,criterio);
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
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
