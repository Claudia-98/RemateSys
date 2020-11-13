<template>
            <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Inicio</li>
                <li class="breadcrumb-item">Inventario</li>
                <li class="breadcrumb-item active">Artículos</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Artículos ó productos
                        <button type="button" @click="abrirModal('categoria','registrar')" class="btn btn-secondary">
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="alias">Alias</option>
                                      <option value="tipo">Tipo de artículo</option>
                                      <option value="medida">Medida del artículo</option>
                                      <option value="proveedor">Proveedor</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarProducto(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarProducto(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>        </th>
                                    <th>Nombre</th>
                                    <th>Alias</th>
                                    <th>Código</th>
                                    <th>Precio normal</th>
                                    <th>Precio mayoreo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria in arrayProducto" :key="categoria.id">
                                    <td>
                                        <button type="button" @click="abrirModal('categoria','actualizar',categoria)" class="btn btn-warning btn-sm">
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
                                    <td>
                                        <img class="imgUser" v-bind:src="'/uploads/'+categoria.foto" />
                                    </td>
                                    <td v-text="categoria.nombre"></td>
                                    <td v-text="categoria.alias"></td>
                                    <td v-text="categoria.codigo"></td>
                                    <td v-text="categoria.precio_venta"></td>
                                    <td v-text="categoria.precio_mayoreo"></td>
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
                                        <input type="text" v-model="nombre" class="form-control" placeholder="Nombre del producto">
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Alias</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="alias" class="form-control" placeholder="Alias del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="codigo" class="form-control" placeholder="Código del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Categoria de producto</label>
                                    <div class="col-md-9">
                                        <select id="cargoo" class="form-control" v-model="tipo_id" >
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="tipo in arrayTipo" :key="tipo.id" :value="tipo.id" v-text="tipo.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="Descripción del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio venta</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="precio_venta" class="form-control" placeholder="Precio venta del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio mayoreo</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="precio_mayoreo" class="form-control" placeholder="Precio para venta al mayoreo del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio compra</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="precio_compra" class="form-control" placeholder="Precio compra del producto">
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Medida del producto</label>
                                    <div class="col-md-9">
                                        <select id="cargoo" class="form-control" v-model="medida_id" >
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="medida in arrayMedida" :key="medida.id" :value="medida.id" v-text="medida.medida"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Proveedor</label>
                                    <div class="col-md-9">
                                        <select id="cargoo" class="form-control" v-model="persona_id" >
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="persona in arrayPersona" :key="persona.id" :value="persona.id" v-text="persona.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-md-3 form-control-label" for="text-input">Imagen del producto</label>
                                        <div class="class-md-9">
                                            <input
                                                type="file"
                                                id="image"
                                                class="form-control"
                                                name="image"
                                                @change="getImage"
                                                accept="image/*"
                                            />
                                        </div>
                                    </div>
                                
                                <div v-show="errorProducto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjProducto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarProducto()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarProducto()">Actualizar</button>
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
                producto_id: 0,
                persona_id:0,
                tipo_id:0,
                medida_id:0,
                medida : '',
                nombre:'',
                alias:'',
                codigo:'',
                foto:'',
                descripcion:'',
                precio_venta:0.0,
                precio_mayoreo:0.0,
                precio_compra:0.0,
                arrayProducto : [],
                arrayTipo:[],
                arrayPersona:[],
                arrayMedida:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorProducto : 0,
                errorMostrarMsjProducto : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'medida',
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
            listarProducto (page,buscar,criterio){
                let me=this;
                var url= '/producto?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayProducto = respuesta.productos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getImage(event) {
                this.foto = event.target.files[0];
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.listarProducto(page,buscar,criterio);
            },
            obtenerMedida(){
                let me = this;

                axios.get('producto/obtenerMedida').then(function (response) {
                    var respuesta= response.data;
                    me.arrayMedida = respuesta.medida;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerTipo(){
                let me = this;

                axios.get('producto/obtenerTipo').then(function (response) {
                    var respuesta= response.data;
                    me.arrayTipo = respuesta.tipo;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            obtenerPersona(){
                let me = this;

                axios.get('producto/obtenerPersona').then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersona = respuesta.persona;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            registrarProducto(){
                if (this.validarProducto()){
                    return;
                }
                
                let me = this;

                axios.post('/producto/registrar',{
                    'nombre': this.nombre,
                    'alias':this.alias,
                    'codigo':this.codigo,
                    'foto':this.foto,
                    'descripcion':this.descripcion,
                    'precio_venta':this.precio_venta,
                    'precio_mayorista':this.precio_mayoreo,
                    'precio_compra':this.precio_compra,
                    'idpersona':this.persona_id,
                    'idtipo':this.tipo_id,
                    'idmedida':this.medida_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            actualizarProducto(){
               if (this.validarProducto()){
                    return;
                }
                
                let me = this;

                axios.put('/producto/actualizar',{
                    'nombre': this.nombre,
                    'id': this.producto_id,
                    'alias':this.alias,
                    'descripcion':this.descripcion,
                    'codigo':this.codigo,
                    'foto':this.foto,
                    'precio_venta':this.precio_venta,
                    'precio_mayorista':this.precio_mayoreo,
                    'precio_compra':this.precio_compra,
                    'idpersona':this.persona_id,
                    'idtipo':this.tipo_id,
                    'idmedida':this.medida_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarProducto(1,'','nombre');
                }).catch(function (error) {
                    console.log(error);
                }); 
            },
            desactivarMedida(id){
               Swal.fire({
                title: 'Desactivar artículo',
                text: "¿Esta seguro de desactivar esta artículo?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Desactivarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/producto/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarProducto(1,'','nombre');
                        Swal.fire(
                        'Desactivado!',
                        'El artículo ha sido desactivada con éxito.',
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
                title: 'Desactivar artículo',
                text: "¿Esta seguro de activar esta artículo?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Activarlo'
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/producto/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarProducto(1,'','nombre');
                        Swal.fire(
                        'Activado!',
                        'El artículo ha sido activada con éxito.',
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
            validarProducto(){
                this.errorProducto=0;
                this.errorMostrarMsjProducto =[];

                if (!this.nombre) this.errorMostrarMsjProducto.push("El nombre del artículo no puede estar vacío.");
                if (!this.alias) this.errorMostrarMsjProducto.push("El alias del artículo no puede estar vacío.");
                if (!this.codigo) this.errorMostrarMsjProducto.push("El codigo del artículo no puede estar vacío.");
                if (!this.precio_venta||this.precio_venta<=0) this.errorMostrarMsjProducto.push("El precio de venta del artículo no puede estar vacío o ser menor a 0.");
                if (!this.precio_mayoreo||this.precio_mayoreo<=0) this.errorMostrarMsjProducto.push("El precio de mayoreo del artículo no puede estar vacío o ser menor a 0.");
                if (!this.precio_compra||this.precio_compra<=0) this.errorMostrarMsjProducto.push("El precio de compra del artículo no puede estar vacío o ser menor a 0.");
                // if (!this.persona_id) this.errorMostrarMsjProducto.push("El artículo tiene que tener un proveedor.");
                if (!this.medida_id) this.errorMostrarMsjProducto.push("El artículo tiene que tener una medida asignada.");
                if (!this.tipo_id) this.errorMostrarMsjProducto.push("El artículo tiene que tener una categoria asignada.");

                if (this.errorMostrarMsjProducto.length) this.errorProducto = 1;

                return this.errorProducto;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar artículo';
                                this.nombre= '';
                                this.alias='';
                                this.codigo='';
                                this.foto='';
                                this.descripcion='';
                                this.precio_venta=0;
                                this.precio_mayoreo=0;
                                this.precio_compra=0;
                                this.persona_id=0;
                                this.tipo_id=0;
                                this.medida_id=0;
                                this.tipoAccion = 1;
                                this.obtenerMedida();
                                this.obtenerTipo();
                                this.obtenerPersona();
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar artículo';
                                this.tipoAccion=2;
                                this.producto_id=data['id'];
                                this.nombre= data['nombre'];
                                this.alias=data['alias'];
                                this.codigo=data['codigo'];
                                this.foto=data['foto'];
                                this.descripcion=data['descripcion'];
                                this.precio_venta=data['precio_venta'];
                                this.precio_mayoreo=data['precio_mayorista'];
                                this.precio_compra=data['precio_compra'];
                                this.persona_id=data['idpersonas'];
                                this.tipo_id=data['idtipo'];
                                this.medida_id=data['idmedida'];
                                this.obtenerMedida();
                                this.obtenerTipo();
                                this.obtenerPersona();
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() {
            this.listarProducto(1,this.buscar,this.criterio);
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
    .imgUser {
        width: 100px !important;
        height: 100px;
        /* border-radius: 150px; */
        border: 10px rgb(4, 1, 74);
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
