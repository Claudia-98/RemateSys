<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Sistema</li>
                <li class="breadcrumb-item">Acceso</li>
                <li class="breadcrumb-item active">Usuarios</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('usuario','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="usuario">Usuario</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarUsuario(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarUsuario(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                    <th>Dirección</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                                    <td>
                                        <button type="button" @click="abrirModal('usuario','actualizar',usuario)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="usuario.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="desactivarUsuario(usuario.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm" @click="activarUsuario(usuario.id)">
                                                <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="usuario.persona.nombre"></td>
                                    <td v-text="usuario.usuario"></td>
                                    <td v-text="usuario.rol.nombre"></td>
                                    <td v-text="usuario.persona.direccion"></td>
                                    <td>
                                        <div v-if="usuario.estado">
                                            <span class="badge badge-success">Activo</span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger">Inactivo</span>
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
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del usuario">
                                        
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="usuario" class="form-control" placeholder="Ingrese un usuario para iniciar sesión">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Contraseña</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="password" class="form-control" placeholder="Ingrese una contraseña">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="direccion" class="form-control" placeholder="Ingrese la dirección del usuario">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">No. Telefonico</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="telefono" class="form-control" placeholder="Ingrese el número telefónico">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">E-Mail</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Ingrese el correo electronico del usuario">
                                    </div>
                                </div>
                                 <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Rol</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="id_rol" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Tipo</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="id_tipo" class="form-control" placeholder="Ingrese descripción">
                                    </div>
                                </div>
                                <div v-show="errorUsuario" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUsuario()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
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
                usuario_id: 0,
                nombre : '',
                direccion : '',
                telefono:'',
                id_tipo:0,
                id_rol:0,
                usuario:'',
                password:'',
                email:'',
                arrayUsuario : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorUsuario : 0,
                errorMostrarMsjUsuario : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
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
            listarUsuario (page,buscar,criterio){
                let me=this;
                var url= '/usuarios?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUsuario = respuesta.usuarios.data;
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
                me.listarUsuario(page,buscar,criterio);
            },
            registrarUsuario(){
                if (this.validarUsuario()){
                    return;
                }
                
                let me = this;

                axios.post('/usuarios/registrar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'telefono':this.telelfono,
                    'email':this.email,
                    'usuario':this.usuario,
                    'password':this.password,
                    'idtipo':this.id_tipo,
                    'idrol':this.id_rol,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarUsuario(){
               if (this.validarUsuario()){
                    return;
                }
                
                let me = this;

                axios.put('/usuarios/actualizar',{
                    'nombre': this.nombre,
                    'direccion': this.direccion,
                    'id': this.usuario_id,
                    'telefono':this.telelfono,
                    'email':this.email,
                    'usuario':this.usuario,
                    'password':this.password,
                    'idtipo':this.id_tipo,
                    'idrol':this.id_rol,
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarUsuario(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarUsuario(id){
               Swal.fire({
                title: 'Desactivar usuario',
                text: "¿Esta seguro de desactivar este usuario?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Desactivarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/usuarios/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUsuario(1,'','nombre');
                        Swal.fire(
                        'Desactivado!',
                        'El usuario ha sido desactivada con éxito.',
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
            activarUsuario(id){
               Swal.fire({
                title: 'Desactivar usuario',
                text: "¿Esta seguro de activar este usuario?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/usuarios/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarUsuario(1,'','nombre');
                        Swal.fire(
                        'Activado!',
                        'El usuario ha sido activada con éxito.',
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
            validarUsuario(){
                this.errorUsuario=0;
                this.errorMostrarMsjUsuario =[];

                if (!this.nombre) this.errorMostrarMsjUsuario.push("El nombre del usuario no puede estar vacío.");
                if (!this.direccion) this.errorMostrarMsjUsuario.push("La dirección del usuario no puede estar vacío.");
                if (!this.telefono) this.errorMostrarMsjUsuario.push("El número de teléfono del usuario no puede estar vacío.");
                if (!this.usuario) this.errorMostrarMsjUsuario.push("El nombre de usuario no puede estar vacío.");
                if (!this.password) this.errorMostrarMsjUsuario.push("La contraseña del usuario no puede estar vacío.");
                if (!this.id_tipo) this.errorMostrarMsjUsuario.push("El tipo del usuario no puede estar vacío.");
                if (!this.id_rol) this.errorMostrarMsjUsuario.push("El rol del usuario no puede estar vacío.");

                if (this.errorMostrarMsjUsuario.length) this.errorUsuario = 1;

                return this.errorUsuario;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "usuario":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Usuario';
                                this.nombre= '';
                                this.direccion = '';
                                this.telefono='';
                                this.email='';
                                this.id_tipo=0;
                                this.id_rol=0;
                                this.usuario='';
                                this.password='';
                                this.foto='';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar Usuario';
                                this.tipoAccion=2;
                                this.usuario_id=data['id'];
                                this.nombre = data['persona']['nombre'];
                                this.direccion= data['persona']['direccion'];
                                this.telefono = data['persona']['telefono'];
                                this.email = data['persona']['email'];
                                this.id_tipo = data['persona']['idtipo'];
                                this.id_rol = data['idrol'];
                                this.usuario = data['usuario'];
                                this.password = data['password'];
                                // this.foto = data['foto'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarUsuario(1,this.buscar,this.criterio);
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
